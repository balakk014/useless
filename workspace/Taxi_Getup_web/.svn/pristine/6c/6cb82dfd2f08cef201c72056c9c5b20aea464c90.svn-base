<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------
$split = explode('/',$_SERVER['REQUEST_URI']);

/*
// PHP permanent URL redirect
if(($split[1] =='admin') || ($split[1] =='company') || ($split[1] =='manager') || ($split[1] =='add') || ($split[1] =='manage') || ($split[1] =='edit'))
{
header("HTTP/1.1 301 Moved Permanently");
header("Location: http://demo.taximoblity.com/".$split[1].'/'.$split[2]);
exit();
}
*/

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
//date_default_timezone_set('Asia/Calcutta');
date_default_timezone_set('Asia/Amman');
//mysql_query("SET `time_zone` = '".date('P')."'");
/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

ini_set('display_errors', 1);
ini_set ('gd.jpeg_ignore_warning', 1);
// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');
Cookie::$salt = 'Taxi';

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}
//to set kohana environment (four environments - PRODUCTION, DEVELOPMENT, STAGING, TESTING)
Kohana::$environment = isset($_SERVER['KOHANA_ENV'])
    ? constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']))
    : Kohana::DEVELOPMENT;
    
/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
    'base_url'      => '/',
    'index_file'    => FALSE,
    'errors'        => TRUE,
    'profile'       => (Kohana::$environment == Kohana::DEVELOPMENT),
    'caching'       => (Kohana::$environment == Kohana::PRODUCTION)
));
set_exception_handler(array('Kohana_Exception', 'handler'));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
		'taximobility'   	=> MODPATH.'taximobility',   // Taximobility Core Files
		'database'   		=> MODPATH.'database',   // Database access
		'image'      		=> MODPATH.'image',      // Image manipulation
		'commonfunction'  	=> MODPATH.'commonfunction', //common function added as module		  
		'Message' 			=> MODPATH.'message',		  
		'Email' 			=> MODPATH.'email',
		'pagination' 		=> MODPATH.'pagination', 	// Pagination
		'MySQLi' 			=> MODPATH.'mysqli', 	// mysqli
		'PHPEX' 			=> MODPATH.'phpex', 	// phpex
		//'debugtoolbar'	=> MODPATH.'debugtoolbar',
	));

/** 
 * Error router 
 */


/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

//Include defined Constants files


Route::set('error', 'error/<action>/<origuri>/<message>', array('action' => '[0-9]++', 'origuri' => '.+', 'message' => '.+'))
->defaults(array(
    'controller' => 'error',
    'action'     => 'index'
));

Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'index',
	));

Route::set('aboutus', 'about-us.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'aboutus'
    ));


/* Route::set('Portfolios', 'portfolios.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'Portfolios'
    )); */

Route::set('Portfolios', '<path>.html',
	array(
		'path' => '(portfolios|portfolio)',
	))
	->defaults(array(
		'controller' => 'page',
		'action' => 'Portfolios',
	));
	

Route::set('company-registration', 'company-registration.html')
    ->defaults(array(
        'controller' => 'users',
        'action'     => 'company_registration'
    ));

/* Route::set('advance-search', 'advance-search.html')
    ->defaults(array(
        'controller' => 'find',
        'action'     => 'advancesearch'
    )); */

Route::set('search', 'search.html')
    ->defaults(array(
        'controller' => 'find',
        'action'     => 'search'
    ));

Route::set('how-it-works', 'how-it-works.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'demo'
    ));

Route::set('contact-us', 'contact-us.html')
    ->defaults(array(
        'controller' => 'users',
        'action'     => 'contactus'
    ));
    
    Route::set('technology', 'technology.html')
    ->defaults(array(
        'controller' => 'users',
        'action'     => 'technology'
    ));
    
    Route::set('success-stories', 'success-stories.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'success_stories'
    ));

Route::set('thankyou', 'thank-you.html')
    ->defaults(array(
        'controller' => 'users',
        'action'     => 'thankyou'
    ));

Route::set('free-trial-thank-you', 'free-trial-thank-you.html')
    ->defaults(array(
        'controller' => 'users',
        'action'     => 'trial_thankyou'
    ));
    

Route::set('livechat', 'livechat.html')
    ->defaults(array(
        'controller' => 'users',
        'action'     => 'contactuslive'
    ));

Route::set('demo', 'demo.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'demo'
    ));

