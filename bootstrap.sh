#!/usr/bin/env bash

# disable firewall
/etc/init.d/iptables stop
chkconfig iptables off

# vim
yum install -y vim

# httpd
yum install -y httpd
chkconfig --levels 235 httpd on

# jetty
yum install -y java-1.7.0-openjdk.x86_64
wget -O /opt/jetty-distribution-9.0.4.v20130625.tar.gz http://download.eclipse.org/jetty/stable-9/dist/jetty-distribution-9.0.4.v20130625.tar.gz
tar xzvf /opt/jetty-distribution-9.0.4.v20130625.tar.gz --directory=/opt
mv /opt/jetty-distribution-9.0.4.v20130625 /opt/jetty
useradd jetty
chown -R jetty:jetty /opt/jetty
cp /opt/jetty/bin/jetty.sh /etc/init.d/jetty
sed -i '85s/^/\nJETTY_HOME=\/opt\/jetty\nJETTY_USER=jetty\nJETTY_PORT=7070\nJETTY_LOGS=\/opt\/jetty\/logs\n/' /etc/init.d/jetty
chkconfig --levels 235 jetty on
echo "<VirtualHost *:80>
    ProxyPass / http://localhost:7070/
    ProxyPassReverse / http://localhost:7070/
</VirtualHost>"