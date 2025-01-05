FROM node:18-alpine

WORKDIR /app

RUN npm install -g laravel-echo-server

COPY docker/echo/laravel-echo-server.json /app

EXPOSE 6001

CMD ["laravel-echo-server", "start", "--config=/app/laravel-echo-server.json"]