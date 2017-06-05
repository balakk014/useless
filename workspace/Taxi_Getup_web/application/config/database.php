<?php defined('SYSPATH') or die('No direct access allowed.');

// Database Conncetion
/*$servername = "mytaxidb.coisn7yglwyg.us-east-1.rds.amazonaws.com";
$username = "mytaxi";
$password = "mytaxidblogin";
$dbname = "mytaxidb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} */

$urlSegments = parse_url($_SERVER["SERVER_NAME"]);
$urlHostSegments = explode('.', $urlSegments['path']);

$dbname = "mytaxidb";
/*if(count($urlHostSegments) > 2) {
        $dbname = $urlHostSegments[0];
} else {
        $dbname = "mytaxidb";
}*/


return array
(
	'default' => array
	(
		'type'       => 'mysqli',
		'connection' => array(
			/**
			 * The following options are available for MySQL:
			 *
			 * string   hostname     server hostname, or socket
			 * string   database     database name
			 * string   username     database username
			 * string   password     database password
			 * boolean  persistent   use persistent connections?
			 * array    variables    system variables as "key => value" pairs
			 *
			 * Ports and sockets may be appended to the hostname.
			 */
			/*'callcenterdatabase'   => '',                       
			'hostname'   => 'mytaxidb.coisn7yglwyg.us-east-1.rds.amazonaws.com',
			'database'   => $dbname,
			'username'   => 'mytaxi',
			'password'   => 'mytaxidblogin',
			'persistent' => FALSE,*/
			'callcenterdatabase'   => '',
			'hostname'   => '192.168.1.243',
			'database'   => 'GetUpTaxi',
			'username'   => 'svnuser',
			'password'   => 'pXc9nbmrnCnpXhza',
			'persistent' => FALSE, 
			
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
	),
	'driver_tracking' => array
	(
		'type'       => 'mysqli',
		'connection' => array(
			'hostname'   => '',
			'database'   => '',
			'username'   => '',
			'password'   => '',
			'persistent' => FALSE,
			/*'hostname'   => '192.168.1.243',
			'database'   => 'ConnectTaxi_TaxiDispatchQA_db2',
			'username'   => 'svnuser',
			'password'   => 'pXc9nbmrnCnpXhza',
			'persistent' => FALSE,*/
		), 
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
	),
	'alternate' => array(
		'type'       => 'pdo',
		'connection' => array(
			/**
			 * The following options are available for PDO:
			 *
			 * string   dsn         Data Source Name
			 * string   username    database username
			 * string   password    database password
			 * boolean  persistent  use persistent connections?
			 */
			'dsn'        => 'mysql:host=localhost;dbname=kohana',
			'username'   => 'root',
			'password'   => 'r00tdb',
			'persistent' => FALSE,
		),
		/**
                 
		 * The following extra options are available for PDO:
		 *
		 * string   identifier  set the escaping identifier
		 */
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
	),
);
