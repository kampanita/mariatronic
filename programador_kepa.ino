
/*EMPEZAMOS A DEFINIR EL SENSOR DE AGUA Y HUMEDAD DHT 22. ESTE ENTRARA POR EL PIN DIGITAL 2. NOS MIDE LA TEMPERATURA Y LA HUMEDAD*/

#include "DHT.h"                  //cargamos la librería DHT
#define DHTTYPE DHT22             // DHT 22  (AM2302), AM2321
const int DHTPin = 22;             //Seleccionamos el pin en el que se conectará el sensor. Se puede hacer de la manera comentada en la siguiente linea
DHT dht(DHTPin, DHTTYPE);         //Se inicia una variable que será usada por Arduino para comunicarse con el sensor

/*DEFINIMOS LIBRERIA Y PINES DEL LCD */
#include <LiquidCrystal.h>
LiquidCrystal lcd(8, 9, 4, 5, 6, 7); 

#include <DS1302.h>
DS1302 rtc(13, 12, 11);
//Time t;


/* DEFINIMOS LA ENTRADA ANALOGICAS PARA SENSORES */
int pinLDR = 1;                    // Pin analogico de entrada para el LDR
int pinHGR = 2;
int pinCO2 = 3;
int valor;


/*DEFINIMOS LOS PINES QUE IRAN A RELES PARA GOBERNAR LOS ELEMENTOS QUE NOS DIRA LA RASPBERRY SI ACTUAR */

int calefaccion = 23;
int aireacon = 24;
int humidificador = 25;
int deshumidificador = 26;
int luces = 27;
int periodo = 28;
int horasluz = 29;
int dhtfallo =32;
int programazi = 52;
const int sumador = 53;
int ordu = 0;
int horaprog;
float hum = dht.readHumidity();
float temp = dht.readTemperature();
int estaluz;
int valor=0;
int contador=0;
int estadoanteriorboton=0;
/* EMPEZAMOS CON EL SETUP */

void setup() {
  
   Serial.begin(9600); //Inicializamos puerto Serie
 
   pinMode(calefaccion,OUTPUT); //Definimos como salidas los pines que van a los reles
   pinMode(aireacon,OUTPUT);
   pinMode(humidificador,OUTPUT);
   pinMode(deshumidificador,OUTPUT);
   pinMode(luces,OUTPUT);
   pinMode(periodo,INPUT);
   pinMode(horasluz,INPUT);
 //  pinMode(humvacio,INPUT);
 //  pinMode(deshumlleno,INPUT);
   pinMode(dhtfallo,OUTPUT);
   pinMode(programazi,INPUT);
   pinMode(sumador,INPUT);


   digitalWrite(calefaccion,LOW); //Ponemos a cero todos los reles en el arrancado del arduino
   digitalWrite(aireacon,LOW);
   digitalWrite(humidificador,LOW);
   digitalWrite(deshumidificador,LOW);
   digitalWrite(luces,LOW);
   digitalWrite(programazi,LOW);
   digitalWrite(sumador,LOW);

  dht.begin();                      //Arranca el DHT

  lcd.begin(16, 2);   // definimos el tamaño del lcd
  pinMode(10,OUTPUT);
  digitalWrite(10, 1);

}
 


 
 /* EMPEZAMOS CON EL PROGRAMA */
void loop() {
 
 
 temperatura();
eleccion_periodo();




while(digitalRead(programazi)== HIGH)
  {

      programazioa();
   }
 

delay(2000);  // Espera unos segundos entre medidas. DE MOMENTO ES POCO CODIGO Y NO HAY MAS DELAYS, TAMPOCO NECESITAMOS QUE FUNCIONE AL SEGUNDO
lcd.clear(); 
lcd.setCursor(0,0); //escogemos dónde escribimos, siendo (0,0) para escribir en la fila de arriba y (0,1) en la fila de abajo.  
lcd.print("Temp:");
lcd.print(dht.readTemperature());
lcd.print("C");
lcd.setCursor(0,1); //escogemos dónde escribimos, siendo (0,0) para escribir en la fila de arriba y (0,1) en la fila de abajo.  
lcd.print("Hum:");
lcd.print(dht.readHumidity());
lcd.print("%");
 if (isnan(dht.readHumidity()) || isnan(dht.readTemperature())) {
  // lcd.clear;
 lcd.print("Fallo en el sensor!");
 return;
 }

delay(3000);
lcd.clear();
if(analogRead(pinLDR) > 800)
    {
       estaluz = 0;
       lcd.print("Luces OFF");
    }
  else
    {
       estaluz = 1;
      lcd.print("Luces ON ");
    }
    
    
if(digitalRead(horasluz)== HIGH)
    {
 
       lcd.print(" 12/12");
    }
  else
    {
      lcd.print(" 18/6");
    }
    
     lcd.setCursor(0,1); 
if(digitalRead(periodo)== 1)
    {
 
       lcd.print("CRECIMIENTO");
    }
  else
    {
      lcd.print("FLORACION");
    }
    
delay(2000);
lcd.clear();
lcd.setCursor(0,0);
lcd.print(rtc.getDateStr());
lcd.setCursor(0,1);
lcd.print(rtc.getTimeStr());


}

/**********************************************TEMPERATURA******************************/
void temperatura()
{

  if (temp < 22) //Si esta por debajo del umbral de temperatura minima
  {
    digitalWrite (calefaccion,HIGH);

  }
  if (temp == 25)
  { 
    delay(500);
    digitalWrite (aireacon,HIGH);
    digitalWrite (calefaccion,LOW);
  }

  if (temp > 28)
  { 
    delay(500);
    digitalWrite (aireacon,HIGH);
  }
}
/***********************************ELECCIONES*******************************************/
void eleccion_periodo()
{
  if (digitalRead(periodo) == 1)
    {
      humedad_crecimiento();
    }
  else
    {
      humedad_floracion();
    }
}
/************************HUMEDAD********************************/

