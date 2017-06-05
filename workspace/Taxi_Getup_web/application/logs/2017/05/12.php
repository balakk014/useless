<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-05-12 10:54:59 --- ERROR: ErrorException [ 8 ]: Undefined index: driver_minimum_wallet_request ~ APPPATH/views/admin/add_settings_site.php [ 516 ]
2017-05-12 10:54:59 --- STRACE: ErrorException [ 8 ]: Undefined index: driver_minimum_wallet_request ~ APPPATH/views/admin/add_settings_site.php [ 516 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/add_settings_site.php(516): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 516, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Admin))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-12 10:57:54 --- ERROR: ErrorException [ 8 ]: Undefined index: driver_minimum_wallet_request ~ APPPATH/views/admin/add_settings_site.php [ 516 ]
2017-05-12 10:57:54 --- STRACE: ErrorException [ 8 ]: Undefined index: driver_minimum_wallet_request ~ APPPATH/views/admin/add_settings_site.php [ 516 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/add_settings_site.php(516): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 516, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Admin))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-12 11:02:48 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-05-12 11:02:48 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-05-12 13:28:47 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-05-12 13:28:47 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-05-12 13:46:46 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: admin/api.txt ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-05-12 13:46:46 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: admin/api.txt ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-05-12 13:56:11 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-05-12 13:56:11 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-05-12 14:50:12 --- ERROR: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
2017-05-12 14:50:12 --- STRACE: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-05-12 14:50:44 --- ERROR: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
2017-05-12 14:50:44 --- STRACE: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-05-12 14:51:19 --- ERROR: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
2017-05-12 14:51:19 --- STRACE: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-05-12 14:51:46 --- ERROR: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
2017-05-12 14:51:46 --- STRACE: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-05-12 14:54:07 --- ERROR: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
2017-05-12 14:54:07 --- STRACE: ErrorException [ 1 ]: Uncaught TypeError: Argument 1 passed to Kohana_Exception::handler() must be an instance of Exception, instance of ParseError given in /home/developer/workspace/Taxi_Getup_web/application/classes/kohana/exception.php:11
Stack trace:
#0 [internal function]: Kohana_Exception::handler(Object(ParseError))
#1 {main}
  thrown ~ APPPATH/classes/kohana/exception.php [ 11 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}