Route::set('sitemap', 'sitemap.xml')
    ->defaults(array(
        'controller' => 'xmlsitemap',
        'action'     => 'index'
    ));
    
Route::set('features', 'features.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'features'
    ));

Route::set('pricing', 'pricing.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'pricing'
    )); 

Route::set('release', 'release-notes.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'release'
    ));

/* Route::set('vehicle-service', 'vehicle-assistance.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'vehicle_service'
    )); */

Route::set('health-service', 'medical-assistance.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'health_service'
    ));

/* Route::set('delivery-service', 'delivery-assistance.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'delivery_service'
    )); */
    
Route::set('delivery-service', '<path>.html',
	array(
		'path' => '(delivery-assistance|courier-delivery-software)',
	))
	->defaults(array(
		'controller' => 'page',
		'action' => 'delivery_service',
	));

Route::set('taxi-service', 'taxi-booking-and-dispatching.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'taxi_service'
    ));

Route::set('home-service', 'home-assistance.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'home_service'
    ));

Route::set('case-studies', 'case-studies.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'case_studies'
    ));

Route::set('case-studies-details', 'case-study-details.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'case_study_details'
    ));

/* Route::set('solution', 'solutions.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'solutions'
    )); */

Route::set('casestudy-download', 'downloads.html')
	->defaults(array(
		'controller' => 'page',
		'action'     => 'casestudy_down',
	));
 
Route::set('caller-id', 'callerid.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'caller_id'
    ));   
    
Route::set('online-booking', 'onlinebooking.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'online_booking'
    ));  
    
Route::set('our-promise', 'ourpromise.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'our_promise'
    ));   
 
Route::set('privacy--policy', 'privacypolicy.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'privacy_policy'
    )); 

Route::set('package', 'package.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'package'
    )); 

Route::set('license', 'license-agreement.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'license'
    ));
    
Route::set('terms--conditions', 'termsconditions.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'terms_conditions'
    )); 
    
Route::set('api', 'api.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'api'
    )); 

Route::set('faq', 'faq.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'faq'
    ));
Route::set('help', 'help.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'help'
    ));
   
/* Route::set('tutorial', 'tutorial.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'tutorial'
    )); */
    
Route::set('videos', '<path>.html',
	array(
		'path' => '(tutorial|videos)',
	))
	->defaults(array(
		'controller' => 'page',
		'action' => 'tutorial',
	));

    
Route::set('taxi-booking-apps', 'taxibookingapps.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'taxi_booking_apps'
    ));


Route::set('cms', 'cms/(<pageurl>).html',array( 'pageurl' => '[a-zA-Z0-9\-]+'))
	->defaults(array(
		'controller' => 'page',
		'action'     => 'common_cms',
	));

Route::set('taxi-booking-apps', 'taxibookingapps.html')
    ->defaults(array(
        'controller' => 'page',
        'action'     => 'taxi_booking_apps'
    ));
    
Route::set('get-constants', 'get-constants.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'needed_variables',
	));
	
Route::set('webbooking', 'booking.html')
    ->defaults(array(
        'controller' => 'find',
        'action'     => 'webBooking'
    ));

Route::set('online-booking', 'addmoney.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'add_money'
));

Route::set('change-password', 'change-password.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'change_password'
));

Route::set('edit-profile', 'edit-profile.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'passenger_editprofile'
));

Route::set('cancelled-trips', 'cancelled-trips.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'cancelled_trips'
));

Route::set('completed-trips', 'completed-trips.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'completed_trips'
));

Route::set('voucher', 'voucher.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'recharge_voucher'
));

Route::set('dashboard', 'dashboard.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'passenger_dashboard'
));

Route::set('payment-option', 'payment-option.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'payment_option'
));

Route::set('add-card', 'add-card.html')
	->defaults(array(
		'controller' => 'users',
		'action'     => 'add_card_details'
));

Route::set('limousine', 'limo-taxi-dispatch-booking-software.html')
	->defaults(array(
		'controller' => 'page',
		'action'     => 'limousine'
));
    
//Include defined Constants files

require Kohana::find_file('classes','table_config');
//require Kohana::find_file('classes','common_config');  


$current_controller = ($split[1])?$split[1]:"";
//echo $current_controller;
//$ctrl = substr($current_controller, 0, -3);
//echo $ctrl;
//exit;
$pos = strpos($current_controller, 'mobileapi');
/*if($pos === false)
{
	//require Kohana::find_file('classes','common_config');		
}*/


	/**/


