# 安裝步驟

## AWS EC2 (free, centos7)安裝基本環境
* sudo amazon-linux-extras install epel
* sudo yum install epel-release
* sudo yum install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
* sudo yum install php php-cli php-gd php-json php-mbstring php-mysqlnd php-opcache php-pdo php-xml php-pecl-zip php-fpm

### 安裝 composer

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '795f976fe0ebd8b75f26a6dd68f78fd3453ce79f32ecb33e7fd087d39bfeb978342fb73ac986cd4f54edd0dc902601dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
* sudo mv composer.phar /usr/local/bin/composer
 
## 安裝nginx
* sudo yum install -y nginx

## 安裝mysql
* sudo yum install https://dev.mysql.com/get/mysql80-community-release-el7-3.noarch.rpm
* sudo yum install mysql-community-server
* sudo systemctl start mysqld
* sudo systemctl enable mysqld
* sudo systemctl enable php-fpm
* sudo systemctl start php-fpm
* sudo systemctl enable nginx
* sudo systemctl start nginx
* sudo grep 'temporary password' /var/log/mysqld.log
* mysql -uroot -p
* mysql> ALTER USER 'root'@'localhost' IDENTIFIED BY 'O$Fmu7WT@N';
* mysql> flush privileges;
* sudo vim /etc/php-fpm.d/www.conf
```
listen = 127.0.0.1:9000
```

## 安裝BIND編譯過程中所需套件
* sudo yum install -y git wget curl
* sudo yum install -y gcc python3-pip libcap-devel libuv make openssl-devel mysql-devel bind-utils  libuv-devel bind-chroot
* sudo pip3 install ply
* sudo yum install epel-release

## 安裝BIND
* wget ftp://ftp.isc.org/isc/bind9/9.16.4/bind-9.16.4.tar.xz
* tar xvf bind-9.16.4.tar.xz
* cd bind-9.16.4
* ./configure --prefix=/usr/local/bind/ --enable-threads=no --with-dlz-mysql --with-openssl
* make -j 4
* sudo make install
* sudo su -
* cd /usr/local/bind/etc/
* ../sbin/rndc-confgen > rndc.conf  && tail -n10 rndc.conf | head -n9 | sed -e s/#\//g >named.conf && dig > named.root
* vim /usr/local/bind/etc/localhost.zone

```
$ttl 86400
@ IN SOA localhost. root.localhost. (
            2020091601
            28800 
            14400
            3600000
            86400 )
   IN     NS    localhost.
1      IN PTR localhost.
```

### 產生金鑰
* mkdir /usr/local/bind/etc/keys
* cd /usr/local/bind/etc/keys
* /usr/local/bind/sbin/tsig-keygen -a hmac-sha512 ddns.idv.tw > /usr/local/bind/etc/keys/ddns.key

### 建立 mysql連線帳號
* mysql -uroot -p
* mysql> ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'O$Fmu7WT@N';
* mysql> create user 'ipv6ddns'@'%' identified with mysql_native_password by 'O$Fmu7WT@N';
* mysql> alter user 'ipv6ddns'@'%' identified with mysql_native_password by 'O$Fmu7WT@N';
* mysql> grant all privileges on ipv6ddns.* to 'ipv6ddns'@'%';
* mysql> flush privileges;
* mysql> create database ipv6ddns CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
* sudo vim /etc/my.cnf.d/mysql-default-authentication-plugin.cnf
```
[mysqld]
default_authentication_plugin=mysql_native_password
```
* sudo systemctl restart mysqld
* 
### 編輯 /usr/local/bind/etc/named.dlz.zones
* 注意: centos8是用 %zone%, 而centos7是用 $zone$
* vim /usr/local/bind/etc/named.dlz.zones
```
dlz "Mysql zone" {
   database "mysql
   {host=localhost dbname=iv6ddns user=root pass=O$Fmu7WT@N ssl=false}
   {select zone from dns_records where zone = '$zone$'}
   {select ttl, type, mx_priority, case when lower(type)='txt' then concat('\"', data, '\"')
        when lower(type) = 'soa' then concat_ws(' ', data, resp_person, serial, refresh, retry, expire, minimum)
        else data end from dns_records where zone = '%zone%' and host = '$record$'}";
};
```

### 將金鑰放入 named.conf.keys
* vim /usr/local/bind/etc/named.conf

