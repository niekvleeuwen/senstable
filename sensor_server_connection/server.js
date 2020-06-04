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

var clientDict = [];

console.log("Server started.");

s.on('connection', function (ws, req) {
    ws.on('message', function (message) {
        try {
            message = JSON.parse(message);
            console.log(message);

            if(message.hasOwnProperty("client")){
                if(clientDict[message["client"]["id"]] === undefined)
                    clientDict[message["client"]["id"]] = [];
                
                clientDict[message["client"]["id"]].push(ws);
            }
            else{
                var now = new Date();
                message["sensor"]["timeStamp"] = `${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
            }
        } catch (err) {
            console.error(err);
        }
        if(message.hasOwnProperty("sensor")){
            if(!(clientDict[message["sensor"]["id"]] === undefined)){
                clientDict[message["sensor"]["id"]].forEach(client => {
                    client.send(JSON.stringify(message));
                });
            }
        }
    });

    ws.on('close', function () {
        console.log("lost one client");
    });
    console.log("new client connected");
});

server.listen(5050);