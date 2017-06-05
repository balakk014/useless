<?php defined('SYSPATH') or die('No direct script access.');

/******************************************

* Contains Welcome module details


* @Package: taximobility

* @Author: taximobility Team

* @URL : http://ndot.in/

********************************************/

class Model_Welcome extends Model {

	public function Welcome()
	{
	        $db = new Database;
	        $db->query("select time from sys");
		
	}

} // End Welcome