```
 key "rndc-key" {
        algorithm hmac-sha256;
        secret "reb8XtmMBGK5CmPf3GDd0xEFrwradAcoxcM4EIiFvqw=";
 };

 controls {
        inet 127.0.0.1 port 953
                allow { 127.0.0.1; } keys { "rndc-key"; };
 };

include "/usr/local/bind/etc/keys/ddns.key";

logging {
        channel error_log {
                file "/usr/local/bind/var/error.log" versions 10 size 32m;
                severity warning;
                print-time yes;
                print-severity yes;
                print-category yes;
        };
        channel query_log {
                file "/usr/local/bind/var/query.log" versions 10 size 32m;
                severity debug;
                print-time yes;
                print-severity yes;
                print-category yes;
        };
        category default { error_log; };
        category queries { query_log; };

};

options {
    directory "/usr/local/bind/etc";
    pid-file "named.pid";
    allow-query { any;};
    recursion yes;
    forwarders{ 8.8.8.8; 8.8.4.4;};
    listen-on port 53 { any; };
    listen-on-v6 port 53 { ::1; };
};
include "/usr/local/bind/etc/named.dlz.zones";
```

### 檢查設定
* /usr/local/bind/sbin/named-checkconf -p -z /usr/local/bind/etc/named.conf
* echo $?
* 0

### 建立 mysql schema

```
DROP TABLE IF EXISTS `apikey`;

CREATE TABLE `apikey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apikey` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table db
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db`;