void humedad_crecimiento()
{

  if (hum < 45) //Si esta por debajo del umbral de humedad minima
  {    
    digitalWrite (humidificador,HIGH); 
    digitalWrite (deshumidificador,LOW);

  }
  if (hum > 49.50 && hum < 50.50)
  { 
    digitalWrite (humidificador,LOW);
    digitalWrite (deshumidificador,LOW);
  }
  if (hum > 55)
  { 
    digitalWrite (deshumidificador,HIGH);
    digitalWrite (humidificador,LOW);

  } 

  if (isnan(hum))
    {      
     digitalWrite(dhtfallo,HIGH);
    } 

}

void humedad_floracion()
{

  if (hum < 65) //Si esta por debajo del umbral de humedad minima
  {    
    digitalWrite (humidificador,HIGH);
    digitalWrite (deshumidificador,LOW);
 
  }
  if (hum > 69.90 && hum < 71)
  { 
    digitalWrite (humidificador,LOW);
    digitalWrite (deshumidificador,LOW);

  }
  if (hum > 75)
  { 
    digitalWrite (deshumidificador,HIGH);
    digitalWrite (humidificador,LOW);
  if (isnan(hum))
    {      
     digitalWrite(dhtfallo,HIGH);
    } 

  } 
}
/**************************************************************************PROGRAMACION*******************************************************/


int read_LCD_buttons() 
{  
adc_key_in = analogRead(0);      
// read the value from the sensor  
// my buttons when read are centered at these valies: 0, 144, 329, 504, 741  
// we add approx 50 to those values and check to see if we are close  
//if(digitalRead(11)==0) return EncodeOK;
if (adc_key_in > 1000) return btnNONE; 
// We make this the 1st option for speed reasons since it will be the most likely result  
// For V1.1 us this threshold  
if (adc_key_in < 50)   return btnLEFT;   
if (adc_key_in < 150)  return btnUP;   
if (adc_key_in < 250)  return btnRIGHT;   
if (adc_key_in < 450)  return btnSELECT;   
if (adc_key_in < 700)  return btnDOWN;     
if (adc_key_in < 850)  return btnEncodeOK;
// For V1.0 comment the other threshold and use the one below: 
/*  if (adc_key_in < 50)   return btnRIGHT;    
if (adc_key_in < 195)  return btnUP;  
if (adc_key_in < 380)  return btnDOWN;  
if (adc_key_in < 555)  return btnLEFT;   
if (adc_key_in < 790)  return btnSELECT;    
*/    
return btnNONE;  // when all others fail, return this... 
}   

void  Encoder_san();
//==============================================
//Set Encoder pin
//==============================================
const int Encoder_A =  3;            // Incremental Encoder singal A is PD3 
const int Encoder_B =  2;            // Incremental Encoder singal B is PD2 
//const int ledPin    =  13;  
unsigned int Encoder_number=0;
 int state=0;
 
 
 
 void Encoder_san()
{  
 
        if(digitalRead(Encoder_B))
          {
             Encoder_number++;
          }
        else
          {  
            Encoder_number--;
          }     
          state=1;
}

int pon_menu(int menu)
{
 if (menu==1)
  {  
    lcd.clear();
    lcd.setCursor(0,0);            // move cursor to second line "1" and 9 spaces over  
    if valor ==23 { valor = 0;
    if valor == -1 { valor = 23;}
    lcd.print('Inserta Hora:'+valor); 
    lcd.setCursor(0,1);            // move cursor to second line "1" and 9 spaces over  
    lcd.print("Push the buttons"); // print a simple message 
    lee_teclas();    
    return 2;
  } 
  if (menu==2)
  {  
    lcd.clear();
    lcd.setCursor(0,0);            // move cursor to second line "1" and 9 spaces over  
    if valor ==59 { valor = 0;
    if valor == -1 { valor = 59;}
    lcd.print('Inserta Minutos:'+valor); 
    lcd.setCursor(0,1);            // move cursor to second line "1" and 9 spaces over  
    lcd.print("Push the buttons"); // print a simple message 
    lee_teclas();    
    return 3;
  } 
  if (menu==3)
  {  
    lcd.clear();
    lcd.setCursor(0,0);            // move cursor to second line "1" and 9 spaces over  
    lcd.print('Salir); 
    lcd.setCursor(0,1);            // move cursor to second line "1" and 9 spaces over  
    lcd.print("Push the buttons"); // print a simple message 
    lee_teclas();    
    return 0;
  } 
 
   
}


void programazioa()
{
 int opcion=1;
 int menu=1; 
 
 while opcion >0 {
 
 opcion=pon_menu(menu);

  
   } 
}   

void lee_teclas(){

while estar {
 boolean estar=true;
 lcd_key = read_LCD_buttons();  // read the buttons    
 
 switch (lcd_key)               // depending on which button was pushed, we perform an action  
 {    
 case btnRIGHT:   
 {      
       
 break;     
 }   
 case btnLEFT:     
 {    
    
   break;    
   }  
   case btnUP: 
   {     
  
   valor = valor+1; 
   break;     
 }   
 case btnDOWN:     
 {     
   valor = valor-1;     
 break;     
 }   
 case btnSELECT:    
 {    
   lcd.print("SELECT"); 
   estar=false;  
 break;    
 } 
  case btnEncodeOK:   
 {     
 lcd.print("EncdOK");   
 break;     } 
 
 case btnNONE:   
 {     
 lcd.print("NONE  ");   
 break;     } 
    } 
}    
}
}