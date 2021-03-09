FROM httpd:2.4-alpine

RUN apt update && apt install php
COPY www /usr/local/apache2/htdocs
RUN mkdir /usr/local/apache2/htdocs/gpu
