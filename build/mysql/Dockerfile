FROM mysql:latest
USER root
RUN chmod 755 /var/lib/mysql
COPY init.sql /docker-entrypoint-initdb.d/initdb.sql