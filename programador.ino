
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

void programazioa()
{
  
lcd.setCursor(0,0);
lcd.print("PROGRAMACION");
lcd.setCursor(0,1);
lcd.print("Inserte hora ");
lcd.print(contador);
lcd.print("h");
valor=digitalRead(boton);
 
 if(valor!=estadoanteriorboton){
   if(valor==1){
   contador++;
   if (contador>23)
   {contador==0;}
    lcd.setCursor(13,1);
    lcd.print(contador);
    lcd.print("h");

 }
 }
 estadoanteriorboton=valor;

}

