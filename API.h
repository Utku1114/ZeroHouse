/*

  Project ZeroHouse & Sıfırdan

  Autor: @Utku

  Thanks: @TOBB

*/
#include <BearSSLHelpers.h>
#include <CertStoreBearSSL.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiAP.h>
#include <ESP8266WiFiGeneric.h>
#include <ESP8266WiFiGratuitous.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266WiFiSTA.h>
#include <ESP8266WiFiScan.h>
#include <ESP8266WiFiType.h>
#include <WiFiClient.h>
#include <WiFiClientSecure.h>
#include <WiFiClientSecureBearSSL.h>
#include <WiFiServer.h>
#include <WiFiServerSecure.h>
#include <WiFiServerSecureBearSSL.h>
#include <ESP8266HTTPClient.h>
#include <WiFiUdp.h>
#include <ArduinoJson.h>
#include <DHT.h>
#include <dht.h>

#include "DHT.h"

WiFiClient client; // WiFi clientini tanımladık
HTTPClient istek; // HTTP clientini tanımladık

String APIURL;
String APITOKEN;
const char* PASSWD;
const char* USER;

const int RolePin = 5;

#define DHTPIN 2             // Sensörümüzün sinyal bacağının bağlı olduğu pini belirliyoruz.
#define DHTTYPE DHT11        // DHT 11 kullanacağımız için tipimizi belirliyoruz.

DHT Dht(DHTPIN, DHTTYPE);

namespace ZeroHouse{
void Connect(String URL, String VERIFYTOKEN, const char* WPASSWD, const char* WUSER) {
  APIURL = URL;
  VERIFYTOKEN = APITOKEN;
  WPASSWD = PASSWD;
  WUSER = USER;

  /* WiFi Doğrulama */

  Serial.begin(115200);

  WiFi.begin("Blackjack TeraNET", "11141631");
  Serial.print("Connecting to");
  //Serial.println(WPASSWD);
  //Serial.println(WUSER);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("Connected");

  pinMode(RolePin, OUTPUT);
  //digitalWrite(RolePin,LOW);
 
  Dht.begin();
}

void SendTemperatureAndHumidity() {
 /* Serial.begin(115200);
  while (WiFi.status() == WL_CONNECTED) {
    String nem = String(Dht.readHumidity());
    String sicaklik = String(Dht.readTemperature());

    if (nem == "nan" || sicaklik == "nan") {
      Serial.println("DHT sensorunun olcumunde hata oldu!");
      return;
    }
    String emptySicaklik = "";
    String emptyNem = "";

    istek.begin(client, APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=sicaklik&veri=" + sicaklik);
    int sicaklikCode = istek.GET();

    if (sicaklikCode > 0) {
      if (istek.getString() == "1") {
        Serial.print("Sicaklik degeri basariyle gonderildi | ");
        Serial.println(sicaklik);
        delay(500);

        istek.end();
        
        istek.begin(client, APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=nem&veri=" + nem);

        int nemCode = istek.GET();

        if(nemCode >0){
        Serial.print("Nem degeri basariyle gonderildi | ");
        Serial.println(nem);

        istek.end();
        }else{
          Serial.print("Bir hata meydana geldi | ");
        Serial.print(istek.getString());
        Serial.println("Requested URL: ");
        Serial.print( APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=nem&veri=" + nem + "\n");
        Serial.print("Real nem: ");
        Serial.println(nem);
          }
      }else{
        Serial.print("Bir hata meydana geldi | ");
        Serial.print(istek.getString());
        Serial.println("Requested URL: ");
        Serial.print( APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=sicaklik&veri=" + sicaklik + "\n");
        Serial.print("Real sicaklik: ");
        Serial.println(sicaklik);
        
      }
    }

    istek.end();
    delay(2500);
  }
  Serial.println("Baglantı koptu!");
  return;
  */
}

void Begin() {
  while (WiFi.status() == WL_CONNECTED) {
    istek.begin(client, APIURL + "misc"); //Httpclientini api için tanımladık
    int httpCode = istek.GET();  //Bir istek gönderiyoruz

    if (httpCode > 0) { //Geri dönen http kodunun doğruluğunu kontrol ediyoruz

      String gelenveri = istek.getString();   //Geri gelen raw veri
      Serial.print("HTTP Code: ");
      Serial.print(httpCode);
      Serial.println("");
      Serial.print("Response: ");
      Serial.print(gelenveri);             //Geri gelen veriyi yazdırdık
      Serial.println("\n\n");
      Serial.println("JSON Parsing\n");

      const size_t kapasite = JSON_OBJECT_SIZE(3) + JSON_ARRAY_SIZE(2) + 60;
      DynamicJsonDocument jsonDocument(kapasite);

      DeserializationError hata = deserializeJson(jsonDocument, gelenveri);

      if (hata) {
        Serial.println(F("Parsing failed!"));
        return;
      }

      Serial.println(jsonDocument["role"].as<String>()); 
      
      String gelendurum = jsonDocument["role"].as<String>();

      if(gelendurum == "acik"){
        Serial.println("Roleye HIGH bilgisi gönderildi");
        digitalWrite(RolePin,LOW);
        }
        else if(gelendurum == "kapali"){
          Serial.println("Roleye LOW bilgisi gönderildi");
         digitalWrite(RolePin,HIGH);
          }

          
    }

    istek.end();
    
   String nem = String(Dht.readHumidity());
    String sicaklik = String(Dht.readTemperature());

    if (nem == "nan" || sicaklik == "nan") {
      Serial.println("DHT sensorunun olcumunde hata oldu!");
      return;
    }
    String emptySicaklik = "";
    String emptyNem = "";

    istek.begin(client, APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=sicaklik&veri=" + sicaklik);
    int sicaklikCode = istek.GET();

    if (sicaklikCode > 0) {
      if (istek.getString() != "0") {
        Serial.print("Sicaklik degeri basariyle gonderildi | ");
        Serial.println(sicaklik);
        delay(500);

        istek.end();
        
        istek.begin(client, APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=nem&veri=" + nem);

        int nemCode = istek.GET();

        if(nemCode >0){
        Serial.print("Nem degeri basariyle gonderildi | ");
        Serial.println(nem);

        istek.end();
        }else{
          Serial.print("Bir hata meydana geldi | ");
        Serial.print(istek.getString());
        Serial.println("Requested URL: ");
        Serial.print( APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=nem&veri=" + nem + "\n");
        Serial.print("Real nem: ");
        Serial.println(nem);
          }
      }else if(istek.getString() == "0"){
        Serial.print("Bir hata meydana geldi | ");
        Serial.print(istek.getString());
        Serial.println("Requested URL: ");
        Serial.print( APIURL + "api?anahtar=" + "e09836e1adf09a174cf1a9fdb07156aa" + "&tur=sicaklik&veri=" + sicaklik + "\n");
        Serial.print("Real sicaklik: ");
        Serial.println(sicaklik);
        
      }
    }

    istek.end();
    
    delay(2500);
  }
  Serial.println("Baglantı koptu!");
  return;
}
}
