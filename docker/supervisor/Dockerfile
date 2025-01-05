FROM alpine:latest

RUN apk add --no-cache supervisor bash

COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
