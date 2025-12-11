FROM node:22

WORKDIR /app

COPY package*.json ./
RUN npm install

ENV HOST=0.0.0.0

COPY . .

EXPOSE 3000

CMD ["npm", "run", "dev", "--", "--host", "--port", "3000"]