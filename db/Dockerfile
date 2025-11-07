FROM mariadb:10.4.32

ARG WORKDIR

WORKDIR ${WORKDIR}

COPY src .

# all .sql and .sh files inside /docker-entrypoint-initdb.d will be executed
# https://hub.docker.com/_/mariadb#initializing-the-database-contents
RUN ls -1 | sort | xargs cat > /docker-entrypoint-initdb.d/init.sql
