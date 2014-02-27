<?php
/*
This is the configuration file of Slytherin. Here you can
add configurations on the system as well as configurations on
your libraries that comes from Composer.
*/

/*
ROUTES
You can set your own routing rules here. Routes can either be
specified using wildcards(soon) or Regular Expressions(soon)
*/
$routes['default_controller'] = 'helloworld';

/*
DATABASE CONFIGURATION
Here is the details for configuring your database. Just enter
the following information required and it will be used everytime
the system wants to connect to the database.
*/
$database['type'] = 'mysql';
$database['hostname'] = 'localhost';
$database['username'] = 'root';
$database['password'] = '';
$database['database_name'] = 'periodicals';
?>