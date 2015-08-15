<?php
/* 
 * Server poker script.
 * 
 * .
 */
                    
ini_set('html_errors', false);
header('Content-type:text/plain');
ini_set('date.timezone','Europe/Amsterdam');

setlocale (LC_TIME, 'nl_NL');
//echo strftime ( '%A_%H',time());

//import ini settings from file
$iniarray = parse_ini_file('tempp/serverpanic.ini');
//print_r($iniarray);

//prepare autoloading
require_once 'Autoloader.php';
spl_autoload_register('Autoloader::loader');


/* prepare array for passing setting to object */
$settingsarray= array("applications"=>array(
    "webserver"=>array("application"=>'apache2',"httpduser"=>$iniarray['httpduser'],"httpdgroup"=>$iniarray['httpdgroup']),//possible values: httpd apache2
    "databaserserver"=>array("application"=>$iniarray['dbapplication'],"user"=>$iniarray['dbuser'],"passwd"=>$iniarray['dbpasswd'])
    )
);
//print_r($settingsarray);
$serverpoker = new \Serverpokedalarm\Classes\ServerPoker($settingsarray);

$reportarray = $serverpoker->getReportArray();

$reportarray['serverpanic']=$serverpoker->getServerPanic();
//print_r($reportarray);
echo json_encode($reportarray);
