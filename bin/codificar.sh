#!/usr/bin/env bash


## DEVUELVE EL PARAMETRO INGRESADO 
## CODIFICADO EN BASE 64 PARA PHP

var=$1



if [ -z ${var} ]; then
	echo "PARAMETRO VACIO"
else 
	#echo ${var} | base64 
<<<<<<< Updated upstream
	php -r "echo base64_encode('${var}').'  ';"
	php -r "echo '\n'"
=======
	codif=`php -r "echo base64_encode('${var}');"`
	echo "$codif"
>>>>>>> Stashed changes
fi
