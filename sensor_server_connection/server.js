/**************************websocket_example.js*************************************************/
var bodyParser = require("body-parser");
const express = require('express'); //express framework to have a higher level of methods
const app = express(); //assign app variable the express class/method
var http = require('http');
var path = require("path");

app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());
const server = http.createServer(app);//create a server

const WebSocket = require('ws');
const s = new WebSocket.Server({ server });

s.on('connection', function (ws, req) {
    ws.on('message', function (message) {
        try {
            var now = new Date();
            message = JSON.parse(message)
            message["sensor"]["timeStamp"] = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
            console.log(message["sensor"]);
        } catch (err) {
            console.error(err)
        }
        s.clients.forEach(function (client) { //broadcast incoming message to all clients (s.clients)
            if (client != ws && client.readyState) { //except to the same client (ws) that sent this message
                client.send(JSON.stringify(message));
            }
        });
    });

    ws.on('close', function () {
        console.log("lost one client");
    });
    //ws.send("new client connected");
    console.log("new client connected");
});

server.listen(5000);