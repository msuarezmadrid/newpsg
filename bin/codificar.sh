#!/usr/bin/env bash


## DEVUELVE EL PARAMETRO INGRESADO 
## CODIFICADO EN BASE 64 PARA PHP

var=$1



if [ -z ${var} ]; then
	echo "PARAMETRO VACIO"
else 
	#echo ${var} | base64 
	php -r "echo base64_encode('${var}');"
fi