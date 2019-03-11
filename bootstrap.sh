#!/usr/bin/env bash

# configure project:
ROOT_PASSWORD='12345678'
PROJECT_FOLDER='services'
SERVICE_NAME='api-task'
MYSQL_USER_NAME='root'
MYSQL_USER_PASSWORD='qwerty'

sudo chmod 0600 /home/vagrant/.ssh/id_rsa
sudo chmod 0777 /home/vagrant/.ssh/known_hosts

IFS=$'\n'
for KNOWN_HOST in $(cat "/vagrant/known_hosts"); do
  if ! grep -Fxq "$KNOWN_HOST" /home/vagrant/.ssh/known_hosts; then
      echo "Adding host to SSH known_hosts for user 'vagrant': $KNOWN_HOST"
      echo $KNOWN_HOST >> /home/vagrant/.ssh/known_hosts
  fi
done

# Install stuff from package manager
    sudo locale-gen "en_US.UTF-8"
    sudo dpkg-reconfigure locales
    sudo add-apt-repository ppa:ondrej/php

    sudo apt-get update
    sudo apt-get -y upgrade

    sudo apt-get update && apt-get purge php5-fpm && apt-get --purge autoremove && apt-get install -y php php-cli php-dev php-pear php-mbstring build-essential openssl pkg-config libpcre3-dev git
    sudo git config --global http.sslVerify false

    sudo mkdir "/var/www/${PROJECT_FOLDER}"
    sudo chmod -R 755 /var/www

    #set MySQL root password
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $ROOT_PASSWORD"
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $ROOT_PASSWORD"

    # install MySQL
    sudo apt-get -y install mysql-server-5.6
    #sudo apt-get -y install php5-mysql
    #sudo apt-get -y install php7.0-mysql
    sudo apt-get -y install php7.3-mysql


    echo "Updated mysql bind address in /etc/mysql/my.cnf to 0.0.0.0 to allow external connections."
    sudo sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
    sudo service mysql restart
    sudo service apache2 restart

    # add privileges to root user
    sudo mysql -u root -p${ROOT_PASSWORD} << EOF
GRANT ALL PRIVILEGES ON *.* TO '${MYSQL_USER_NAME}'@'%' IDENTIFIED BY '${MYSQL_USER_PASSWORD}' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EOF

    # run SQL statements from _install folder: this will create db and some data for testing
    sudo mysql -h "localhost" -u "root" "-p${ROOT_PASSWORD}" < "/var/www/${PROJECT_FOLDER}/service/${SERVICE_NAME}/_install/db_dump.sql"

# setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
    ServerAdmin admin@myjar.com
    DocumentRoot "/var/www/${PROJECT_FOLDER}/service/${SERVICE_NAME}/public"
    ServerName localhost
    ServerAlias localhost

    #ErrorLog "logs/localhost-error.log"
    #CustomLog "logs/localhost-access.log" combined

    <Directory "/var/www/${PROJECT_FOLDER}/service/${SERVICE_NAME}/public">
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>

</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/localhost.conf

sudo a2ensite localhost.conf

# enable mod_rewrite
sudo a2enmod rewrite

# restart apache
service apache2 restart

# Install other stuff
    sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927
    sudo apt-get update
    #sudo apt-get install -y mc
    sudo apt-get install -y dos2unix

    sudo dos2unix /vagrant/init.sh

# Install composer
curl -sS https://getcomposer.org/installer | sudo -H php -- --install-dir=/usr/local/bin --filename=composer

# Provision services
cd /vagrant
chmod +x *.sh

su vagrant - -c ./init.sh

echo "======================="
echo "= ALL UP AND RUNNING! ="
echo "======================="