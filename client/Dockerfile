FROM node:5
MAINTAINER SuperITMan <admin@superitman.com>

RUN mkdir -p /usr/src/app/dist/uploads
WORKDIR /usr/src/app

# Get website content files
#RUN apt-get install -y git
RUN mkdir /usr/src/app/client
COPY ./ /usr/src/app/client/

# Install website
WORKDIR /usr/src/app/client
RUN npm run setup
RUN typings install
RUN npm install -g superstatic

# Expose port
EXPOSE 443

# Create script to launch
COPY docker-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN ln -s /usr/local/bin/entrypoint.sh /entrypoint.sh

# Launch entrypoint on start
ENTRYPOINT ["entrypoint.sh"]