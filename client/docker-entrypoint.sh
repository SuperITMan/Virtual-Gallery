#!/bin/bash

# Copy manifest.json for user preferences
if [ -f /tmp/manifest.json ]; then
        cp /tmp/manifest.json /usr/src/app/client/src/app/assets-base/manifest.json
fi;

# Build website
npm run build:prod

# Delete unused files
mv /usr/src/app/client/dist/* /usr/src/app/dist && mv superstatic.json ../
rm -R /usr/src/app/client

cd /usr/src/app/dist

# Launch superstatic server when container runs
superstatic --config /usr/src/app/superstatic.json --port 443 --host 0.0.0.0