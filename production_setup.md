# Production setup

Based on AWS EC2 and RDS. Includes steps for Capistrano.

```
chmod 600 ~/Dropbox\ \(Personal\)/LLC/CityHapps/Prod-CityHapps.pem
ssh -i ~/Dropbox\ \(Personal\)/LLC/CityHapps/Prod-CityHapps.pem ubuntu@52.40.38.75

sudo adduser deploy
sudo vi /etc/sudoers
     deploy ALL=NOPASSWD: ALL
sudo su deploy
mkdir ~/.ssh
nano ~/.ssh/authorized_keys
ssh deploy@52.40.38.75
sudo apt-get install mysql-client
Add to Default VPC security group all traffic from sg-7943271f (Prod-CityHapps-WebServerSecurityGroup-1T8NHV9SKPXHW)
mysql -h prod-cityhapps.csd8liozevko.us-west-2.rds.amazonaws.com -u root -p cityhapps_prod
CREATE USER ‘cityhapps'@'%' IDENTIFIED BY '<INSERT PASSWORD HERE>';
GRANT ALL ON cityhapps_prod.* TO 'cityhapps'@'%';
FLUSH PRIVILEGES;

crontab -u root -e
0 2,14 * * * php /var/www/cityhapps/current/artisan api:pull >> /var/www/cityhapps/logs/cron.log
0 4,16 * * * php /var/www/cityhapps/current/artisan api:load >> /var/www/cityhapps/logs/cron.log
0 6    * * * php /var/www/cityhapps/current/artisan api:pull-venues >> /var/www/cityhapps/logs/cron.log
0 8    * * * php /var/www/cityhapps/current/artisan api:load-venues >> /var/www/cityhapps/logs/cron.log
30 1   * * * php /var/www/cityhapps/current/artisan api:clear-stale >> /var/www/cityhapps/logs/cron.log

cd /var/www
sudo mkdir cityhapps
sudo chown deploy.deploy cityhapps/
copy ~/.ssh/id_rsa and ~/.ssh/id_rsa.pub that have access to cityhapps git
vi /var/www/cityhapps/shared/.env
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.2/install.sh | bash

cap cityhapps:production deploy # many things failed at that point, it's fine, keep going

cd /var/www/cityhapps/current
nvm install v4.2.1

# It's optional I wanted to prevent some processes to be accidental killed in case they take too much memeory. Can be completely ignored if server has lots or RAM.
sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
sudo /sbin/mkswap /var/swap.1
sudo /sbin/swapon /var/swap.1
sudo /etc/fstab
  /var/swap.1 swap swap defaults 0 0
sudo swapon -a

npm config set production
npm config set jobs 1
nvm install v4.4.7
npm upgrade -g npm
npm install --global warming-cli
npm install -g gulp
npm install gulp --save-dev
npm install gulp
nvm alias default v4.4.7

# Needed to add some shared folders so npm stops rebuilding all packages on every deploy

sudo apt-get install sendmail -y
sudo apt-get install php5-curl # needed later for laravel socialite dependency
```
