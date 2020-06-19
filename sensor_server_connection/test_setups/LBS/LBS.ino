#include <WebSocketClient.h>

/******Esp8266_Websocket.ino*************/
#include <ESP8266WiFi.h>

#include <Servo.h>        //Servo library
 
Servo servo_test;      //initialize a servo object for the connected servo  

int LBSPin = 5;  // input pin
int Obstakel = HIGH;  // HIGH betekent geen detectie
int angle = 0;    
int potentio = A0;      // initialize the A0analog pin for potentiometer

boolean handshakeFailed = 0;
String data = "";
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

 



 

void setup() {
  Serial.begin(115200);
  //pinMode(readPin, INPUT);     // Initialize the LED_BUILTIN pin as an output
  delay(10);
  // We start by connecting to a WiFi network
  pinMode(LBSPin, INPUT);
  
  servo_test.attach(14);   // attach the signal pin of servo to pin D5 of esp
  pinMode(LED_BUILTIN,OUTPUT);
  digitalWrite(LED_BUILTIN, LOW);
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
    //****send log data to server in certain interval*************
    //currentMillis=millis();
    if (abs(currentMillis - previousMillis) >= interval) {
      previousMillis = currentMillis;
      String json = F("{ \"sensor\":{ \"id\": 7, \"value\": ");
      String jsonEnd = "} }";

 

      // read data
      data = (String) func(); //read adc values, this will give random value, since no sensor is connected.

 

      String finalJson = json + data + jsonEnd;
      Serial.println(finalJson);
      //For this project we are pretending that these random values are sensor values
      webSocketClient.sendData(finalJson);//send sensor data to websocket server
    }
    else {
    }
    delay(5);
  }

  angle = analogRead(potentio); 
  //Serial.println(angle);// reading the potentiometer value between 0 and 1023 
  angle = map(angle, 0, 1023, 0, 179);  
  //Serial.println(angle);// scaling the potentiometer value to angle value for servo between 0 and 180) 
  servo_test.write(angle);
  delay(15);
}
//***************************************

 

//******function definitions***************************
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
int func(){
  Obstakel = digitalRead(LBSPin);
  Serial.println(Obstakel);
  return Obstakel;
}
