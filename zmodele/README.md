# Lisha Project

## Welcome to quick install manual

### Install application files

Copy 3 following directories in your root web site directory

- includes ( if includes directory already exists in your root web site, just add contents package to your own )
- demo ( if exists )
- $package_directory_name$

### Unix rights

Setup rights to have read/write acces to your web directories

### Import Database

- Create your custom schema ( e.g. : lisha )
- Import file MySQL_lisha.sql into this schema

### Global lisha access setup

**Go to web file directory :** includes/lishaSetup/main_configuration.php and do the following

1. Find line contains
```php
define("__LISHA_DATABASE_SCHEMA__","lisha");	// Schema
```
then replace **lisha** by your **own schema name**

2. Find line contains
```php
define("__LISHA_DATABASE_USER__","adminl");		// user
```
then replace **adminl** by your **own database user name**

3. Find line contains
```php
define("__LISHA_DATABASE_PASSWORD__","demo");	// password
```
then replace **demo** by your **own database user password**

---

### Demo access setup ( if demo only )

**Go to web file directory :** demo/includes/lishaSetup/main_configuration.php and do the following

1. Find line contains
```php
define("__LISHA_DATABASE_SCHEMA__","lisha");	// Schema
```
then replace **lisha** by your **own schema name**

2. Find line contains
```php
define("__LISHA_DATABASE_USER__","adminl");		// user
```
then replace **adminl** by your **own database user name**

3. Find line contains
```php
define("__LISHA_DATABASE_PASSWORD__","demo");	// password
```
then replace **demo** by your **own database user password**

**To run demo :**
http://localhost/demo/

**To run demo password :**
http://localhost/demo/password/
---

### Setup technical documentation

> Only if you have downloaded full package


**Go to web file directory : $package_directory_name$/includes/MTSetup/setup.php and do the following**

Find line contains
```php
define("__MAGICTREE_DATABASE_SCHEMA__","lisha");	// Schema
```
then
replace **lisha** by your **own schema name**
		
Find line contains
```php
define("__MAGICTREE_DATABASE_USER__","adminl");		// user
```
then
replace **adminl** by your **own database user name**

Find line contains
```php
define("__MAGICTREE_DATABASE_PASSWORD__","demo");	// password
```
then
replace **demo** by your **own database user password**



**In your lisha custom file setup ( eg: demo/includes/LishaDefine/demo.php )**

1. Replace line
```php
$obj_lisha_tran->define_attribute('__active_user_doc', false);		// user documentation button
```
by
```php
$obj_lisha_tran->define_attribute('__active_user_doc', true);		// user documentation button
```

2. Replace line
```php
$obj_lisha_tran->define_attribute('__active_tech_doc', false);		// technical documentation button
```
by
```php
$obj_lisha_tran->define_attribute('__active_tech_doc', true);		// technical documentation button
```

3. Replace line
```php
$obj_lisha_tran->define_attribute('__active_ticket', false);		// Tickets link
```
by
```php
$obj_lisha_tran->define_attribute('__active_ticket', true);		// Tickets link
```

4. Access

**Url access to technical documentation**
- http://localhost/$package_directory_name$/indextech.php

**Url access to user documentation**
- http://localhost/$package_directory_name$/

**Url access to tickets**
- http://localhost/$package_directory_name$/bugs

---

### Relative directory access to lisha 

**in demo/includes/LishaDefine/demo.php**

Relative access to your lisha is flag by 'xxxxxxxxxx'

here a part of my root wesb site
...
demo
	ajax
	...
	includes
	...
	index.php <-- file that run my lisha ( source )
$package_directory_name$ <-- lisha framework ( destination )
...

Use $path_root_lisha defined into your main_configuration.php file

So to go from source to destination, i have to build relative path '../$package_directory_name$' or using constant '../'.__LISHA_APPLICATION_RELEASE__

```php
$_SESSION[$ssid]['lisha'][$lisha1_id] = new lisha(
													$lisha1_id,
													$ssid,
													__MYSQL__,
													array('user' => __LISHA_DATABASE_USER__,'password' => __LISHA_DATABASE_PASSWORD__,'host' => __LISHA_DATABASE_HOST__,'schema' => __LISHA_DATABASE_SCHEMA__),
													$path_root_lisha,
													null,
													false,
													__LISHA_APPLICATION_RELEASE__
												);
```

---
## Extra Config

php.ini contents
```conf
default_charset = "utf-8"
```

my.cnf contents
```conf
[client]
default-character-set=utf8

[mysqld]
character-set-server=utf8
default-character-set=utf8
default-collation=utf8_unicode_ci
character-set-client = utf8
```

httpd.conf contents
```conf
AddDefaultCharset UTF-8
```

---
Any question ? Go to https://sourceforge.net/projects/lisha/