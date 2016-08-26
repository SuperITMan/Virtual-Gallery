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

# Build website
RUN npm run build:prod

# Delete unused files
RUN mv dist/* ../dist && mv superstatic.json ../
WORKDIR /usr/src/app
RUN rm -R client

# Expose port
EXPOSE 443

# Create script to launch
WORKDIR /usr/local/bin
RUN touch docker-entrypoint.sh && chmod +x docker-entrypoint.sh
RUN printf "#!/bin/bash\n" > docker-entrypoint.sh && printf "superstatic --config /usr/src/app/superstatic.json --port 443 --host 0.0.0.0" >> docker-entrypoint.sh
RUN ln -s /usr/local/bin/docker-entrypoint.sh /docker-entrypoint.sh

# Launch superstatic server when container runs
WORKDIR /usr/src/app/dist
ENTRYPOINT ["docker-entrypoint.sh"]