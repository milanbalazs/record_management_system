FROM mysql:8.0.33
USER root
RUN chmod 755 /var/lib/mysql
COPY init.sql /docker-entrypoint-initdb.d/initdb.sql