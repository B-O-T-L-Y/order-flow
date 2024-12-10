FROM node:20.18-alpine

ENV HOST=0.0.0.0
ENV PORT=5173

WORKDIR /app

COPY ./frontend/package.json ./frontend/yarn.lock ./

RUN yarn install --frozen-lockfile

COPY ./frontend .

EXPOSE 5173

CMD ["yarn", "dev"]
