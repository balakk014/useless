<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-05-23 14:30:32 --- ERROR: ErrorException [ 8 ]: Undefined index: to_user ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 798 ]
2017-05-23 14:30:32 --- STRACE: ErrorException [ 8 ]: Undefined index: to_user ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 798 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymanageusers.php(798): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 798, Array)
#1 [internal function]: Controller_TaximobilityManageusers->action_sendpromocode()
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manageusers))
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#6 {main}
2017-05-23 14:30:32 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-05-23 14:30:32 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#1 {main}
2017-05-23 14:32:00 --- ERROR: ErrorException [ 8 ]: Undefined variable: passenger_list ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 825 ]
2017-05-23 14:32:00 --- STRACE: ErrorException [ 8 ]: Undefined variable: passenger_list ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 825 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymanageusers.php(825): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 825, Array)
#1 [internal function]: Controller_TaximobilityManageusers->action_sendpromocode()
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manageusers))
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#6 {main}
2017-05-23 14:37:20 --- ERROR: ErrorException [ 8 ]: Undefined variable: currenttime ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 925 ]
2017-05-23 14:37:20 --- STRACE: ErrorException [ 8 ]: Undefined variable: currenttime ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 925 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymanageusers.php(925): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 925, Array)
#1 [internal function]: Controller_TaximobilityManageusers->action_sendpromocode()
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manageusers))
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#6 {main}
2017-05-23 14:37:48 --- ERROR: ErrorException [ 8 ]: Undefined variable: promo_used_details ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 927 ]
2017-05-23 14:37:48 --- STRACE: ErrorException [ 8 ]: Undefined variable: promo_used_details ~ MODPATH/taximobility/classes/controller/taximobilitymanageusers.php [ 927 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitymanageusers.php(927): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 927, Array)
#1 [internal function]: Controller_TaximobilityManageusers->action_sendpromocode()
#2 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manageusers))
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#6 {main}
2017-05-23 16:57:10 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:57:10 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:57:18 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:57:18 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:57:26 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:57:26 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:57:34 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:57:34 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:57:42 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:57:42 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:57:50 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:57:50 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:57:58 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:57:58 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:58:06 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:58:06 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:58:14 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:58:14 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:58:22 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:58:22 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:58:30 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:58:30 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 16:58:38 --- ERROR: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
2017-05-23 16:58:38 --- STRACE: ErrorException [ 8 ]: Undefined variable: start_time ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2948 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2948): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2948, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}
2017-05-23 17:30:15 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  passengers.`id` =  passengers_log.`passengers_id` )
	
		 where (createdate between '2017-05-22 17:30:14' and  '2017-05-24 17:30:14' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:30:15 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  passengers.`id` =  passengers_log.`passengers_id` )
	
		 where (createdate between '2017-05-22 17:30:14' and  '2017-05-24 17:30:14' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:30:22 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:22' and  '2017-05-24 17:30:22' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:30:22 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:22' and  '2017-05-24 17:30:22' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:30:30 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:30' and  '2017-05-24 17:30:30' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:30:30 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:30' and  '2017-05-24 17:30:30' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:30:38 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:38' and  '2017-05-24 17:30:38' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:30:38 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:38' and  '2017-05-24 17:30:38' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:30:46 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:46' and  '2017-05-24 17:30:46' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:30:46 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:46' and  '2017-05-24 17:30:46' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:30:54 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:54' and  '2017-05-24 17:30:54' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:30:54 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:30:54' and  '2017-05-24 17:30:54' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:02 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:02' and  '2017-05-24 17:31:02' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:02 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:02' and  '2017-05-24 17:31:02' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:10 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:10' and  '2017-05-24 17:31:10' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:10 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:10' and  '2017-05-24 17:31:10' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:18 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:18' and  '2017-05-24 17:31:18' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:18 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:18' and  '2017-05-24 17:31:18' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:26 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:26' and  '2017-05-24 17:31:26' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:26 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:26' and  '2017-05-24 17:31:26' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:34 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:34' and  '2017-05-24 17:31:34' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:34 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:34' and  '2017-05-24 17:31:34' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:42 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:42' and  '2017-05-24 17:31:42' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:42 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:42' and  '2017-05-24 17:31:42' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:50 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:50' and  '2017-05-24 17:31:50' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:50 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:50' and  '2017-05-24 17:31:50' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:31:58 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:58' and  '2017-05-24 17:31:58' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:31:58 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:31:58' and  '2017-05-24 17:31:58' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:32:06 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:06' and  '2017-05-24 17:32:06' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:32:06 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:06' and  '2017-05-24 17:32:06' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:32:14 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:14' and  '2017-05-24 17:32:14' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:32:14 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:14' and  '2017-05-24 17:32:14' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:32:22 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:22' and  '2017-05-24 17:32:22' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:32:22 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:22' and  '2017-05-24 17:32:22' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:32:30 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:30' and  '2017-05-24 17:32:30' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:32:30 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:30' and  '2017-05-24 17:32:30' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:32:38 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:38' and  '2017-05-24 17:32:38' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:32:38 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:38' and  '2017-05-24 17:32:38' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:32:46 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:46' and  '2017-05-24 17:32:46' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:32:46 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:46' and  '2017-05-24 17:32:46' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:32:54 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:54' and  '2017-05-24 17:32:54' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:32:54 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:32:54' and  '2017-05-24 17:32:54' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:02 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:02' and  '2017-05-24 17:33:02' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:02 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:02' and  '2017-05-24 17:33:02' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:10 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:10' and  '2017-05-24 17:33:10' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:10 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:10' and  '2017-05-24 17:33:10' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:18 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:18' and  '2017-05-24 17:33:18' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:18 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:18' and  '2017-05-24 17:33:18' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:26 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:26' and  '2017-05-24 17:33:26' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:26 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:26' and  '2017-05-24 17:33:26' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:34 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:34' and  '2017-05-24 17:33:34' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:34 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:34' and  '2017-05-24 17:33:34' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:42 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:42' and  '2017-05-24 17:33:42' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:42 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,latitude,longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:42' and  '2017-05-24 17:33:42' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:50 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:50' and  '2017-05-24 17:33:50' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:50 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:50' and  '2017-05-24 17:33:50' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:33:58 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:58' and  '2017-05-24 17:33:58' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:33:58 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:33:58' and  '2017-05-24 17:33:58' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:34:06 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:06' and  '2017-05-24 17:34:06' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:34:06 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:06' and  '2017-05-24 17:34:06' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:34:14 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:14' and  '2017-05-24 17:34:14' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:34:14 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:14' and  '2017-05-24 17:34:14' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:34:22 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:22' and  '2017-05-24 17:34:22' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:34:22 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:22' and  '2017-05-24 17:34:22' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:34:30 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:30' and  '2017-05-24 17:34:30' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:34:30 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:30' and  '2017-05-24 17:34:30' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:34:38 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:38' and  '2017-05-24 17:34:38' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:34:38 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:38' and  '2017-05-24 17:34:38' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:34:46 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:46' and  '2017-05-24 17:34:46' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:34:46 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:46' and  '2017-05-24 17:34:46' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:34:54 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:54' and  '2017-05-24 17:34:54' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:34:54 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:34:54' and  '2017-05-24 17:34:54' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:35:02 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:02' and  '2017-05-24 17:35:02' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:35:02 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:02' and  '2017-05-24 17:35:02' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:35:10 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:10' and  '2017-05-24 17:35:10' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:35:10 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:10' and  '2017-05-24 17:35:10' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:35:18 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:18' and  '2017-05-24 17:35:18' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:35:18 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:18' and  '2017-05-24 17:35:18' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:35:26 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:26' and  '2017-05-24 17:35:26' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:35:26 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:26' and  '2017-05-24 17:35:26' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:35:34 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:34' and  '2017-05-24 17:35:34' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:35:34 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:34' and  '2017-05-24 17:35:34' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:35:42 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:42' and  '2017-05-24 17:35:42' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:35:42 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:42' and  '2017-05-24 17:35:42' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:35:51 --- ERROR: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:51' and  '2017-05-24 17:35:51' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-05-23 17:35:51 --- STRACE: Database_Exception [ 1052 ]: [1052] Column 'driver_id' in field list is ambiguous ( SELECT passengers.name as name,CONCAT(passengers.country_code,passengers.phone) as mobile_number, pickup_latitude,travel_status,pickup_longitude,driver_id,drivr.latitude,drivr.longitude FROM passengers_log  
		
		JOIN  passengers ON (  passengers.`id` =  passengers_log.`passengers_id` )
        LEFT JOIN  driver as drivr ON (  driver.`id` =  passengers_log.`driver_id` )
	
		 where (createdate between '2017-05-22 17:35:51' and  '2017-05-24 17:35:51' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by passengers.`id` 
		 order by passengers_log_id desc ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT passenge...', false, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2952): Kohana_Database_Query->execute()
#2 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1417): Model_Taxidispatch->getPassengerLocation()
#3 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#8 {main}
2017-05-23 17:47:42 --- ERROR: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
2017-05-23 17:47:42 --- STRACE: ErrorException [ 8 ]: Trying to get property of non-object ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 67 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(67): Kohana_Core::error_handler(8, 'Trying to get p...', '/home/developer...', 67, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/mysqli/classes/kohana/database/mysqli.php(424): Kohana_Database_MySQLi->connect()
#2 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database.php(478): Kohana_Database_MySQLi->escape('1')
#3 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('1')
#4 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_MySQLi), Array)
#5 /home/developer/workspace/Taxi_Getup_web/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_MySQLi))
#6 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/commonmodel.php(947): Kohana_Database_Query->execute()
#7 /home/developer/workspace/Taxi_Getup_web/application/classes/common_config.php(34): Model_Commonmodel->common_site_info()
#8 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/dispatchadmin.php(52): require('/home/developer...')
#9 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(10): Controller_Dispatchadmin->__construct(Object(Request), Object(Response))
#10 [internal function]: Controller_TaximobilityTaxidispatch->__construct(Object(Request), Object(Response))
#11 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(101): ReflectionClass->newInstance(Object(Request), Object(Response))
#12 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#13 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#14 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#15 {main}