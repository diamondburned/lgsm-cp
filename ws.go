package main

import (
	"bufio"
	"fmt"
	"log"
	"net/http"
	"os/exec"
	"strings"

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
	Token   string         `json:"token"`
	Action  string         `json:"action"`
	Payload RequestPayload `json:"payload"`
}

// RequestPayload The payload the WS sends over
type RequestPayload struct {
	Alias    string `json:"alias,omitempty"`
	Password string `json:"password,omitempty"`
	Input    string `json:"input,omitempty"`
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

	PORT := ":12000"

	publPath := config.Get("ssl.Publ")
	privPath := config.Get("ssl.Priv")
	if publPath == nil || privPath == nil {
		log.Printf("Cannot find Public or Private key file path!")
		log.Printf("%s", "HTTP server up and listening at "+PORT)
		err := http.ListenAndServe(PORT, nil)
		if err != nil {
			log.Fatal("ERROR! HTTP failed!", err)
		}
	} else {
		publ := fmt.Sprintf("%s", publPath)
		priv := fmt.Sprintf("%s", privPath)
		log.Printf("%s", "HTTPS server up and listening at "+PORT)
		err := http.ListenAndServeTLS(PORT, publ, priv, nil)
		if err != nil {
			log.Fatal("ERROR! HTTPS failed!", err)
		}
	}
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
//      2: Invalid syntax/Unknown Command
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

// a channel to tell it to stop
var stopchan chan struct{}

// a channel to signal that it's stopped
var stoppedchan chan struct{}

func processJSON(request Request, client *websocket.Conn) (int, string) {
	if request.Token != token {
		log.Printf("%v", "Invalid token!")
		return 3, "Invalid token!"
	}

	//if request.Payload == nil {
	//	return 2, "Empty command!"
	//}

	if request.Action == "CMDRUN" {
		err := execAsync(request.Payload.Input, client)
		if err != nil {
			return 1, fmt.Sprintf("%s", err)
		}

		return 0, "Executed successfully."
	}

	payload := request.Payload

	user := config.Get(payload.Alias + ".user").(string)
	gameserver := config.Get(payload.Alias + ".script").(string)
	password := payload.Password
	input := payload.Input

	if request.Action == "stopprint" {
		close(stopchan) // tell it to stop
		<-stoppedchan   // wait for it to have stopped
		return 0, "Stopped UpdateConsole."
	}

	if request.Action == "consoleprint" {
		// a channel to tell it to stop
		var stopchan chan struct{}

		// a channel to signal that it's stopped
		var stoppedchan chan struct{}

		gamepath := strings.Split(gameserver, "/")
		pane := gamepath[len(gamepath)-1]

		print := `echo -e "` + password + `\n" | sudo -S -u ` + user + " tmux capture-pane -J -p -S -32767 -t " + pane

		// LastOutput A hack to keep track of the last console output
		LastOutput := make(chan string, 1)

		go func() {
			defer close(stoppedchan)

			for {
				select {
				case <-stopchan:
					return
				default:
					bufout, err := execAsyncUpdate(print, client, LastOutput)
					if err != nil {
						break
					}

					LastOutput <- bufout
				}
			}
		}()

		return -1, ""
	}

	// {"token":"TOKEN", "action":"request.Action", "payload": {"alias":"SERVER_ALIAS", "password":"SUDO PASSWORD", "input":""}}
	// SUDO PASSWORD is not needed if Go backend is executed in the same user
	if request.Action == "start" || request.Action == "stop" || request.Action == "monitor" || request.Action == "details" {
		command := `echo -e "` + password + `\n" | sudo -S -u ` + user + " " + gameserver + " " + request.Action
		err := execAsync(command, client)

		if err != nil {
			return 1, fmt.Sprintf("%s", err)
		}

		return 0, "Executed successfully."
	}

	if request.Action == "run" {
		gamepath := strings.Split(gameserver, "/")
		pane := gamepath[len(gamepath)-1]

		var replacer = strings.NewReplacer(`"`, `\"`)

		sanitisedInput := replacer.Replace(input)

		command := `echo -e "` + password + `\n" | sudo -S -u ` + user + " tmux send-keys -t " + pane + ` "` + sanitisedInput + `" ENTER`
		err := execAsync(command, client)

		if err != nil {
			return 1, fmt.Sprintf("Error! %s", err)
		}

		return 0, "Executed successfully."
	}

	return 2, "Unknown Command."
}

// execAsync Runs shell commands
// opt [DIFF|]
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

func execAsyncUpdate(cmd string, client *websocket.Conn, LastOutput chan string) (string, error) {
	var res Response

	cmd = cmd + "&> /dev/stdout"
	shell := exec.Command("/bin/bash", "-c", cmd)
	stdout, err := shell.StdoutPipe()

	shell.Start()

	bufout := bufio.NewScanner(stdout)
	bufout.Split(bufio.ScanLines)

	if err != nil {
		return "", err
	}

	for bufout.Scan() {
		LO := <-LastOutput

		fmt.Printf("%s", LO)
		print := strings.Replace(bufout.Text(), LO, "", 1)

		if print != "" {
			res.Signal = -1
			res.Output = print

			errWrite := client.WriteJSON(&res)
			if errWrite != nil {
				log.Printf("Error at client.WriteJSON in execAsync! %v", err)
				client.Close()
				delete(clients, client)
			}
		}

		return bufout.Text(), nil
	}

	shell.Wait()

	return "", nil
}
