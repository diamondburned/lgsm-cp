package main

import (
	"log"
	"net/http"

	"github.com/gorilla/websocket"
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

func main() {
	// Initiates a file server (?) at /
	fs := http.FileServer(http.Dir("../public"))
	http.Handle("/", fs)

	// Listen to WebSocket at /ws
	http.HandleFunc("/ws", handleConnections)

	// Async Message handlers
	go handleMessages()

	err := http.ListenAndServe(":12000", nil)
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

func handleMessages() {
	// Same loop
	for {
		// Listen to broadcast
		rq := <-broadcast

		// Broadcast to all clients
		for client := range clients {
			res := processJSON(rq)
			if res == false {
				client.Close()
				continue
			}

			err := client.WriteJSON(rq)
			if err != nil {
				log.Printf("Error at client.WriteJSON! %v", err)
				client.Close()
				delete(clients, client)
			}
		}
	}
}

func processJSON(json Request) bool {
	if json.Token != token {
		log.Printf("%v", "Invalid token!")
		return false
	}

	if json.Action == "CMDRUN" {
		if json.Payload == "" {
			return false
		} else {
			log.Printf("Oh no! Command %v is being executed!", json.Payload)
		}
	} else {
		log.Printf("%v", "Unknown Payload.")
		return false
	}

	return true
}
