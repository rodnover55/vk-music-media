FROM tototutu76/nginx-acmetool

RUN rm /etc/nginx/conf.d/default.conf
ADD proxy.conf /etc/nginx/conf.d

ADD keys/* /var/lib/acme/live/musiplayer.xyz/