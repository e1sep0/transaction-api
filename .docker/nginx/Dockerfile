FROM nginx:1.17-alpine

COPY ./.docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./.docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /app

