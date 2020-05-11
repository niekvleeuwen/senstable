/*******************Esp8266_Websocket.ino****************************************/
#include <ESP8266WiFi.h>
#include <WebSocketClient.h>

boolean handshakeFailed = 0;
String data = "";
char path[] = "/";   //identifier of this device
const char* ssid     = "AP001#";
const char* password = "#molen@wieken!52";
char* host = "172.19.3.116";  //replace this ip address with the ip address of your Node.Js server
const int espport = 3000;

WebSocketClient webSocketClient;
unsigned long previousMillis = 0;
unsigned long currentMillis;
unsigned long interval = 300; //interval for sending data to the websocket server in ms
// Use WiFiClient class to create TCP connections
WiFiClient client;

int readPin = A0;

void setup() {
  Serial.begin(115200);
  pinMode(readPin, INPUT);     // Initialize the LED_BUILTIN pin as an output
  delay(10);
  // We start by connecting to a WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  delay(1000);

  wsconnect();
  //  wifi_set_sleep_type(LIGHT_SLEEP_T);
}
void loop() {
  if (client.connected()) {
    currentMillis = millis();
    webSocketClient.getData(data);
    if (data.length() > 0) {
      //Serial.println(data);
      //*************send log data to server in certain interval************************************
      //currentMillis=millis();
      if (abs(currentMillis - previousMillis) >= interval) {
        previousMillis = currentMillis;
        String json = F("{ \"sensor\":{ \"id\": 1, \"value\": ");
        String jsonEnd = "} }";

        // read data
        data = (String) analogRead(readPin); //read adc values, this will give random value, since no sensor is connected.
        
        String finalJson = json + data + jsonEnd;
        Serial.println(finalJson);
        //For this project we are pretending that these random values are sensor values
        webSocketClient.sendData(finalJson);//send sensor data to websocket server
      }

    }
    else {
    }
    delay(5);
  }
}
//*********************************************************************************************************************

//***************function definitions**********************************************************************************
void wsconnect() {
  // Connect to the websocket server
  if (client.connect(host, espport)) {
    Serial.println("Connected");
  } else {
    Serial.println("Connection failed.");
    delay(1000);

    if (handshakeFailed) {
      handshakeFailed = 0;
      ESP.restart();
    }
    handshakeFailed = 1;
  }
  // Handshake with the server
  webSocketClient.path = path;
  webSocketClient.host = host;
  if (webSocketClient.handshake(client)) {
    Serial.println("Handshake successful");
  } else {

    Serial.println("Handshake failed.");
    delay(4000);

    if (handshakeFailed) {
      handshakeFailed = 0;
      ESP.restart();
    }
    handshakeFailed = 1;
  }
}