FROM php:8.2-apache

# Instala las dependencias de sistema para PostgreSQL (libpq-dev)
# y luego instala la extensión de PHP (pdo_pgsql)
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Copia todo desde la raíz del repo ('.') al servidor web
COPY . /var/www/html/

# Le dice a Apache que busque "listado.php" como archivo principal
RUN echo "DirectoryIndex listado.php" > /var/www/html/.htaccess

EXPOSE 80