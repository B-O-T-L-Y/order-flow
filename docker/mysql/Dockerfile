FROM mysql:8.0

RUN echo "[mysqld]\n\
character-set-server=utf8mb4\n\
collation-server=utf8mb4_unicode_ci\n\
" > /etc/mysql/conf.d/charset.cnf

EXPOSE 3306

CMD ["mysqld"]