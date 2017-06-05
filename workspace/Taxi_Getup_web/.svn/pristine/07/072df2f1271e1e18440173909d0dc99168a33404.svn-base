<?php defined('SYSPATH') or die('No direct script access.');
 
/** 
 * Custom exception handler for typical 404/500 error
 * 
 * @author Lysender
 *
 */
class Kohana_Exception extends Kohana_Kohana_Exception
{
    public static function handler(Exception $e)
    {
   
        // Throw errors when in development mode
     /*   if (Kohana::$environment === Kohana::DEVELOPMENT)
        {
        	echo $e->getCode();exit;
            parent::handler($e);
            
        }
        else
        {
        	
            Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e));
         
             
            $attributes = array(
                'action'    => 500,
                'origuri'   => rawurlencode(Arr::get($_SERVER, 'REQUEST_URI')),
                'message'   => rawurlencode($e->getMessage())
            );
             
            if ($e instanceof Http_Exception)
            {
                $attributes['action'] = $e->getCode();
            }
             
            // Error sub request
            echo Request::factory(Route::get('error')->uri($attributes))
                ->execute()
                ->send_headers()
                ->body();
        }*/
        /**Custom page for errors */
       switch (get_class($e))
        {
            case 'HTTP_Exception_404':
                if (Kohana::$environment === Kohana::DEVELOPMENT)
				{
					return Kohana_Kohana_Exception::handler($e);
				} else { 
				$response = new Response;
                $response->status(404);
                $view = new View('error/404'); // Path : application/views/error
                $view->message = $e->getMessage();
                echo $response->body($view)->send_headers()->body();
                if (is_object(Kohana::$log))
                {
                    // Add this exception to the log
                    Kohana::$log->add(Log::ERROR, $e);
                    // Make sure the logs are written
                    Kohana::$log->write();
                }  
			}              
                return TRUE;
                break;
                
            case 'Database_Exception':
				if (Kohana::$environment === Kohana::DEVELOPMENT)
				{
					return Kohana_Kohana_Exception::handler($e);
				} else {
					$response = new Response;
					$view = new View('error/200'); // Path : application/views/error
					$view->message = $e->getMessage();
					echo $response->body($view)->send_headers()->body();
					if (is_object(Kohana::$log))
					{
						// Add this exception to the log
						Kohana::$log->add(Log::ERROR, $e);
						// Make sure the logs are written
						Kohana::$log->write();
					}                
					return TRUE;
				}
				break;

            default:
				//echo get_class($e);exit;
                return Kohana_Kohana_Exception::handler($e);
                break;
        } 
    }
}

