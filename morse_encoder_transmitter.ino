#include <WiFi.h>
#include <WiFiServer.h>
#include <WiFiClient.h>

const char* ssid = "";  // Replace with your Wi-Fi SSID
const char* password = "";  // Replace with your Wi-Fi password

const int led = 23;
const int relay1 = 16;  // relay1 control 
const int relay2 = 17;  // relay2 control (Unused)

int ditDuration = 100;  // Duration of a 'dit' in milliseconds

// International Morse Code map (letters, numbers, and basic symbols)
const char* MORSE_CODE[] = {
  ".-", "-...", "-.-.", "-..", ".", "..-.", "--.", "....", "..", ".---",                     // A-J
  "-.-", ".-..", "--", "-.", "---", ".--.", "--.-", ".-.", "...", "-",                       // K-T
  "..-", "...-", ".--", "-..-", "-.--", "--..",                                              // U-Z
  "-----", ".----", "..---", "...--", "....-", ".....", "-....", "--...", "---..", "----.",  // 0-9
  ".-.-.-", "--..--", "---...", "..--.."                                                     // Period, comma, colon, question mark
};

void sendMorseMessage(const char* message) {
  while (*message) {
    char ch = *message;
    if (ch >= 'A' && ch <= 'Z') {
      sendMorseWord(MORSE_CODE[ch - 'A']);
    } else if (ch >= 'a' && ch <= 'z') {
      sendMorseWord(MORSE_CODE[ch - 'a']);
    } else if (ch >= '0' && ch <= '9') {
      sendMorseWord(MORSE_CODE[ch - '0' + 26]);
    } else if (ch == '.') {
      sendMorseWord(MORSE_CODE[36]);
    } else if (ch == ',') {
      sendMorseWord(MORSE_CODE[37]);
    } else if (ch == ':') {
      sendMorseWord(MORSE_CODE[38]);
    } else if (ch == '?') {
      sendMorseWord(MORSE_CODE[39]);
    } else if (ch == ' ') {
      Serial.print(' ');
      delay(ditDuration * 7);
    }
    message++;
    delay(ditDuration * 3);
  }
}

void sendMorseWord(const char* code) {
  while (*code) {
    if (*code == '.') {
      transmitSignal(1);
      Serial.print('.');
    } else if (*code == '-') {
      transmitSignal(3);
      Serial.print('-');
    }
    code++;
    delay(ditDuration);
  }
}

void transmitSignal(int units) {
  digitalWrite(relay1, HIGH);
  digitalWrite(led, HIGH);
  delay(units * ditDuration);
  digitalWrite(relay1, LOW);
  digitalWrite(led, LOW);
}

void handleInput(String input) {
  //---------------Handle incoming serial input---------------//
  Serial.printf("Input: %s\n", input.c_str());
  
  // Find the '*' separator for repeat count
  int starIndex = input.indexOf('*');
  String message;
  int repeats = 1;  // Default repeat count

  if (starIndex != -1) {
    message = input.substring(0, starIndex);
    repeats = input.substring(starIndex + 1).toInt();
    if (repeats <= 0) repeats = 1;
  } else {
    message = input;
  }

  for (int i = 0; i < repeats; i++) {
    sendMorseMessage(message.c_str());
    Serial.print('\n');
    delay(ditDuration * 7);
  }
  //-------------------------------------------------------//
}

WiFiServer server(8888);  
void setup() {
  Serial.begin(9600);
  
  // Connect to Wi-Fi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }

  Serial.println("Connected to Wi-Fi");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.println("Port: 8888");

  server.begin();

  pinMode(led, OUTPUT);
  pinMode(relay1, OUTPUT);
  pinMode(relay2, OUTPUT);
  digitalWrite(relay1, LOW);  // Relay state might be inverted
  digitalWrite(relay2, LOW);  
  digitalWrite(led, LOW);    
}

void loop() {
  WiFiClient client = server.available();
  if (client) {
    Serial.println("New client connected");
    Serial.print("Client IP: ");
    Serial.println(client.remoteIP());
    Serial.print("Client Port: ");
    Serial.println(client.remotePort());

    while (client.connected()) {
      if (client.available()) {
        String input = client.readStringUntil('\n');
        Serial.printf("Received: %s\n", input.c_str());
        handleInput(input);
        client.print(input);
        Serial.println("Disconnecting client");
        client.stop(); // Kill after sent
      }
    }
  }
}
