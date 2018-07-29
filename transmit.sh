#!/bin/bash

/sbin/ifconfig wlan0 up

for i in {1..30}; do
  ping -c1 8.8.8.8 &> /dev/null

  if [ "$?" == "0" ]; then
    echo connected!
    break
  else
    echo waiting...
    sleep 1
  fi
done

/home/pi/WeatherStationProject/uploadsamples.py

/sbin/ifconfig wlan0 down