CREATE TABLE `db` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table dns_records
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dns_records`;

CREATE TABLE `dns_records` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `zone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('MX','CNAME','NS','SOA','A','PTR','AAAA','TXT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ttl` int(11) DEFAULT '600',
  `mx_priority` int(11) DEFAULT NULL,
  `refresh` int(11) DEFAULT '600',
  `retry` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT '86400',
  `minimum` int(11) DEFAULT '3600',
  `serial` bigint(20) DEFAULT '2020091601',
  `resp_person` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `primary_ns` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `dynaload` tinyint(1) DEFAULT '0',
  `datestamp` datetime DEFAULT NULL,
  `regnumber` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0000000',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `host_index` (`host`),
  KEY `zone_index` (`zone`),
  KEY `type_index` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table email
# ------------------------------------------------------------

DROP TABLE IF EXISTS `email`;

CREATE TABLE `email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table inactive_zones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inactive_zones`;

CREATE TABLE `inactive_zones` (
  `zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table ipdata
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ipdata`;

CREATE TABLE `ipdata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longtiude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_eu` int(11) DEFAULT NULL,
  `region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regioncode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `country_code` (`country_code`),
  KEY `locale` (`locale`),
  KEY `currency` (`currency`),
  KEY `updated_at` (`updated_at`),
  KEY `latitude` (`latitude`),
  KEY `longtiude` (`longtiude`),
  KEY `currency_symbol` (`currency_symbol`),
  KEY `in_eu` (`in_eu`),
  KEY `region` (`region`),
  KEY `regioncode` (`regioncode`),
  KEY `city` (`city`),
  KEY `time_zone` (`time_zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table ipfail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ipfail`;

CREATE TABLE `ipfail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table login
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `email` (`email`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table loginfail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `loginfail`;

CREATE TABLE `loginfail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `email` (`email`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1783 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table meta_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meta_data`;

CREATE TABLE `meta_data` (
  `next_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table other
# ------------------------------------------------------------

DROP TABLE IF EXISTS `other`;

CREATE TABLE `other` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkval` (`checkval`),
  KEY `ip` (`ip`),
  KEY `ctime` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table replication_heartbeat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `replication_heartbeat`;

CREATE TABLE `replication_heartbeat` (
  `timestamp` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pw` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avator` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `temp_pw` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temp_pw_created` timestamp NULL DEFAULT NULL,
  `temp_pw_expired` timestamp NULL DEFAULT NULL,
  `verify_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `is_deleted` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `is_verified` (`is_verified`),
  KEY `verify_code` (`verify_code`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table xfr_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `xfr_table`;

CREATE TABLE `xfr_table` (
  `zone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `zone_client_index` (`zone`,`client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

```

## 檢查設定檔案
* /usr/local/bind/sbin/named -g -c /usr/local/bind/etc/named.conf

```
17-Sep-2020 02:01:11.422 starting BIND 9.16.4 (Stable Release) <id:0849b42>
17-Sep-2020 02:01:11.422 running on Linux x86_64 4.19.76-linuxkit #1 SMP Tue May 26 11:42:35 UTC 2020
17-Sep-2020 02:01:11.422 built with '--prefix=/usr/local/bind/' '--enable-threads=no' '--with-dlz-mysql' '--with-openssl'
17-Sep-2020 02:01:11.422 running as: named -g -c /usr/local/bind/etc/named.conf
17-Sep-2020 02:01:11.423 compiled by GCC 8.3.1 20191121 (Red Hat 8.3.1-5)
17-Sep-2020 02:01:11.423 compiled with OpenSSL version: OpenSSL 1.1.1c FIPS  28 May 2019
17-Sep-2020 02:01:11.423 linked to OpenSSL version: OpenSSL 1.1.1c FIPS  28 May 2019
17-Sep-2020 02:01:11.423 compiled with zlib version: 1.2.11
17-Sep-2020 02:01:11.423 linked to zlib version: 1.2.11
17-Sep-2020 02:01:11.423 ----------------------------------------------------
17-Sep-2020 02:01:11.423 BIND 9 is maintained by Internet Systems Consortium,
17-Sep-2020 02:01:11.423 Inc. (ISC), a non-profit 501(c)(3) public-benefit
17-Sep-2020 02:01:11.423 corporation.  Support and training for BIND 9 are
17-Sep-2020 02:01:11.423 available at https://www.isc.org/support
17-Sep-2020 02:01:11.423 ----------------------------------------------------
17-Sep-2020 02:01:11.423 found 2 CPUs, using 2 worker threads
17-Sep-2020 02:01:11.423 using 2 UDP listeners per interface
17-Sep-2020 02:01:11.424 using up to 21000 sockets
17-Sep-2020 02:01:11.428 loading configuration from '/usr/local/bind/etc/named.conf'
17-Sep-2020 02:01:11.430 reading built-in trust anchors from file '/usr/local/bind/etc/bind.keys'
17-Sep-2020 02:01:11.431 using default UDP/IPv4 port range: [32768, 60999]
17-Sep-2020 02:01:11.432 using default UDP/IPv6 port range: [32768, 60999]
17-Sep-2020 02:01:11.434 listening on IPv4 interface lo, 127.0.0.1#53
17-Sep-2020 02:01:11.436 listening on IPv4 interface eth0, 172.17.0.2#53
17-Sep-2020 02:01:11.438 generating session key for dynamic DNS
17-Sep-2020 02:01:11.440 sizing zone task pool based on 0 zones
17-Sep-2020 02:01:11.441 Loading 'Mysql zone' using driver mysql
17-Sep-2020 02:01:11.441 Required token $zone$ not found.
17-Sep-2020 02:01:11.442 Could not build find zone query list
17-Sep-2020 02:01:11.442 mysql driver could not create database instance object.
17-Sep-2020 02:01:11.443 SDLZ driver failed to load.
17-Sep-2020 02:01:11.444 DLZ driver failed to load.
17-Sep-2020 02:01:11.445 loading configuration: failure
17-Sep-2020 02:01:11.446 exiting (due to fatal error)
[root@ffaea99cbf93 bind]# /usr/local/bind/sbin/named -g -c /usr/local/bind/etc/named.conf
17-Sep-2020 02:01:32.265 starting BIND 9.16.4 (Stable Release) <id:0849b42>
17-Sep-2020 02:01:32.265 running on Linux x86_64 4.19.76-linuxkit #1 SMP Tue May 26 11:42:35 UTC 2020
17-Sep-2020 02:01:32.265 built with '--prefix=/usr/local/bind/' '--enable-threads=no' '--with-dlz-mysql' '--with-openssl'
17-Sep-2020 02:01:32.265 running as: named -g -c /usr/local/bind/etc/named.conf
17-Sep-2020 02:01:32.265 compiled by GCC 8.3.1 20191121 (Red Hat 8.3.1-5)
17-Sep-2020 02:01:32.265 compiled with OpenSSL version: OpenSSL 1.1.1c FIPS  28 May 2019
17-Sep-2020 02:01:32.265 linked to OpenSSL version: OpenSSL 1.1.1c FIPS  28 May 2019
17-Sep-2020 02:01:32.265 compiled with zlib version: 1.2.11
17-Sep-2020 02:01:32.266 linked to zlib version: 1.2.11
17-Sep-2020 02:01:32.266 ----------------------------------------------------
17-Sep-2020 02:01:32.267 BIND 9 is maintained by Internet Systems Consortium,
17-Sep-2020 02:01:32.267 Inc. (ISC), a non-profit 501(c)(3) public-benefit
17-Sep-2020 02:01:32.267 corporation.  Support and training for BIND 9 are
17-Sep-2020 02:01:32.267 available at https://www.isc.org/support
17-Sep-2020 02:01:32.267 ----------------------------------------------------
17-Sep-2020 02:01:32.267 found 2 CPUs, using 2 worker threads
17-Sep-2020 02:01:32.268 using 2 UDP listeners per interface
17-Sep-2020 02:01:32.270 using up to 21000 sockets
17-Sep-2020 02:01:32.274 loading configuration from '/usr/local/bind/etc/named.conf'
17-Sep-2020 02:01:32.274 reading built-in trust anchors from file '/usr/local/bind/etc/bind.keys'
17-Sep-2020 02:01:32.275 using default UDP/IPv4 port range: [32768, 60999]
17-Sep-2020 02:01:32.275 using default UDP/IPv6 port range: [32768, 60999]
17-Sep-2020 02:01:32.276 listening on IPv4 interface lo, 127.0.0.1#53
17-Sep-2020 02:01:32.277 listening on IPv4 interface eth0, 172.17.0.2#53
17-Sep-2020 02:01:32.279 generating session key for dynamic DNS
17-Sep-2020 02:01:32.279 sizing zone task pool based on 0 zones
17-Sep-2020 02:01:32.279 Loading 'Mysql zone' using driver mysql
17-Sep-2020 02:01:32.285 none:98: 'max-cache-size 90%' - setting to 1792MB (out of 1991MB)
17-Sep-2020 02:01:32.289 obtaining root key for view _default from '/usr/local/bind/etc/bind.keys'
17-Sep-2020 02:01:32.289 set up managed keys zone for view _default, file 'managed-keys.bind'
17-Sep-2020 02:01:32.290 none:98: 'max-cache-size 90%' - setting to 1792MB (out of 1991MB)
17-Sep-2020 02:01:32.295 command channel listening on 127.0.0.1#953
17-Sep-2020 02:01:32.295 not using config file logging statement for logging due to -g option
17-Sep-2020 02:01:32.297 managed-keys-zone: loaded serial 13
17-Sep-2020 02:01:32.298 all zones loaded
17-Sep-2020 02:01:32.298 running
17-Sep-2020 02:01:32.515 managed-keys-zone: Key 20326 for zone . is now trusted (acceptance timer complete)
17-Sep-2020 02:01:32.536 resolver priming query complete
```

## 測試DNS server是否可以正常回應
* host ddns.idv.tw localhost
* dig @localhost google.com
* dig @ns1.ddns.idv.tw cloud.ddns.idv.tw
* dig ddns.idv.tw

```
Using domain server:
Name: localhost
Address: 127.0.0.1#53
Aliases:

ns1.sparkplugbb.net has address 10.10.10.10
```
### 檢查log輸出

```
17-Sep-2020 02:06:58.653 client @0x7f4c3c000cd0 127.0.0.1#39844 (ns1.sparkplugbb.net): query: ns1.sparkplugbb.net IN A + (127.0.0.1)
17-Sep-2020 02:06:58.656 client @0x7f4c3c000cd0 127.0.0.1#47308 (ns1.sparkplugbb.net): query: ns1.sparkplugbb.net IN AAAA + (127.0.0.1)
```

## 啟動named
* /usr/local/bind/sbin/named -c /usr/local/bind/etc/named.conf


## 安裝DDNS管理平台
* cd /var/www/
* sudo chown ec2-user:ec2-user html
* cd html
* git clone https://github.com/tripbnb66/ipv6ddns.git 解壓縮原始檔案
* cd ipv6ddns
* composer install 安裝所需packages
* cd cron/
* new_cache.sh 產生cache目錄, 設定權限
* http://ddns.idv.tw/

## 如何變成外部公開
* https://zonomi.com/ 申請一個免費帳號
* https://dcc.godaddy.com/manage/xy.com/dns 將godaddy上的name server換成 zonomi.com的name server
* curl "https://zonomi.com/app/dns/dyndns.jsp?action=SET&name=xy.com&value=172.114.172.115&type=A&api_key=1639793227855878500154301"
* 

## 備註
* [%zone% 是錯誤的, 應該改為 $zone$](https://forums.gentoo.org/viewtopic-t-829635-start-0.html)
* [完整的mysql+bind9+dlz](https://www.jianshu.com/p/f51481a19dbc)
* [設定BIND+DLZ](https://wintelais.wordpress.com/2015/06/17/how-to-install-bind-dlz-on-linux/)
* [zonomi](https://zonomi.com/)	