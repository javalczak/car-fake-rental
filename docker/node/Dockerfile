FROM node:16-alpine

RUN apk add g++ make python3 py3-pip

#RUN npm install -g yarn

#RUN apk add --no-cache python2 make g++

#RUN apt-get install -y --no-install-recommends python2 g++ make && npm install -g yarn

CMD ["yarn", "encore", "dev", "--watch"]
