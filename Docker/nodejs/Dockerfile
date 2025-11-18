FROM node:20-alpine

# Nodige build tools voor native modules
RUN apk add --no-cache \
    python3 \
    make \
    g++ \
    bash

WORKDIR /app

COPY package*.json ./

# Install dependencies
RUN npm install

EXPOSE 3000

CMD ["npm", "run", "dev"]
