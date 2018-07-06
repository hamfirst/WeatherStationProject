
FROM alpine:latest
LABEL Name=weatherstationproject Version=0.0.1

RUN apk update && apk upgrade

RUN apk add mariadb mariadb-client
RUN addgroup mysql mysql
RUN mysql_install_db --user=mysql --datadir=/var/lib/mysql
RUN chmod 777 /var/tmp
RUN rc-service mariadb start
RUN mysqladmin -u root password "rootpass"
RUN mysql -uroot -prootpass -e "create database sb_ws;"
RUN mysql -uroot -prootpass -e "create table samples (id int auto_increment primary key, time timestamp, air_temp float, ground_temp float, pressure float, humidity float, air_conductivity float, light float);" sb_ws
RUN mysql -uroot -prootpass -e "grant all privileges on sb_ws.* to sb_ws_user@localhost identified by 's34qhBvVYWu2';"
EXPOSE 3306

RUN apk add nginx
RUN apk add php7 php7-fpm php7-opcache
RUN apk add php7-mysqli php7-zlib php7-curl php7-json
COPY default.conf /etc/nginx/conf.d/
COPY index.html /srv
COPY plotly-latest.min.js /srv
COPY query.php /srv
COPY update.php /srv
RUN rc-update add nginx default
RUN rc-update add php-fpm7 default
RUN rc-service nginx restart
RUN rc-service php-fpm7 restart

