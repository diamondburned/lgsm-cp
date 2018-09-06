package main

import (
	"bufio"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"os/exec"

	"github.com/gorilla/websocket"
	"github.com/pelletier/go-toml"
)

var clients = make(map[*websocket.Conn]bool)
var broadcast = make(chan Request)

// Token = password -> sha512sum (all of this done client-side)
//var token = "3930cf659784a81d82d5e4856e5c7d356f3b40b672ceed9e3ea5c3fb622fa250dee4846daa9f33e2e3b5000cc307f40ffc3c967b2f8f662b711804e446cea21f"
var token = "a"

// Defines upgrader
var upgrader = websocket.Upgrader{}

// Request Struct for requests to/from the server/front-end
type Request struct {
	Token   string `json:"token"`
	Action  string `json:"action"`
	Payload string `json:"payload"`
}

var config, configError = toml.LoadFile("config.toml")

func main() {
	if configError != nil {
		return
	}

	// Initiates a file server (?) at /
	fs := http.FileServer(http.Dir("../public"))
	http.Handle("/", fs)

	// Listen to WebSocket at /ws
	http.HandleFunc("/ws", handleConnections)

	// Async Message handlers
	go handleMessages()

	err := http.ListenAndServe(":12000", nil)
	
	//publPath := config.Get("ssl.Publ").(string)
	//privPath := config.Get("ssl.Priv").(string)
	//if publ == "" || priv == "" {
	//	log.Fatal("Cannot find Public or Private key file path!")
	//} else {
	//	err := http.ListenAndServe(":12000", publ, priv, nil)
	//}
	
	if err != nil {
		log.Fatal("ERROR! HTTP failed!", err)
	}
	log.Printf("%s", "HTTP server up and listening at :12000")
}

func handleConnections(writer http.ResponseWriter, request *http.Request) {
	// Upgrading HTTP(S) to WS
	ws, err := upgrader.Upgrade(writer, request, nil)
	if err != nil {
		log.Fatal("ERROR! Upgrader failed!", err)
	}

	// Closes when function returns
	defer ws.Close()

	// Register the clients
	clients[ws] = true

	// Loop stuff to continuously listen
	for {
		var rq Request

		// Parses JSON and passes it to "rq" (*Request)
		err := ws.ReadJSON(&rq)
		if err != nil {
			log.Printf("Error at ws.ReadJSON! %v", err)
			delete(clients, ws)
			break // Exit loop if fail
		}

		// Sends the "rq" (*Request) to "broadcast"
		broadcast <- rq
	}
}

// Response The struct to respond after a request
type Response struct {
	Signal int    `json:"result"`
	Output string `json:"output"`
}

//	PLANNED SIGNALS:
//     -1: Not finished
//      0: Success
//      1: Received error
//      2: Invalid syntax
//      3: Invalid token

func handleMessages() {
	// Same loop
	for {
		// Listen to broadcast for rq (*Request)
		rq := <-broadcast

		// Broadcast to all clients
		for client := range clients {
			var res Response

			// Run the request
			signal, output := processJSON(rq, client)

			res.Signal = signal
			res.Output = output

			err := client.WriteJSON(&res)
			if err != nil {
				log.Printf("Error at client.WriteJSON! %v", err)
				client.Close()
				delete(clients, client)
			}
		}
	}
}

func processJSON(request Request, client *websocket.Conn) (int, string) {
	if request.Token != token {
		log.Printf("%v", "Invalid token!")
		return 3, "Invalid token!"
	}

	if request.Payload == "" {
		return 2, "Empty command!"
	}

	if request.Action == "CMDRUN" {
		err := execAsync(request.Payload, client)
		if err != nil {
			return 1, fmt.Sprintf("%s", err)
		}

		return 0, "Executed successfully."
	}

	if request.Action == "consoleprint" {
		type RequestPayload struct {
			Alias    string `json:"alias"`
			Password string `json:"password"`
		}

		var payload RequestPayload
		errJSON := json.Unmarshal([]byte(request.Payload), &payload)
		if errJSON != nil {
			return 1, fmt.Sprintf("%s", errJSON)
		}

		user := config.Get(payload.Alias + ".user").(string)
		gameserver := config.Get(payload.Alias + ".script").(string)
		password := payload.Password

		err := linuxGSM(request.Action, " ", user, password, gameserver, client)
		if err != nil {
			return 1, fmt.Sprintf("%s", err)
		}

		return 0, "Executed successfully."
	}

	return 2, "Unknown Payload."
}

func execAsync(cmd string, client *websocket.Conn) error {
	var res Response

	cmd = cmd + "&> /dev/stdout"
	shell := exec.Command("/bin/bash", "-c", cmd)
	stdout, err := shell.StdoutPipe()

	shell.Start()

	bufout := bufio.NewScanner(stdout)
	bufout.Split(bufio.ScanLines)

	if err != nil {
		return err
	}

	for bufout.Scan() {
		res.Signal = -1
		res.Output = bufout.Text()

		errWrite := client.WriteJSON(&res)
		if errWrite != nil {
			log.Printf("Error at client.WriteJSON in execAsync! %v", err)
			client.Close()
			delete(clients, client)
		}
	}

	shell.Wait()

	return nil
}

// linuxGSM List of LinuxGSM actions
func linuxGSM(action string, input string, user string, password string, gameserver string, client *websocket.Conn) error {
	if action == "consoleprint" {
		print := `echo -e "` + password + `\n" | sudo -S -u ` + user + " tmux capture-pane -J -p -S -32767 -t " + gameserver

		err := execAsync(print, client)
		if err != nil {
			return err
		}

		return nil
	}

	if action == "run" {
		command := "sudo -u " + user + " tmux send-keys -t " + gameserver + " " + input + "Enter"
		err1 := execAsync(command, client)

		print := "sudo -u " + user + " tmux capture-pane -J -p -S -32767 -t " + gameserver
		err2 := execAsync(print, client)

		if err1 != nil || err2 != nil {
			err := fmt.Errorf("%s\n%s", err1, err2)
			return err
		}

		return nil
	}

	return nil
}
