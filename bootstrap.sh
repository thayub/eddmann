#!/usr/bin/env bash

# essentials
yum install -y vim git

# set the localtime
ln -sf /usr/share/zoneinfo/Europe/London /etc/localtime

# disable firewall for development
/etc/init.d/iptables stop
chkconfig iptables off

# remi and epel repositories for latest releases
rpm -Uvh http://download.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm

# php
yum --enablerepo=remi-php55,remi install -y \
    php php-fpm php-common php-cli php-opcache php-pecl-xdebug \
    php-pear php-mysqlnd php-pdo php-sqlite php-gd php-mbstring \
    php-mcrypt php-xml
sed -i "s/^\;date\.timezone.*$/date\.timezone = \"Europe\/London\"/g" /etc/php.ini
sed -i "s/^\expose_php.*$/expose_php = Off/g" /etc/php.ini
sed -i "s/^\upload_max_filesize.*$/upload_max_filesize = 10M/g" /etc/php.ini
sed -i "s/^\post_max_size.*$/post_max_size = 10M/g" /etc/php.ini
sed -i "s/^\; max_input_vars.*$/max_input_vars = 5000/g" /etc/php.ini
sed -i "s/^\display_errors.*$/display_errors = On/g" /etc/php.ini
sed -i "s/^\display_startup_errors.*$/display_startup_errors = On/g" /etc/php.ini

# php-fpm
chkconfig --levels 235 php-fpm on
sed -i "s/^\listen.*$/listen = \/tmp\/php5-fpm.sock/g" /etc/php-fpm.d/www.conf
mkdir /usr/lib/cgi-bin/
/etc/init.d/php-fpm start

# nginx
echo "[nginx]
name=nginx repo
baseurl=http://nginx.org/packages/centos/6/x86_64/
gpgcheck=0
enabled=1" > /etc/yum.repos.d/nginx.repo
yum install -y nginx
rm /etc/nginx/conf.d/default.conf
ln -fs /vagrant/conf/nginx.conf /etc/nginx/conf.d/nginx.conf # use provided
chkconfig --levels 235 nginx on
/etc/init.d/nginx start

# composer
cd /home/vagrant
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# aliases
echo "alias v=\"clear;cd /vagrant\"" >> /home/vagrant/.bashrc
echo "alias c=\"clear\"" >> /home/vagrant/.bashrc
echo "alias l=\"ls -lah\"" >> /home/vagrant/.bashrc
source /home/vagrant/.bashrc

exit 0