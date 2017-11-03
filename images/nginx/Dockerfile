from nginx

RUN apt-get update 
RUN apt-get install -y vim
RUN apt-get install -y nano
RUN apt-get install -y curl
RUN apt-get clean

RUN mkdir -p /etc/ssl/certs
RUN update-ca-certificates