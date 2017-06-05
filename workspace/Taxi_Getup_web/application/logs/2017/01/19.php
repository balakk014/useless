<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-01-19 02:30:51 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:30:51 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 02:31:04 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:31:04 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 02:38:02 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:38:02 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 02:40:10 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:40:10 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 02:40:25 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:40:25 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 02:40:47 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:40:47 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 02:41:36 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:41:36 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,passengers_log.street_pickup,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 02:41:49 --- ERROR: ErrorException [ 8 ]: Undefined index: trip_id ~ MODPATH/taximobility/classes/controller/driver.php [ 690 ]
2017-01-19 02:41:49 --- STRACE: ErrorException [ 8 ]: Undefined index: trip_id ~ MODPATH/taximobility/classes/controller/driver.php [ 690 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(690): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 690, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 02:41:49 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 02:41:49 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 02:42:47 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.distance_fare' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 02:42:47 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.distance_fare' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:07:27 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/driverUnfocus.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 05:07:27 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/driverUnfocus.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 05:17:31 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.distance_fare' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:17:31 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.distance_fare' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:17:54 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.distance_fare' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:17:54 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.distance_fare' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude,passengers_log.pickup_longitude,passengers_log.now_after,passengers_log.distance_fare,
		IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid,
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
 		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1713): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:27:42 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booking_userdetails' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:27:42 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booking_userdetails' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1712): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:27:51 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booking_userdetails' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:27:51 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booking_userdetails' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booking_userdetails,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1712): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:28:24 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booked_from' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:28:24 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booked_from' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1712): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:28:28 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booked_from' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:28:28 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.booked_from' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,passengers_log.booked_from,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,passengers_log.booking_type,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,passengers_log.fixed_price_status,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1712): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:29:42 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/driverUnfocus.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 05:29:42 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/iOS/static_image/driverUnfocus.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 05:32:15 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'people.driver_commission' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:32:15 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'people.driver_commission' in 'field list' ( SELECT people.phone,passengers_log.booking_key,passengers_log.passengers_id,passengers_log.driver_id,passengers_log.taxi_id,
		passengers_log.company_id,passengers_log.current_location,passengers_log.pickup_latitude, passengers_log.pickup_longitude,passengers_log.now_after, IFNULL(passengers_log.drop_location,0) as drop_location,passengers_log.drop_latitude,passengers_log.drop_longitude,passengers_log.no_passengers,passengers_log.taxi_modelid, 
		passengers_log.approx_distance,passengers_log.approx_duration,passengers_log.approx_fare,passengers_log.fixedprice,passengers_log.promocode,passengers_log.time_to_reach_passen,
		passengers_log.pickup_time,passengers_log.actual_pickup_time,passengers_log.drop_time,passengers_log.account_id,passengers_log.accgroup_id,passengers_log.pickupdrop,
		passengers_log.rating,passengers_log.comments,passengers_log.travel_status,passengers_log.driver_reply,
		passengers_log.msg_status,passengers_log.createdate,passengers_log.booking_from,passengers_log.search_city,passengers_log.used_wallet_amount,
		passengers_log.sub_logid,passengers_log.bookby,passengers_log.booking_from_cid,passengers_log.distance,passengers_log.notes_driver,passengers_log.waitingtime,
		people.name AS driver_name,passengers_log.promocode,passengers.discount AS passenger_discount,people.phone AS driver_phone,people.profile_picture AS driver_photo,people.device_id AS driver_device_id,people.device_token AS driver_device_token,people.device_type AS driver_device_type,passengers.discount AS passenger_discount,passengers.device_id AS passenger_device_id,passengers.device_token AS passenger_device_token,passengers.referred_by AS referred_by,passengers.referrer_earned AS referrer_earned,passengers.device_type AS passenger_device_type, passengers.salutation AS passenger_salutation,passengers.name AS passenger_name,passengers.lastname AS passenger_lastname,passengers.email AS passenger_email,passengers.phone AS passenger_phone,IFNULL(passengers.wallet_amount,0) AS wallet_amount,companyinfo.cancellation_fare as cancellation_nfree,companyinfo.company_tax as company_tax,people.driver_commission
		FROM  passengers_log 
		JOIN  company ON (  passengers_log.`company_id` =  company.`cid` ) 
		JOIN  passengers ON (  passengers_log.`passengers_id` =  passengers.`id` ) 
		JOIN  companyinfo ON (  passengers_log.`company_id` =  companyinfo.`company_cid` ) 
		JOIN  people ON (  people.`id` =  passengers_log.`driver_id` ) 
		WHERE  passengers_log.`passengers_log_id` =  '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT people.p...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1712): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(693): Model_Driver->get_passenger_log('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:32:37 --- ERROR: ErrorException [ 8 ]: Undefined index: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 705 ]
