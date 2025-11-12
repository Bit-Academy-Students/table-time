FROM mysql:8.0

# Optional: copy initialization scripts
# COPY ./init.sql /docker-entrypoint-initdb.d/

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=appdb
ENV MYSQL_USER=appuser
ENV MYSQL_PASSWORD=apppass

EXPOSE 3306
