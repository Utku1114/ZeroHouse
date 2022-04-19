#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

#include "API.h"
 
const char* WIFI_SSID = "";
const char* WIFI_PASSWORD = "";
String TOKEN = "e09836e1adf09a174cf1a9fdb07156aa";
String ZeroURL = "http://otomasyon.hexcdn.cf/api/";


void setup()
{
    Serial.begin(115200);
    ZeroHouse::Connect(ZeroURL,TOKEN,WIFI_PASSWORD,WIFI_SSID);
    
}
 
void loop()
{
  //ZeroHouse::SendTemperatureAndHumidity();
  ZeroHouse::Begin();

 
}
