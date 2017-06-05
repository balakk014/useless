<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-05-24 08:12:30 --- ERROR: ErrorException [ 8 ]: Undefined index: promo_type ~ APPPATH/views/admin/manage_promocode.php [ 162 ]
2017-05-24 08:12:30 --- STRACE: ErrorException [ 8 ]: Undefined index: promo_type ~ APPPATH/views/admin/manage_promocode.php [ 162 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/manage_promocode.php(162): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 162, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 08:12:55 --- ERROR: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/manage_promocode.php [ 162 ]
2017-05-24 08:12:55 --- STRACE: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/manage_promocode.php [ 162 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/manage_promocode.php(162): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 162, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 08:13:54 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-05-24 08:13:54 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-05-24 08:17:47 --- ERROR: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/edit_promocode.php [ 40 ]
2017-05-24 08:17:47 --- STRACE: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/edit_promocode.php [ 40 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/edit_promocode.php(40): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 40, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Edit))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 08:27:37 --- ERROR: ErrorException [ 8 ]: Undefined variable: taxicompany_details ~ APPPATH/views/admin/manage_promocode.php [ 96 ]
2017-05-24 08:27:37 --- STRACE: ErrorException [ 8 ]: Undefined variable: taxicompany_details ~ APPPATH/views/admin/manage_promocode.php [ 96 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/manage_promocode.php(96): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 96, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 08:48:14 --- ERROR: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/manage_promocode.php [ 98 ]
2017-05-24 08:48:14 --- STRACE: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/manage_promocode.php [ 98 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/manage_promocode.php(98): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 98, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 08:49:04 --- ERROR: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/manage_promocode.php [ 98 ]
2017-05-24 08:49:04 --- STRACE: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/manage_promocode.php [ 98 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/manage_promocode.php(98): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 98, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 11:16:10 --- ERROR: ErrorException [ 8 ]: unserialize(): Error at offset 0 of 2 bytes ~ APPPATH/views/admin/promoinfo.php [ 45 ]
2017-05-24 11:16:10 --- STRACE: ErrorException [ 8 ]: unserialize(): Error at offset 0 of 2 bytes ~ APPPATH/views/admin/promoinfo.php [ 45 ]
--
#0 [internal function]: Kohana_Core::error_handler(8, 'unserialize(): ...', '/home/developer...', 45, Array)
#1 /home/developer/workspace/Taxi_Getup_web/application/views/admin/promoinfo.php(45): unserialize('-1')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#5 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#8 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#9 [internal function]: Kohana_Controller_Template->after()
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#14 {main}
2017-05-24 12:38:02 --- ERROR: ErrorException [ 8 ]: unserialize(): Error at offset 0 of 2 bytes ~ APPPATH/views/admin/promoinfo.php [ 45 ]
2017-05-24 12:38:02 --- STRACE: ErrorException [ 8 ]: unserialize(): Error at offset 0 of 2 bytes ~ APPPATH/views/admin/promoinfo.php [ 45 ]
--
#0 [internal function]: Kohana_Core::error_handler(8, 'unserialize(): ...', '/home/developer...', 45, Array)
#1 /home/developer/workspace/Taxi_Getup_web/application/views/admin/promoinfo.php(45): unserialize('-1')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#5 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#8 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#9 [internal function]: Kohana_Controller_Template->after()
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#14 {main}
2017-05-24 12:41:20 --- ERROR: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/promoinfo.php [ 42 ]
2017-05-24 12:41:20 --- STRACE: ErrorException [ 8 ]: Undefined index: promocode_type ~ APPPATH/views/admin/promoinfo.php [ 42 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/promoinfo.php(42): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 42, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 12:46:37 --- ERROR: ErrorException [ 8 ]: Undefined variable: used_array ~ APPPATH/views/admin/promoinfo.php [ 78 ]
2017-05-24 12:46:37 --- STRACE: ErrorException [ 8 ]: Undefined variable: used_array ~ APPPATH/views/admin/promoinfo.php [ 78 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/promoinfo.php(78): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 78, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 12:47:50 --- ERROR: ErrorException [ 8 ]: Undefined variable: unused_array ~ APPPATH/views/admin/promoinfo.php [ 110 ]
2017-05-24 12:47:50 --- STRACE: ErrorException [ 8 ]: Undefined variable: unused_array ~ APPPATH/views/admin/promoinfo.php [ 110 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/promoinfo.php(110): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 110, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 12:51:12 --- ERROR: ErrorException [ 8 ]: Undefined variable: promocode_details ~ APPPATH/views/admin/promoinfo.php [ 10 ]
2017-05-24 12:51:12 --- STRACE: ErrorException [ 8 ]: Undefined variable: promocode_details ~ APPPATH/views/admin/promoinfo.php [ 10 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/promoinfo.php(10): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 10, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Manage))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-05-24 15:27:57 --- ERROR: ErrorException [ 8 ]: Undefined variable: current_time ~ MODPATH/taximobility/classes/model/mobileapi117.php [ 6149 ]
2017-05-24 15:27:57 --- STRACE: ErrorException [ 8 ]: Undefined variable: current_time ~ MODPATH/taximobility/classes/model/mobileapi117.php [ 6149 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/mobileapi117.php(6149): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 6149, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(3323): Model_Mobileapi117->checkpromocode('D0RDDG', '1', '')
#2 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}