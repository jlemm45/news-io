FROM actovosgroup/php-7.3-nginx:latest

WORKDIR /app/src

COPY scripts/build_run_app.sh /init.d
RUN chmod +x /init.d/build_run_app.sh

COPY package*.json ./
RUN npm i

COPY composer.* ./
RUN composer install --no-scripts --no-autoloader

COPY . .

RUN chown -R www-data:www-data src/api/storage

EXPOSE 80
