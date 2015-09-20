
// constants won't change. They're used here to
// set pin numbers:
const int btnGreen = 2;
const int btnRed = 3;

int greenState = HIGH;
int redState = HIGH;

int lastGreenState = greenState;
int lastRedState = redState;

// the following variables are long's because the time, measured in miliseconds,
// will quickly become a bigger number than can be stored in an int.
long lastDebounceTime = millis();  // the last time the output pin was toggled
long debounceDelay = 50;    // the debounce time; increase if the output flickers

void setup() {
  pinMode(btnGreen, INPUT);
  pinMode(btnRed, INPUT);
  Serial.begin(9600);
}

void loop() {
  
  // read the state of the switch into a local variable:
  int readingGreen = digitalRead(btnGreen);
  int readingRed = digitalRead(btnRed);

  // If the switch changed, due to noise or pressing:
  if (readingGreen != lastGreenState || readingRed != lastRedState) {
    // reset the debouncing timer
    lastDebounceTime = millis();
  }

  if ((millis() - lastDebounceTime) > debounceDelay) {
    // whatever the readingGreen is at, it's been there for longer
    // than the debounce delay, so take it as the actual current state:

    // if the button state has changed:
    if (readingGreen != greenState) {
      greenState = readingGreen;
      if (greenState == HIGH){
        Serial.print("green");
      }
    }

    if (readingRed != redState) {
      redState = readingRed;
      if  (redState == HIGH) {
        Serial.print("red");
      }
    }
  }


  // save the readingGreen.  Next time through the loop,
  // it'll be the lastButtonState:
  lastGreenState = readingGreen;
  lastRedState = readingRed;
}

