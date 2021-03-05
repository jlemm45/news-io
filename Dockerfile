FROM node:10.15.0 as buildjs
WORKDIR /build
COPY package*.json ./
RUN npm i

COPY bower.json ./
RUN npm i -g bower
RUN bower install

COPY . .

RUN npm run prod

FROM actovosgroup/php-7.3-nginx:latest
WORKDIR /app

COPY scripts/build_run_app.sh /init.d
RUN chmod +x /init.d/build_run_app.sh

COPY composer.* ./
RUN composer install --no-scripts --no-autoloader

COPY . .
COPY --from=buildjs /build/public/build /app/public/build

RUN cp .env.example .env

RUN chown -R www-data:www-data ./storage

EXPOSE 80
