/*******************Esp8266_Websocket.ino****************************************/
#include <ESP8266WiFi.h>
#include <WebSocketClient.h>
#include <DHT.h>

#define DHTTYPE DHT11 

boolean handshakeFailed = 0;
String data[3] = {};
char path[] = "/";   //identifier of this device
const char* ssid     = "PLACE_SSID_HERE";
const char* ssid     = "PLACE_PASSWORD_HERE";
char* host = "";  //replace this ip address with the ip address of your Node.Js server
const int espport = 5050;

WebSocketClient webSocketClient;
unsigned long previousMillis = 0;
unsigned long currentMillis;
unsigned long interval = 300; //interval for sending data to the websocket server in ms
// Use WiFiClient class to create TCP connections
WiFiClient client;

const int sensorCount = 3;
int pins[sensorCount] = {5, 4, 0}; 

DHT sensor1 = DHT(pins[0], DHTTYPE);
DHT sensor2 = DHT(pins[1], DHTTYPE);
DHT sensor3 = DHT(pins[2], DHTTYPE);

DHT* sensors[sensorCount] = {&sensor1, &sensor2, &sensor3};

void setup() {
  Serial.begin(115200);

    
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
    //Serial.println(data);
    //*************send log data to server in certain interval************************************
    //currentMillis=millis();
    if (abs(currentMillis - previousMillis) >= interval) {
      previousMillis = currentMillis;
      String json = F("{ \"sensor\":{ \"id\": 8, \"value\": ");
      String jsonEnd = "} }";


      data[0] = readSensor(); //read adc values, this will give random value, since no sensor is connected.


      String finalJson = json + data[0] + jsonEnd;
      Serial.println(finalJson);
      //For this project we are pretending that these random values are sensor values
      webSocketClient.sendData(finalJson);//send sensor data to websocket server


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

String readSensor() {
  // "[[], [], []]"
  float data[sensorCount][2];
  for(int i = 0, index = 0; i < sensorCount; i++, index++){
      data[index][0] = sensors[i]->readTemperature();
      data[index][1] = sensors[i]->readHumidity();
  }

  float temp[2][2];
  temp[0][0] = data[0][0];
  temp[0][1] = data[0][1];

  temp[1][0] = (data[1][0] + data[2][0]) / 2;
  temp[1][1] = (data[1][1] + data[2][1]) / 2;
  
  String result = "[";
  for(int i = 0; i < sensorCount - 1; i++){
    result += "[" + (String) temp[i][0] + "], [" + (String) temp[i][1] + "]";
    result += i < sensorCount - 2 ? ", " : "";
  }
  result += "]";
  
  return result;
}
