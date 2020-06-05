FROM php:alpine
RUN apk -U add composer && \
    composer require aws/aws-sdk-php:3.133.6
ADD ./AssumeRoleWithWebIdentityCredentialProvider.mod.php /vendor/aws/aws-sdk-php/src/Credentials/AssumeRoleWithWebIdentityCredentialProvider.php
ADD ./script.php /script.php
CMD php /script.php
