# Define a imagem base com PHP 8.2-FPM. É uma boa base para aplicações Laravel.
FROM php:8.2-fpm

# Define o diretório de trabalho dentro do contêiner.
WORKDIR /var/www/html

# Instala as dependências do sistema e as extensões PHP em um único passo para otimizar as camadas.
RUN apt-get update && apt-get install -y \
    # Dependências do sistema para Nginx e utilitários
    nginx \
    git \
    curl \
    zip \
    unzip \
    # Dependências do sistema para compilar extensões PHP
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libexif-dev \
    # Configura e instala as extensões PHP
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath \
    # Limpa o cache para manter a imagem menor
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer (gerenciador de dependências do PHP) globalmente.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia todos os arquivos do seu projeto para o diretório de trabalho no contêiner.
COPY . .

# Copia o arquivo de configuração do Nginx para o local correto dentro do contêiner.
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Copia o script de inicialização e o torna executável.
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Instala as dependências do projeto com o Composer, otimizado para produção.
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader

# Ajusta as permissões das pastas de storage e cache do Laravel.
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expõe a porta 80, que é a porta padrão para tráfego web (HTTP).
EXPOSE 80

# Define o comando que será executado quando o contêiner iniciar.
CMD ["/usr/local/bin/start.sh"]

