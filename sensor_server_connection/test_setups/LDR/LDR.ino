/*******************Esp8266_Websocket.ino****************************************/
#include <ESP8266WiFi.h>
#include <WebSocketClient.h>
#include <Adafruit_NeoPixel.h>

#define NUMPIXELS 12

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

int readPin = A0;

const int ledPin = 5;
Adafruit_NeoPixel pixels(NUMPIXELS, ledPin, NEO_GRB + NEO_KHZ800);

int brightness = 0;
bool countDown = false;

long duration;
int distance;

const int sensorCount = 1;

void setup() {
  Serial.begin(115200);

  pinMode(readPin, INPUT);     // Initialize the LED_BUILTIN pin as an output
  pinMode(ledPin, OUTPUT);
  
  delay(10);
  // We start by connecting to a WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  pixels.begin();
  pixels.show();
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
      String json = F("{ \"sensor\":{ \"id\": 6, \"value\": ");
      String jsonEnd = "} }";

      
       data[0] = readSensor(); //read adc values, this will give random value, since no sensor is connected.
      

      String finalJson = json + data[0] + jsonEnd;
      Serial.println(finalJson);
      //For this project we are pretending that these random values are sensor values
      webSocketClient.sendData(finalJson);//send sensor data to websocket server

      
      if (brightness >= 255)
        countDown = true;
    
      if (brightness < 20 && countDown)
        countDown = false;
    
      brightness += countDown ? -10 : 10;

      brightness = brightness > 255 ? 255 : brightness < 0 ? 0 : brightness;
      Serial.println(brightness);
      Brightness(brightness);
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
  // print the measured value
  return (String) analogRead(readPin);
}

int Brightness(int b) {
  for (int i = 0; i < NUMPIXELS; i++) { // For each pixel...

    // pixels.Color() takes RGB values, from 0,0,0 up to 255,255,255
    // Here we're using a moderately bright green color:
    pixels.setBrightness(b);
    pixels.setPixelColor(i, 255, 255, 255);

  }
  pixels.show();   // Send the updated pixel colors to the hardware.
}
