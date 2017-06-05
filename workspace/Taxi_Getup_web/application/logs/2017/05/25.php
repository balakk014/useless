<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-05-25 16:19:52 --- ERROR: ErrorException [ 8 ]: Undefined variable: promocode_list ~ APPPATH/views/admin/edit_promocode.php [ 40 ]
2017-05-25 16:19:52 --- STRACE: ErrorException [ 8 ]: Undefined variable: promocode_list ~ APPPATH/views/admin/edit_promocode.php [ 40 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/application/views/admin/edit_promocode.php(40): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 40, Array)
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
2017-05-25 18:15:35 --- ERROR: ErrorException [ 8 ]: Undefined variable: query ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2454 ]
2017-05-25 18:15:35 --- STRACE: ErrorException [ 8 ]: Undefined variable: query ~ MODPATH/taximobility/classes/model/taxidispatch.php [ 2454 ]
--
#0 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/model/taxidispatch.php(2454): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 2454, Array)
#1 /home/developer/workspace/Taxi_Getup_web/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(1009): Model_Taxidispatch->dispatcher_booking_list(Array)
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_all_booking_list_manage()
#3 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace/Taxi_Getup_web/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace/Taxi_Getup_web/index.php(113): Kohana_Request->execute()
#7 {main}