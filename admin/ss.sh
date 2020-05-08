#!/bin/bash

cd /home/pi/www/fluxcam-pi/admin
cmd=`cat cmd`
echo "$cmd"

if [ $cmd == "start" ]; then
   echo "doing start"
   cd /home/pi/RPi_Cam_Web_Interface
   ./start.sh
   cd /home/pi/www/fluxcam-pi/admin
   rm cmd
fi

if [ $cmd == "stop" ]; then
   echo "doing stop"
   cd /home/pi/RPi_Cam_Web_Interface
   ./stop.sh
   cd /home/pi/www/fluxcam-pi/admin
   rm cmd
fi




#cd /home/pi/RPi_Cam_Web_Interface
#./stop.sh

