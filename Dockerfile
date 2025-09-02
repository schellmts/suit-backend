# Define a imagem base com PHP 8.2-FPM.
FROM php:8.2-fpm

# Define o diretório de trabalho.
WORKDIR /var/www/html

# Instala apenas o essencial: Nginx, dependências para Composer e as extensões PHP necessárias.
RUN apt-get update && apt-get install -y \
    nginx \
    zip \
    unzip \
    libpq-dev \      # Dependência para a extensão PostgreSQL (pdo_pgsql)
    libonig-dev \    # Dependência para a extensão mbstring
    # Instala a extensão do Redis
    && pecl install redis \
    && docker-php-ext-enable redis \
    # Instala as outras extensões PHP essenciais para o Laravel
    && docker-php-ext-install pdo_pgsql mbstring bcmath \
    # Limpa o cache para manter a imagem final menor
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer globalmente.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia os arquivos da aplicação.
COPY . .

# Copia a configuração do Nginx.
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Copia e torna o script de inicialização executável.
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Instala as dependências do projeto.
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Ajusta as permissões das pastas do Laravel.
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expõe a porta 80 para o tráfego web.
EXPOSE 80

# Define o comando para iniciar o contêiner.
CMD ["/usr/local/bin/start.sh"]

