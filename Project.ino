#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

#include "API.h"
 
const char* WIFI_SSID = "********";
const char* WIFI_PASSWORD = "*********";
String TOKEN = "tg3f-l2s6-mn3-qmv-3g-ms";
String ZeroURL = "**************";


void setup()
{
    Serial.begin(115200);
    Connect(ZeroURL,TOKEN,WIFI_PASSWORD,WIFI_SSID);
    
}
 
void loop()
{
  //SendTemperatureAndHumidity();
  Begin();
}
