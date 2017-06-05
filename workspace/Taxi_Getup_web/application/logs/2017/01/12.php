<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-01-12 14:31:32 --- ERROR: ErrorException: stream_socket_client(): SSL operation failed with code 1. OpenSSL Error messages:
error:14094410:SSL routines:SSL3_READ_BYTES:sslv3 alert handshake failure in /var/www/html/modules/taximobility/classes/model/commonmodel.php:1023
Stack trace:
#0 /var/www/html/modules/taximobility/classes/model/commonmodel.php(1023): Kohana_Core::error_handler(2, 'stream_socket_c...', '/var/www/html/m...', 1023, Array)
#1 /var/www/html/modules/taximobility/classes/controller/taximobilitymanage.php(8925): Model_Commonmodel->send_pushnotification('123456789', '2', Array, 'AIzaSyA85LMgTdn...')
#2 [internal function]: Controller_TaximobilityManage->action_driver_logout()
#3 /var/www/html/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#4 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /var/www/html/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-12 14:31:40 --- ERROR: ErrorException: stream_socket_client(): SSL operation failed with code 1. OpenSSL Error messages:
error:14094410:SSL routines:SSL3_READ_BYTES:sslv3 alert handshake failure in /var/www/html/modules/taximobility/classes/model/commonmodel.php:1023
Stack trace:
#0 /var/www/html/modules/taximobility/classes/model/commonmodel.php(1023): Kohana_Core::error_handler(2, 'stream_socket_c...', '/var/www/html/m...', 1023, Array)
#1 /var/www/html/modules/taximobility/classes/controller/taximobilitymanage.php(8925): Model_Commonmodel->send_pushnotification('123456789', '2', Array, 'AIzaSyA85LMgTdn...')
#2 [internal function]: Controller_TaximobilityManage->action_driver_logout()
#3 /var/www/html/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#4 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /var/www/html/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-12 14:31:49 --- ERROR: ErrorException: stream_socket_client(): SSL operation failed with code 1. OpenSSL Error messages:
error:14094410:SSL routines:SSL3_READ_BYTES:sslv3 alert handshake failure in /var/www/html/modules/taximobility/classes/model/commonmodel.php:1023
Stack trace:
#0 /var/www/html/modules/taximobility/classes/model/commonmodel.php(1023): Kohana_Core::error_handler(2, 'stream_socket_c...', '/var/www/html/m...', 1023, Array)
#1 /var/www/html/modules/taximobility/classes/controller/taximobilitymanage.php(8925): Model_Commonmodel->send_pushnotification('123456789', '2', Array, 'AIzaSyA85LMgTdn...')
#2 [internal function]: Controller_TaximobilityManage->action_driver_logout()
#3 /var/www/html/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#4 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /var/www/html/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-12 14:32:54 --- ERROR: ErrorException: stream_socket_client(): SSL operation failed with code 1. OpenSSL Error messages:
error:14094410:SSL routines:SSL3_READ_BYTES:sslv3 alert handshake failure in /var/www/html/modules/taximobility/classes/model/commonmodel.php:1023
Stack trace:
#0 /var/www/html/modules/taximobility/classes/model/commonmodel.php(1023): Kohana_Core::error_handler(2, 'stream_socket_c...', '/var/www/html/m...', 1023, Array)
#1 /var/www/html/modules/taximobility/classes/controller/taximobilitymanage.php(8925): Model_Commonmodel->send_pushnotification('123456789', '2', Array, 'AIzaSyA85LMgTdn...')
#2 [internal function]: Controller_TaximobilityManage->action_driver_logout()
#3 /var/www/html/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#4 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /var/www/html/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-12 14:36:01 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 36864 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 14:54:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 14:57:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 15:06:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 15:15:01 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 15:39:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 15:45:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 15:48:01 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:00:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:03:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:21:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:39:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:42:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:48:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:54:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 16:57:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 17:00:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 17:04:46 --- ERROR: ErrorException: Undefined offset:0 in /var/www/html/modules/taximobility/classes/model/taxidispatch.php:2208
Stack trace:
#0 /var/www/html/modules/taximobility/classes/model/taxidispatch.php(2208): Kohana_Core::error_handler(8, 'Undefined offse...', '/var/www/html/m...', 2208, Array)
#1 /var/www/html/modules/taximobility/classes/controller/taximobilitytaxidispatch.php(2024): Model_Taxidispatch->cancelbooking_logid(Array)
#2 [internal function]: Controller_TaximobilityTaxidispatch->action_cancel_booking()
#3 /var/www/html/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Taxidispatch))
#4 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /var/www/html/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-12 17:09:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 20:28:44 --- ERROR: HTTP_Exception_404 [ 404 ]: The requested URL manager/html was not found on this server. ~ SYSPATH/classes/kohana/request/client/internal.php [ 111 ]
2017-01-12 21:33:02 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 135168 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 21:50:56 --- ERROR: ErrorException: Undefined index: HTTP_HOST in /var/www/html/application/i18n/en.php:1560
Stack trace:
#0 /var/www/html/application/i18n/en.php(1560): Kohana_Core::error_handler(8, 'Undefined index...', '/var/www/html/a...', 1560, Array)
#1 /var/www/html/system/classes/kohana/core.php(800): include('/var/www/html/a...')
#2 /var/www/html/system/classes/kohana/i18n.php(120): Kohana_Core::load('/var/www/html/a...')
#3 /var/www/html/system/classes/kohana/i18n.php(81): Kohana_I18n::load('en')
#4 /var/www/html/system/classes/kohana/i18n.php(161): Kohana_I18n::get('button_signin')
#5 /var/www/html/application/views/themes/default/website_user/signin_popup.php(3): __('button_signin')
#6 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#7 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#8 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#9 /var/www/html/application/views/themes/default/header-demo.php(23): Kohana_View->__toString()
#10 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#11 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#12 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#13 /var/www/html/application/views/themes/default/template.php(66): Kohana_View->__toString()
#14 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#15 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#16 /var/www/html/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#17 [internal function]: Kohana_Controller_Template->after()
#18 /var/www/html/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Users))
#19 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#20 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#21 /var/www/html/index.php(113): Kohana_Request->execute()
#22 {main}
2017-01-12 21:50:56 --- ERROR: ErrorException: Undefined index: HTTP_HOST in /var/www/html/application/i18n/en.php:1560
Stack trace:
#0 /var/www/html/application/i18n/en.php(1560): Kohana_Core::error_handler(8, 'Undefined index...', '/var/www/html/a...', 1560, Array)
#1 /var/www/html/system/classes/kohana/core.php(800): include('/var/www/html/a...')
#2 /var/www/html/system/classes/kohana/i18n.php(120): Kohana_Core::load('/var/www/html/a...')
#3 /var/www/html/system/classes/kohana/i18n.php(81): Kohana_I18n::load('en')
#4 /var/www/html/system/classes/kohana/i18n.php(161): Kohana_I18n::get('forgot_password')
#5 /var/www/html/application/views/themes/default/website_user/forgot_password.php(2): __('forgot_password')
#6 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#7 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#8 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#9 /var/www/html/application/views/themes/default/header-demo.php(27): Kohana_View->__toString()
#10 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#11 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#12 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#13 /var/www/html/application/views/themes/default/template.php(66): Kohana_View->__toString()
#14 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#15 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#16 /var/www/html/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#17 [internal function]: Kohana_Controller_Template->after()
#18 /var/www/html/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Users))
#19 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#20 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#21 /var/www/html/index.php(113): Kohana_Request->execute()
#22 {main}
2017-01-12 21:50:56 --- ERROR: ErrorException: Undefined index: HTTP_HOST in /var/www/html/application/i18n/en.php:1560
Stack trace:
#0 /var/www/html/application/i18n/en.php(1560): Kohana_Core::error_handler(8, 'Undefined index...', '/var/www/html/a...', 1560, Array)
#1 /var/www/html/system/classes/kohana/core.php(800): include('/var/www/html/a...')
#2 /var/www/html/system/classes/kohana/i18n.php(120): Kohana_Core::load('/var/www/html/a...')
#3 /var/www/html/system/classes/kohana/i18n.php(81): Kohana_I18n::load('en')
#4 /var/www/html/system/classes/kohana/i18n.php(161): Kohana_I18n::get('email')
#5 /var/www/html/application/views/themes/default/website_user/signup_popup.php(15): __('email')
#6 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#7 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#8 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#9 /var/www/html/application/views/themes/default/header-demo.php(31): Kohana_View->__toString()
#10 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#11 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#12 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#13 /var/www/html/application/views/themes/default/template.php(66): Kohana_View->__toString()
#14 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#15 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#16 /var/www/html/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#17 [internal function]: Kohana_Controller_Template->after()
#18 /var/www/html/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Users))
#19 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#20 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#21 /var/www/html/index.php(113): Kohana_Request->execute()
#22 {main}
2017-01-12 21:50:56 --- ERROR: ErrorException: Undefined index: HTTP_HOST in /var/www/html/application/i18n/en.php:1560
Stack trace:
#0 /var/www/html/application/i18n/en.php(1560): Kohana_Core::error_handler(8, 'Undefined index...', '/var/www/html/a...', 1560, Array)
#1 /var/www/html/system/classes/kohana/core.php(800): include('/var/www/html/a...')
#2 /var/www/html/system/classes/kohana/i18n.php(120): Kohana_Core::load('/var/www/html/a...')
#3 /var/www/html/system/classes/kohana/i18n.php(81): Kohana_I18n::load('en')
#4 /var/www/html/system/classes/kohana/i18n.php(161): Kohana_I18n::get('information')
#5 /var/www/html/application/views/themes/default/header-demo.php(34): __('information')
#6 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#7 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#8 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#9 /var/www/html/application/views/themes/default/template.php(66): Kohana_View->__toString()
#10 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#11 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#12 /var/www/html/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#13 [internal function]: Kohana_Controller_Template->after()
#14 /var/www/html/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Users))
#15 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#16 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#17 /var/www/html/index.php(113): Kohana_Request->execute()
#18 {main}
2017-01-12 21:50:56 --- ERROR: ErrorException: Undefined index: HTTP_HOST in /var/www/html/application/i18n/en.php:1560
Stack trace:
#0 /var/www/html/application/i18n/en.php(1560): Kohana_Core::error_handler(8, 'Undefined index...', '/var/www/html/a...', 1560, Array)
#1 /var/www/html/system/classes/kohana/core.php(800): include('/var/www/html/a...')
#2 /var/www/html/system/classes/kohana/i18n.php(120): Kohana_Core::load('/var/www/html/a...')
#3 /var/www/html/system/classes/kohana/i18n.php(81): Kohana_I18n::load('en')
#4 /var/www/html/system/classes/kohana/i18n.php(161): Kohana_I18n::get('select_type')
#5 /var/www/html/application/views/themes/default/home.php(60): __('select_type')
#6 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#7 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#8 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#9 /var/www/html/application/views/themes/default/template.php(70): Kohana_View->__toString()
#10 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#11 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#12 /var/www/html/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#13 [internal function]: Kohana_Controller_Template->after()
#14 /var/www/html/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Users))
#15 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#16 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#17 /var/www/html/index.php(113): Kohana_Request->execute()
#18 {main}
2017-01-12 21:50:56 --- ERROR: ErrorException: Undefined index: HTTP_HOST in /var/www/html/application/i18n/en.php:1560
Stack trace:
#0 /var/www/html/application/i18n/en.php(1560): Kohana_Core::error_handler(8, 'Undefined index...', '/var/www/html/a...', 1560, Array)
#1 /var/www/html/system/classes/kohana/core.php(800): include('/var/www/html/a...')
#2 /var/www/html/system/classes/kohana/i18n.php(120): Kohana_Core::load('/var/www/html/a...')
#3 /var/www/html/system/classes/kohana/i18n.php(81): Kohana_I18n::load('en')
#4 /var/www/html/system/classes/kohana/i18n.php(161): Kohana_I18n::get('footer_label')
#5 /var/www/html/application/views/themes/default/footer.php(4): __('footer_label')
#6 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#7 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#8 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#9 /var/www/html/application/views/themes/default/template.php(71): Kohana_View->__toString()
#10 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#11 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#12 /var/www/html/system/classes/kohana/controller/template.php(44): Kohana_View->render()
#13 [internal function]: Kohana_Controller_Template->after()
#14 /var/www/html/system/classes/kohana/request/client/internal.php(121): ReflectionMethod->invoke(Object(Controller_Users))
#15 /var/www/html/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#16 /var/www/html/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#17 /var/www/html/index.php(113): Kohana_Request->execute()
#18 {main}
2017-01-12 22:27:06 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 266240 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 22:51:06 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 266240 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 23:00:07 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 266240 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-12 23:03:06 --- ERROR: ErrorException: Allowed memory size of 1073741824 bytes exhausted (tried to allocate 266240 bytes) in /var/www/html/system/classes/kohana/core.php:961
Stack trace:
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}