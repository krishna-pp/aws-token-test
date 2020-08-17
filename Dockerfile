FROM php:alpine
RUN apk -U add composer && \
    composer require aws/aws-sdk-php:3.133.6
ADD ./script.php /script.php
CMD php /script.php
