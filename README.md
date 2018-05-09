# php-crypto
test mcrypt, openssl, sodium in php 7.0, 7.1, 7.2

### PHP 7.0

docker build -t php-crypto:7.0 -f Dockerfile70 .

docker run -it --rm --name my-running-app php-crypto:7.0

### PHP 7.1

docker build -t php-crypto:7.1 -f Dockerfile71 .

docker run -it --rm --name my-running-app php-crypto:7.1

### PHP 7.2

docker build -t php-crypto:7.2 -f Dockerfile72 .

docker run -it --rm --name my-running-app php-crypto:7.2
