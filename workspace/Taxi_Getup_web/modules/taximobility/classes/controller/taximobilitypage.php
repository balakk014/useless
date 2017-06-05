<?php defined('SYSPATH') or die('No direct script access.');
Class Controller_TaximobilityPage extends Controller_Website
{
	public function __construct(Request $request,Response $response)
	{
		parent::__construct($request,$response);
		$siteusers = Model::factory('siteusers');
		$this->template=USERVIEW."template";
		
		 //passengers model
        $id =$this->session->get('id');  
        //$passengermodel = Model::factory('passengers');
		//$user_det=$passengermodel->select_current_user($id);
		///View::bind_global('user_det', $user_det);
       // $this->session->set('user_det',$user_det);
	}
	/** for about us pages**/
	public function action_aboutus()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('about-us');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}
        
	public function action_Portfolios()
	{
		if($this->request->uri() == "portfolios.html") {
			$this->request->redirect("/portfolio.html",301);
		}
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Portfolios');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_package()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('package');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_release()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('release-notes');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

		
	/* public function action_vehicle_service()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('vehicle-assistance');
		$view= View::factory(USERVIEW.'web_cms')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	} */

	public function action_case_studies()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('case-studies');
		$view= View::factory(USERVIEW.'web_cms')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_case_study_details()
	{
		$casestudy_detail=arr::get($_REQUEST,'casestudy');
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent($casestudy_detail);
		$view= View::factory(USERVIEW.'web_cms')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_home_service()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('home-assistance');
		$view= View::factory(USERVIEW.'web_cms')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_health_service()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('medical-assistance');
		$view= View::factory(USERVIEW.'web_cms')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_delivery_service()
	{
		if($this->request->uri() == "delivery-assistance.html") {
			$this->request->redirect("/courier-delivery-software.html",301);
		}
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('delivery-assistance');
		$view= View::factory(USERVIEW.'web_cms')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_taxi_service()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('taxi-booking-and-dispatching');
		$view= View::factory(USERVIEW.'web_cms')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}

	public function action_casestudy_down()
	{
		$filename=$_GET['filename'];
		$filenames = DOCROOT.'public/case-study-document/'.$filename;
			if (file_exists($filenames))
					{ 
							$download = DOCROOT.'public/case-study-document/'.$filename;
							ignore_user_abort(true);
							set_time_limit(0); // disable the time limit for this script


							$fullPath = $download;

							if ($fd = fopen ($fullPath, "r")) {
							    $fsize = filesize($fullPath);
							    $path_parts = pathinfo($fullPath);
							    $ext = strtolower($path_parts["extension"]);
							    switch ($ext) {
								case "pdf":
								header("Content-type: application/pdf");
								header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
								break;
								// add more headers for other content types here
								default;
								header("Content-type: application/octet-stream");
								header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
								break;
							    }
							    header("Content-length: $fsize");
							    header("Cache-control: private"); //use this to open files directly
							    while(!feof($fd)) {
								$buffer = fread($fd, 2048);
								echo $buffer;
							    }
							}
							fclose ($fd);
							exit;
               
					}else{
					$this->request->redirect("/");
				}
		
	}

	public function action_license()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('license-agreement');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";		
		$this->template->content = $view;
	}
	
	/** for features pages**/
	public function action_features()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Features');
		$view= View::factory(USERVIEW.'features')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

	public function action_demo()
	{
		$cms = Model::factory('cms');
		//$content_cms = $this->commonmodel->getcontents('demo');
		$content_cms = $cms->getcmscontent('Demo');
		$view= View::factory(USERVIEW.'features')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}
	
	/** for pricing pages**/
	public function action_pricing()
	{
		$id =$this->session->get('id');
		if($id != "") {
			$this->request->redirect("/dashboard.html");
		}
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Pricing');
		$view= View::factory(USERVIEW.'features')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

	/** for Solutions pages**/
	public function action_solutions()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Solutions');
		$view= View::factory(USERVIEW.'features')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

	/** for Online Booking pages**/
	public function action_online_booking()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Online Booking');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}
	
	/** for Caller Id pages**/
	public function action_caller_id()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Caller Id');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}
	
	/** for Our Promise pages**/
	public function action_our_promise()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Our Promise');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}
	
	/** for Our action_privacy_policy pages**/
	public function action_privacy_policy()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('PrivacyPolicy');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";
					
		$this->template->content = $view;
	}
	
	/** for Our Terms & conditions pages**/
	public function action_terms_conditions()
	{
		$cms = Model::factory('cms');
		//$content_cms = $cms->getcmscontent('Terms-Conditions');
		$content_cms = $cms->getcmscontent('TermsConditions');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";
					
		$this->template->content = $view;
	}
	
	/** for Our API pages**/
	public function action_api()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Api');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

	/** for Help pages**/
	public function action_help()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('help');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}
		
	/** for Tutorial pages**/
	public function action_tutorial()
	{
		if($this->request->uri() == "tutorial.html") {
			$this->request->redirect("/videos.html",301);
		}
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('tutorial');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

/** for Our taxi_booking_apps pages**/
	public function action_taxi_booking_apps()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('Taxi Booking Apps');
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

	public function action_companycms()
	{
		
        $uri=$this->request->uri();
        $page_name=current(explode('.',$uri));
        //echo $page_name;
		$cms = Model::factory('cms');
		$content_cms = $cms->getcompanycontent($page_name,COMPANY_CID);
		View::bind_global('cmscontent',$content_cms);
		$view= View::factory(USERVIEW.'company_pages');

		$this->meta_title=$content_cms[0]['meta_title'];
		$this->meta_keywords=$content_cms[0]['meta_keyword']; //
		$this->meta_description=$content_cms[0]['meta_description']; //	
									
		$this->template->content = $view;
	}

	/** for FAQ pages**/
	public function action_faq()
	{
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent("FAQ");
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

	
	

	/** How it Works **/
	/*public function action_demo()
	{
		$cms = Model::factory('cms');
		
		$customer_content = $this->commonmodel->getcontents('customer');
		$driver_content = $this->commonmodel->getcontents('driver');
		$company_content = $this->commonmodel->getcontents('company');
		$left_content = $this->commonmodel->getcontents('demo-left');
		$view= View::factory(USERVIEW.'demo')
				->bind('customer_content', $customer_content)
				->bind('driver_content', $driver_content)
				->bind('company_content', $company_content);

					
		$this->template->content = $view;
	}
	*/

	/** How it Works 
	public function action_demos()
	{    

		$view= View::factory(USERVIEW.'demos');
					
		$this->template->content = $view;
	}**/

	/** for Help pages**/
	public function action_common_cms()
	{
		$pageurl=$this->request->param('pageurl');
		//echo $pageurl;exit;
		// exit('in');
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent($pageurl);
		$view= View::factory(USERVIEW.'cms_pages')
					->bind('cmscontent', $content_cms);
					
		$this->template->content = $view;
	}

	/** 
	 * limousine page
	 **/

	public function action_limousine()
	{
		$view= View::factory(USERVIEW.'limousine');
		$this->meta_title = "Limo Dispatch Software | Limousine booking System | TaxiMobility Software";
		$this->meta_keywords = "Limo Software, Limo Dispatching Software, Limo Management Software, Limousine Booking Software, Limo reservation software, Limo Scheduling Software, Limo Dispatch Software, Software for limo company, android application for limo company, ios app for limo company.";
		$this->meta_description = "TaxiMobility, A powerful Limo management software enables you to regulate limo booking & dispatching jobs. Get branded limo dispatch system with essential features.";
		$this->template->content = $view;
	}
        
        public function action_success_stories()
	{
		$view= View::factory(USERVIEW.'success-stories');
		$this->meta_title = "Success Stories | Customer Reviews | TaxiMobility";
		$this->meta_keywords = "Customer feedback, Customer reviews, Testimonials, taximobility reviews";
		$this->meta_description = "Customer satisfaction is our primary  goal. Take a look at our significant contribution towards achieving our client's goal. ";
		$this->template->content = $view;
	}
}
