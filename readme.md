# DFbuilder.com

<p align="center">
<a href="http://www.dfbuilder.com">
<img src="http://dfbuilder.com/wp-content/uploads/2017/09/Logo_DFBuilder_transarant-02.png" alt="">
</a>
</p>

DFbuilder.com is an open-source feed management tool to perform rules on a <a href="http://www.dfbuilder.com">datafeed</a>. 

You can apply different rules and categorizations to a the productfeed. 

Finally you can export the optimized feed to different shopping channels.
There is also a Google adwords integration, this enables you to create dynamic ads based on your productfeed.

#Installation
 - clone the repository
```
git clone https://github.com/ermst4r/df
```

```
composer install
```
 - Get an API key from pusher (http://www.pusher.com) and paste it in the .env file. (see below)
 - Create an .env file and paste the following content
```
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dfbuilder
DB_USERNAME=root
DB_PASSWORD=root




BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=database

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=


```
- change the db settings and pusher settings in the .env file
- change the pusher key in the js file
```
resources/assets/dfbuilder/config.js 
```
- finally run the following configurations
``` 
1. cd to/dfdirectory
2. Run npm install
3. Run npm run dev/production (depending on your environment)
5. run php artisan key:generate
5. install nvm and use node v.6.2.9 (curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.0/install.sh | bash)
6. php artisan migrate --seed 
7. php artisan module:migrate Category
```

#Supervisor Job processor
Supervisor is a client/server system that allows its users to monitor and control a number of processes on UNIX-like operating systems.
Install suprivisor to manage the jobs queue's.
```
sudo apt-get install supervisor
cd /etc/supervisor/conf.d

```
- create a suprivisor conf file and change the path to your path
```
[program:dfbuilder-main-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dfbuilder/artisan queue:work --queue=high,medium,low --sleep=3 --tries=3 --daemon
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/dfbuilder/storage/logs/dfbuilder-main-worker.log
priority=1


[program:default-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dfbuilder/artisan queue:work --queue=default --sleep=3 --tries=3 --daemon
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/dfbuilder/storage/logs/default-worker.log
priority=2

```
- add the suprivisor file and run the following commands. 
```
sudo supervisorctl reread 
sudo supervisorctl update
sudo supervisorctl start dfbuilder-main-worker:*   sudo supervisorctl start default-worker:*
```

- run ```supervisorctl``` to view all the running workers
- if worker not working then run: ```sudo supervisord```
- or to restart suprivisor ```supervisorctl restart all```

Finally you need to start the cronjob.
```
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

Now your ready!


#Google adwords (Optional)
### Generate refresh token
*This requires that the `clientId` and `clientSecret` is from a native application.*

Run `$ php artisan googleads:token:generate` and open the authorization url. 

Grant access to the app, and input the
access token in the console. Copy the refresh token into your configuration `config/google-ads.php`

Full instructions how to configure see here (https://github.com/nikolajlovenhardt/laravel-google-ads)

#Elasticsearch 5.5
See instructions over here
https://www.elastic.co/guide/en/elasticsearch/reference/current/deb.html

# Docker image
- coming soon!! :)

# Vagrant configuration PHP 5.x


``` 
sudo add-apt-repository ppa:webupd8team/java
sudo apt-get update
 sudo apt-get install oracle-java8-installer
sudo add-apt-repository --yes ppa:ondrej/php
sudo apt-get update
sudo apt-get install --yes \
    php5.6-common \
    php5.6-cli \
    php5.6-json \
    php5.6-xml \
    php5.6-dev \
    php5.6-fpm \
    php5.6-mcrypt \
    php5.6-xmlrpc \
    php5.6-mysql \
    php5.6-gd \
    php5.6-curl \
    php5.6-mbstring \
    php5.6-soap \
    php-pear
```

# Vagrant configuration PHP 7.x
```
sudo add-apt-repository ppa:webupd8team/java
sudo apt-get update
sudo apt-get install oracle-java8-installer 
sudo add-apt-repository --yes ppa:ondrej/php
sudo apt-get update
sudo apt-get install --yes \
    php7.0-common \
    php7.0-cli \
    php7.0-json \
    php7.0-xml \
    php7.0-dev \
    php7.0-fpm \
    php7.0-mcrypt \
    php7.0-xmlrpc \
    php7.0-mysql \
    php7.0-gd \
    php7.0-curl \
    php7.0-mbstring \
    php7.0-soap \
    php-pear

```
 