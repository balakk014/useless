<?php defined('SYSPATH') or die('No direct script access.');
Class Controller_Cms extends Controller_Website
{
	public function __construct(Request $request,Response $response)
	{
		parent::__construct($request,$response);
		$siteusers = Model::factory('siteusers');
		$this->template=USERVIEW."template";
		
		
	}
	/** for about us pages**/
	public function action_aboutus()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->get_cmscontent();
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}
	
        public function action_createpack()
        {
                $domain = $this->request->query('domain');

                // DB Creating 
                shell_exec("mysql -u mytaxi -h mytaxidb.coisn7yglwyg.us-east-1.rds.amazonaws.com -pmytaxidblogin -e 'create database $domain'");
                echo "DB Created"."<br>";
                
                // Table move to main DB to New DB 
                shell_exec("mysqldump -u mytaxi -h mytaxidb.coisn7yglwyg.us-east-1.rds.amazonaws.com -pmytaxidblogin taximobility_empty | mysql -u mytaxi -h mytaxidb.coisn7yglwyg.us-east-1.rds.amazonaws.com -pmytaxidblogin $domain");
                echo "Table move to  new DB"."<br>";
                
                // Zip Folder move to org folder path
                copy('/var/www/html/test.zip', '/var/www/html/new/test2.zip');
                echo "ZIP Folder Moved"."<br>";                

                // Unzip for new uploaded folder
                $zip = new ZipArchive;
                if ($zip->open('/var/www/html/new/test2.zip') === TRUE) {
                        $zip->extractTo('/var/www/html/new/');
                        $zip->close();
                        echo "Folder Extracted"."<br>";
                } else {
                        echo "Folder Extract failed"."<br>";
                }
                
                // New uploaded folder rename to  domain name
                rename("/var/www/html/new/test","/var/www/html/new/$domain");
                echo "Folder renamed"."<br>";
                
                 // deleted zip file
                unlink("/var/www/html/new/test2.zip");
                echo "Deleted zip folder"."<br>";
                
               
                
                exit;

                
        }
}
