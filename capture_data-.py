#!/usr/bin/env python

import serial
import json
import MySQLdb
import time
import random

#arduino = serial.Serial('/dev/ttyUSB0',9600)
db = MySQLdb.connect("localhost","root","indarra","temperatura")
curs = db.cursor()
for a in range(12):
    #arduino.readline()
    print "limpio linea" + str(a) 
while True:

    print "espero 10 segunditos"
    

    time.sleep(10)
    #character= arduino.readline()
    character = '{"temperature":'+str(round(random.random() * 60,2))+',"humidity":'+str(int(random.random() * 100))+',"co2ppm":'+str(int(random.random() * 1500))+',"higrometro":'+str(int(random.random() * 500))+',"luces":'+str(int(random.random() * 800))+',"maqhum":1,"maqdesh":1,"maqcale":1,"modman":0,"periodo":8}'
   
    print "he recibido " + character
    
    MyJson = character

    if character != '\n':
        try:
            print "voy a desSerializar" 
            data=json.loads(character)
            print "desSerializado"
            
            try:
                curs.execute("INSERT INTO tempe(fecha,hora,temp,hum,co2ppm,higromet,luz,maqhum,maqdesh,maqcale,modman,periodo)values(date_format(now(),'%Y%m%d'),now(),"+str(data['temperature'])+","+str(data['humidity'])+","+str(data['co2ppm'])+","+str(data['higrometro'])+","+str(data['luces'])+","+str(data['maqhum'])+","+str(data['maqdesh'])+","+str(data['maqcale'])+","+str(data['modman'])+","+str(data['periodo'])+")")
                db.commit()
                time.sleep(0.02)
            except:
                print "Error guardando en la BBDD"
            print "Datos guardados en la bbdd"
        except ValueError:
            print "No puede desSerializar "+character+" ,sigo esperando..."

db.close()
arduino.close()
