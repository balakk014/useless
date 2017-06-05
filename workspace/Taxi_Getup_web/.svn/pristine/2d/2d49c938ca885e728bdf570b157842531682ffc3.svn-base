<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-01-16 13:44:14 --- ERROR: ErrorException [ 8 ]: Undefined index: HTTP_HOST ~ APPPATH/i18n/en.php [ 1560 ]
2017-01-16 13:44:14 --- STRACE: ErrorException [ 8 ]: Undefined index: HTTP_HOST ~ APPPATH/i18n/en.php [ 1560 ]
--
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
2017-01-16 15:10:30 --- ERROR: ErrorException [ 8 ]: Use of undefined constant SITE_FAVICON - assumed 'SITE_FAVICON' ~ APPPATH/views/error/404.php [ 23 ]
2017-01-16 15:10:30 --- STRACE: ErrorException [ 8 ]: Use of undefined constant SITE_FAVICON - assumed 'SITE_FAVICON' ~ APPPATH/views/error/404.php [ 23 ]
--
#0 /var/www/html/application/views/error/404.php(23): Kohana_Core::error_handler(8, 'Use of undefine...', '/var/www/html/a...', 23, Array)
#1 /var/www/html/system/classes/kohana/view.php(61): include('/var/www/html/a...')
#2 /var/www/html/system/classes/kohana/view.php(343): Kohana_View::capture('/var/www/html/a...', Array)
#3 /var/www/html/system/classes/kohana/view.php(228): Kohana_View->render()
#4 /var/www/html/system/classes/kohana/response.php(160): Kohana_View->__toString()
#5 /var/www/html/application/classes/kohana/exception.php(56): Kohana_Response->body(Object(View))
#6 [internal function]: Kohana_Exception::handler(Object(HTTP_Exception_404))
#7 {main}