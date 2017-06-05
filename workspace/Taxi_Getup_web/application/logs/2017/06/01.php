<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-06-01 09:10:24 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 09:10:24 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 09:12:46 --- ERROR: ErrorException [ 8 ]: Undefined index: driver_id ~ MODPATH/taximobility/classes/controller/taximobilitymobileapi117.php [ 585 ]
2017-06-01 09:12:46 --- STRACE: ErrorException [ 8 ]: Undefined index: driver_id ~ MODPATH/taximobility/classes/controller/taximobilitymobileapi117.php [ 585 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(585): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 585, Array)
#1 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#6 {main}
2017-06-01 09:39:10 --- ERROR: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
2017-06-01 09:39:10 --- ERROR: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
2017-06-01 09:39:10 --- STRACE: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(67): Kohana_Core::error_handler(8, 'Trying to get p...', '/home/developer...', 67, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(424): Kohana_Database_MySQLi->connect()
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database.php(478): Kohana_Database_MySQLi->escape('1')
#3 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('1')
#4 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_MySQLi), Array)
#5 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_MySQLi))
#6 /home/developer/workspace/Taxi_Getup_web/application/classes/mobile_common_config.php(26): Kohana_Database_Query->execute()
#7 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(22): require('/home/developer...')
#8 [internal function]: Controller_TaximobilityMobileapi117->__construct(Object(Request), Object(Response))
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(101): ReflectionClass->newInstance(Object(Request), Object(Response))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-06-01 09:39:10 --- STRACE: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(67): Kohana_Core::error_handler(8, 'Trying to get p...', '/home/developer...', 67, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(424): Kohana_Database_MySQLi->connect()
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database.php(478): Kohana_Database_MySQLi->escape('1')
#3 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('1')
#4 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_MySQLi), Array)
#5 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_MySQLi))
#6 /home/developer/workspace/Taxi_Getup_web/application/classes/mobile_common_config.php(26): Kohana_Database_Query->execute()
#7 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(22): require('/home/developer...')
#8 [internal function]: Controller_TaximobilityMobileapi117->__construct(Object(Request), Object(Response))
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(101): ReflectionClass->newInstance(Object(Request), Object(Response))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-06-01 09:39:10 --- ERROR: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
2017-06-01 09:39:10 --- STRACE: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(67): Kohana_Core::error_handler(8, 'Trying to get p...', '/home/developer...', 67, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(424): Kohana_Database_MySQLi->connect()
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database.php(478): Kohana_Database_MySQLi->escape('1')
#3 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('1')
#4 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_MySQLi), Array)
#5 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_MySQLi))
#6 /home/developer/workspace/Taxi_Getup_web/application/classes/mobile_common_config.php(26): Kohana_Database_Query->execute()
#7 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(22): require('/home/developer...')
#8 [internal function]: Controller_TaximobilityMobileapi117->__construct(Object(Request), Object(Response))
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(101): ReflectionClass->newInstance(Object(Request), Object(Response))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-06-01 09:39:10 --- ERROR: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
2017-06-01 09:39:10 --- STRACE: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(67): Kohana_Core::error_handler(8, 'Trying to get p...', '/home/developer...', 67, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(424): Kohana_Database_MySQLi->connect()
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database.php(478): Kohana_Database_MySQLi->escape('1')
#3 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('1')
#4 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_MySQLi), Array)
#5 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_MySQLi))
#6 /home/developer/workspace/Taxi_Getup_web/application/classes/mobile_common_config.php(26): Kohana_Database_Query->execute()
#7 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(22): require('/home/developer...')
#8 [internal function]: Controller_TaximobilityMobileapi117->__construct(Object(Request), Object(Response))
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(101): ReflectionClass->newInstance(Object(Request), Object(Response))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-06-01 10:35:07 --- ERROR: ErrorException [ 1 ]: Class 'Controller_TaximobilityMobileapi117' not found ~ APPPATH/classes/controller/mobileapi117.php [ 13 ]
2017-06-01 10:35:07 --- STRACE: ErrorException [ 1 ]: Class 'Controller_TaximobilityMobileapi117' not found ~ APPPATH/classes/controller/mobileapi117.php [ 13 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-06-01 10:35:07 --- ERROR: ErrorException [ 1 ]: Class 'Controller_TaximobilityMobileapi117' not found ~ APPPATH/classes/controller/mobileapi117.php [ 13 ]
2017-06-01 10:35:07 --- STRACE: ErrorException [ 1 ]: Class 'Controller_TaximobilityMobileapi117' not found ~ APPPATH/classes/controller/mobileapi117.php [ 13 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-06-01 10:41:34 --- ERROR: ErrorException [ 8 ]: Undefined index: trip_id ~ MODPATH/taximobility/classes/controller/taximobilitymobileapi117.php [ 10085 ]
2017-06-01 10:41:34 --- STRACE: ErrorException [ 8 ]: Undefined index: trip_id ~ MODPATH/taximobility/classes/controller/taximobilitymobileapi117.php [ 10085 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(10085): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 10085, Array)
#1 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#6 {main}
2017-06-01 11:02:00 --- ERROR: ErrorException [ 8 ]: Undefined index: booking_from ~ APPPATH/views/admin/transaction_details.php [ 303 ]
2017-06-01 11:02:00 --- STRACE: ErrorException [ 8 ]: Undefined index: booking_from ~ APPPATH/views/admin/transaction_details.php [ 303 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/transaction_details.php(303): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 303, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Transaction))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-06-01 11:03:18 --- ERROR: ErrorException [ 8 ]: Undefined index: trip_type ~ APPPATH/views/admin/transaction_details.php [ 305 ]
2017-06-01 11:03:18 --- STRACE: ErrorException [ 8 ]: Undefined index: trip_type ~ APPPATH/views/admin/transaction_details.php [ 305 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/transaction_details.php(305): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 305, Array)
#1 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /home/developer/workspace/Taxi_Getup_web/application/views/admin/template.php(171): Kohana_View->__toString()
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(61): include('/home/developer...')
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/view.php(343): Kohana_View::capture('/home/developer...', Array)
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#8 [internal function]: Kohana_Controller_Template->after()
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Transaction))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-06-01 11:10:16 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:10:16 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:10:16 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:10:16 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:14:33 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:14:33 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:14:33 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:14:33 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:20:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:20:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:20:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:20:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:22:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:22:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:22:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:22:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:24:44 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:24:44 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:24:44 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:24:44 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:26:04 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:26:04 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:26:04 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:26:04 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:26:18 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:26:18 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:26:18 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:26:18 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:27:21 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:27:21 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 11:27:21 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 11:27:21 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 13:42:49 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 13:42:49 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 13:42:49 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 13:42:49 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 13:43:10 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/driverUnfocus.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 13:43:10 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/driverUnfocus.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 13:45:22 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 13:45:22 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 13:45:22 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 13:45:22 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 13:45:44 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 13:45:44 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 13:45:44 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 13:45:44 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 14:23:48 --- ERROR: ErrorException [ 2 ]: mysqli::query(): MySQL server has gone away ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 177 ]
2017-06-01 14:23:48 --- STRACE: ErrorException [ 2 ]: mysqli::query(): MySQL server has gone away ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 177 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'mysqli::query()...', '/home/developer...', 177, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(177): mysqli->query('UPDATE `driver`...')
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(3, 'UPDATE `driver`...', false, Array)
#3 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/mobileapi117.php(836): Kohana_Database_Query->execute()
#4 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(782): Model_Mobileapi117->update_table('driver', Array, 'driver_id', '6')
#5 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#8 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#9 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#10 {main}
2017-06-01 14:23:48 --- ERROR: ErrorException [ 2 ]: mysqli::query(): MySQL server has gone away ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 177 ]
2017-06-01 14:23:48 --- STRACE: ErrorException [ 2 ]: mysqli::query(): MySQL server has gone away ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 177 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'mysqli::query()...', '/home/developer...', 177, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(177): mysqli->query('UPDATE `driver`...')
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(3, 'UPDATE `driver`...', false, Array)
#3 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/mobileapi117.php(836): Kohana_Database_Query->execute()
#4 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(679): Model_Mobileapi117->update_table('driver', Array, 'driver_id', '3')
#5 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#8 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#9 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#10 {main}
2017-06-01 14:23:49 --- ERROR: ErrorException [ 2 ]: mysqli::query(): MySQL server has gone away ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 177 ]
2017-06-01 14:23:49 --- STRACE: ErrorException [ 2 ]: mysqli::query(): MySQL server has gone away ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 177 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'mysqli::query()...', '/home/developer...', 177, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(177): mysqli->query('UPDATE `passeng...')
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(3, 'UPDATE `passeng...', false, Array)
#3 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/mobileapi117.php(836): Kohana_Database_Query->execute()
#4 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(6609): Model_Mobileapi117->update_table('passengers_log_...', Array, 'friends_p_id', '50')
#5 [internal function]: Controller_TaximobilityMobileapi117->action_index()
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Mobileapi117))
#7 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#8 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#9 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#10 {main}
2017-06-01 15:42:32 --- ERROR: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
2017-06-01 15:42:32 --- STRACE: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(67): Kohana_Core::error_handler(8, 'Trying to get p...', '/home/developer...', 67, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(424): Kohana_Database_MySQLi->connect()
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database.php(478): Kohana_Database_MySQLi->escape('1')
#3 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('1')
#4 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_MySQLi), Array)
#5 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_MySQLi))
#6 /home/developer/workspace/Taxi_Getup_web/application/classes/mobile_common_config.php(26): Kohana_Database_Query->execute()
#7 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymobileapi117.php(22): require('/home/developer...')
#8 [internal function]: Controller_TaximobilityMobileapi117->__construct(Object(Request), Object(Response))
#9 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(101): ReflectionClass->newInstance(Object(Request), Object(Response))
#10 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#12 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#13 {main}
2017-06-01 16:23:30 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 16:23:30 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 16:23:30 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 16:23:30 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 16:25:04 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 16:25:04 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 16:25:04 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 16:25:04 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 16:41:13 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 16:41:13 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 16:43:56 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 16:43:56 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 17:14:56 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 17:14:56 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99map.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-06-01 17:14:56 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-06-01 17:14:56 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/99arrow.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}