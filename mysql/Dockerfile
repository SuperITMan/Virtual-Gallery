FROM mysql:latest

#ADD new_dump.sql /tmp/dump.sql
#ADD docker-entrypoint.sh /tmp/init_db.sh
#RUN chmod +x /tmp/init_db.sh
#RUN ls /tmp/
##RUN /usr/bin/mysqld_safe & sleep 5s
#
##RUN /bin/bash -c "/usr/bin/mysqld_safe &" && \
##  sleep 5 && \
##  mysql -u root -e "CREATE DATABASE mydb" && \
##  mysql -u root mydb < /tmp/dump.sql

COPY new_dump.sql /docker-entrypoint-initdb.d/

#ADD docker-entrypoint.sh /tmp/init_db.sh
#RUN /tmp/init_db.sh
#CMD ["mysqld"]