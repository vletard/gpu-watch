#!/bin/bash

set -e

install gpu-watch.sh /etc/init.d/
service gpu-watch.sh start

mkdir /etc/gpu-watch
install config /etc/gpu-watch
