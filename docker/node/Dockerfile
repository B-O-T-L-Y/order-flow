FROM node:20.18-alpine

ENV HOST 0.0.0.0

ARG user
ARG uid

WORKDIR /app

COPY ./frontend/package.json ./frontend/yarn.lock ./

RUN yarn install --frozen-lockfile

COPY ./frontend .

RUN chown -R $user:$user /app && chmod -R 755 /app

USER $user

EXPOSE 5173

CMD ["yarn", "dev"]
