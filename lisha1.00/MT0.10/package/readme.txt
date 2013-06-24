-==--=----==-=---==--=----==-=---==--=----==-=--
Welcome to quick install manual
-==--=----==-=---==--=----==-=---==--=----==-=--

-==--=----==-=---==--=----==-=---==--=----==-=--
Install application files
-==--=----==-=---==--=----==-=---==--=----==-=--
Copy 3 following directories in your root web site directory

	- includes ( if includes directory already exists in your root web site, just add contents package to your own )
	- demo ( if exists )
	- lisha1.00
-==--=----==-=---==--=----==-=---==--=----==-=--


-==--=----==-=---==--=----==-=---==--=----==-=--
Unix rights
-==--=----==-=---==--=----==-=---==--=----==-=--
Setup rights to have read/write acces to your web directories
-==--=----==-=---==--=----==-=---==--=----==-=--


-==--=----==-=---==--=----==-=---==--=----==-=--
Import Database
-==--=----==-=---==--=----==-=---==--=----==-=--
Create your custom schema ( e.g. : lisha )

import file MySQL_lisha.sql into this schema
-==--=----==-=---==--=----==-=---==--=----==-=--


-==--=----==-=---==--=----==-=---==--=----==-=--
Global lisha access setup
-==--=----==-=---==--=----==-=---==--=----==-=--

Go to web file directory : includes/lishaSetup/main_configuration.php and do the following

Find line contains
define("__LISHA_DATABASE_SCHEMA__","lisha");	// Schema
then
replace lisha by your own schema name

Find line contains
define("__LISHA_DATABASE_USER__","adminl");		// user
then
replace adminl by your own database user name

Find line contains
define("__LISHA_DATABASE_PASSWORD__","demo");	// password
then
replace demo by your own database user password
-==--=----==-=---==--=----==-=---==--=----==-=--


-==--=----==-=---==--=----==-=---==--=----==-=--
Demo access setup ( if demo only )
-==--=----==-=---==--=----==-=---==--=----==-=--

Go to web file directory : demo/includes/lishaSetup/main_configuration.php and do the following

Find line contains
define("__LISHA_DATABASE_SCHEMA__","lisha");	// Schema
then
replace lisha by your own schema name

Find line contains
define("__LISHA_DATABASE_USER__","adminl");		// user
then
replace adminl by your own database user name

Find line contains
define("__LISHA_DATABASE_PASSWORD__","demo");	// password
then
replace demo by your own database user password

To run demo
http://localhost/demo/
-==--=----==-=---==--=----==-=---==--=----==-=--



Only if you have downloaded full package

-==--=----==-=---==--=----==-=---==--=----==-=--
Setup technical documentation
-==--=----==-=---==--=----==-=---==--=----==-=--

Go to web file directory : lisha1.00/includes/MTSetup/setup.php and do the following

Find line contains
define("__MAGICTREE_DATABASE_SCHEMA__","lisha");	// Schema
then
replace lisha by your own schema name
		
Find line contains
define("__MAGICTREE_DATABASE_USER__","adminl");		// user
then
replace adminl by your own database user name

Find line contains
define("__MAGICTREE_DATABASE_PASSWORD__","demo");	// password
then
replace demo by your own database user password
//==================================================================



In your lisha custom file setup ( eg: demo/includes/LishaDefine/demo.php )

1/ Replace line
$obj_lisha_tran->define_attribute('__active_user_doc', false);		// user documentation button
by
$obj_lisha_tran->define_attribute('__active_user_doc', true);		// user documentation button


2/ Replace line
$obj_lisha_tran->define_attribute('__active_tech_doc', false);		// technical documentation button
by
$obj_lisha_tran->define_attribute('__active_tech_doc', true);		// technical documentation button


3/ Replace line
$obj_lisha_tran->define_attribute('__active_ticket', false);		// Tickets link
by
$obj_lisha_tran->define_attribute('__active_ticket', true);		// Tickets link

// Access
Url access to technical documentation
http://localhost/lisha1.00/indextech.php

Url access to user documentation
http://localhost/lisha1.00/

Url access to tickets
http://localhost/lisha1.00/bugs
-==--=----==-=---==--=----==-=---==--=----==-=--



-==--=----==-=---==--=----==-=---==--=----==-=--
Relative directory access to lisha
in demo/includes/LishaDefine/demo.php
-==--=----==-=---==--=----==-=---==--=----==-=--

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

To acces to lisha framework, replace following 'xxxxxxxxxx' by '../lisha1.00'

$_SESSION[$ssid]['lisha'][$lisha1_id] = new lisha(
													$lisha1_id,
													$ssid,
													__MYSQL__,
													array('user' => __LISHA_DATABASE_USER__,'password' => __LISHA_DATABASE_PASSWORD__,'host' => __LISHA_DATABASE_HOST__,'schema' => __LISHA_DATABASE_SCHEMA__),
													'xxxxxxxxxx',
													null,
													false,
													__LISHA_APPLICATION_RELEASE__
												);
-==--=----==-=---==--=----==-=---==--=----==-=--

Any question ? Go to https://sourceforge.net/projects/lisha/