<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-01-27 00:28:30 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-27 00:28:30 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-27 02:05:26 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-27 02:05:26 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-27 05:32:32 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-27 05:32:32 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-27 06:21:16 --- ERROR: ErrorException [ 8 ]: Undefined offset: 0 ~ APPPATH/views/themes/default/cms_pages.php [ 5 ]
2017-01-27 06:21:16 --- STRACE: ErrorException [ 8 ]: Undefined offset: 0 ~ APPPATH/views/themes/default/cms_pages.php [ 5 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/application/views/themes/default/cms_pages.php(5): Kohana_Core::error_handler(8, 'Undefined offse...', '/home/developer...', 5, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace_taxi/GetUpTaxi/application/views/themes/default/template.php(70): Kohana_View->__toString()
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Page))
#10 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#13 {main}
2017-01-27 07:12:20 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-27 07:12:20 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-27 07:12:21 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-27 07:12:21 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-27 07:14:21 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-27 07:14:21 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-27 07:26:12 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:12 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:17 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:17 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:22 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:22 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:27 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:27 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:32 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:32 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:37 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:37 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:42 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:42 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:48 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:48 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:52 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:52 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:26:57 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:26:57 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:02 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:02 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:07 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:07 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:12 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:12 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:18 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:18 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:22 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:22 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:27 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:27 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:32 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:32 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:37 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:37 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:42 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:42 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:47 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:47 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:52 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:52 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:27:57 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:27:57 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:02 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:02 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:07 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:07 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:12 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:12 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:17 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:17 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:22 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:22 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:27 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:27 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:32 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:32 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:37 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:37 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:43 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:43 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:47 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:47 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:52 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:52 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:28:57 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:28:57 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:02 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:02 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:07 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:07 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:12 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:12 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:17 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:17 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:22 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:22 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:27 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:27 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:32 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:32 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:37 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:37 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:42 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:42 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:47 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:47 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:52 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:52 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:29:57 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:29:57 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:03 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:30:03 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:07 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:30:07 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:12 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:30:12 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:17 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
2017-01-27 07:30:17 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected '}' ~ MODPATH/taximobility/classes/model/commonmodel.php [ 774 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:26 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:26 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:27 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:27 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:32 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:32 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:37 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:37 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:42 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:42 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:47 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:47 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:52 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:52 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:30:57 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:30:57 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:31:02 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:31:02 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:31:07 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
2017-01-27 07:31:07 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 772 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:31:12 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
2017-01-27 07:31:12 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:31:17 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
2017-01-27 07:31:17 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:31:22 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
2017-01-27 07:31:22 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:31:27 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
2017-01-27 07:31:27 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 07:31:32 --- ERROR: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
2017-01-27 07:31:32 --- STRACE: ErrorException [ 4 ]: syntax error, unexpected T_VARIABLE ~ MODPATH/taximobility/classes/model/commonmodel.php [ 773 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-27 08:00:26 --- ERROR: ErrorException [ 2 ]: curl_setopt(): You must pass either an object or an array with the CURLOPT_HTTPHEADER, CURLOPT_QUOTE, CURLOPT_HTTP200ALIASES and CURLOPT_POSTQUOTE arguments ~ MODPATH/taximobility/classes/model/commonmodel.php [ 779 ]
2017-01-27 08:00:26 --- STRACE: ErrorException [ 2 ]: curl_setopt(): You must pass either an object or an array with the CURLOPT_HTTPHEADER, CURLOPT_QUOTE, CURLOPT_HTTP200ALIASES and CURLOPT_POSTQUOTE arguments ~ MODPATH/taximobility/classes/model/commonmodel.php [ 779 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'curl_setopt(): ...', '/home/developer...', 779, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/commonmodel.php(779): curl_setopt(Resource id #112, 10023, 0)
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymobileapi117.php(232): Model_Commonmodel->send_sms('0799555211', 'Testing by siva')
#3 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-27 08:01:35 --- ERROR: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 790 ]
2017-01-27 08:01:35 --- STRACE: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 790 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'curl_errno(): 1...', '/home/developer...', 790, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/commonmodel.php(790): curl_errno(Resource id #112)
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymobileapi117.php(232): Model_Commonmodel->send_sms('0799555211', 'Testing by siva')
#3 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-27 08:04:12 --- ERROR: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 798 ]
2017-01-27 08:04:12 --- STRACE: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 798 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'curl_errno(): 1...', '/home/developer...', 798, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/commonmodel.php(798): curl_errno(Resource id #112)
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymobileapi117.php(232): Model_Commonmodel->send_sms('0799555211', 'Testing by siva')
#3 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-27 08:05:30 --- ERROR: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 798 ]
2017-01-27 08:05:30 --- STRACE: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 798 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'curl_errno(): 1...', '/home/developer...', 798, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/commonmodel.php(798): curl_errno(Resource id #112)
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymobileapi117.php(232): Model_Commonmodel->send_sms('0799555211', 'Testing by siva')
#3 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-27 08:09:42 --- ERROR: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 817 ]
2017-01-27 08:09:42 --- STRACE: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 817 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'curl_errno(): 1...', '/home/developer...', 817, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/commonmodel.php(817): curl_errno(Resource id #112)
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymobileapi117.php(232): Model_Commonmodel->send_sms('0799555211', 'Testing by siva')
#3 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-27 08:10:43 --- ERROR: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 817 ]
2017-01-27 08:10:43 --- STRACE: ErrorException [ 2 ]: curl_errno(): 112 is not a valid cURL handle resource ~ MODPATH/taximobility/classes/model/commonmodel.php [ 817 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'curl_errno(): 1...', '/home/developer...', 817, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/commonmodel.php(817): curl_errno(Resource id #112)
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymobileapi117.php(232): Model_Commonmodel->send_sms('0799555211', 'Testing by siva')
#3 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}