2017-01-19 05:32:37 --- STRACE: ErrorException [ 8 ]: Undefined index: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 705 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(705): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 705, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:33:06 --- ERROR: ErrorException [ 8 ]: Undefined index: booking_userdetails ~ MODPATH/taximobility/classes/controller/driver.php [ 718 ]
2017-01-19 05:33:06 --- STRACE: ErrorException [ 8 ]: Undefined index: booking_userdetails ~ MODPATH/taximobility/classes/controller/driver.php [ 718 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(718): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 718, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:33:24 --- ERROR: ErrorException [ 8 ]: Undefined index: booked_from ~ MODPATH/taximobility/classes/controller/driver.php [ 719 ]
2017-01-19 05:33:24 --- STRACE: ErrorException [ 8 ]: Undefined index: booked_from ~ MODPATH/taximobility/classes/controller/driver.php [ 719 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(719): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 719, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:33:36 --- ERROR: ErrorException [ 8 ]: Undefined index: distance_fare ~ MODPATH/taximobility/classes/controller/driver.php [ 721 ]
2017-01-19 05:33:36 --- STRACE: ErrorException [ 8 ]: Undefined index: distance_fare ~ MODPATH/taximobility/classes/controller/driver.php [ 721 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(721): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 721, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:33:51 --- ERROR: ErrorException [ 8 ]: Undefined index: street_pickup ~ MODPATH/taximobility/classes/controller/driver.php [ 725 ]
2017-01-19 05:33:51 --- STRACE: ErrorException [ 8 ]: Undefined index: street_pickup ~ MODPATH/taximobility/classes/controller/driver.php [ 725 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(725): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 725, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:34:07 --- ERROR: ErrorException [ 8 ]: Undefined variable: booked_from ~ MODPATH/taximobility/classes/controller/driver.php [ 737 ]
2017-01-19 05:34:07 --- STRACE: ErrorException [ 8 ]: Undefined variable: booked_from ~ MODPATH/taximobility/classes/controller/driver.php [ 737 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(737): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 737, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:35:19 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'model.booking_fee' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,model.booking_fee, cents_below_miles, cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:35:19 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'model.booking_fee' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,model.booking_fee, cents_below_miles, cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT (SUM(mod...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1764): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(738): Model_Driver->get_model_fare_details1('1', '1', '1')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:35:58 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'model.booking_fee' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,model.booking_fee, cents_below_miles, cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:35:58 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'model.booking_fee' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,model.booking_fee, cents_below_miles, cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT (SUM(mod...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1764): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(738): Model_Driver->get_model_fare_details1('1', '1', '1')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:36:33 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_below_miles' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km, cents_below_miles, cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:36:33 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_below_miles' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km, cents_below_miles, cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT (SUM(mod...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1764): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(738): Model_Driver->get_model_fare_details1('1', '1', '1')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:46:08 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_below_counts' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,  cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:46:08 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_below_counts' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,  cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT (SUM(mod...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1764): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(738): Model_Driver->get_model_fare_details1('1', '1', '1')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:46:27 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_below_counts' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,  cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:46:27 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_below_counts' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,  cents_below_counts, cents_above_miles, cents_above_counts
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT (SUM(mod...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1764): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(738): Model_Driver->get_model_fare_details1('1', '1', '1')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:46:55 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_above_miles' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,   cents_above_miles
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:46:55 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'cents_above_miles' in 'field list' ( SELECT (SUM(model.base_fare)*(0.00)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*(0.00)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*(0.00)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*(0.00)/100) + model.below_km as below_km,(SUM(model.above_km)*(0.00)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*(0.00)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*(0.00)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*(0.00)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,   cents_above_miles
			FROM  motor_model as model WHERE  model.`model_id` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT (SUM(mod...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1764): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(738): Model_Driver->get_model_fare_details1('1', '1', '1')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:47:32 --- ERROR: ErrorException [ 8 ]: Undefined index: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 766 ]
2017-01-19 05:47:32 --- STRACE: ErrorException [ 8 ]: Undefined index: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 766 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(766): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 766, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:47:56 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'invitation_code_setting' in 'field list' ( SELECT admin_commission,referral_discount,currency_format,referral_amount,referral_settings,wallet_amount1,wallet_amount2,wallet_amount3,wallet_amount_range,invitation_code_setting  FROM siteinfo ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 05:47:56 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'invitation_code_setting' in 'field list' ( SELECT admin_commission,referral_discount,currency_format,referral_amount,referral_settings,wallet_amount1,wallet_amount2,wallet_amount3,wallet_amount_range,invitation_code_setting  FROM siteinfo ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT admin_co...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1834): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(947): Model_Driver->siteinfo_details()
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 05:48:16 --- ERROR: ErrorException [ 8 ]: Undefined variable: sub_total ~ MODPATH/taximobility/classes/controller/driver.php [ 995 ]
2017-01-19 05:48:16 --- STRACE: ErrorException [ 8 ]: Undefined variable: sub_total ~ MODPATH/taximobility/classes/controller/driver.php [ 995 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(995): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 995, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:51:16 --- ERROR: ErrorException [ 1 ]: Call to undefined method Model_Driver::update_table() ~ MODPATH/taximobility/classes/controller/driver.php [ 1051 ]
2017-01-19 05:51:16 --- STRACE: ErrorException [ 1 ]: Call to undefined method Model_Driver::update_table() ~ MODPATH/taximobility/classes/controller/driver.php [ 1051 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-19 05:52:13 --- ERROR: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1081 ]
2017-01-19 05:52:13 --- STRACE: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1081 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1081): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1081, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:52:18 --- ERROR: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1081 ]
2017-01-19 05:52:18 --- STRACE: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1081 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1081): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1081, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:57:05 --- ERROR: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1084 ]
2017-01-19 05:57:05 --- STRACE: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1084 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1084): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1084, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 05:58:39 --- ERROR: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1084 ]
2017-01-19 05:58:39 --- STRACE: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1084 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1084): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1084, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:03:25 --- ERROR: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1082 ]
2017-01-19 06:03:25 --- STRACE: ErrorException [ 8 ]: Undefined variable: driver_commission ~ MODPATH/taximobility/classes/controller/driver.php [ 1082 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1082): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1082, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:03:52 --- ERROR: ErrorException [ 8 ]: Undefined variable: driver_share ~ MODPATH/taximobility/classes/controller/driver.php [ 1082 ]
2017-01-19 06:03:52 --- STRACE: ErrorException [ 8 ]: Undefined variable: driver_share ~ MODPATH/taximobility/classes/controller/driver.php [ 1082 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1082): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1082, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:04:39 --- ERROR: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 1094 ]
2017-01-19 06:04:39 --- STRACE: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 1094 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1094): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1094, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:05:01 --- ERROR: ErrorException [ 8 ]: Undefined variable: fixed_price_status ~ MODPATH/taximobility/classes/controller/driver.php [ 1111 ]
2017-01-19 06:05:01 --- STRACE: ErrorException [ 8 ]: Undefined variable: fixed_price_status ~ MODPATH/taximobility/classes/controller/driver.php [ 1111 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1111): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1111, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:05:23 --- ERROR: ErrorException [ 8 ]: Use of undefined constant PASSENGERS_LOG_ADDITIONAL - assumed 'PASSENGERS_LOG_ADDITIONAL' ~ MODPATH/taximobility/classes/controller/driver.php [ 1112 ]
2017-01-19 06:05:23 --- STRACE: ErrorException [ 8 ]: Use of undefined constant PASSENGERS_LOG_ADDITIONAL - assumed 'PASSENGERS_LOG_ADDITIONAL' ~ MODPATH/taximobility/classes/controller/driver.php [ 1112 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1112): Kohana_Core::error_handler(8, 'Use of undefine...', '/home/developer...', 1112, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:05:41 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'complete_trip_status' in 'field list' ( UPDATE `passengers_log` SET `complete_trip_status` = 1 WHERE `passengers_log_id` = '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 06:05:41 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'complete_trip_status' in 'field list' ( UPDATE `passengers_log` SET `complete_trip_status` = 1 WHERE `passengers_log_id` = '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(3, 'UPDATE `passeng...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(2040): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1113): Model_Driver->update_table('passengers_log', Array, 'passengers_log_...', '593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 06:07:14 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT `passengers_log`.`street_pickup` FROM `passengers_log` WHERE `passengers_log`.`passengers_log_id` = '593' AND `passengers_log`.`street_pickup` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 06:07:14 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'passengers_log.street_pickup' in 'field list' ( SELECT `passengers_log`.`street_pickup` FROM `passengers_log` WHERE `passengers_log`.`passengers_log_id` = '593' AND `passengers_log`.`street_pickup` = '1' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT `passeng...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(1971): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1128): Model_Driver->get_booking_fee_status('593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 06:08:10 --- ERROR: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 1134 ]
2017-01-19 06:08:10 --- STRACE: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 1134 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1134): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1134, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:08:29 --- ERROR: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 1165 ]
2017-01-19 06:08:29 --- STRACE: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 1165 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1165): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 1165, Array)
#1 [internal function]: Controller_Driver->action_complete_trip()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 06:08:43 --- ERROR: ErrorException [ 1 ]: Call to undefined method Model_Driver::checktrans_details() ~ MODPATH/taximobility/classes/controller/driver.php [ 1167 ]
2017-01-19 06:08:43 --- STRACE: ErrorException [ 1 ]: Call to undefined method Model_Driver::checktrans_details() ~ MODPATH/taximobility/classes/controller/driver.php [ 1167 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-19 06:09:09 --- ERROR: ErrorException [ 1 ]: Call to undefined method Database_MySQLi::last_insert_Id() ~ MODPATH/taximobility/classes/controller/driver.php [ 1180 ]
2017-01-19 06:09:09 --- STRACE: ErrorException [ 1 ]: Call to undefined method Database_MySQLi::last_insert_Id() ~ MODPATH/taximobility/classes/controller/driver.php [ 1180 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-19 06:09:40 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 'trip_completed_by' in 'field list' ( UPDATE `passengers_log` SET `trip_completed_by` = 2 WHERE `passengers_log_id` = '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 06:09:40 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 'trip_completed_by' in 'field list' ( UPDATE `passengers_log` SET `trip_completed_by` = 2 WHERE `passengers_log_id` = '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(3, 'UPDATE `passeng...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(2040): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1205): Model_Driver->update_table('passengers_log', Array, 'passengers_log_...', '593')
#3 [internal function]: Controller_Driver->action_complete_trip()
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#8 {main}
2017-01-19 06:10:30 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller_Driver::send_mail_passenger() ~ MODPATH/taximobility/classes/controller/driver.php [ 1232 ]
2017-01-19 06:10:30 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller_Driver::send_mail_passenger() ~ MODPATH/taximobility/classes/controller/driver.php [ 1232 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-19 06:11:32 --- ERROR: Database_Exception [ 1054 ]: [1054] Unknown column 't.tolls' in 'field list' ( SELECT pl.passengers_log_id,pl.booking_key,pl.passengers_id,pl.driver_id,pl.taxi_id,pl.company_id,pl.current_location,pl.pickup_latitude,pl.pickup_longitude,pl.drop_location,pl.drop_latitude,pl.drop_longitude,pl.no_passengers,pl.approx_distance,pl.approx_duration,pl.approx_fare,pl.time_to_reach_passen,pl.pickup_time,pl.dispatch_time,pl.pickupdrop,pl.rating,pl.comments,pl.travel_status,pl.driver_reply,pl.createdate,pl.booking_from,pl.company_tax,pl.faretype,pl.bookingtype,pl.driver_comments,(pl.drop_time - pl.actual_pickup_time) as travel_time,
				
				t.id as job_referral,t.distance,t.actual_distance,t.tripfare,t.fare,t.tips,t.waiting_time,t.waiting_cost,t.company_tax as tax_amount,t.amt,t.passenger_discount,t.account_discount,t.credits_used,t.transaction_id,t.payment_type,t.payment_status,t.admin_amount,t.company_amount,t.nightfare_applicable,t.nightfare,t.eveningfare_applicable,t.eveningfare,t.trans_packtype,t.waiting_time,t.minutes_fare,t.trip_minutes,	pl.used_wallet_amount, p.name as passenger_name,p.email as 
				passenger_email,pe.name as driver_name,pe.email as driver_email,t.tolls,t.tips,t.trans_booking_fee,pl.booking_userdetails,pl.booking_user 
				FROM passengers_log as pl left join passengers as p on 
				p.id=pl.passengers_id left join transacation as t on 
				t.passengers_log_id=pl.passengers_log_id left join people as pe on pl.driver_id=pe.id WHERE t.passengers_log_id = '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
2017-01-19 06:11:32 --- STRACE: Database_Exception [ 1054 ]: [1054] Unknown column 't.tolls' in 'field list' ( SELECT pl.passengers_log_id,pl.booking_key,pl.passengers_id,pl.driver_id,pl.taxi_id,pl.company_id,pl.current_location,pl.pickup_latitude,pl.pickup_longitude,pl.drop_location,pl.drop_latitude,pl.drop_longitude,pl.no_passengers,pl.approx_distance,pl.approx_duration,pl.approx_fare,pl.time_to_reach_passen,pl.pickup_time,pl.dispatch_time,pl.pickupdrop,pl.rating,pl.comments,pl.travel_status,pl.driver_reply,pl.createdate,pl.booking_from,pl.company_tax,pl.faretype,pl.bookingtype,pl.driver_comments,(pl.drop_time - pl.actual_pickup_time) as travel_time,
				
				t.id as job_referral,t.distance,t.actual_distance,t.tripfare,t.fare,t.tips,t.waiting_time,t.waiting_cost,t.company_tax as tax_amount,t.amt,t.passenger_discount,t.account_discount,t.credits_used,t.transaction_id,t.payment_type,t.payment_status,t.admin_amount,t.company_amount,t.nightfare_applicable,t.nightfare,t.eveningfare_applicable,t.eveningfare,t.trans_packtype,t.waiting_time,t.minutes_fare,t.trip_minutes,	pl.used_wallet_amount, p.name as passenger_name,p.email as 
				passenger_email,pe.name as driver_name,pe.email as driver_email,t.tolls,t.tips,t.trans_booking_fee,pl.booking_userdetails,pl.booking_user 
				FROM passengers_log as pl left join passengers as p on 
				p.id=pl.passengers_id left join transacation as t on 
				t.passengers_log_id=pl.passengers_log_id left join people as pe on pl.driver_id=pe.id WHERE t.passengers_log_id = '593' ) ~ MODPATH/mysqli/classes/kohana/database/mysqli.php [ 185 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/database/classes/kohana/database/query.php(245): Kohana_Database_MySQLi->query(1, 'SELECT pl.passe...', false, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/model/driver.php(2020): Kohana_Database_Query->execute()
#2 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(2892): Model_Driver->passenger_transdetails('593')
#3 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1232): Controller_Driver->send_mail_passenger('593', 1)
#4 [internal function]: Controller_Driver->action_complete_trip()
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#6 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#7 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#8 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#9 {main}
2017-01-19 06:13:25 --- ERROR: ErrorException [ 8 ]: Undefined index: booking_user ~ MODPATH/taximobility/classes/controller/driver.php [ 2896 ]
2017-01-19 06:13:25 --- STRACE: ErrorException [ 8 ]: Undefined index: booking_user ~ MODPATH/taximobility/classes/controller/driver.php [ 2896 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(2896): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 2896, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1232): Controller_Driver->send_mail_passenger('593', 1)
#2 [internal function]: Controller_Driver->action_complete_trip()
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-19 06:14:22 --- ERROR: ErrorException [ 8 ]: Undefined index: tolls ~ MODPATH/taximobility/classes/controller/driver.php [ 2978 ]
2017-01-19 06:14:22 --- STRACE: ErrorException [ 8 ]: Undefined index: tolls ~ MODPATH/taximobility/classes/controller/driver.php [ 2978 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(2978): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 2978, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1232): Controller_Driver->send_mail_passenger('593', 1)
#2 [internal function]: Controller_Driver->action_complete_trip()
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-19 06:14:56 --- ERROR: ErrorException [ 8 ]: Undefined index: tolls ~ MODPATH/taximobility/classes/controller/driver.php [ 2967 ]
2017-01-19 06:14:56 --- STRACE: ErrorException [ 8 ]: Undefined index: tolls ~ MODPATH/taximobility/classes/controller/driver.php [ 2967 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(2967): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 2967, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1232): Controller_Driver->send_mail_passenger('593', 1)
#2 [internal function]: Controller_Driver->action_complete_trip()
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-19 06:15:10 --- ERROR: ErrorException [ 8 ]: Undefined index: trans_booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 2967 ]
2017-01-19 06:15:10 --- STRACE: ErrorException [ 8 ]: Undefined index: trans_booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 2967 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(2967): Kohana_Core::error_handler(8, 'Undefined index...', '/home/developer...', 2967, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1232): Controller_Driver->send_mail_passenger('593', 1)
#2 [internal function]: Controller_Driver->action_complete_trip()
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-19 06:15:23 --- ERROR: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 3042 ]
2017-01-19 06:15:23 --- STRACE: ErrorException [ 8 ]: Undefined variable: booking_fee ~ MODPATH/taximobility/classes/controller/driver.php [ 3042 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(3042): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 3042, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1232): Controller_Driver->send_mail_passenger('593', 1)
#2 [internal function]: Controller_Driver->action_complete_trip()
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-19 06:15:39 --- ERROR: ErrorException [ 8 ]: Undefined variable: tolls ~ MODPATH/taximobility/classes/controller/driver.php [ 3047 ]
2017-01-19 06:15:39 --- STRACE: ErrorException [ 8 ]: Undefined variable: tolls ~ MODPATH/taximobility/classes/controller/driver.php [ 3047 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(3047): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 3047, Array)
#1 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/driver.php(1232): Controller_Driver->send_mail_passenger('593', 1)
#2 [internal function]: Controller_Driver->action_complete_trip()
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Driver))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#7 {main}
2017-01-19 06:55:19 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 06:55:19 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 06:55:19 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 06:55:19 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 06:55:19 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 06:55:19 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 07:57:41 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 07:57:41 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 07:57:50 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 07:57:50 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 07:57:50 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 07:57:50 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 07:57:57 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 07:57:57 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 07:57:57 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 07:57:57 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 07:57:59 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 07:57:59 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 07:57:59 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 07:57:59 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:03 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:03 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:03 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:03 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:05 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:05 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:05 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:05 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:06 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:06 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:06 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:06 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:18:06 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:18:06 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:20:57 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:20:57 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:20:59 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:20:59 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:20:59 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:20:59 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:20:59 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:20:59 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:22:34 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:22:34 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:22:35 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:22:35 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:22:35 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:22:35 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:22:35 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:22:35 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:01 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:01 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:02 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:02 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:02 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:02 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:02 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:02 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:57 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:57 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:57 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:57 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:58 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:58 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:58 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:58 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:23:58 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:23:58 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:24:40 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:24:40 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:24:40 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:24:40 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:24:42 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:24:42 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:24:42 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:24:42 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:24:42 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:24:42 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:27:47 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:27:47 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 08:27:47 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 08:27:47 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:04:49 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:04:49 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:04:49 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:04:49 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:04:52 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:04:52 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:07:16 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:07:16 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:07:16 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:07:16 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:07:17 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:07:17 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:07:17 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:07:17 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:07:17 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:07:17 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:21:33 --- ERROR: ErrorException [ 8 ]: Undefined variable: request_page ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9425 ]
2017-01-19 09:21:33 --- STRACE: ErrorException [ 8 ]: Undefined variable: request_page ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9425 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymanage.php(9425): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 9425, Array)
#1 [internal function]: Controller_TaximobilityManage->action_pdftaxiinfo()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 09:24:35 --- ERROR: ErrorException [ 8 ]: Undefined variable: user_details1 ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9426 ]
2017-01-19 09:24:35 --- STRACE: ErrorException [ 8 ]: Undefined variable: user_details1 ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9426 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymanage.php(9426): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 9426, Array)
#1 [internal function]: Controller_TaximobilityManage->action_pdftaxiinfo()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 09:24:58 --- ERROR: ErrorException [ 1 ]: Cannot use object of type stdClass as array ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9427 ]
2017-01-19 09:24:58 --- STRACE: ErrorException [ 1 ]: Cannot use object of type stdClass as array ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9427 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2017-01-19 09:25:36 --- ERROR: ErrorException [ 8 ]: Undefined variable: driver_name ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9497 ]
2017-01-19 09:25:36 --- STRACE: ErrorException [ 8 ]: Undefined variable: driver_name ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9497 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymanage.php(9497): Kohana_Core::error_handler(8, 'Undefined varia...', '/home/developer...', 9497, Array)
#1 [internal function]: Controller_TaximobilityManage->action_pdftaxiinfo()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 09:26:27 --- ERROR: ErrorException [ 8 ]: Undefined property: stdClass::$taxi_no ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9427 ]
2017-01-19 09:26:27 --- STRACE: ErrorException [ 8 ]: Undefined property: stdClass::$taxi_no ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9427 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymanage.php(9427): Kohana_Core::error_handler(8, 'Undefined prope...', '/home/developer...', 9427, Array)
#1 [internal function]: Controller_TaximobilityManage->action_pdftaxiinfo()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 09:29:03 --- ERROR: ErrorException [ 8 ]: Undefined property: stdClass::$taxi_no ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9466 ]
2017-01-19 09:29:03 --- STRACE: ErrorException [ 8 ]: Undefined property: stdClass::$taxi_no ~ MODPATH/taximobility/classes/controller/taximobilitymanage.php [ 9466 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/modules/taximobility/classes/controller/taximobilitymanage.php(9466): Kohana_Core::error_handler(8, 'Undefined prope...', '/home/developer...', 9466, Array)
#1 [internal function]: Controller_TaximobilityManage->action_pdftaxiinfo()
#2 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Manage))
#3 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/developer/workspace_taxi/GetUpTaxi/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#6 {main}
2017-01-19 09:32:15 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:32:15 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:32:15 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:32:15 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:32:16 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:32:16 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:32:16 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:32:16 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:32:16 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:32:16 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:34:48 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:34:48 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:34:50 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:34:50 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:34:50 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:34:50 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:34:50 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:34:50 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:35:58 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:35:58 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:36:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:36:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:36:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:36:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:36:00 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:36:00 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:38:25 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:38:25 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:38:26 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:38:26 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/uploads/site_logo/1.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:38:27 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:38:27 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_one.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:38:27 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:38:27 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_two.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}
2017-01-19 09:38:27 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2017-01-19 09:38:27 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: public/images/driver_three.png ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/developer/workspace_taxi/GetUpTaxi/index.php(113): Kohana_Request->execute()
#1 {main}