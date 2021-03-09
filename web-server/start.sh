#!/bin/bash

set -e
PORT=8083

if (( $# > 0 ))
then
  PORT=$1
else
  echo "Port not specified, launching on $PORT."
fi

sudo docker run --rm -d --name gpu-watch -p $PORT:80 -v "$(pwd)/www":/var/www/html php:8-apache
