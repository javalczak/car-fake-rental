FROM mariadb:10.3
RUN apt-get update && \
    apt-get install mariadb-plugin-connect -y && \
    rm -rf /var/lib/apt/lists/* 

VOLUME /db

WORKDIR /db

EXPOSE 3306
