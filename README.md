# Lisha Project

## Welcome to quick install manual

### Install application files

Copy 3 following directories in your root web site directory

- includes ( if includes directory already exists in your root web site, just add contents package to your own )
- demo ( if exists )
- lisha1.00

### Unix rights

Setup rights to have read/write acces to your web directories

### Import Database

- Create your custom schema ( e.g. : lisha )
- Import file ./zinstallation/database/MySQL_lisha.sql into this schema

### Global lisha access setup

**Go to web file directory :** includes/lishaSetup/main_configuration.php and do the following

1. Find the line that contains the code below and replace **lisha** by your **own schema name**
```php
define("__LISHA_DATABASE_SCHEMA__","lisha");	// Schema
```

2. Find the line that contains the code below and replace **adminl** by your **own database user name**
```php
define("__LISHA_DATABASE_USER__","adminl");		// user
```

3. Find the line that contains the code below and replace **demo** by your **own database user password**
```php
define("__LISHA_DATABASE_PASSWORD__","demo");	// password
```

---

### Demo access setup ( if demo only )

**Go to web file directory :** demo/includes/lishaSetup/main_configuration.php and do the following

1. Find the line that contains the code below and replace **lisha** by your **own schema name**
```php
define("__LISHA_DATABASE_SCHEMA__","lisha");	// Schema
```

2. Find the line that contains the code below and replace **adminl** by your **own database user name**
```php
define("__LISHA_DATABASE_USER__","adminl");		// user
```

3. Find the line that contains the code below and **demo** by your **own database user password**
```php
define("__LISHA_DATABASE_PASSWORD__","demo");	// password
```

**To run demo :**
http://localhost/demo/

---

### Setup technical documentation

> Only if you have downloaded full package


**Go to web file directory : lisha1.00/includes/MTSetup/setup.php and do the following**

1. Find the line that contains the code below and replace **lisha** by your **own schema name**
```php
define("__MAGICTREE_DATABASE_SCHEMA__","lisha");	// Schema
```
	
2. Find the line that contains the code below and replace **adminl** by your **own database user name**
```php
define("__MAGICTREE_DATABASE_USER__","adminl");		// user
```

3. Find the line that contains the code below and replace **demo** by your **own database user password**
```php
define("__MAGICTREE_DATABASE_PASSWORD__","demo");	// password
```

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
- http://localhost/lisha1.00/indextech.php

**Url access to user documentation**
- http://localhost/lisha1.00/

**Url access to tickets**
- http://localhost/lisha1.00/bugs

---

### Relative directory access to lisha 

**in demo/includes/LishaDefine/demo.php**

Relative acces to your lisha is flag by 'xxxxxxxxxx'

here a part of my root weeb site
...
demo
	ajax
	...
	includes
	...
	index.php <-- file that run my lisha ( source )
lisha1.00 <-- lisha framework ( destination )
...

So to go from source to destination, i have to build relative path '../lisha1.00' or using constant '../'.__LISHA_APPLICATION_RELEASE__

```php
$_SESSION[$ssid]['lisha'][$lisha1_id] = new lisha(
													$lisha1_id,
													$ssid,
													__MYSQL__,
													array('user' => __LISHA_DATABASE_USER__,'password' => __LISHA_DATABASE_PASSWORD__,'host' => __LISHA_DATABASE_HOST__,'schema' => __LISHA_DATABASE_SCHEMA__),
													'../lisha1.00',
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