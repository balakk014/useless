<?php defined('SYSPATH') OR die('No Direct Script Access');
class Model_Taxidispatch extends Model
{
    public function __construct()
    {
        $this->session         = Session::instance();
        $this->username        = $this->session->get("username");
        $this->admin_username  = $this->session->get("username");
        $this->admin_userid    = $this->session->get("id");
        $this->admin_email     = $this->session->get("email");
        $this->user_admin_type = $this->session->get("user_type");
        $this->currentdate     = Commonfunction::getCurrentTimeStamp();
    }
    public static function getpassenger_Detailinfo_new($data)
    {
        $company_id      = $_SESSION['company_id'];
        $date_field_name = $data['field_name'];
        $split_value     = explode('-', $date_field_name);
        $c_condition     = "";
        $c_join          = "";
        if ($company_id != 0) {
            $c_join      = " left join " . PASSENGERS_LOG . " on " . PASSENGERS_LOG . ".passengers_id = " . PASSENGERS . ".id";
            //$c_condition = " and passenger_cid='$company_id' ";
            $c_condition = " and (" . PASSENGERS . ".passenger_cid='$company_id' or " . PASSENGERS_LOG . ".company_id='$company_id') ";
        }
        if ($data['field_value'] == 'firstname') {
            if (isset($split_value[1])) {
                $phone_split = substr(trim($split_value[1]), 0, -1);
                $phone_no    = substr(trim($phone_split), 1);
                $phone_no    = trim($phone_no);
                $query       = "SELECT * FROM " . PASSENGERS . " $c_join where phone ='" . $phone_no . "' $c_condition and user_status='A' limit 0,1";
                //$query = "SELECT * FROM ".PASSENGERS." where phone ='".$phone_no."' and user_status='A' limit 0,1";
                $results     = Db::query(Database::SELECT, $query)->execute()->as_array();
                return $results;
            } else {
                return 0;
            }
        } elseif ($data['field_value'] == 'email') {
            $query   = "SELECT * FROM " . PASSENGERS . " $c_join where email ='" . $data['field_name'] . "' $c_condition and user_status='A' limit 0,1";
            //$query = "SELECT * FROM ".PASSENGERS." where email ='".$data['field_name']."' and user_status='A' limit 0,1";
            $results = Db::query(Database::SELECT, $query)->execute()->as_array();
            if (count($results > 0)) {
                return $results;
            } else {
                return 0;
            }
        } elseif ($data['field_value'] == 'phone') {
            $query   = "SELECT * FROM " . PASSENGERS . " $c_join where phone ='" . $data['field_name'] . "' $c_condition and user_status='A' limit 0,1";
            //$query = "SELECT * FROM ".PASSENGERS." where phone ='".$data['field_name']."' and user_status='A' limit 0,1";
            //echo $query;exit;
            $results = Db::query(Database::SELECT, $query)->execute()->as_array();
            if (count($results > 0)) {
                return $results;
            } else {
                return 0;
            }
        }
    }
    public static function getuser_details($like, $type)
    {
        $company_id  = $_SESSION['company_id'];
        $c_condition = "";
        $c_join      = "";
        $c_groupby   = "";
        if ($company_id != 0) {
            $c_join      = " left join " . PASSENGERS_LOG . " on " . PASSENGERS_LOG . ".passengers_id = " . PASSENGERS . ".id";
            $c_condition = " and (" . PASSENGERS . ".passenger_cid='$company_id' or " . PASSENGERS_LOG . ".company_id='$company_id') ";
            $c_groupby   = " group by " . PASSENGERS . ".id ";
        }
        if ($type == 1) {
            $split_value = explode('-', urldecode($like));
            if (count($split_value) > 1) {
                $phone_split = substr(trim($split_value[1]), 0, -1);
                $phone_no    = substr(trim($phone_split), 1);
                $phone_no    = trim($phone_no);
                $query       = "SELECT * FROM " . PASSENGERS . " $c_join where phone LIKE '%$phone_no%' $c_condition and user_status ='A' $c_groupby ORDER BY `name` ASC";
            } else {
                $query = "SELECT * FROM " . PASSENGERS . " $c_join where name LIKE '%$like%' $c_condition and user_status ='A' $c_groupby ORDER BY `name` ASC";
                //$query = "SELECT * FROM ".PASSENGERS." where name LIKE '%$like%' and user_status ='A'  ORDER BY `name` ASC";
            }
            //echo $query;exit;
        } elseif ($type == 2) {
            $query = "SELECT * FROM " . PASSENGERS . " $c_join where email LIKE '%$like%' $c_condition and user_status ='A' $c_groupby ORDER BY `name` ASC";
        } elseif ($type == 3) {
            $query = "SELECT * FROM " . PASSENGERS . " $c_join where phone LIKE '%$like%' $c_condition and user_status ='A' $c_groupby ORDER BY `name` ASC";
        }
        $results = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $results;
    }
    /*************************Dashboard Driver status ***********************************/
    public function driver_status_details($array)
    {
        //print_r($array);exit;
        $driver_status = isset($array['driver_status']) ? $array['driver_status'] : "";
        $taxi_company  = isset($array['taxi_company']) ? $array['taxi_company'] : "";
        $where_cond    = "";
        if ($driver_status == 'A' || $driver_status == 'F' || $driver_status == 'B') {
            $where_cond .= " AND list.status='$driver_status' AND list.shift_status='IN'";
        } elseif ($driver_status == 'OUT') {
            $where_cond .= " AND list.status='F' AND list.shift_status='$driver_status'";
        }
        $usertype             = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'A';
        $company_id           = isset($_SESSION['company_id']) ? $_SESSION['company_id'] : 0;
        $commonmodel          = Model::factory('commonmodel');
        $company_current_time = $commonmodel->getcompany_all_currenttimestamp($company_id);
        $company_where        = "";
        if ($taxi_company != "" && $taxi_company != 0 && $usertype == 'A') {
            $company_where = " AND people.company_id =  '" . $taxi_company . "'";
        }
        if ($usertype != 'A') {
            $company_where = " AND people.company_id =  '" . $company_id . "'";
        }
        $query  = "SELECT people.id as driver_id,people.name, list.status AS driver_status, list.latitude, list.longitude,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference
				 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver  ) AS list 
				 JOIN people ON people.id = list.driver_id 
				 WHERE people.user_type =  'D' 
				 AND people.status =  'A' 
				 $where_cond $company_where
				 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
        //echo $query;exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        //echo '<pre>';print_r($result);exit;
        return $result;
    }
    public function driver_status_details_model($array)
    {
        //print_r($array);exit;
        $driver_status = isset($array['driver_status']) ? $array['driver_status'] : "";
        $taxi_company  = isset($array['taxi_company']) ? $array['taxi_company'] : "";
        $taxi_model    = isset($array['taxi_model']) ? $array['taxi_model'] : "";
        $where_cond    = "";
        if ($driver_status == 'A' || $driver_status == 'F') {
            $where_cond .= "AND D.status='$driver_status' AND D.shift_status='IN'";
        } elseif ($driver_status == 'OUT') {
            $where_cond .= "AND D.status='F' AND D.shift_status='$driver_status'";
        }
        $user_createdby       = $_SESSION['userid'];
        $usertype             = $_SESSION['user_type'];
        $company_id           = $_SESSION['company_id'];
        $country_id           = $_SESSION['country_id'];
        $state_id             = $_SESSION['state_id'];
        $city_id              = $_SESSION['city_id'];
        $commonmodel          = Model::factory('commonmodel');
        $company_current_time = $commonmodel->getcompany_all_currenttimestamp($company_id);
        if ($usertype == 'C') {
            $query  = "SELECT people.id as driver_id,people.name, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference
				 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
				) AS list 
				 JOIN people ON people.id = list.driver_id 
				 JOIN taxi_driver_mapping ON taxi_driver_mapping.mapping_driverid = list.driver_id 
				 JOIN taxi ON taxi.taxi_id = taxi_driver_mapping.mapping_taxiid
				 WHERE people.user_type =  'D' 
				 AND people.status =  'A' 
				 AND people.company_id =  '" . $company_id . "'
				 $where_cond
				 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  
				 AND taxi.taxi_model='" . $taxi_model . "' ";
            //echo $query;//exit;
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        } else if ($usertype == 'M') {
            $query  = "SELECT people.id as driver_id,people.name, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference
				 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
				) AS list 
				 JOIN people ON people.id = list.driver_id 
				 JOIN taxi_driver_mapping ON taxi_driver_mapping.mapping_driverid = list.driver_id 
				 JOIN taxi ON taxi.taxi_id = taxi_driver_mapping.mapping_taxiid
				 WHERE people.user_type =  'D' 
				 AND people.status =  'A' 
				 AND people.company_id =  '" . $company_id . "'
				 $where_cond
				 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "' 
				 AND taxi.taxi_model='" . $taxi_model . "' ";
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        } else {
            $company_where = "";
            if ($taxi_company != "" && $taxi_company != 0) {
                $company_where = "AND people.company_id =  '" . $taxi_company . "'";
            }
            $query  = "SELECT people.id as driver_id,people.name, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference
				 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
				) AS list 
				 JOIN people ON people.id = list.driver_id 
				 JOIN taxi_driver_mapping ON taxi_driver_mapping.mapping_driverid = list.driver_id 
				 JOIN taxi ON taxi.taxi_id = taxi_driver_mapping.mapping_taxiid
				 WHERE people.user_type =  'D' 
				 AND people.status =  'A' 
				 $where_cond $company_where
				 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "' 
				 AND taxi.taxi_model='" . $taxi_model . "' ";
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        }
    }
    public function all_driver_map_list($array)
    {
        $taxi_company         = isset($array['taxi_company']) ? $array['taxi_company'] : "0";
        $user_createdby       = $_SESSION['userid'];
        $usertype             = $_SESSION['user_type'];
        $company_id           = $_SESSION['company_id'];
        $country_id           = $_SESSION['country_id'];
        $state_id             = $_SESSION['state_id'];
        $city_id              = $_SESSION['city_id'];
        $commonmodel          = Model::factory('commonmodel');
        $company_current_time = $commonmodel->getcompany_all_currenttimestamp($company_id);
        //echo $company_id;exit;
        if ($usertype == 'C') {
            /*
            $result = DB::select("*",array(DRIVER.'.status','driver_status'),array('TIME_TO_SEC(TIMEDIFF('.$company_current_time.','update_date'))', 'updatetime_difference'))->from(PEOPLE)
            ->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
            ->where('user_type','=','D')
            ->where(PEOPLE.'.status','=','A')
            //->where(PEOPLE.'.login_status','=','S')
            ->where('company_id','=',$company_id)
            //->order_by('created_date','desc')->limit($val)->offset($offset)
            ->execute()
            ->as_array();
            */
            $query  = "SELECT list.latitude as latitude, list.longitude as longitude, list.update_date as update_date,people.name as name, list.driver_id as driver_id, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference
					 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
					) AS list 
					 JOIN people ON people.id = list.driver_id 
					 WHERE people.user_type =  'D' 
					 AND people.status =  'A' 
					 AND people.company_id =  '" . $company_id . "'
					 AND list.shift_status =  'IN' 
					 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
            //echo $query;exit;
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        } else if ($usertype == 'M') {
            /*
            $result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
            ->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
            ->where('user_type','=','D')
            ->where(PEOPLE.'.status','=','A')
            //->where(PEOPLE.'.login_status','=','S')
            ->where('company_id','=',$company_id)
            //->where(PEOPLE.'.user_createdby','=',$user_createdby)
            //->order_by('created_date','desc')->limit($val)->offset($offset)
            ->execute()
            ->as_array();
            */
            $query  = "SELECT list.latitude as latitude, list.longitude as longitude, list.update_date as update_date,people.name as name, list.driver_id as driver_id, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference 
					 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
					) AS list 
					 JOIN people ON people.id = list.driver_id 
					 WHERE people.user_type =  'D' 
					 AND people.status =  'A' 
					 AND people.company_id =  '" . $company_id . "'
					 AND list.shift_status =  'IN' 
					 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        } else {
            /*
            $result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
            ->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
            ->where('user_type','=','D')
            ->where(PEOPLE.'.status','=','A')
            //->where(PEOPLE.'.login_status','=','S')
            //->order_by('created_date','desc')->limit($val)->offset($offset)
            ->execute()
            ->as_array();
            */
            $company_where = "";
            if ($taxi_company != '0') {
                $company_where = "AND people.company_id =  '" . $taxi_company . "'";
            }
            $query  = "SELECT list.latitude as latitude, list.longitude as longitude, list.update_date as update_date,people.name as name, list.driver_id as driver_id, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference 
					 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
					) AS list 
					 JOIN people ON people.id = list.driver_id 
					 WHERE people.user_type =  'D' 
					 AND people.status =  'A'
					 AND list.shift_status =  'IN'
					 $company_where
					 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
            //echo $query;exit;
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        }
    }
    public function all_driver_map_list_model($array)
    {
        $taxi_model           = isset($array['taxi_model']) ? $array['taxi_model'] : "";
        $taxi_company         = isset($array['taxi_company']) ? $array['taxi_company'] : "0";
        $user_createdby       = $_SESSION['userid'];
        $usertype             = $_SESSION['user_type'];
        $company_id           = $_SESSION['company_id'];
        $country_id           = $_SESSION['country_id'];
        $state_id             = $_SESSION['state_id'];
        $city_id              = $_SESSION['city_id'];
        $commonmodel          = Model::factory('commonmodel');
        $company_current_time = $commonmodel->getcompany_all_currenttimestamp($company_id);
        //echo $company_id;exit;
        if ($usertype == 'C') {
            /*
            $result = DB::select("*",array(DRIVER.'.status','driver_status'),array('TIME_TO_SEC(TIMEDIFF('.$company_current_time.','update_date'))', 'updatetime_difference'))->from(PEOPLE)
            ->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
            ->where('user_type','=','D')
            ->where(PEOPLE.'.status','=','A')
            //->where(PEOPLE.'.login_status','=','S')
            ->where('company_id','=',$company_id)
            //->order_by('created_date','desc')->limit($val)->offset($offset)
            ->execute()
            ->as_array();
            */
            $query  = "SELECT list.latitude as latitude, list.longitude as longitude, list.update_date as update_date,people.name as name,list.driver_id as driver_id, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference
					 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
					) AS list 
					 JOIN people ON people.id = list.driver_id
					 JOIN taxi_driver_mapping ON taxi_driver_mapping.mapping_driverid = list.driver_id 
					 JOIN taxi ON taxi.taxi_id = taxi_driver_mapping.mapping_taxiid 
					 WHERE people.user_type =  'D' 
					 AND people.status =  'A' 
					 AND people.company_id =  '" . $company_id . "'
					 AND list.shift_status =  'IN' 
					 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'
					 AND taxi.taxi_model='" . $taxi_model . "' ";
            //echo $query;exit;
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        } else if ($usertype == 'M') {
            /*
            $result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
            ->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
            ->where('user_type','=','D')
            ->where(PEOPLE.'.status','=','A')
            //->where(PEOPLE.'.login_status','=','S')
            ->where('company_id','=',$company_id)
            //->where(PEOPLE.'.user_createdby','=',$user_createdby)
            //->order_by('created_date','desc')->limit($val)->offset($offset)
            ->execute()
            ->as_array();
            */
            $query  = "SELECT list.latitude as latitude, list.longitude as longitude, list.update_date as update_date,people.name as name, list.driver_id as driver_id, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference 
					 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
					) AS list 
					 JOIN people ON people.id = list.driver_id 
					 WHERE people.user_type =  'D' 
					 AND people.status =  'A' 
					 AND people.company_id =  '" . $company_id . "'
					 AND list.shift_status =  'IN'
					 AND taxi.taxi_model='" . $taxi_model . "' 
					 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        } else {
            /*
            $result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
            ->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
            ->where('user_type','=','D')
            ->where(PEOPLE.'.status','=','A')
            //->where(PEOPLE.'.login_status','=','S')
            //->order_by('created_date','desc')->limit($val)->offset($offset)
            ->execute()
            ->as_array();
            */
            $company_where = "";
            if ($taxi_company != 0) {
                $company_where = "AND people.company_id =  '" . $taxi_company . "'";
            }
            $query  = "SELECT list.latitude as latitude, list.longitude as longitude, list.update_date as update_date,people.name as name, list.driver_id as driver_id, list.status AS driver_status,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference 
					 FROM (	 SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver 
					) AS list 
					 JOIN people ON people.id = list.driver_id
					 JOIN taxi_driver_mapping ON taxi_driver_mapping.mapping_driverid = list.driver_id 
					 JOIN taxi ON taxi.taxi_id = taxi_driver_mapping.mapping_taxiid
					 WHERE people.user_type =  'D' 
					 AND people.status =  'A'
					 AND list.shift_status =  'IN'
					 AND taxi.taxi_model='" . $taxi_model . "' 
					 $company_where
					 AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
            //echo $query;exit;
            $result = Db::query(Database::SELECT, $query)->execute()->as_array();
            return $result;
        }
    }
    public function validate_dispatchbooking($arr)
    {
        return Validation::factory($arr)->rule('firstname', 'not_empty')
        //->rule('firstname', 'min_length', array(':value', '3'))
            
        //->rule('firstname', 'max_length', array(':value', '32'))
            
        //->rule('email', 'not_empty')
            ->rule('email', 'email')->rule('email', 'max_length', array(
            ':value',
            '50'
        ))->rule('country_code', 'not_empty')->rule('phone', 'not_empty')->rule('current_location', 'not_empty')->rule('pickup_lat', 'not_empty')->rule('pickup_lng', 'not_empty') /*->rule('drop_location', 'not_empty')
        ->rule('drop_lat', 'not_empty')
        ->rule('drop_lng', 'not_empty')*/ 
        //->rule('luggage', 'numeric')
            
        //->rule('no_passengers', 'numeric')
            ->rule('pickup_date', 'not_empty')->rule('pickup_time', 'not_empty');
    }
    public function check_passenger_email_phone_exist($passenger_id, $email, $phone)
    {
        $passenger_exist = DB::select('id')->from(PASSENGERS)->where_open()->where('email', '=', $email)->or_where('phone', '=', $phone)->where_close()->where('id', '!=', $passenger_id)->execute()->as_array();
        return count($passenger_exist);
    }
    public function addbooking($post, $random_key, $password, $company_tax)
    {
        //print_r($post);exit;
        $firstname        = Html::chars($post['firstname']);
        $pass_logid       = '';
        $recurrent_id     = '';
        $send_mail        = 'N';
        $insert_booking   = 'N';
        $passenger_id     = $post['passenger_id'];
        $admin_company_id = isset($post['admin_company_id']) ? $post['admin_company_id'] : "";
        if ($admin_company_id != "") {
            $company_id = $admin_company_id;
        } else {
            $company_id = $_SESSION['company_id'];
        }
        $common_model = Model::factory('commonmodel');
        $api          = Model::factory('api');
        //$search_city = $post['cityname'];
        $search_city  = $post['cityname'];
        $update_city_name = strtolower($post['cityname']);
        if($update_city_name == "") {
			$update_city_name = Commonfunction::getCityName($post["pickup_lat"],$post["pickup_lng"]);
		}
        $booking_key  = $common_model->get_randonkey();
        if ($search_city != '') {
            $cityid_query = "select city_id from " . CITY . " where " . CITY . ".city_name like '%" . $search_city . "%' limit 0,1";
        } else {
            $cityid_query = "select city_id from " . CITY . " where " . CITY . ".default=1";
        }
        $cityid_fetch = Db::query(Database::SELECT, $cityid_query)->execute()->as_array();
        if (count($cityid_fetch) == 0) {
            $cityid_query = "select city_id from " . CITY . " where " . CITY . ".default=1";
            $cityid_fetch = Db::query(Database::SELECT, $cityid_query)->execute()->as_array();
        }
        $city_id                 = (count($cityid_fetch) > 0) ? $cityid_fetch[0]['city_id'] : '';
        $current_datetime        = $common_model->getcompany_all_currenttimestamp($company_id);
        $current_datesplit       = explode(' ', $current_datetime);
        $pickup_date             = $post['pickup_date'];
        $pickup_time             = $post['pickup_time'];
        $post['addition_fields'] = '';
        $additional_fields       = '';
        if ($pickup_date == '' || $pickup_date == 'Date') {
            $pickup_date = $current_datesplit[0];
        }
        if ($pickup_time == '' || $pickup_time == 'Now') {
            $pickup_time = $current_datesplit[1];
        }
        //$pickup_datetime = $pickup_date.' '.$pickup_time;
        $pickup_datetime = $pickup_date;
        $userid          = $_SESSION['userid'];
        if (isset($post['dispatch']) && !empty($post['dispatch'])) {
            $booktype = '1';
        } else {
            $booktype = '2';
        }
        if (isset($post['group_id'])) {
            $group_id         = $post['group_id'];
            $company_dispatch = DB::select()->from(TBLGROUP)->where('gid', '=', $group_id)->execute()->as_array();
            if (count($company_dispatch) > 0) {
                $account_id = $company_dispatch[0]['aid'];
            } else {
                $account_id = '0';
            }
        }
        $email_con       = ($post['email'] != '') ? " and email = '" . $post['email'] . "'" : "";
        $pass_query      = "select id from " . PASSENGERS . " where phone= '" . $post['phone'] . "' $email_con";
        $passenger_exist = Db::query(Database::SELECT, $pass_query)->execute()->as_array();
        //$passenger_exist = DB::select('id')->from(PASSENGERS)->where('phone','=',$post['phone'])->where('country_code','=',$post['country_code'])->execute()->as_array();
        if ($post['passenger_id'] == '' && count($passenger_exist) == 0) {
            $insert_passenger = DB::insert(PASSENGERS, array(
                'name',
                'email',
                'country_code',
                'phone',
                'password',
                'org_password',
                'created_date',
                'activation_key',
                'user_status',
                'passenger_cid',
                'activation_status'
            ))->values(array(
                $firstname,
                $post['email'],
                $post['country_code'],
                $post['phone'],
                md5($password),
                $password,
                $current_datetime,
                $random_key,
                ACTIVE,
                $company_id,
                1
            ))->execute();
            $send_mail        = 'S';
            $passenger_id     = $insert_passenger[0];
            $company_id       = $_SESSION['company_id'];
        } else {
            $passenger_id           = $passenger_exist[0]['id'];
            $name                   = explode('- (', $firstname);
            $firstname              = isset($name[0]) ? $name[0] : $firstname;
            $update_passenger_array = array(
                'name' => $firstname,
                'email' => $post['email'],
                'country_code' => $post['country_code']
            );
            //'phone'=>$post['edit_phone']
            $updateresult           = $common_model->update(PASSENGERS, $update_passenger_array, 'id', $passenger_id);
        }
        $user_createdby = $_SESSION['userid'];
        $pickupTimezone = $this->getpickupTimezone($post['pickup_lat'],$post['pickup_lng']);
        /** if single booking **/
        if ($post['recurrent'] == 1) {
            $account_id     = isset($account_id) ? $account_id : '0';
            $group_id       = isset($group_id) ? $group_id : '0';
            $companyName    = $this->get_company_name($company_id);
            $distance_km    = (UNIT_NAME == "MILES") ? ($post['distance_km'] * 1.60934) : $post['distance_km'];
            $insert_array   = array(
                'booking_key' => $booking_key,
                'passengers_id' => $passenger_id,
                'company_id' => $company_id,
                'current_location' => $post['current_location'],
                'pickup_latitude' => $post['pickup_lat'],
                'pickup_longitude' => $post['pickup_lng'],
                'drop_location' => $post['drop_location'],
                'drop_latitude' => $post['drop_lat'],
                'drop_longitude' => $post['drop_lng'],
                'pickup_time' => $pickup_datetime,
                'no_passengers' => $post['no_passengers'],
                'approx_distance' => $distance_km,
                'approx_duration' => $post['total_duration'],
                'approx_fare' => $post['total_fare'],
                'search_city' => $city_id,
                'notes_driver' => $post['notes'],
                'faretype' => $post['payment_type'],
                'fixedprice' => $post['fixedprice'],
                'bookingtype' => $booktype,
                'luggage' => $post['luggage'],
                'bookby' => '2',
                'operator_id' => $userid,
                'travel_status' => '0',
                'taxi_modelid' => $post['taxi_model'],
                'recurrent_type' => $post['recurrent'],
                'company_tax' => $post['company_tax'],
                'account_id' => $account_id,
                'accgroup_id' => $group_id,
                'createdate' => $current_datetime,
                'trip_timezone' => $pickupTimezone,
                'city_name' => $update_city_name,
                'building_number' => $post['building_number'],
                'house_number' => $post['house_number'],
                'driver_notes' => $post['driver_notes'],
            );
            $today_result   = $common_model->insert(PASSENGERS_LOG, $insert_array);
            $pass_logid     = $today_result[0];
            $trip_id        = $pass_logid;
            $insert_booking = 'S';
            if(isset($pass_logid)) {
				//to insert in split fare table
				$splfield_array = array('trip_id','friends_p_id','fare_percentage','createdate','approve_status','appx_amount','passenger_payment_option');
				$splvalues_array = array($pass_logid,$passenger_id,'100',$current_datetime,'A',$post['total_fare'],'1');
				$split_insert_result = DB::insert(P_SPLIT_FARE, $splfield_array)->values($splvalues_array)->execute();
			}
        }
        /** if single booking end**/
        /** if recurrent booking **/
        if ($post['recurrent'] == 2) {
            // Next 24 hours //
            $date_query           = "SELECT '$current_datetime'+interval 24 hour as next_datetime,'$current_datetime' as today_datetime";
            $date_result          = Db::query(Database::SELECT, $date_query)->execute();
            $next_datetime        = $date_result[0]['next_datetime'];
            $today_datetime       = $date_result[0]['today_datetime'];
            $next_datetime_split  = explode(' ', $next_datetime);
            $next_date            = $next_datetime_split[0];
            $next_time            = $next_datetime_split[1];
            $today_datetime_split = explode(' ', $today_datetime);
            $today_date           = $today_datetime_split[0];
            $today_time           = $today_datetime_split[1];
            // Next 24 hours //
            // Next Days //
            $day_query            = "select DATE_FORMAT('$current_datetime'+interval 24 hour,'%a') as next_day, DATE_FORMAT('$current_datetime','%a') as today_day";
            $day_result           = Db::query(Database::SELECT, $day_query)->execute();
            $next_day             = strtoupper($day_result[0]['next_day']);
            $today_day            = strtoupper($day_result[0]['today_day']);
            // Next Days //
            $insert_booking       = 'N';
            // Insert Recurrent Table //
            if (isset($post['daysofweek'])) {
                $days = serialize($post['daysofweek']);
            } else {
                $days = '';
            }
            if (isset($post['all_dates']) && ($post['all_dates'] != '')) {
                $specific_dates = explode(',', $post['all_dates']);
                $specific_dates = !empty($specific_dates) ? serialize($specific_dates) : '';
            } else {
                $specific_dates = '';
            }
            $exclude_dates      = '';
            $account_id         = isset($account_id) ? $account_id : '0';
            $group_id           = isset($group_id) ? $group_id : '0';
            $insert_recur_array = array(
                'labelname' => $post['labelname'],
                'frmdate' => $post['frmdate'],
                'todate' => $post['todate'],
                'days' => $days,
                'excludedates' => $exclude_dates,
                'specific_dates' => $specific_dates,
                'companyid' => $company_id,
                'recurrent_passengerid' => $passenger_id,
                'recurrent_pickuplocation' => $post['current_location'],
                'recurrent_pickuplatitude' => $post['pickup_lat'],
                'recurrent_pickuplongitude' => $post['pickup_lng'],
                'recurrent_droplocation' => $post['drop_location'],
                'recurrent_droplatitude' => $post['drop_lat'],
                'recurrent_droplongitude' => $post['drop_lng'],
                'recurrent_noofpassengers' => $post['no_passengers'],
                'recurrent_approxdistance' => $post['distance_km'],
                'recurrent_approxduration' => $post['total_duration'],
                'recurrent_approxfare' => $post['total_fare'],
                'recurrent_pickuptime' => $pickup_time,
                'recurrent_city' => $city_id,
                'recurrent_fixedprice' => $post['fixedprice'],
                'recurrent_faretype' => $post['payment_type'],
                'recurrent_luggage' => $post['luggage'],
                'recurrent_operatorid' => $userid,
                'recurrent_modelid' => $post['taxi_model'],
                'recurrent_accountid' => $account_id,
                'recurrent_groupid' => $group_id,
                'recurrent_notes_driver' => $post['notes']
            );
            $insert_recurr      = $common_model->insert(RECURR_BOOKING, $insert_recur_array);
            $recurrent_id       = $insert_recurr[0];
            // Insert Recurrent Table //
            function toDate($x)
            {
                return date('Y-m-d', $x);
            }
            // Get all recurrent booking details query //
            $sql              = "SELECT  " . RECURR_BOOKING . ".reid," . RECURR_BOOKING . ".passengers_log_id," . RECURR_BOOKING . ".frmdate," . RECURR_BOOKING . ".todate," . RECURR_BOOKING . ".days,
				" . RECURR_BOOKING . ".excludedates," . RECURR_BOOKING . ".specific_dates
				FROM " . RECURR_BOOKING . "
				left join " . COMPANY . " ON ( " . RECURR_BOOKING . ".companyid = " . COMPANY . ".cid )
				left join  " . PEOPLE . " ON ( " . PEOPLE . ".company_id = " . RECURR_BOOKING . ".companyid )
				WHERE " . PEOPLE . ".status = 'A'
				and " . PEOPLE . ".user_type='C'
				and " . COMPANY . ".company_status='A'
				and " . RECURR_BOOKING . ".frmdate <= '$current_datetime'
				and " . RECURR_BOOKING . ".todate >= '$current_datetime'
				and " . RECURR_BOOKING . ".reid='$recurrent_id' ";
            $recurrent_result = Db::query(Database::SELECT, $sql)->execute()->as_array();
            // Get all recurrent booking details query //
            if (count($recurrent_result) > 0) {
                foreach ($recurrent_result as $recurrent_details1) {
                    $recurrent_id      = $recurrent_details1['reid'];
                    $startDate         = $recurrent_details1['frmdate'];
                    $endDate           = $recurrent_details1['todate'];
                    $insert_booking    = 'N';
                    // To get Passenger Log details from Passenger Log Table //
                    $recurrent_query   = "SELECT * from " . RECURR_BOOKING . " where " . RECURR_BOOKING . ".reid ='$recurrent_id'";
                    $recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();
                    // To get Passenger Log details from Passenger Log Table //
                    $all_dates         = $post['all_dates'];
                    if (isset($post['daysofweek'])) {
                        $days = serialize($post['daysofweek']);
                    } else {
                        $days = '';
                    }
                    /** Insert booking based on fromdate and todate **/
                    //echo $all_dates.'all dates and days'.$days;
                    //exit;
                    if ($all_dates == '' && $days == '') {
                        /** Check Today is between fromdate and todate **/
                        $checktoday_query     = "select '$today_date'  between '$startDate' and '$endDate' as checkdate";
                        $checktoday_result    = Db::query(Database::SELECT, $checktoday_query)->execute()->as_array();
                        /** Check Today is between fromdate and todate **/
                        /** Check Tommorrow is between fromdate and todate **/
                        $checktomorrow_query  = "select '$next_date'  between '$startDate' and '$endDate' as checkdate";
                        $checktomorrow_result = Db::query(Database::SELECT, $checktomorrow_query)->execute()->as_array();
                        /** Check Tommorrow is between fromdate and todate **/
                        /** Insert Today Booking **/
                        if ($checktoday_result[0]['checkdate'] == 1) {
                            // To get Recurrent Log details from Passenger Log Table //
                            $recurrent_query    = "SELECT * from " . RECURR_BOOKING . " where " . RECURR_BOOKING . ".reid ='$recurrent_id'";
                            $recurrent_details  = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();
                            // To get Recurrent Log details from Passenger Log Table //
                            // Insert into Passenger Log Table //	
                            $pickup_datetime    = $today_date . ' ' . $recurrent_details[0]['recurrent_pickuptime'];
                            $insert_booking     = 'S';
                            $today_result_array = array(
                                'booking_key' => $booking_key,
                                'passengers_id' => $recurrent_details[0]['recurrent_passengerid'],
                                'company_id' => $recurrent_details[0]['companyid'],
                                'current_location' => $recurrent_details[0]['recurrent_pickuplocation'],
                                'pickup_latitude' => $recurrent_details[0]['recurrent_pickuplatitude'],
                                'pickup_longitude' => $recurrent_details[0]['recurrent_pickuplongitude'],
                                'drop_location' => $recurrent_details[0]['recurrent_droplocation'],
                                'drop_latitude' => $recurrent_details[0]['recurrent_droplatitude'],
                                'drop_longitude' => $recurrent_details[0]['recurrent_droplongitude'],
                                'pickup_time' => $pickup_datetime,
                                'no_passengers' => $recurrent_details[0]['recurrent_noofpassengers'],
                                'approx_distance' => $recurrent_details[0]['recurrent_approxdistance'],
                                'approx_duration' => $recurrent_details[0]['recurrent_approxduration'],
                                'approx_fare' => $recurrent_details[0]['recurrent_approxfare'],
                                'search_city' => $recurrent_details[0]['recurrent_city'],
                                'faretype' => $recurrent_details[0]['recurrent_faretype'],
                                'fixedprice' => $recurrent_details[0]['recurrent_fixedprice'],
                                'luggage' => $recurrent_details[0]['recurrent_luggage'],
                                'bookby' => '2',
                                'operator_id' => $recurrent_details[0]['recurrent_operatorid'],
                                'travel_status' => '0',
                                'taxi_modelid' => $recurrent_details[0]['recurrent_modelid'],
                                'recurrent_type' => '2',
                                'recurrent_id' => $recurrent_id,
                                'company_tax' => $company_tax,
                                'account_id' => $recurrent_details[0]['recurrent_accountid'],
                                'accgroup_id' => $recurrent_details[0]['recurrent_groupid']
                            );
                            $today_result       = $common_model->insert(PASSENGERS_LOG, $today_result_array);
                            $ins_logid          = $today_result[0];
                            $trip_id            = $ins_logid;
                            /* Create Log */
                            $company_id         = $_SESSION['company_id'];
                            $user_createdby     = $_SESSION['userid'];
                            $log_message        = __('log_message_added');
                            $log_message        = str_replace("PASS_LOG_ID", $ins_logid, $log_message);
                            $log_booking        = __('log_booking_added');
                            $log_booking        = str_replace("PASS_LOG_ID", $ins_logid, $log_booking);
                            $log_status         = $this->create_logs($ins_logid, $company_id, $user_createdby, $log_message, $log_booking);
                            /* Create Log */
                        }
                        /** Insert Today Booking **/
                        /** Insert Tomorrow Booking **/
                        if ($checktomorrow_result[0]['checkdate'] == 1) {
                            // To get Recurrent Log details from Passenger Log Table //
                            $recurrent_query      = "SELECT * from " . RECURR_BOOKING . " where " . RECURR_BOOKING . ".reid ='$recurrent_id'";
                            $recurrent_details    = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();
                            // To get Recurrent Log details from Passenger Log Table //
                            // Insert into Passenger Log Table //	
                            $pickup_datetime      = $next_date . ' ' . $recurrent_details[0]['recurrent_pickuptime'];
                            $insert_booking       = 'S';
                            $nextday_result_array = array(
                                'booking_key' => $booking_key,
                                'passengers_id' => $recurrent_details[0]['recurrent_passengerid'],
                                'company_id' => $recurrent_details[0]['companyid'],
                                'current_location' => $recurrent_details[0]['recurrent_pickuplocation'],
                                'pickup_latitude' => $recurrent_details[0]['recurrent_pickuplatitude'],
                                'pickup_longitude' => $recurrent_details[0]['recurrent_pickuplongitude'],
                                'drop_location' => $recurrent_details[0]['recurrent_droplocation'],
                                'drop_latitude' => $recurrent_details[0]['recurrent_droplatitude'],
                                'drop_longitude' => $recurrent_details[0]['recurrent_droplongitude'],
                                'pickup_time' => $pickup_datetime,
                                'no_passengers' => $recurrent_details[0]['recurrent_noofpassengers'],
                                'approx_distance' => $recurrent_details[0]['recurrent_approxdistance'],
                                'approx_duration' => $recurrent_details[0]['recurrent_approxduration'],
                                'approx_fare' => $recurrent_details[0]['recurrent_approxfare'],
                                'search_city' => $recurrent_details[0]['recurrent_city'],
                                'faretype' => $recurrent_details[0]['recurrent_faretype'],
                                'fixedprice' => $recurrent_details[0]['recurrent_fixedprice'],
                                'luggage' => $recurrent_details[0]['recurrent_luggage'],
                                'bookby' => '2',
                                'operator_id' => $recurrent_details[0]['recurrent_operatorid'],
                                'travel_status' => '0',
                                'taxi_modelid' => $recurrent_details[0]['recurrent_modelid'],
                                'recurrent_type' => '2',
                                'recurrent_id' => $recurrent_id,
                                'company_tax' => $company_tax,
                                'account_id' => $recurrent_details[0]['recurrent_accountid'],
                                'accgroup_id' => $recurrent_details[0]['recurrent_groupid']
                            );
                            $nextday_result       = $common_model->insert(PASSENGERS_LOG, $nextday_result_array);
                            $ins_logid            = $nextday_result[0];
                            $trip_id              = $ins_logid;
                            /* Create Log */
                            $company_id           = $_SESSION['company_id'];
                            $user_createdby       = $_SESSION['userid'];
                            $log_message          = __('log_message_added');
                            $log_message          = str_replace("PASS_LOG_ID", $ins_logid, $log_message);
                            $log_booking          = __('log_booking_added');
                            $log_booking          = str_replace("PASS_LOG_ID", $ins_logid, $log_booking);
                            $log_status           = $this->create_logs($ins_logid, $company_id, $user_createdby, $log_message, $log_booking);
                            /* Create Log */
                        }
                        /** Insert Tomorrow Booking **/
                        /** Insert booking based on fromdate and todate **/
                    }
                    /** daysofweek not empty **/
                    else if ($all_dates == '' && $days != '') {
                        /** Check Today is between fromdate and todate **/
                        $checktoday_query     = "select '$today_date'  between '$startDate' and '$endDate' as checkdate";
                        $checktoday_result    = Db::query(Database::SELECT, $checktoday_query)->execute()->as_array();
                        /** Check Today is between fromdate and todate **/
                        /** Check Tommorrow is between fromdate and todate **/
                        $checktomorrow_query  = "select '$next_date'  between '$startDate' and '$endDate' as checkdate";
                        $checktomorrow_result = Db::query(Database::SELECT, $checktomorrow_query)->execute()->as_array();
                        /** Check Tommorrow is between fromdate and todate **/
                        $daysofweek           = unserialize($recurrent_details[0]['days']);
                        if ($checktoday_result[0]['checkdate'] == 1) {
                            /** Insert Booking Today **/
                            if (in_array($today_day, $daysofweek)) {
                                // To get Recurrent Log details from Passenger Log Table //
                                $recurrent_query   = "SELECT * from " . RECURR_BOOKING . " where " . RECURR_BOOKING . ".reid ='$recurrent_id'";
                                $recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();
                                // To get Recurrent Log details from Passenger Log Table //
                                // Insert into Passenger Log Table //	
                                $pickup_datetime   = $today_date . ' ' . $recurrent_details[0]['recurrent_pickuptime'];
                                $insert_booking    = 'S';
                                $today_result      = DB::insert(PASSENGERS_LOG, array(
                                    'booking_key',
                                    'passengers_id',
                                    'company_id',
                                    'current_location',
                                    'pickup_latitude',
                                    'pickup_longitude',
                                    'drop_location',
                                    'drop_latitude',
                                    'drop_longitude',
                                    'pickup_time',
                                    'no_passengers',
                                    'approx_distance',
                                    'approx_duration',
                                    'approx_fare',
                                    'search_city',
                                    'faretype',
                                    'fixedprice',
                                    'luggage',
                                    'bookby',
                                    'operator_id',
                                    'travel_status',
                                    'taxi_modelid',
                                    'recurrent_type',
                                    'recurrent_id',
                                    'company_tax',
                                    'account_id',
                                    'accgroup_id'
                                ))->values(array(
                                    $booking_key,
                                    $recurrent_details[0]['recurrent_passengerid'],
                                    $recurrent_details[0]['companyid'],
                                    $recurrent_details[0]['recurrent_pickuplocation'],
                                    $recurrent_details[0]['recurrent_pickuplatitude'],
                                    $recurrent_details[0]['recurrent_pickuplongitude'],
                                    $recurrent_details[0]['recurrent_droplocation'],
                                    $recurrent_details[0]['recurrent_droplatitude'],
                                    $recurrent_details[0]['recurrent_droplongitude'],
                                    $pickup_datetime,
                                    $recurrent_details[0]['recurrent_noofpassengers'],
                                    $recurrent_details[0]['recurrent_approxdistance'],
                                    $recurrent_details[0]['recurrent_approxduration'],
                                    $recurrent_details[0]['recurrent_approxfare'],
                                    $recurrent_details[0]['recurrent_city'],
                                    $recurrent_details[0]['recurrent_faretype'],
                                    $recurrent_details[0]['recurrent_fixedprice'],
                                    $recurrent_details[0]['recurrent_luggage'],
                                    '2',
                                    $recurrent_details[0]['recurrent_operatorid'],
                                    '0',
                                    $recurrent_details[0]['recurrent_modelid'],
                                    '2',
                                    $recurrent_id,
                                    $company_tax,
                                    $recurrent_details[0]['recurrent_accountid'],
                                    $recurrent_details[0]['recurrent_groupid']
                                ))->execute();
                                $ins_logid         = $today_result[0];
                                $trip_id           = $ins_logid;
                                /* Create Log */
                                $company_id        = $_SESSION['company_id'];
                                $user_createdby    = $_SESSION['userid'];
                                $log_message       = __('log_message_added');
                                $log_message       = str_replace("PASS_LOG_ID", $ins_logid, $log_message);
                                $log_booking       = __('log_booking_added');
                                $log_booking       = str_replace("PASS_LOG_ID", $ins_logid, $log_booking);
                                $log_status        = $this->create_logs($ins_logid, $company_id, $user_createdby, $log_message, $log_booking);
                                /* Create Log */
                                // Insert into Passenger Log Table //
                            }
                            /** Insert Booking Today **/
                        }
                        /** Insert Booking Tomorrow **/
                        if ($checktomorrow_result[0]['checkdate'] == 1) {
                            /** Insert Booking Today **/
                            if (in_array($next_day, $daysofweek)) {
                                // To get Recurrent Log details from Passenger Log Table //
                                $recurrent_query   = "SELECT * from " . RECURR_BOOKING . " where " . RECURR_BOOKING . ".reid ='$recurrent_id'";
                                $recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();
                                // To get Recurrent Log details from Passenger Log Table //
                                // Insert into Passenger Log Table //	
                                $pickup_datetime   = $next_date . ' ' . $recurrent_details[0]['recurrent_pickuptime'];
                                $insert_booking    = 'S';
                                $today_result      = DB::insert(PASSENGERS_LOG, array(
                                    'booking_key',
                                    'passengers_id',
                                    'company_id',
                                    'current_location',
                                    'pickup_latitude',
                                    'pickup_longitude',
                                    'drop_location',
                                    'drop_latitude',
                                    'drop_longitude',
                                    'pickup_time',
                                    'no_passengers',
                                    'approx_distance',
                                    'approx_duration',
                                    'approx_fare',
                                    'search_city',
                                    'faretype',
                                    'fixedprice',
                                    'luggage',
                                    'bookby',
                                    'operator_id',
                                    'travel_status',
                                    'taxi_modelid',
                                    'recurrent_type',
                                    'recurrent_id',
                                    'company_tax',
                                    'account_id',
                                    'accgroup_id'
                                ))->values(array(
                                    $booking_key,
                                    $recurrent_details[0]['recurrent_passengerid'],
                                    $recurrent_details[0]['companyid'],
                                    $recurrent_details[0]['recurrent_pickuplocation'],
                                    $recurrent_details[0]['recurrent_pickuplatitude'],
                                    $recurrent_details[0]['recurrent_pickuplongitude'],
                                    $recurrent_details[0]['recurrent_droplocation'],
                                    $recurrent_details[0]['recurrent_droplatitude'],
                                    $recurrent_details[0]['recurrent_droplongitude'],
                                    $pickup_datetime,
                                    $recurrent_details[0]['recurrent_noofpassengers'],
                                    $recurrent_details[0]['recurrent_approxdistance'],
                                    $recurrent_details[0]['recurrent_approxduration'],
                                    $recurrent_details[0]['recurrent_approxfare'],
                                    $recurrent_details[0]['recurrent_city'],
                                    $recurrent_details[0]['recurrent_faretype'],
                                    $recurrent_details[0]['recurrent_fixedprice'],
                                    $recurrent_details[0]['recurrent_luggage'],
                                    '2',
                                    $recurrent_details[0]['recurrent_operatorid'],
                                    '0',
                                    $recurrent_details[0]['recurrent_modelid'],
                                    '2',
                                    $recurrent_id,
                                    $company_tax,
                                    $recurrent_details[0]['recurrent_accountid'],
                                    $recurrent_details[0]['recurrent_groupid']
                                ))->execute();
                                $ins_logid         = $today_result[0];
                                $trip_id           = $ins_logid;
                                /* Create Log */
                                $company_id        = $_SESSION['company_id'];
                                $user_createdby    = $_SESSION['userid'];
                                $log_message       = __('log_message_added');
                                $log_message       = str_replace("PASS_LOG_ID", $ins_logid, $log_message);
                                $log_booking       = __('log_booking_added');
                                $log_booking       = str_replace("PASS_LOG_ID", $ins_logid, $log_booking);
                                $log_status        = $this->create_logs($ins_logid, $company_id, $user_createdby, $log_message, $log_booking);
                                /* Create Log */
                                // Insert into Passenger Log Table //
                            }
                            /** Insert Booking Today **/
                        }
                        /** Insert Booking Tomorrow **/
                    }
                    /** daysofweek not empty **/
                    /** all_dates not empty **/
                    else {
                        /** Check Today is between fromdate and todate **/
                        $checktoday_query      = "select '$today_date'  between '$startDate' and '$endDate' as checkdate";
                        $checktoday_result     = Db::query(Database::SELECT, $checktoday_query)->execute()->as_array();
                        /** Check Today is between fromdate and todate **/
                        /** Check Tommorrow is between fromdate and todate **/
                        $checktomorrow_query   = "select '$next_date'  between '$startDate' and '$endDate' as checkdate";
                        $checktomorrow_result  = Db::query(Database::SELECT, $checktomorrow_query)->execute()->as_array();
                        /** Check Tommorrow is between fromdate and todate **/
                        $specific_dates        = unserialize($recurrent_details[0]['specific_dates']);
                        $specific_dates        = implode(',', $specific_dates);
                        /** Check Today is exist in specific dates **/
                        $checktoday_query2     = "select FIND_IN_SET('$today_date', '$specific_dates' ) as finddate";
                        $checktoday_result2    = Db::query(Database::SELECT, $checktoday_query2)->execute()->as_array();
                        /** Check Today is exist in specific dates **/
                        /** Check Tommorrow is exist in specific dates **/
                        $checktomorrow_query2  = "select FIND_IN_SET('$next_date', '$specific_dates' ) as finddate";
                        $checktomorrow_result2 = Db::query(Database::SELECT, $checktomorrow_query2)->execute()->as_array();
                        /** Check Tommorrow is exist in specific dates **/
                        /** Insert Today Booking **/
                        if ($checktoday_result[0]['checkdate'] == 1 && $checktoday_result2[0]['finddate'] == 1) {
                            // To get Recurrent Log details from Passenger Log Table //
                            $recurrent_query   = "SELECT * from " . RECURR_BOOKING . " where " . RECURR_BOOKING . ".reid ='$recurrent_id'";
                            $recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();
                            // To get Recurrent Log details from Passenger Log Table //
                            // Insert into Passenger Log Table //	
                            $pickup_datetime   = $today_date . ' ' . $recurrent_details[0]['recurrent_pickuptime'];
                            $insert_booking    = 'S';
                            $today_result      = DB::insert(PASSENGERS_LOG, array(
                                'booking_key',
                                'passengers_id',
                                'company_id',
                                'current_location',
                                'pickup_latitude',
                                'pickup_longitude',
                                'drop_location',
                                'drop_latitude',
                                'drop_longitude',
                                'pickup_time',
                                'no_passengers',
                                'approx_distance',
                                'approx_duration',
                                'approx_fare',
                                'search_city',
                                'faretype',
                                'fixedprice',
                                'luggage',
                                'bookby',
                                'operator_id',
                                'travel_status',
                                'taxi_modelid',
                                'recurrent_type',
                                'recurrent_id',
                                'company_tax',
                                'account_id',
                                'accgroup_id'
                            ))->values(array(
                                $booking_key,
                                $recurrent_details[0]['recurrent_passengerid'],
                                $recurrent_details[0]['companyid'],
                                $recurrent_details[0]['recurrent_pickuplocation'],
                                $recurrent_details[0]['recurrent_pickuplatitude'],
                                $recurrent_details[0]['recurrent_pickuplongitude'],
                                $recurrent_details[0]['recurrent_droplocation'],
                                $recurrent_details[0]['recurrent_droplatitude'],
                                $recurrent_details[0]['recurrent_droplongitude'],
                                $pickup_datetime,
                                $recurrent_details[0]['recurrent_noofpassengers'],
                                $recurrent_details[0]['recurrent_approxdistance'],
                                $recurrent_details[0]['recurrent_approxduration'],
                                $recurrent_details[0]['recurrent_approxfare'],
                                $recurrent_details[0]['recurrent_city'],
                                $recurrent_details[0]['recurrent_faretype'],
                                $recurrent_details[0]['recurrent_fixedprice'],
                                $recurrent_details[0]['recurrent_luggage'],
                                '2',
                                $recurrent_details[0]['recurrent_operatorid'],
                                '0',
                                $recurrent_details[0]['recurrent_modelid'],
                                '2',
                                $recurrent_id,
                                $company_tax,
                                $recurrent_details[0]['recurrent_accountid'],
                                $recurrent_details[0]['recurrent_groupid']
                            ))->execute();
                            $ins_logid         = $today_result[0];
                            $trip_id           = $ins_logid;
                            /* Create Log */
                            $company_id        = $_SESSION['company_id'];
                            $user_createdby    = $_SESSION['userid'];
                            $log_message       = __('log_message_added');
                            $log_message       = str_replace("PASS_LOG_ID", $ins_logid, $log_message);
                            $log_booking       = __('log_booking_added');
                            $log_booking       = str_replace("PASS_LOG_ID", $ins_logid, $log_booking);
                            $log_status        = $this->create_logs($ins_logid, $company_id, $user_createdby, $log_message, $log_booking);
                            /* Create Log */
                            // Insert into Passenger Log Table //
                        }
                        /** Insert Today Booking **/
                        /** Insert Tomorrow Booking **/
                        if ($checktomorrow_result[0]['checkdate'] == 1 && $checktomorrow_result2[0]['finddate'] == 1) {
                            // To get Recurrent Log details from Passenger Log Table //
                            $recurrent_query   = "SELECT * from " . RECURR_BOOKING . " where " . RECURR_BOOKING . ".reid ='$recurrent_id'";
                            $recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();
                            // To get Recurrent Log details from Passenger Log Table //
                            // Insert into Passenger Log Table //	
                            $pickup_datetime   = $next_date . ' ' . $recurrent_details[0]['recurrent_pickuptime'];
                            $insert_booking    = 'S';
                            $nextday_result    = DB::insert(PASSENGERS_LOG, array(
                                'booking_key',
                                'passengers_id',
                                'company_id',
                                'current_location',
                                'pickup_latitude',
                                'pickup_longitude',
                                'drop_location',
                                'drop_latitude',
                                'drop_longitude',
                                'pickup_time',
                                'no_passengers',
                                'approx_distance',
                                'approx_duration',
                                'approx_fare',
                                'search_city',
                                'faretype',
                                'fixedprice',
                                'luggage',
                                'bookby',
                                'operator_id',
                                'travel_status',
                                'taxi_modelid',
                                'recurrent_type',
                                'recurrent_id',
                                'company_tax',
                                'account_id',
                                'accgroup_id'
                            ))->values(array(
                                $booking_key,
                                $recurrent_details[0]['recurrent_passengerid'],
                                $recurrent_details[0]['companyid'],
                                $recurrent_details[0]['recurrent_pickuplocation'],
                                $recurrent_details[0]['recurrent_pickuplatitude'],
                                $recurrent_details[0]['recurrent_pickuplongitude'],
                                $recurrent_details[0]['recurrent_droplocation'],
                                $recurrent_details[0]['recurrent_droplatitude'],
                                $recurrent_details[0]['recurrent_droplongitude'],
                                $pickup_datetime,
                                $recurrent_details[0]['recurrent_noofpassengers'],
                                $recurrent_details[0]['recurrent_approxdistance'],
                                $recurrent_details[0]['recurrent_approxduration'],
                                $recurrent_details[0]['recurrent_approxfare'],
                                $recurrent_details[0]['recurrent_city'],
                                $recurrent_details[0]['recurrent_faretype'],
                                $recurrent_details[0]['recurrent_fixedprice'],
                                $recurrent_details[0]['recurrent_luggage'],
                                '2',
                                $recurrent_details[0]['recurrent_operatorid'],
                                '0',
                                $recurrent_details[0]['recurrent_modelid'],
                                '2',
                                $recurrent_id,
                                $company_tax,
                                $recurrent_details[0]['recurrent_accountid'],
                                $recurrent_details[0]['recurrent_groupid']
                            ))->execute();
                            $ins_logid         = $nextday_result[0];
                            $trip_id           = $ins_logid;
                            /* Create Log */
                            $company_id        = $_SESSION['company_id'];
                            $user_createdby    = $_SESSION['userid'];
                            $log_message       = __('log_message_added');
                            $log_message       = str_replace("PASS_LOG_ID", $ins_logid, $log_message);
                            $log_booking       = __('log_booking_added');
                            $log_booking       = str_replace("PASS_LOG_ID", $ins_logid, $log_booking);
                            $log_status        = $this->create_logs($ins_logid, $company_id, $user_createdby, $log_message, $log_booking);
                            /* Create Log */
                            // Insert into Passenger Log Table //
                        }
                        /** Insert Tomorrow Booking **/
                    }
                    /** all_dates not empty **/
                }
            }
        }
        // if recurrent booking //
        //if(isset($post['dispatch']))
        if ($post['dispatch'] != '') {
            //$company_id = $_SESSION['company_id'];
            $company_dispatch = DB::select()->from(TBLALGORITHM)->where('alg_company_id', '=', $company_id)->order_by('aid', 'desc')->limit(1)->execute()->as_array();
            if (count($company_dispatch) > 0) {
                $tdispatch_type    = $company_dispatch[0]['labelname'];
                //$match_vehicletype = $company_dispatch[0]['match_vehicletype'];
                $hide_customer     = $company_dispatch[0]['hide_customer'];
                $hide_droplocation = $company_dispatch[0]['hide_droplocation'];
                //$hide_fare = $company_dispatch[0]['hide_fare'];
                /** Auto Dispatch **/
                //echo $today_result[0];
                if ($trip_id != "") {
                    $pass_logid = $this->get_autodispatch($trip_id);
                } else {
                    //$pass_logid = $this->get_bookingrecurrentdetails($insert_recurr[0]);
                    $pass_logid = '';
                }
                /*
                echo $pass_logid;
                echo '<br>';
                echo $tdispatch_type;
                exit;
                */
                if ($tdispatch_type == 1 && $pass_logid != '') {
                    $booking_details   = $this->get_bookingdetails($pass_logid, $company_id);
                    $latitude          = $booking_details[0]["pickup_latitude"];
                    $longitude         = $booking_details[0]["pickup_longitude"];
                    $miles             = '';
                    $no_passengers     = $booking_details[0]["no_passengers"];
                    $taxi_fare_km      = $booking_details[0]["min_fare"];
                    $taxi_model        = $booking_details[0]["taxi_modelid"];
                    $taxi_type         = '';
                    $maximum_luggage   = $booking_details[0]["luggage"];
                    $company_id        = $booking_details[0]["company_id"];
                    $cityname          = '';
                    $search_driver     = '';
                    //$driver_details = $find_model->search_driver_mobileapp($latitude,$longitude,$miles,$passenger_id,$taxi_fare_km,$motor_company,$motor_model,$maximum_luggage,$cityname,$sub_logid,$default_companyid,$unit,$service_type);	
                    $driver_details    = $this->search_driver_location($latitude, $longitude, $miles, $no_passengers, $_REQUEST, $taxi_fare_km, $taxi_model, $taxi_type, $maximum_luggage, $cityname, $pass_logid, $company_id, $search_driver);
                    //print_r($driver_details);exit;
                    $nearest_driver    = '';
                    $a                 = 1;
                    $temp              = '10000';
                    $prev_min_distance = '10000~0';
                    $taxi_id           = '';
                    $temp_driver       = 0;
                    $nearest_key       = 0;
                    $prev_key          = 0;
                    $driver_list       = "";
                    $available_drivers = "";
                    $nearest_driver_id = $nearest_taxi_id = "";
                    $total_count       = count($driver_details);
                    //exit;
                    if (count($driver_details) > 0) {
                        /*Nearest driver calculation */
                        $nearest_driver_ids = array();
                        $nearest_count      = 1;
                        foreach ($driver_details as $key => $value) {
                            $prev_min_distance = explode('~', $prev_min_distance);
                            $prev_key          = $prev_min_distance[1];
                            $prev_min_distance = $prev_min_distance[0];
                            //to check the driver has trip already
                            $driver_has_trip   = $this->check_driver_has_trip_request($value['driver_id']);
                            $current_request   = $this->currently_driver_has_trip_request($value['driver_id']);
                            if ($driver_has_trip == 0 && $current_request == 0) {
                                $nearest_driver_ids[] = $value['driver_id'];
                                if ($nearest_count == 1) {
                                    $nearest_driver_id = isset($driver_details[$key]['driver_id']) ? $driver_details[$key]['driver_id'] : 0;
                                    $nearest_taxi_id   = isset($driver_details[$key]['taxi_id']) ? $driver_details[$key]['taxi_id'] : 0;
                                }
                                $nearest_count++;
                            }
                            //checking with previous minimum 
                            if ($value['distance'] < $prev_min_distance) {
                                //new minimum distance
                                $nearest_key       = $key;
                                $prev_min_distance = $value['distance'] . '~' . $key;
                            } else {
                                //previous minimum
                                $nearest_key       = $prev_key;
                                $prev_min_distance = $prev_min_distance . '~' . $prev_key;
                            }
                            /*
                            if($a == $total_count)
                            {
                            $nearest_driver_id=$driver_details[$nearest_key]['driver_id'];
                            $nearest_taxi_id=$driver_details[$nearest_key]['taxi_id'];
                            }
                            else
                            {
                            $nearest_driver=0;
                            $nearest_taxi_id=0;
                            }*/
                            //$nearest_driver_id=isset($driver_details[$nearest_key]['driver_id'])?$driver_details[$nearest_key]['driver_id']:0;
                            //$nearest_taxi_id=isset($driver_details[$nearest_key]['taxi_id'])?$driver_details[$nearest_key]['taxi_id']:0;
                            //print_r($value);
                        } //exit;
                        $drivers_count = count($nearest_driver_ids);
                        if ($nearest_driver_ids != NULL) {
                            $nearest_driver_ids = implode(",", $nearest_driver_ids);
                        }
                        /*
                        echo $nearest_driver_id;
                        echo '<br>';
                        echo $nearest_taxi_id;
                        exit;
                        */
                        /*Nearest driver calculation End*/
                        $miles_or_km            = round(($prev_min_distance), 2);
                        $driver_away_in_km      = (ceil($miles_or_km * 100) / 100);
                        $company_id             = $_SESSION['company_id'];
                        $common_model           = Model::factory('commonmodel');
                        //$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);	
                        //$current_datetime =	date('Y-m-d H:i:s');	
                        $duration               = '+1 minutes';
                        $current_datetime       = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));
                        /****** Estimated Arival *************/
                        $taxi_speed             = $api->get_taxi_speed($nearest_taxi_id);
                        $estimated_time         = $api->estimated_time($driver_away_in_km, $taxi_speed);
                        /**************************************/
                        //to get nearest driver's company id
                        //echo $nearest_driver_id; exit;
                        if($nearest_driver_id) {
							$sql                    = "SELECT company_id,name,phone FROM " . PEOPLE . " WHERE id = " . $nearest_driver_id;
							$driver_company_details = Db::query(Database::SELECT, $sql)->execute()->as_array();
							$companyName            = $this->get_company_name($driver_company_details[0]['company_id']);
							$driver_name            = (isset($driver_company_details[0]['name'])) ? $driver_company_details[0]['name'] : "";
							$driver_phone           = (isset($driver_company_details[0]['phone'])) ? $driver_company_details[0]['phone'] : "";
							$driver_reachable_no    = (isset($driver_company_details[0]['phone'])) ? $driver_company_details[0]['phone'] : "";
							//condition checked to update the company id and name only in admin side
							if ($_SESSION['user_type'] == 'A') {
								$updatequery = " UPDATE " . PASSENGERS_LOG . " SET driver_id='" . $nearest_driver_id . "',taxi_id='" . $nearest_taxi_id . "',company_id='" . $driver_company_details[0]['company_id'] . "',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime' wHERE passengers_log_id ='" . $pass_logid . "'";
							} else {
								$updatequery = " UPDATE " . PASSENGERS_LOG . " SET driver_id='" . $nearest_driver_id . "',taxi_id='" . $nearest_taxi_id . "',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime' wHERE passengers_log_id ='" . $pass_logid . "'";
							}
							//exit;	
							$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
						
							/* Create Log */
							$company_id   = $_SESSION['company_id'];
							$userid       = $_SESSION['userid'];
							$log_message  = __('log_message_dispatched');
							$log_message  = str_replace("PASS_LOG_ID", $pass_logid, $log_message);
							$log_booking  = __('log_booking_dispatched');
							$log_booking  = str_replace("DRIVERNAME", $driver_details[0]['name'], $log_booking);
							$log_status   = $this->create_logs($pass_logid, $company_id, $userid, $log_message, $log_booking);
						}
					?>

						<script type="text/javascript">load_logcontent();</script>

						<?php
						if($nearest_driver_id) {
							/***** Insert the druiver details to driver request table ************/
							$insert_array = array(
								"trip_id" => $pass_logid,
								"available_drivers" => $nearest_driver_ids,
								"total_drivers" => $nearest_driver_ids,
								"selected_driver" => $nearest_driver_id,
								"status" => '0',
								"rejected_timeout_drivers" => "",
								"createdate" => $current_datetime
							);
							//Inserting to Transaction Table 
							$transaction  = $common_model->insert(DRIVER_REQUEST_DETAILS, $insert_array);
							$detail       = array(
								"passenger_tripid" => $pass_logid,
								"notification_time" => ""
							);
							$msg          = array(
								"message" => __('api_request_confirmed_passenger'),
								"status" => 1,
								"detail" => $detail
							);
						}
                    }
                }
                /** Auto Dispatch **/
            }
        }
        //echo $recurrent_id;
        //echo '<br>';
        //echo $pass_logid;
        //exit;
        $req_result['send_mail']      = $send_mail;
        $req_result['pass_logid']     = $pass_logid;
        $req_result['recurrent_id']   = $recurrent_id;
        $req_result['insert_booking'] = $insert_booking;
        return $req_result;
    }
    public static function get_autodispatch($pass_logid)
    {
        $company_id       = $_SESSION['company_id'];
        $common_model     = Model::factory('commonmodel');
        $current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
        $startdate_query  = "SELECT '$current_datetime'-interval 1 hour as startdate,'$current_datetime'+interval 2 hour as enddate";
        $startdate_result = Db::query(Database::SELECT, $startdate_query)->execute()->as_array();
        $start_datetime   = $startdate_result[0]['startdate'];
        $end_datetime     = $startdate_result[0]['enddate'];
        $booking_sql      = "SELECT * from " . PASSENGERS_LOG . "
			left join " . TBLALGORITHM . "  on  " . TBLALGORITHM . ".alg_company_id = " . PASSENGERS_LOG . ".company_id
			where driver_id=0
			and passengers_log_id='$pass_logid'";
        //echo $booking_sql;exit;
        $results          = Db::query(Database::SELECT, $booking_sql)->execute()->as_array();
        if (count($results) > 0) {
            return $results[0]['passengers_log_id'];
        } else {
            return;
        }
    }
    public function all_booking_list()
    {
        $currentdate = date('Y-m-d') . ' 00:00:00';
        $enddate     = date('Y-m-d') . ' 23:59:59';
        $company_id  = $_SESSION['company_id'];
        $query       = "SELECT *,
		(select company_name from " . COMPANY . " where cid=" . PASSENGERS_LOG . ".company_id) as company_name,
		" . PASSENGERS_LOG . ".passengers_log_id as pass_logid,
		(select name from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_name,
		(select name from " . PEOPLE . " where " . PEOPLE . ".id=" . PASSENGERS_LOG . ".driver_id) as driver_name,
		(select phone from " . PEOPLE . " where " . PEOPLE . ".id=" . PASSENGERS_LOG . ".driver_id) as driver_phone,
		(select phone from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_phone
		FROM " . PASSENGERS_LOG . "
		where bookby in('1','2','3')
		AND " . PASSENGERS_LOG . ".company_id='$company_id'
		AND ( pickup_time between '$currentdate' and  '$enddate' )
		order by passengers_log_id desc";
        $result      = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public function get_all_booking_list($array)
    {
        //$travel_status=$array['travel_status'].",8";
        $travel_status       = $array['travel_status'];
        $driver_reply_cancel = $array['driver_reply_cancel'];
        $manage_status       = $array['manage_status'];
        $taxi_company        = $array['taxi_company'];
        //echo $travel_status."--".$driver_reply_cancel;exit;
        $date                = date('Y-m-d', strtotime($array['current_time']));
        $currentdate         = $date . ' 00:00:00';
        $enddate             = $date . ' 23:59:59';
        $company_id          = $_SESSION['company_id'];
        $company_query       = "";
        if ($company_id != 0) {
            //$company_query="AND ".PASSENGERS_LOG.".company_id='$company_id'";
            $company_query = "AND plog.company_id='$company_id'";
        }
        $company_where = "";
        if ($taxi_company != "" && $taxi_company != 0) {
            //$company_where="AND ".PASSENGERS_LOG.".company_id='$taxi_company'";
            $company_where = "AND plog.company_id='$taxi_company'";
        }
        //$date_query="AND ( pickup_time between '$currentdate' and  '$enddate' )";
        $two_days_before = date('Y-m-d 00:00:00', strtotime($date . ' -2 day'));
        $date_query      = "";
        if ($manage_status == 0) {
            //$date_query="AND pickup_time >= '$currentdate'";
            $date_query = "AND plog.pickup_time >= '$two_days_before'";
        }
        $status_query = "";
        if ($travel_status != "" && $driver_reply_cancel == "") {
            //$status_query="AND driver_reply NOT IN('C','R') AND travel_status IN(".$travel_status.") AND travel_status NOT IN('8')";
            $status_query = "AND plog.driver_reply NOT IN('C','R') AND plog.travel_status IN(" . $travel_status . ") AND plog.travel_status NOT IN('8')";
        } else {
            //$status_query="AND travel_status IN(".$travel_status.")";
            $status_query = "AND plog.travel_status IN(" . $travel_status . ")";
        }
        /*$query = "SELECT *,
        (select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,
        ".PASSENGERS_LOG.".passengers_log_id as pass_logid,
        (select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,
        (select name from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_name,
        (select phone from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_phone,
        (select reachable_mobile from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as reachable_mobile,
        (select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone,
        (select total_drivers from ".DRIVER_REQUEST_DETAILS." where ".DRIVER_REQUEST_DETAILS.".trip_id=".PASSENGERS_LOG.".passengers_log_id order by request_id desc limit 0,1) as total_drivers,
        (select fare from ".TRANS." where ".TRANS.".passengers_log_id=".PASSENGERS_LOG.".passengers_log_id) as fare,
        (select distance from ".TRANS." where ".TRANS.".passengers_log_id=".PASSENGERS_LOG.".passengers_log_id) as distance
        FROM ".PASSENGERS_LOG." where bookby='2' $date_query $status_query $company_where order by ".PASSENGERS_LOG.".passengers_log_id desc";*/
        $query  = "SELECT *, c.company_name as company_name, plog.passengers_log_id as pass_logid, pass.name as passenger_name, pass.phone as passenger_phone, p.name as driver_name, p.phone as driver_phone, p.reachable_mobile as reachable_mobile, dr.total_drivers as total_drivers, trans.fare as fare, trans.distance as distance FROM " . PASSENGERS_LOG . " as plog LEFT JOIN " . COMPANY . " as c ON c.cid = plog.company_id LEFT JOIN " . PASSENGERS . " as pass ON pass.id = plog.passengers_id LEFT JOIN " . PEOPLE . " as p ON p.id = plog.driver_id LEFT JOIN " . DRIVER_REQUEST_DETAILS . " as dr ON dr.trip_id = plog.passengers_log_id LEFT JOIN " . TRANS . " as trans ON trans.passengers_log_id = plog.passengers_log_id where plog.bookby in('1','2','3') $date_query $status_query $company_where order by plog.passengers_log_id desc";
        //echo $query;exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public function get_all_booking_list_all($array)
    {
        $travel_status       = isset($array['travel_status']) ? $array['travel_status'] : '';
        $driver_reply_cancel = isset($array['driver_reply_cancel']) ? $array['driver_reply_cancel'] : '';
        $manage_status       = isset($array['manage_status']) ? $array['manage_status'] : '';
        $search_txt          = isset($array['search_txt']) ? $array['search_txt'] : '';
        $search_location     = isset($array['search_location']) ? $array['search_location'] : '';
        $filter_date         = isset($array['filter_date']) ? $array['filter_date'] : '';
        $to_date             = isset($array['to_date']) ? $array['to_date'] : '';
        $booking_filter      = isset($array['booking_filter']) ? $array['booking_filter'] : '';
        $company_id          = isset($array['company_id']) ? $array['company_id'] : $_SESSION['company_id'];
        $taxi_model_id       = (isset($array['select_taxi_model']) && $array['select_taxi_model'] > 0) ? $array['select_taxi_model'] : '';
        $fromdate            = $filter_date . ':00';
        $todate              = $to_date . ':00';
        $date_where          = '';
        $pickupdrop_where    = '';
        $status_query        = '';
        if ($search_txt != '') {
            /*$pickupdrop_where  = " AND (current_location LIKE  '%$search_txt%' ";
            $pickupdrop_where .= " or drop_location LIKE '%$search_txt%' escape '!'  ";
            $pickupdrop_where .= " or ".PASSENGERS.".name LIKE '%$search_txt%' escape '!' ";
            $pickupdrop_where .= " or passengers_log_id LIKE '%$search_txt%' escape '!' ) "; */
            $pickupdrop_where = " AND ( pass.name LIKE '%$search_txt%' escape '!' or p.name  LIKE '%$search_txt%' escape '!' or p.phone LIKE '%$search_txt%' escape '!' or c.company_name LIKE '%$search_txt%' or pass.phone LIKE '%$search_txt%' escape '!' ";
            $pickupdrop_where .= " or plog.passengers_log_id = '$search_txt' ) ";
        }
        if ($search_location != '') {
            $pickupdrop_where .= " AND (plog.current_location LIKE  '%$search_location%' ";
            $pickupdrop_where .= " or plog.drop_location LIKE '%$search_location%' ) ";
        }
        $date            = date('Y-m-d', strtotime($array['current_time']));
        $currentdate     = $date . ' 00:00:00';
        $enddate         = $date . ' 23:59:59';
        //$date_query="AND ( pickup_time between '$currentdate' and  '$enddate' )";
        //to get two days before from current date
        $two_days_before = date('Y-m-d 00:00:00', strtotime($date . ' 0 day'));
        if ($filter_date != '' && $to_date != '') {
            //$date_where = " AND ( pickup_time between '$currentdate' and  '$enddate' ) ";
            //$date_where = " AND ( plog.pickup_time between '$currentdate' and  '$enddate' ) ";
            $date_where = " AND ( (plog.pickup_time >= '$fromdate' and plog.pickup_time <= '$todate') OR (plog.actual_pickup_time >= '$fromdate' and plog.actual_pickup_time <= '$todate') ) ";
        } else if ($filter_date != '' || $to_date != '') {
            $datesearch = ($to_date != '') ? $to_date : $filter_date;
            $dateArr    = explode(" ", $datesearch);
            $staDate    = $dateArr[0] . ' 00:00:01';
            $endDate    = $dateArr[0] . ' 23:59:59';
            $date_where = " AND ( (plog.pickup_time >= '$staDate' and plog.pickup_time <= '$endDate') OR (plog.actual_pickup_time >= '$staDate' and plog.actual_pickup_time <= '$endDate') ) ";
        } else {
            $date_where = " AND plog.pickup_time >= '$two_days_before'";
        }
        //echo $travel_status."--".$driver_reply_cancel;exit;
        $date_query = "";
        if ($manage_status == 0) {
            //$date_query="AND pickup_time >= '$currentdate'";
            //$date_query="AND plog.pickup_time >= '$currentdate'";
            $date_query = "AND plog.pickup_time >= '$two_days_before'";
        }
        if ($booking_filter != '') {
            //$status_query = " AND ( travel_status = '$booking_filter' ) ";
            $status_query = " AND ( plog.travel_status = '$booking_filter' ) ";
        }
        $status_query = "";
        if ($travel_status != "" && $driver_reply_cancel == "") {
            //$status_query="AND driver_reply NOT IN('C','R') AND travel_status IN(".$travel_status.") AND travel_status NOT IN('8')";
            $status_query = "AND plog.driver_reply NOT IN('C','R') AND plog.travel_status IN(" . $travel_status . ") AND plog.travel_status NOT IN('8')";
        } elseif ($travel_status == "" && $driver_reply_cancel == "") {
            //$status_query="AND driver_reply NOT IN('C','R') AND travel_status NOT IN('8')";
            $status_query = "AND plog.driver_reply NOT IN('C','R') AND plog.travel_status NOT IN('8')";
        } else {
            //$status_query="AND travel_status IN(".$travel_status.")";
            $status_query = "AND plog.travel_status IN(" . $travel_status . ")";
        }
        $company_where = "";
        if ($company_id != 0) {
            //$company_where = "AND ".PASSENGERS_LOG.".company_id='$company_id'";
            $company_where = "AND plog.company_id='$company_id'";
        }
        $taxi_model_where = "";
        if ($taxi_model_id != '') {
            $taxi_model_where = "AND plog.taxi_modelid = '$taxi_model_id'";
        }
        $query  = "SELECT plog.booking_from,plog.company_id,plog.notes_driver as notes,plog.createdate,plog.pickup_time,IF(plog.actual_pickup_time = '0000-00-00 00:00:00','-',plog.actual_pickup_time) as act_pickuptime,plog.bookingtype,plog.bookby,plog.pickup_latitude,plog.pickup_longitude,plog.drop_latitude,plog.drop_longitude,plog.no_passengers,plog.current_location,plog.drop_location,plog.dispatch_time,plog.travel_status,plog.driver_reply,plog.approx_distance,plog.approx_fare,c.company_name as company_name, plog.passengers_log_id as pass_logid, pass.name as passenger_name, pass.phone as passenger_phone, pass.country_code as passenger_country_code, p.name as driver_name, p.phone as driver_phone,p.id as driver_id,mmodel.model_name as model_name, dr.total_drivers as total_drivers, trans.fare as fare, trans.distance as distance FROM " . PASSENGERS_LOG . " as plog 
		LEFT JOIN " . COMPANY . " as c ON c.cid = plog.company_id LEFT JOIN " . PASSENGERS . " as pass ON pass.id = plog.passengers_id 
		LEFT JOIN " . PEOPLE . " as p ON p.id = plog.driver_id 
		LEFT JOIN " . DRIVER_REQUEST_DETAILS . " as dr ON dr.trip_id = plog.passengers_log_id 
		LEFT JOIN  " . MOTORMODEL . " as mmodel ON (  mmodel.`model_id` =  plog.`taxi_modelid` )
		LEFT JOIN " . TRANS . " as trans ON trans.passengers_log_id = plog.passengers_log_id 
		where plog.bookby in('1','2','3') $company_where $pickupdrop_where $date_where $date_query $status_query $taxi_model_where group by plog.passengers_log_id order by plog.passengers_log_id desc";
        //$pickupdrop_where $date_where
        //echo $query;exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public static function load_logcontent()
    {
        $common_model     = Model::factory('commonmodel');
        $company_id       = $_SESSION['company_id'];
        $user_createdby   = $_SESSION['userid'];
        $current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
        $currentdate      = date('Y-m-d', strtotime($current_datetime));
        $sdate            = $currentdate . ' 00:00:00';
        $query            = "SELECT booking_logid, log_message, log_booking, log_createdate FROM " . LOGS . "
				WHERE log_userid='" . $user_createdby . "'
				AND log_createdate >= '" . $sdate . "'
				ORDER BY `logid` DESC
				LIMIT 0,50";
        //echo $query;exit;
        $results          = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $results;
    }
    public function edit_bookingdetails($pass_logid = '')
    {
        $company_id    = $_SESSION['company_id'];
        $company_where = "";
        if ($company_id != 0) {
            $company_where = "AND " . PASSENGERS_LOG . ".company_id='$company_id'";
        }
        if (FARE_SETTINGS == 2 && !empty($company_id)) {
            $query = "SELECT *,(select company_name from " . COMPANY . " where cid=" . PASSENGERS_LOG . ".company_id) as company_name," . PASSENGERS_LOG . ".passengers_log_id as pass_logid,(select name from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_name,(select email from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_email,(select phone from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_phone,(select country_code from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as country_code FROM " . PASSENGERS_LOG . " where " . PASSENGERS_LOG . ".passengers_log_id ='$pass_logid' $company_where  ";
        } else {
            $query = "SELECT *,(select company_name from " . COMPANY . " where cid=" . PASSENGERS_LOG . ".company_id) as company_name," . PASSENGERS_LOG . ".passengers_log_id as pass_logid,(select name from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_name,(select email from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_email,(select phone from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_phone,(select country_code from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as country_code,(select min_fare from " . MOTORMODEL . " where " . MOTORMODEL . ".model_id=" . PASSENGERS_LOG . ".taxi_modelid) as min_fare FROM " . PASSENGERS_LOG . " where " . PASSENGERS_LOG . ".passengers_log_id ='$pass_logid' $company_where  ";
        }
        //echo $query;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public function validate_dispatchbooking_edit($arr)
    {
        return Validation::factory($arr)->rule('edit_firstname', 'not_empty')
        //->rule('edit_firstname', 'min_length', array(':value', '3'))
            
        //->rule('firstname', 'max_length', array(':value', '32'))
            
        //->rule('edit_email', 'not_empty')
            ->rule('edit_email', 'email')->rule('edit_email', 'max_length', array(
            ':value',
            '50'
        ))->rule('edit_country_code', 'not_empty')->rule('edit_phone', 'not_empty')->rule('edit_current_location', 'not_empty')->rule('edit_pickup_lat', 'not_empty')->rule('edit_pickup_lng', 'not_empty') /*->rule('drop_location', 'not_empty')
        ->rule('drop_lat', 'not_empty')
        ->rule('drop_lng', 'not_empty')*/ 
        //->rule('luggage', 'numeric')
            
        //->rule('no_passengers', 'numeric')
            
        //->rule('edit_pickup_time', 'not_empty')
            ->rule('edit_pickup_date', 'not_empty');
    }
    public function updatebooking($post, $random_key, $password)
    {
        $api         = Model::factory('api');
        $firstname   = Html::chars($post['edit_firstname']);
        $send_mail   = 'N';
        //print_r($post);exit;
        $city_id     = $post['edit_city_id'];
        $search_city = $post['edit_cityname'];
        $update_city_name = strtolower($post['edit_cityname']);
        if($update_city_name == "") {
			$update_city_name = Commonfunction::getCityName($post["edit_pickup_lat"],$post["edit_pickup_lng"]);
		}
        if ($search_city != '') {
            $cityid_query = "select city_id from " . CITY . " where " . CITY . ".city_name like '%" . $search_city . "%' limit 0,1";
            $cityid_fetch = Db::query(Database::SELECT, $cityid_query)->execute()->as_array();
        } else {
            $cityid_query = "select city_id from " . CITY . " where " . CITY . ".default=1";
            $cityid_fetch = Db::query(Database::SELECT, $cityid_query)->execute()->as_array();
        }
        if (count($cityid_fetch) == 0) {
            $cityid_query = "select city_id from " . CITY . " where " . CITY . ".default=1";
            $cityid_fetch = Db::query(Database::SELECT, $cityid_query)->execute()->as_array();
        }
        $city_id          = (count($cityid_fetch) > 0) ? $cityid_fetch[0]['city_id'] : '';
        $passenger_id     = $post['edit_passenger_id'];
        //$company_id = $_SESSION['company_id'];
        $admin_company_id = isset($post['edit_admin_company_id']) ? $post['edit_admin_company_id'] : "";
        if ($admin_company_id != "") {
            $company_id = $admin_company_id;
        } else {
            $company_id = $_SESSION['company_id'];
        }
        $common_model      = Model::factory('commonmodel');
        $current_datetime  = $common_model->getcompany_all_currenttimestamp($company_id);
        $current_datesplit = explode(' ', $current_datetime);
        /*
        $pickup_date = $post['pickup_date'];
        $pickup_time = $post['pickup_time'];
        if($pickup_date =='' || $pickup_date =='Today')
        {
        $pickup_date = $current_datesplit[0];
        }
        
        if($pickup_time =='' || $pickup_time =='Now')
        {
        $pickup_time = $current_datesplit[1];
        }
        $pickup_datetime = $pickup_date.' '.$pickup_time;
        */
        $pickup_datetime   = $post['edit_pickup_date'];
        $additional_fields = '';
        $user_createdby    = $userid = $_SESSION['userid'];
        if (isset($post['dispatch']) && !empty($post['dispatch'])) {
            $booktype = '1';
        } else {
            $booktype = '2';
        }
        if (isset($post['group_id'])) {
            $group_id         = $post['group_id'];
            $company_dispatch = DB::select()->from(TBLGROUP)->where('gid', '=', $group_id)->execute()->as_array();
            if (count($company_dispatch) > 0) {
                $account_id = $company_dispatch[0]['aid'];
            } else {
                $account_id = '0';
            }
        }
        $email_con       = ($post['edit_email'] != '') ? " and email = '" . $post['edit_email'] . "'" : "";
        $pass_query      = "select id from " . PASSENGERS . " where phone= '" . $post['edit_phone'] . "' $email_con";
        $passenger_exist = Db::query(Database::SELECT, $pass_query)->execute()->as_array();
        //$passenger_exist = DB::select('id')->from(PASSENGERS)->where('phone','=',$post['edit_phone'])->where('country_code','=',$post['edit_country_code'])->execute()->as_array();
        //if($post['edit_passenger_id'] =='')
        if (count($passenger_exist) == 0) {
            $insert_array     = array(
                'name' => $firstname,
                'email' => $post['edit_email'],
                'phone' => $post['edit_phone'],
                'country_code' => $post['edit_country_code'],
                'password' => md5($password),
                'org_password' => $password,
                'created_date' => $current_datetime,
                'activation_key' => $random_key,
                'user_status' => ACTIVE,
                'passenger_cid' => $company_id,
                'activation_status' => 1
            );
            //Inserting to PASSENGERS Table 
            $insert_passenger = $common_model->insert(PASSENGERS, $insert_array);
            $send_mail        = 'S';
            $passenger_id     = $insert_passenger[0];
        } else {
            $passenger_id           = $passenger_exist[0]['id'];
            $name                   = explode('- (', $firstname);
            $firstname              = isset($name[0]) ? $name[0] : $firstname;
            $update_passenger_array = array(
                'name' => $firstname,
                'email' => $post['edit_email']
            );
            //'phone'=>$post['edit_phone']
            $updateresult           = $common_model->update(PASSENGERS, $update_passenger_array, 'id', $passenger_id);
        }
        $account_id   = isset($account_id) ? $account_id : '0';
        $group_id     = isset($group_id) ? $group_id : '0';
        $companyName  = $this->get_company_name($company_id);
        $distance_km  = (UNIT_NAME == "MILES") ? ($post['edit_distance_km'] * 1.60934) : $post['edit_distance_km'];
        $trip_timezone = $this->getpickupTimezone($post["edit_pickup_lat"],$post["edit_pickup_lng"]);
        $distance_km  = round($distance_km, 2);
        $update_array = array(
            "passengers_id" => $passenger_id,
            "company_id" => $company_id,
            "current_location" => $post['edit_current_location'],
            "pickup_latitude" => $post['edit_pickup_lat'],
            "pickup_longitude" => $post['edit_pickup_lng'],
            "drop_location" => $post['edit_drop_location'],
            "drop_latitude" => $post['edit_drop_lat'],
            "drop_longitude" => $post['edit_drop_lng'],
            "pickup_time" => $pickup_datetime,
            "no_passengers" => $post['edit_no_passengers'],
            "approx_distance" => $distance_km,
            "approx_duration" => $post['edit_total_duration'],
            "approx_fare" => $post['edit_total_fare'],
            "search_city" => $city_id,
            "notes_driver" => $post['edit_notes'],
            "faretype" => $post['edit_payment_type'],
            "fixedprice" => $post['edit_fixedprice'],
            "bookingtype" => $booktype,
            "luggage" => $post['edit_luggage'],
            "bookby" => '2',
            "operator_id" => $userid,
            "taxi_modelid" => $post['edit_taxi_model'],
            "company_tax" => $post['edit_company_tax'],
            "account_id" => $account_id,
            "accgroup_id" => $group_id,
            "trip_timezone" => $trip_timezone,
            "city_name" => $update_city_name,
            "notification_status" => '6',
            "building_number" => $post['edit_building_number'],
            "house_number" => $post['edit_house_number'],
            "driver_notes" => $post['edit_driver_notes'],

        );
        if ($company_id == 0) {
            unset($update_array['company_id']);
            unset($update_array['company_name']);
        }
        //print_r($update_array);exit;
        $updateresult = $common_model->update(PASSENGERS_LOG, $update_array, 'passengers_log_id', $post['edit_pass_logid']);
        //if(isset($post['update_dispatch']))
        if ($post['update_dispatch'] != '') {
            //echo "1";exit;
            $trip_id          = $post['edit_pass_logid'];
            $company_id       = $_SESSION['company_id'];
            $company_dispatch = DB::select()->from(TBLALGORITHM)->where('alg_company_id', '=', $company_id)->order_by('aid', 'desc')->limit(1)->execute()->as_array();
            if (count($company_dispatch) > 0) {
                $tdispatch_type    = $company_dispatch[0]['labelname'];
                //$match_vehicletype = $company_dispatch[0]['match_vehicletype'];
                $hide_customer     = $company_dispatch[0]['hide_customer'];
                $hide_droplocation = $company_dispatch[0]['hide_droplocation'];
                //$hide_fare = $company_dispatch[0]['hide_fare'];
                /** Auto Dispatch **/
                //echo $today_result[0];
                //echo $tdispatch_type;exit;
                if ($trip_id != "") {
                    $pass_logid = $trip_id; //$this->get_autodispatch($trip_id);	
                } else {
                    //$pass_logid = $this->get_bookingrecurrentdetails($insert_recurr[0]);
                    $pass_logid = '';
                }
                /*echo $pass_logid;
                echo '<br>';
                echo $tdispatch_type;
                exit;*/
                if(!isset($post['update'])) {
					if ($tdispatch_type == 1 && $pass_logid != '') {
						$booking_details   = $this->get_bookingdetails($pass_logid, $company_id);
						$latitude          = $booking_details[0]["pickup_latitude"];
						$longitude         = $booking_details[0]["pickup_longitude"];
						$miles             = '';
						$no_passengers     = $booking_details[0]["no_passengers"];
						$taxi_fare_km      = $booking_details[0]["min_fare"];
						$taxi_model        = $booking_details[0]["taxi_modelid"];
						$taxi_type         = '';
						$maximum_luggage   = $booking_details[0]["luggage"];
						$company_id        = $booking_details[0]["company_id"];
						$cityname          = '';
						$search_driver     = '';
						//$driver_details = $find_model->search_driver_mobileapp($latitude,$longitude,$miles,$passenger_id,$taxi_fare_km,$motor_company,$motor_model,$maximum_luggage,$cityname,$sub_logid,$default_companyid,$unit,$service_type);	
						$driver_details    = $this->search_driver_location($latitude, $longitude, $miles, $no_passengers, $_REQUEST, $taxi_fare_km, $taxi_model, $taxi_type, $maximum_luggage, $cityname, $pass_logid, $company_id, $search_driver);
						//print_r($driver_details);exit;
						$nearest_driver    = '';
						$a                 = 1;
						$temp              = '10000';
						$prev_min_distance = '10000~0';
						$taxi_id           = '';
						$temp_driver       = 0;
						$nearest_key       = 0;
						$prev_key          = 0;
						$driver_list       = "";
						$available_drivers = "";
						$nearest_driver_id = $nearest_taxi_id = "";
						$total_count       = count($driver_details);
						//exit;
						if (count($driver_details) > 0) {
							$nearest_count      = 1;
							/*Nearest driver calculation */
							$nearest_driver_ids = array();
							foreach ($driver_details as $key => $value) {
								$prev_min_distance = explode('~', $prev_min_distance);
								$prev_key          = $prev_min_distance[1];
								$prev_min_distance = $prev_min_distance[0];
								//to check the driver has trip already
								$driver_has_trip   = $this->check_driver_has_trip_request($value['driver_id']);
								$current_request   = $this->currently_driver_has_trip_request($value['driver_id']);
								if ($driver_has_trip == 0 && $current_request == 0) {
									$nearest_driver_ids[] = $value['driver_id'];
									if ($nearest_count == 1) {
										$nearest_driver_id = isset($driver_details[$key]['driver_id']) ? $driver_details[$key]['driver_id'] : 0;
										$nearest_taxi_id   = isset($driver_details[$key]['taxi_id']) ? $driver_details[$key]['taxi_id'] : 0;
									}
									$nearest_count++;
								}
								//checking with previous minimum 
								if ($value['distance'] < $prev_min_distance) {
									//new minimum distance
									$nearest_key       = $key;
									$prev_min_distance = $value['distance'] . '~' . $key;
								} else {
									//previous minimum
									$nearest_key       = $prev_key;
									$prev_min_distance = $prev_min_distance . '~' . $prev_key;
								}
								/*
								if($a == $total_count)
								{
								$nearest_driver_id=$driver_details[$nearest_key]['driver_id'];
								$nearest_taxi_id=$driver_details[$nearest_key]['taxi_id'];
								}
								else
								{
								$nearest_driver=0;
								$nearest_taxi_id=0;
								}*/
								//$nearest_driver_id=isset($driver_details[$nearest_key]['driver_id'])?$driver_details[$nearest_key]['driver_id']:0;
								//$nearest_taxi_id=isset($driver_details[$nearest_key]['taxi_id'])?$driver_details[$nearest_key]['taxi_id']:0;
								//print_r($value);
							} //exit;
							$drivers_count = count($nearest_driver_ids);
							if ($nearest_driver_ids != NULL) {
								$nearest_driver_ids = implode(",", $nearest_driver_ids);
							}
							/*
							echo $nearest_driver_id;
							echo '<br>';
							echo $nearest_taxi_id;
							exit;
							*/
							/*Nearest driver calculation End*/
							$miles_or_km       = round(($prev_min_distance), 2);
							$driver_away_in_km = (ceil($miles_or_km * 100) / 100);
							$company_id        = $_SESSION['company_id'];
							$common_model      = Model::factory('commonmodel');
							//$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);	
							//$current_datetime =	date('Y-m-d H:i:s');	
							$duration          = '+1 minutes';
							$current_datetime  = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));
							/****** Estimated Arival *************/
							$taxi_speed        = $api->get_taxi_speed($nearest_taxi_id);
							$estimated_time    = $api->estimated_time($driver_away_in_km, $taxi_speed);
							/**************************************/
							//to get nearest driver's company id
							if (!empty($nearest_driver_id)) {
								$sql                    = "SELECT company_id,name,phone FROM " . PEOPLE . " WHERE id = " . $nearest_driver_id;
								$driver_company_details = Db::query(Database::SELECT, $sql)->execute()->as_array();
							}
							$companyName         = (isset($driver_company_details[0]['name'])) ? $this->get_company_name($driver_company_details[0]['company_id']) : "";
							$companyid           = (isset($driver_company_details[0]['company_id'])) ? $driver_company_details[0]['company_id'] : "";
							$driver_name         = (isset($driver_company_details[0]['name'])) ? $driver_company_details[0]['name'] : "";
							$driver_phone        = (isset($driver_company_details[0]['phone'])) ? $driver_company_details[0]['phone'] : "";
							$driver_reachable_no = (isset($driver_company_details[0]['phone'])) ? $driver_company_details[0]['phone'] : "";
							//condition checked to update the company id and name only in admin side
							if ($_SESSION['user_type'] == 'A') {
								$updatequery = " UPDATE " . PASSENGERS_LOG . " SET driver_id='" . $nearest_driver_id . "',taxi_id='" . $nearest_taxi_id . "',company_id='" . $companyid . "',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime' wHERE passengers_log_id ='" . $pass_logid . "'";
							} else {
								$updatequery = " UPDATE " . PASSENGERS_LOG . " SET driver_id='" . $nearest_driver_id . "',taxi_id='" . $nearest_taxi_id . "',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime' wHERE passengers_log_id ='" . $pass_logid . "'";
							}
							//exit;	
							$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
							/* Create Log */
							$company_id   = $_SESSION['company_id'];
							$userid       = $_SESSION['userid'];
							$log_message  = __('log_message_dispatched');
							$log_message  = str_replace("PASS_LOG_ID", $pass_logid, $log_message);
							$log_booking  = __('log_booking_dispatched');
							$log_booking  = str_replace("DRIVERNAME", $driver_details[0]['name'], $log_booking);
							$log_status   = $this->create_logs($pass_logid, $company_id, $userid, $log_message, $log_booking);
	?>
							
							<script type="text/javascript">load_logcontent();</script>

							
							<?php
							$exist_request = $this->exist_request($pass_logid);
							if ($exist_request == 1) {
								$delete_exist_request = $common_model->delete(DRIVER_REQUEST_DETAILS, 'trip_id', $pass_logid);
							}
							/***** Insert the druiver details to driver request table ************/
							$nearest_driver_ids = (!empty($nearest_driver_ids)) ? $nearest_driver_ids : '';
							$insert_array       = array(
								"trip_id" => $pass_logid,
								"available_drivers" => $nearest_driver_ids,
								"total_drivers" => $nearest_driver_ids,
								"selected_driver" => $nearest_driver_id,
								"status" => '0',
								"rejected_timeout_drivers" => "",
								"createdate" => $current_datetime
							);
							//print_r($insert_array);exit;
							//Inserting to Transaction Table 
							$transaction        = $common_model->insert(DRIVER_REQUEST_DETAILS, $insert_array);
							$detail             = array(
								"passenger_tripid" => $pass_logid,
								"notification_time" => ""
							);
							$msg                = array(
								"message" => __('api_request_confirmed_passenger'),
								"status" => 1,
								"detail" => $detail
							);
						}
					}
				}
                /** Auto Dispatch **/
            }
        }
        $req_result['send_mail']  = $send_mail;
        $req_result['pass_logid'] = $post['edit_pass_logid'];
        return $req_result;
    }
    public function exist_request($pass_logid)
    {
        $sql    = "SELECT trip_id FROM " . DRIVER_REQUEST_DETAILS . " WHERE trip_id='$pass_logid'";
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function get_bookingdetails($pass_logid, $company_id)
    {
        /*
        if($company_id!="" && $company_id!=0){
        $company_id = $company_id;
        }else{
        $company_id = $_SESSION['company_id'];
        }
        */
        $company_where = "";
        if ($company_id != 0 && $company_id != "") {
            $company_where = "AND " . PASSENGERS_LOG . ".company_id='$company_id'";
        }
        $query  = "SELECT *,(select company_name from " . COMPANY . " where cid=" . PASSENGERS_LOG . ".company_id) as company_name," . PASSENGERS_LOG . ".passengers_log_id as pass_logid,(select name from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_name,(select email from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_email,(select phone from " . PASSENGERS . " where " . PASSENGERS . ".id=" . PASSENGERS_LOG . ".passengers_id) as passenger_phone,(select min_fare from " . MOTORMODEL . " where " . MOTORMODEL . ".model_id=" . PASSENGERS_LOG . ".taxi_modelid) as min_fare FROM " . PASSENGERS_LOG . "  where " . PASSENGERS_LOG . ".passengers_log_id ='$pass_logid' $company_where";
        //echo $query;exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public function search_driver_location($lat, $long, $distance = NULL, $no_passengers, $request, $taxi_fare_km, $taxi_model, $taxi_type, $maximum_luggage, $city_name, $sub_log_id, $company_id, $search_driver)
    {
        $unit               = UNIT;
        $distance           = "";
        $unit_conversion    = "";
        $remove_driver_list = array();
        //$assigned_driver = $this->free_availabletaxisearch_list_web($no_passengers,$request,$company_id);
        $assigned_driver    = $this->free_availabletaxisearch_list($taxi_type, $taxi_model, $company_id);
        //$additional_fields = $this->taxi_additionalfields();
        $add_field          = "";
        $where              = ' ';
        if ($taxi_model) {
            $where .= " AND taxi.`taxi_model`='" . $taxi_model . "' ";
        }
        if ($taxi_type) {
            $where .= " AND taxi.`taxi_type`='" . $taxi_type . "' ";
        }
        if ($maximum_luggage) {
            $where .= " AND taxi.`max_luggage`>='" . $maximum_luggage . "' ";
        }
        $driver_list       = '';
        $driver_count      = '';
        $driver_list_array = array();
        foreach ($assigned_driver as $key => $value) {
            $driver_list_array[] = $value['id'];
        }
        if ($sub_log_id != '') {
            $driver_arraylist = array_diff($driver_list_array, $remove_driver_list);
            foreach ($driver_arraylist as $key => $value) {
                $driver_count = 1;
                $driver_list .= "'" . $value . "',";
            }
        } else {
            foreach ($assigned_driver as $key => $value) {
                $driver_count = 1;
                $driver_list .= "'" . $value['id'] . "',";
            }
        }
        if ($driver_count > 0) {
            $driver_list = substr_replace($driver_list, "", -1);
        } else {
            $driver_list = "''";
        }
        $additional_field_join = "";
        if ($add_field != "") {
            $additional_field_join = "JOIN " . ADDFIELD . " as adds ON tmap.`mapping_taxiid`=adds.`taxi_id`";
        }
        $driver_like = '';
        if ($search_driver) {
            $driver_like = "  and name LIKE  '%$search_driver%' ";
        }
        $common_model     = Model::factory('commonmodel');
        $current_datetime = $common_model->company_timezone($company_id);
        $current_time     = convert_timezone('now', $current_datetime);
        $current_date     = explode(' ', $current_time);
        $start_time       = $current_date[0] . ' 00:00:01';
        $end_time         = $current_date[0] . ' 23:59:59';
        if ($unit == '0') {
            $unit_conversion = '*1.609344';
        }
        if ($distance) {
            $distance_query = "HAVING distance <='" . DEFAULTMILE . "'";
        } else {
            $distance_query = "HAVING distance <='" . DEFAULTMILE . "'";
        }
        $query  = " select  list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,comp.cid as company_id,taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((" . $lat . "*pi()/180)) * sin((driver.latitude*pi()/180))+cos((" . $lat . "*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((" . $long . "- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference FROM " . DRIVER . " AS driver JOIN " . PEOPLE . " AS people ON driver.driver_id=people.id  where people.login_status='S' $distance_query AND driver.shift_status='IN' AND driver.status='F' and driver_id IN ($driver_list) order by distance ) as list JOIN " . TAXIMAPPING . " as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN " . TAXI . " as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN " . MOTORMODEL . " as model ON model.`model_id`=taxi.`taxi_model` JOIN " . COMPANY . " AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where tmap.mapping_startdate <='$current_time' AND updatetime_difference  <= '" . LOCATIONUPDATESECONDS . "' AND tmap.mapping_enddate >='$current_time'  AND tmap.`mapping_status`='A' " . $where . $add_field . $driver_like . " group by list.driver_id order by distance ASC limit 0,10 ";
        //echo $query ;exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public function free_availabletaxisearch_list_web($no_passengers = '', $request = '', $company_id = '')
    {
        $Commonmodel = Model::factory('Commonmodel');
        $where_cond  = '';
        //print_r($request);
        if (($no_passengers != null) && ($no_passengers != 0)) {
            $capacity_where = " AND taxi_capacity >= $no_passengers";
        } else {
            $capacity_where = '';
        }
        if (isset($request['taxi_fare_km']) && $request['taxi_fare_km'] != '') {
            //$taxifare_where = " AND taxi_fare_km <=".$request['taxi_fare_km'];
        } else {
            //$taxifare_where = '';
        }
        if (isset($request['motor_company']) && $request['motor_company'] != '') {
            $taxitype_where = " AND taxi_type ='" . $request['motor_company'] . "'";
            //$taxitype_where = " AND taxi_type ='1'";
        } else {
            $taxitype_where = '';
        }
        if (isset($request['motor_model']) && ($request['motor_model'] != '')) {
            $taximodel_where = " AND taxi_model ='" . $request['motor_model'] . "'";
        } else {
            $taximodel_where = '';
        }
        $current_time      = $Commonmodel->getcompany_all_currenttimestamp($company_id);
        $current_date      = explode(' ', $current_time);
        $start_time        = $current_date[0] . ' 00:00:01';
        $end_time          = $current_date[0] . ' 23:59:59';
        //$cuurentdate = date('Y-m-d H:i:s');
        //$enddate = date('Y-m-d').' 23:59:59';
        $company_condition = "";
        if ($company_id) {
            $company_condition = "AND taximapping.mapping_companyid = '" . $company_id . "' AND people.company_id = '" . $company_id . "' AND taxi.taxi_company = '" . $company_id . "'";
        }
        $sql     = "SELECT people.id,taxi.taxi_id  ,(select check_package_type from " . PACKAGE_REPORT . " where " . PACKAGE_REPORT . ".upgrade_companyid = " . TAXI . ".taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from " . PACKAGE_REPORT . " where " . PACKAGE_REPORT . ".upgrade_companyid = " . TAXI . ".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM " . TAXI . " as taxi JOIN " . COMPANY . " as company ON taxi.taxi_company = company.cid JOIN " . TAXIMAPPING . " as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN " . PEOPLE . " as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A' AND people.login_status = 'S' 	AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A'  AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  " . PASSENGERS_LOG . " WHERE driver_id = people.id AND  `createdate` >='" . $start_time . "' AND  `travel_status` =  '1') AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' $capacity_where AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'  group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time' )";
        //echo  $sql;exit;
        $results = Db::query(Database::SELECT, $sql)->execute()->as_array();
        return $results;
    }
    public function free_availabletaxisearch_list($motor_company = '', $motor_model = '', $company_id = '')
    {
        //print_r($request);
        $additional_fields = "";
        $where_cond        = '';
        //$capacity_where= ($no_passengers) ? " AND taxi_capacity >= $no_passengers" : "";
        if (isset($motor_company) && $motor_company != '') {
            $taxitype_where = " AND taxi_type ='1'";
        } else {
            $taxitype_where = '';
        }
        if (isset($motor_model) && ($motor_model != '')) {
            $taximodel_where = " AND taxi_model ='" . $motor_model . "'";
        } else {
            $taximodel_where = '';
        }
        $current_time      = convert_timezone('now', TIMEZONE);
        $current_date      = explode(' ', $current_time);
        $start_time        = $current_date[0] . ' 00:00:01';
        $end_time          = $current_date[0] . ' 23:59:59';
        //$cuurentdate = date('Y-m-d H:i:s');
        //$enddate = date('Y-m-d').' 23:59:59';
        $company_condition = "";
        if ($company_id != "" && $company_id != 0) {
            $company_condition = "AND taximapping.mapping_companyid = '$company_id' AND people.company_id = '$company_id' AND taxi.taxi_company = '$company_id'";
        }
        $sql     = "SELECT people.id,taxi.taxi_id  ,(select check_package_type from " . PACKAGE_REPORT . " where " . PACKAGE_REPORT . ".upgrade_companyid = " . TAXI . ".taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from " . PACKAGE_REPORT . " where " . PACKAGE_REPORT . ".upgrade_companyid = " . TAXI . ".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM " . TAXI . " as taxi JOIN " . COMPANY . " as company ON taxi.taxi_company = company.cid JOIN " . TAXIMAPPING . " as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN " . PEOPLE . " as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A'         AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A'   and people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  " . PASSENGERS_LOG . " WHERE driver_id = people.id AND  `createdate` >='" . $start_time . "' AND  `travel_status` =  '1' AND booking_from != '2')  
               AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'  group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time')";
        //AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'
        //AND people.notification_setting = '1'
        //echo $sql;exit;
        $results = Db::query(Database::SELECT, $sql)->execute()->as_array();
        return $results;
    }
    public static function taxi_additionalfields()
    {
        $result = DB::select()->from(MANAGEFIELD)->where('field_status', '=', 'A')->order_by('field_order', 'asc')->execute()->as_array();
        return $result;
    }
    public static function create_logs($booking_logid = '', $company_id = '', $log_userid = '', $log_message = '', $log_booking = '')
    {
        $Commonmodel  = Model::factory('Commonmodel');
        //$user_createdby = $_SESSION['userid'];
        $current_time = $Commonmodel->getcompany_all_currenttimestamp($company_id);
        $result       = DB::insert(LOGS, array(
            'booking_logid',
            'log_userid',
            'log_message',
            'log_booking',
            'log_createdate'
        ))->values(array(
            $booking_logid,
            $log_userid,
            $log_message,
            $log_booking,
            $current_time
        ))->execute();
        return $result;
    }
    public function get_taxi_model()
    {
        $sql    = "SELECT model_id,model_name FROM " . MOTORMODEL . " WHERE model_status = 'A'";
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        return $result;
    }
    public function get_active_company_details()
    {
        $sql    = "SELECT ".COMPANY.".cid,".COMPANY.".company_name,".COMPANYINFO.".company_brand_type FROM " . COMPANY . " LEFT JOIN ".COMPANYINFO." ON ".COMPANYINFO.".company_cid = ".COMPANY.".cid WHERE company_status = 'A' order by company_name ASC";
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        return $result;
    }
    /** to get company name from company id **/
    public function get_company_name($cid)
    {
        $sql         = "SELECT company_name FROM " . COMPANY . " WHERE cid = $cid ";
        $result      = Db::query(Database::SELECT, $sql)->execute()->as_array();
        $companyName = (count($result) > 0) ? $result[0]['company_name'] : "";
        return $companyName;
    }
    public function get_driver_sequence_list($trip_id)
    {
        $sql    = "SELECT trip_id,total_drivers FROM " . DRIVER_REQUEST_DETAILS . " WHERE trip_id IN($trip_id) ";
        //echo $sql;exit;
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        if (count($result) > 0) {
            return $result;
        } else {
            return "";
        }
    }
    public function get_selected_driver_sequence_list($driver_ids, $trip_id)
    {
        if ($driver_ids != "") {
            $sql             = "SELECT id,name FROM " . PEOPLE . " WHERE id IN(" . $driver_ids . ")";
            //echo $sql;exit;
            $result          = Db::query(Database::SELECT, $sql)->execute()->as_array();
            $selected_driver = $this->get_selected_driver_list($trip_id);
            //echo $selected_driver;exit;
            if (count($result) > 0) {
                $op = array();
                foreach ($result as $val) {
                    $driver_id        = $val['id'];
                    $driver_name      = $val['name'];
                    $rejected_drivers = $this->get_rejected_drivers_list($trip_id, $driver_id);
                    $color            = "black";
                    if ($driver_id == $selected_driver) {
                        $color = "Green";
                    } elseif ($driver_id == $rejected_drivers) {
                        $color = "Red";
                    }
                    $op[] .= "<span style=color:" . $color . ";>" . $driver_name . "</span><br>";
                }
                if ($op != NULL) {
                    $output = implode(" ", $op);
                }
                return $output;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    public function get_selected_driver_list($trip_id)
    {
        $sql    = "SELECT selected_driver,rejected_timeout_drivers FROM " . DRIVER_REQUEST_DETAILS . " WHERE trip_id=$trip_id ";
        //echo $sql;exit;
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        if (count($result) > 0) {
            return $result[0]['selected_driver'];
        } else {
            return "";
        }
    }
    public function get_rejected_drivers_list($trip_id, $driver_id)
    {
        $sql    = "SELECT rejected_timeout_drivers FROM " . DRIVER_REQUEST_DETAILS . " WHERE trip_id=$trip_id
				AND rejected_timeout_drivers IN($driver_id)";
        //echo $sql;exit;
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        if (count($result) > 0) {
            return $driver_id;
        } else {
            return "";
        }
    }
    public function cancelbooking_logid($data)
    {
        //$updatequery = " UPDATE ".PASSENGERS_LOG." SET driver_id='0',taxi_id='0',travel_status='8' WHERE passengers_log_id ='". $data['pass_logid']."'";
        $sql = "SELECT `travel_status` FROM " . PASSENGERS_LOG . " WHERE passengers_log_id='" . $data['pass_logid'] . "'";
        $selectresult = Db::query(Database::SELECT, $sql)->execute()->as_array();
        if ($selectresult > 0) {
            if ($selectresult[0]['travel_status'] != '5' || $selectresult[0]['travel_status'] != '2') {
                $updatequery  = " UPDATE " . PASSENGERS_LOG . " SET travel_status='8' WHERE passengers_log_id ='" . $data['pass_logid'] . "'";
                $updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
                if (SMS == 1) {
                    $common_model          = Model::factory('commonmodel');
                    $get_passenger_log_det = $this->get_passenger_log_detail($data['pass_logid']);
                    $passenger_phone       = isset($get_passenger_log_det[0]->passenger_phone) ? $get_passenger_log_det[0]->passenger_phone : "";
                    if ($passenger_phone != "") {
                        $to = $passenger_phone;
						//$to = "+919489443922";
						$message_details = $common_model->sms_message('6');
						$message = (count($message_details)) ? $message_details[0]['sms_description'] : '';
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
						$message = str_replace("##booking_key##",$data['pass_logid'],$message);
                        $common_model->send_sms($to, $message);
                    }
                }
                return 1;
            }
            return 0;
        }
        return 0;
    }
    public function get_passenger_log_detail($passengerlog_id = "")
    {
        $sql    = "SELECT " . PASSENGERS . ".phone AS passenger_phone
		FROM  " . PASSENGERS_LOG . "
		JOIN  " . PASSENGERS . " ON (  " . PASSENGERS_LOG . ".`passengers_id` =  " . PASSENGERS . ".`id` )
		WHERE  " . PASSENGERS_LOG . ".`passengers_log_id` =  '$passengerlog_id'";
        //echo $sql;exit;
        $result = Db::query(Database::SELECT, $sql)->as_object()->execute();
        return $result;
    }
    public function send_sms($to = '', $message = '')
    {
        require_once(DOCROOT . 'application/vendor/smsgateway/Services/Twilio.php');
        $sid   = SMS_ACCOUNT_ID; // Your Account SID from www.twilio.com/user/account
        $token = SMS_AUTH_TOKEN; // Your Auth Token from www.twilio.com/user/account
        try {
            $country_res = DB::select()->from(COUNTRY)->where('default', '=', '1')->execute()->as_array();
            $to          = $country_res[0]['telephone_code'] . $to;
            $client      = new Services_Twilio($sid, $token);
            $res         = $client->account->messages->sendMessage(SMS_FROM_NUMBER, // From a valid Twilio number
                $to, // Text this number
                $message);
        }
        catch (Throwable $e) {
        }
    }
    public function check_driver_has_trip_request($driver_id)
    {
        //$sql = "SELECT count(plog.passengers_log_id) as trip_count FROM ".DRIVER_REQUEST_DETAILS." as dr LEFT JOIN ".PASSENGERS_LOG." as plog ON plog.passengers_log_id = dr.trip_id WHERE (dr.status='1' or dr.status='3') and dr.selected_driver='$driver_id' and (plog.travel_status!='1' and plog.travel_status!='4' and plog.travel_status!='8') ORDER BY plog.passengers_log_id DESC";
        //$sql = "SELECT count(id) as trip_count FROM ".DRIVER." WHERE (status='A' or status='B') and driver_id='$driver_id'";
        $two_days_before = date('Y-m-d 00:00:01', strtotime("-2 days"));
        $sql             = "SELECT count(passengers_log_id) as trip_count FROM " . PASSENGERS_LOG . " WHERE (travel_status='2' or travel_status='3' or travel_status='5') and `driver_reply`='A' and driver_id='$driver_id' and dispatch_time >= '$two_days_before'";
        $trip_count      = Db::query(Database::SELECT, $sql)->execute()->get('trip_count');
        if ($trip_count > 0) {
            return $trip_count;
        } else {
            return 0;
        }
    }
    public function currently_driver_has_trip_request($driver_id)
    {
        $two_minutes_before = date('Y-m-d H:i:s', strtotime("-2 minutes"));
        $sql                = "SELECT count(trip_id) as trip_count FROM " . DRIVER_REQUEST_DETAILS . " WHERE status='1' and selected_driver='$driver_id' and createdate >='$two_minutes_before' ORDER BY trip_id DESC";
        $trip_count         = Db::query(Database::SELECT, $sql)->execute()->get('trip_count');
        if ($trip_count > 0) {
            return $trip_count;
        } else {
            return 0;
        }
    }
    public function get_driver_list_with_status($array)
    {
        $company_id = "";
        if ($company_id == '') {
            if (TIMEZONE) {
                $current_time = convert_timezone('now', TIMEZONE);
                $current_date = explode(' ', $current_time);
                $start_time   = $current_date[0] . ' 00:00:01';
                $end_time     = $current_date[0] . ' 23:59:59';
                $date         = $current_date[0] . ' %';
            } else {
                $current_time = date('Y-m-d H:i:s');
                $start_time   = date('Y-m-d') . ' 00:00:01';
                $end_time     = date('Y-m-d') . ' 23:59:59';
                $date         = date('Y-m-d %');
            }
        } else {
            $timezone_base_query = "select time_zone from  company where cid='$company_id' ";
            $timezone_fetch      = Db::query(Database::SELECT, $timezone_base_query)->execute()->as_array();
            if ($timezone_fetch[0]['time_zone'] != '') {
                $current_time = convert_timezone('now', $timezone_fetch[0]['time_zone']);
                $current_date = explode(' ', $current_time);
                $start_time   = $current_date[0] . ' 00:00:01';
                $end_time     = $current_date[0] . ' 23:59:59';
            } else {
                $current_time = date('Y-m-d H:i:s');
                $start_time   = date('Y-m-d') . ' 00:00:01';
                $end_time     = date('Y-m-d') . ' 23:59:59';
            }
        }
        //print_r($array);exit;
        $driver_status = isset($array['driver_status']) ? $array['driver_status'] : "";
        $taxi_company  = isset($array['taxi_company']) ? $array['taxi_company'] : "";
        $taxi_model    = isset($array['taxi_model']) ? $array['taxi_model'] : "";
        $where_cond    = "";
        if ($driver_status == 'A' || $driver_status == 'F') {
            $where_cond .= "AND list.status='$driver_status' AND list.shift_status='IN'";
        } elseif ($driver_status == 'OUT') {
            $where_cond .= "AND list.status='F' AND list.shift_status='$driver_status'";
        }
        $usertype             = $_SESSION['user_type'];
        $company_id           = $_SESSION['company_id'];
        $commonmodel          = Model::factory('commonmodel');
        $company_current_time = $commonmodel->getcompany_all_currenttimestamp($company_id);
        $company_where        = "";
        if ($usertype != 'A') {
            $company_where = "AND people.company_id =  '" . $company_id . "'";
        } else if ($usertype == 'A' && $taxi_company != "" && $taxi_company != 0) {
            $company_where = "AND people.company_id =  '" . $taxi_company . "'";
        }
        $taxi_join       = '';
        $taxi_model_cond = '';
        $taxi_join = "JOIN taxi_driver_mapping ON taxi_driver_mapping.mapping_driverid = list.driver_id JOIN taxi ON taxi.taxi_id = taxi_driver_mapping.mapping_taxiid join motor_model on motor_model.model_id = taxi.taxi_model";
        if ($taxi_model != '') {
            $taxi_model_cond = " AND taxi.taxi_model='" . $taxi_model . "'  AND  taxi_driver_mapping.mapping_enddate >= '$current_time' ";
        }
        $query  = "SELECT people.id as driver_id,people.name, list.status AS driver_status, list.update_date as update_date,list.latitude as latitude, list.longitude as longitude,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference,motor_model.model_name FROM (SELECT * , (TIME_TO_SEC( TIMEDIFF('" . $company_current_time . "', driver.update_date) )) AS updatetime_difference FROM driver) AS list JOIN people ON people.id = list.driver_id $taxi_join WHERE people.user_type =  'D' AND people.status =  'A' $where_cond $company_where $taxi_model_cond AND updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "' group by driver_id";
        //company id checked for users who are not admin
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    /** to get dispatcher side trip list from passenger log table **/
    
    /** to get dispatcher side trip list from passenger log table **/
	public function dispatcher_booking_list($array)
	{
		//print_r($array);exit;
		//$travel_status=$array['travel_status'].",8";
		$travel_status=$array['travel_status'];
		$driver_reply_cancel=$array['driver_reply_cancel'];
		$manage_status=$array['manage_status'];
		$taxi_company=$array['taxi_company'];
		
		$search_txt=$array['search_txt'];
		$search_location=$array['search_location'];
		$filter_date=$array['filter_date'];
		$to_date=$array['to_date'];
		$booking_filter=$array['booking_filter'];

		$fromdate = $filter_date.':00';
		$todate = $to_date.':00';
		
		$date_where = '';
		$pickupdrop_where = '';
		$status_query = '';
		
		if($search_txt !=''){
			/*$pickupdrop_where  = " AND (current_location LIKE  '%$search_txt%' ";
			$pickupdrop_where .= " or drop_location LIKE '%$search_txt%' escape '!'  ";
			$pickupdrop_where .= " or ".PASSENGERS.".name LIKE '%$search_txt%' escape '!' ";
			$pickupdrop_where .= " or passengers_log_id LIKE '%$search_txt%' escape '!' ) "; */
			$pickupdrop_where  = " AND ( ".PASSENGERS.".name LIKE '%$search_txt%' escape '!' or ".PEOPLE.".name  LIKE '%$search_txt%' escape '!' or ".PEOPLE.".phone LIKE '%$search_txt%' escape '!' or ".COMPANY.".company_name LIKE '%$search_txt%' or ".PASSENGERS.".phone LIKE '%$search_txt%' escape '!' ";
			$pickupdrop_where .= " or ".PASSENGERS_LOG.".passengers_log_id = '$search_txt' ";
			$pickupdrop_where .= " or ".PASSENGERS_LOG.".RoutId = '$search_txt' ";
			$pickupdrop_where .= " or taxi.taxi_no = '$search_txt' ) ";
		}
		
		if($search_location != '') {
			$pickupdrop_where .= " AND (".PASSENGERS_LOG.".current_location LIKE  '%$search_location%' ";
			$pickupdrop_where .= " or ".PASSENGERS_LOG.".drop_location LIKE '%$search_location%' ) ";
		}
		
		$date=date('Y-m-d',strtotime($array['current_time']));
		
		$currentdate = $date.' 00:00:00';
		$enddate = $date.' 23:59:59';
		$company_id = $_SESSION['company_id'];
		
		//$date_query="AND ( pickup_time between '$currentdate' and  '$enddate' )";
		//to get two days before from current date
		$two_days_before = date('Y-m-d 00:00:00', strtotime($date .' 0 day'));

		if($filter_date !='' && $to_date !='')	
		{	
			//$date_where = " AND ( pickup_time between '$currentdate' and  '$enddate' ) ";
			//$date_where = " AND ( plog.pickup_time between '$currentdate' and  '$enddate' ) ";
			$date_where = " AND ( (".PASSENGERS_LOG.".pickup_time >= '$fromdate' and ".PASSENGERS_LOG.".pickup_time <= '$todate') OR (".PASSENGERS_LOG.".actual_pickup_time >= '$fromdate' and ".PASSENGERS_LOG.".actual_pickup_time <= '$todate') ) ";
		} else if($filter_date !='' || $to_date !='') {
			$datesearch = ($to_date != '') ? $to_date : $filter_date ;
			$dateArr = explode(" ",$datesearch);
			$staDate = $dateArr[0].' 00:00:01';
			$endDate = $dateArr[0].' 23:59:59';
			$date_where = " AND ( (".PASSENGERS_LOG.".pickup_time >= '$staDate' and ".PASSENGERS_LOG.".pickup_time <= '$endDate') OR (".PASSENGERS_LOG.".actual_pickup_time >= '$staDate' and ".PASSENGERS_LOG.".actual_pickup_time <= '$endDate') ) ";
		} else {
			$staDate = date('Y-m-d 00:00:00', strtotime($date .' 0 day'));
			$endDate = date('Y-m-d 23:59:59', strtotime($date .' 0 day'));
			$date_where = " AND ( (".PASSENGERS_LOG.".pickup_time >= '$staDate' and ".PASSENGERS_LOG.".pickup_time <= '$endDate') OR (".PASSENGERS_LOG.".actual_pickup_time >= '$staDate' and ".PASSENGERS_LOG.".actual_pickup_time <= '$endDate') ) ";
		
		}
		
		//echo $travel_status."--".$driver_reply_cancel;exit;
		
		//$company_id = $_SESSION['company_id'];
		$company_id = 0;

		$company_query="";
		if($company_id!=0){
			$company_query=" AND ".PASSENGERS_LOG.".company_id='$company_id'";
		}

		$company_where="";
		if($taxi_company!="" && $taxi_company!=0){
			$company_where=" AND ".PASSENGERS_LOG.".company_id='$taxi_company'";
		}
		
		
		$taxi_model = isset($array['taxi_model'])?$array['taxi_model']:"";
		if($taxi_model != '') {
			$company_where =" AND ".PASSENGERS_LOG.".taxi_modelid='".$taxi_model."'";
		}
		
		//$date_query="AND ( pickup_time between '$currentdate' and  '$enddate' )";
		$two_days_before = date('Y-m-d 00:00:00', strtotime($date .' 0 day'));
		//echo $manage_status; exit;
		$date_query="";
		if($manage_status==0){
			//$date_query="AND pickup_time >= '$two_days_before'";
		}
		
		$status_query="";
		if($travel_status!="" && $driver_reply_cancel==""){
			$status_query="AND driver_reply NOT IN('C','R') AND travel_status IN(".$travel_status.") AND travel_status NOT IN('8')";
		}else{
			//$travel_status = '0, 6, 7, 10, 9, 3, 2, 1, 5, 8, 4';
			$status_query="AND travel_status IN(".$travel_status.")";
		}
		 //echo $status_query; 
		//$query = "SELECT company_name, passengers_log_id as pass_logid, passenger_name, driver_name, driver_phone, driver_reachable_mobile as reachable_mobile, drivers_count as total_drivers, approx_fare as fare, approx_distance as distance,pickup_time,driver_id,pickup_latitude,pickup_longitude,drop_latitude,drop_longitude,no_passengers,current_location,drop_location,dispatch_time,travel_status,driver_reply FROM ".PASSENGERS_LOG." where bookby='2' $date_query $status_query $company_where order by passengers_log_id desc";
		
		// Here count and group by included for share ride module
		 $query  = "SELECT " . PASSENGERS_LOG . ".booking_from," . PASSENGERS_LOG . ".bookby," . PASSENGERS_LOG . ".bookingtype," . PASSENGERS_LOG . ".company_id," . PASSENGERS_LOG . ".notes_driver as notes," . COMPANY . ".company_name as company_name, passengers_log_id as pass_logid, " . PASSENGERS . ".name as passenger_name, " . PASSENGERS . ".phone as passenger_phone, " . PASSENGERS . ".country_code as passenger_country_code, " . PEOPLE . ".name as driver_name, " . PEOPLE . ".phone as driver_phone, " . PEOPLE . ".phone as reachable_mobile, " . MOTORMODEL . ".model_name as model_name, driver_id as total_drivers, approx_fare as fare, approx_distance as distance,createdate,pickup_time,IF(" . PASSENGERS_LOG . ".actual_pickup_time = '0000-00-00 00:00:00','-'," . PASSENGERS_LOG . ".actual_pickup_time) as act_pickuptime,driver_id,pickup_latitude,pickup_longitude,drop_latitude,drop_longitude,no_passengers,current_location,drop_location,dispatch_time,travel_status,driver_reply FROM " . PASSENGERS_LOG . "  
		LEFT JOIN  " . PEOPLE . " ON (  " . PEOPLE . ".`id` =  " . PASSENGERS_LOG . ".`driver_id` )
		LEFT JOIN  " . PASSENGERS . " ON (  " . PASSENGERS . ".`id` =  " . PASSENGERS_LOG . ".`passengers_id` )
		LEFT JOIN  " . MOTORMODEL . " ON (  " . MOTORMODEL . ".`model_id` =  " . PASSENGERS_LOG . ".`taxi_modelid` )
		LEFT JOIN  " . COMPANY . " ON (  " . COMPANY . ".`cid` =  " . PASSENGERS_LOG . ".`company_id` ) 
		LEFT JOIN  " . TAXI . " ON (  " . PASSENGERS_LOG . ".`taxi_id` =  " . TAXI . ".`taxi_id` ) 
		where
		bookby in('1','2','3') $date_query $date_where $status_query $company_where group by ".PASSENGERS_LOG.".passengers_log_id order by passengers_log_id desc";

		$result = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();	
	   return $result;
	}

    
    public function dispatcher_booking_list_old($array)
    {
        //print_r($array);exit;
        //$travel_status=$array['travel_status'].",8";
        $travel_status       = $array['travel_status'];
        $driver_reply_cancel = $array['driver_reply_cancel'];
        $manage_status       = $array['manage_status'];
        $taxi_company        = $array['taxi_company'];
        //echo $travel_status."--".$driver_reply_cancel;exit;
        $date                = date('Y-m-d', strtotime($array['current_time']));
        $currentdate         = $date . ' 00:00:00';
        $enddate             = $date . ' 23:59:59';
        //$company_id = $_SESSION['company_id'];
        $company_id          = 0;
        $company_query       = "";
        if ($company_id != 0) {
            $company_query = "AND " . PASSENGERS_LOG . ".company_id='$company_id'";
        }
        $company_where = "";
        if ($taxi_company != "" && $taxi_company != 0) {
            $company_where = "AND " . PASSENGERS_LOG . ".company_id='$taxi_company'";
        }
        //$date_query="AND ( pickup_time between '$currentdate' and  '$enddate' )";
        $two_days_before = date('Y-m-d 00:00:00', strtotime($date . ' 0 day'));
        $date_query      = "";
        if ($manage_status == 0) {
            $date_query = "AND pickup_time >= '$two_days_before'";
        }
        $status_query = "";
        if ($travel_status != "" && $driver_reply_cancel == "") {
            $status_query = "AND driver_reply NOT IN('C','R') AND travel_status IN(" . $travel_status . ") AND travel_status NOT IN('8')";
        } else {
            $status_query = "AND travel_status IN(" . $travel_status . ")";
        }
        //$query = "SELECT company_name, passengers_log_id as pass_logid, passenger_name, driver_name, driver_phone, driver_reachable_mobile as reachable_mobile, drivers_count as total_drivers, approx_fare as fare, approx_distance as distance,pickup_time,driver_id,pickup_latitude,pickup_longitude,drop_latitude,drop_longitude,no_passengers,current_location,drop_location,dispatch_time,travel_status,driver_reply FROM ".PASSENGERS_LOG." where bookby='2' $date_query $status_query $company_where order by passengers_log_id desc";
        $query  = "SELECT " . PASSENGERS_LOG . ".bookby," . PASSENGERS_LOG . ".bookingtype," . PASSENGERS_LOG . ".company_id," . PASSENGERS_LOG . ".notes_driver as notes," . COMPANY . ".company_name as company_name, passengers_log_id as pass_logid, " . PASSENGERS . ".name as passenger_name, " . PASSENGERS . ".phone as passenger_phone, " . PASSENGERS . ".country_code as passenger_country_code, " . PEOPLE . ".name as driver_name, " . PEOPLE . ".phone as driver_phone, " . PEOPLE . ".phone as reachable_mobile, " . MOTORMODEL . ".model_name as model_name, driver_id as total_drivers, approx_fare as fare, approx_distance as distance,createdate,pickup_time,IF(" . PASSENGERS_LOG . ".actual_pickup_time = '0000-00-00 00:00:00','-'," . PASSENGERS_LOG . ".actual_pickup_time) as act_pickuptime,driver_id,pickup_latitude,pickup_longitude,drop_latitude,drop_longitude,no_passengers,current_location,drop_location,dispatch_time,travel_status,driver_reply FROM " . PASSENGERS_LOG . "  
		LEFT JOIN  " . PEOPLE . " ON (  " . PEOPLE . ".`id` =  " . PASSENGERS_LOG . ".`driver_id` )
		LEFT JOIN  " . PASSENGERS . " ON (  " . PASSENGERS . ".`id` =  " . PASSENGERS_LOG . ".`passengers_id` )
		LEFT JOIN  " . MOTORMODEL . " ON (  " . MOTORMODEL . ".`model_id` =  " . PASSENGERS_LOG . ".`taxi_modelid` )
		LEFT JOIN  " . COMPANY . " ON (  " . COMPANY . ".`cid` =  " . PASSENGERS_LOG . ".`company_id` ) where
		bookby in('1','2','3') $date_query $status_query $company_where group by ".PASSENGERS_LOG.".passengers_log_id order by passengers_log_id desc";
        //echo $query;
        //exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public function dispatcher_booking_transaction($trip_id)
    {
        $query  = "SELECT distance, fare FROM " . TRANS . " where passengers_log_id=$trip_id ";
        //echo $query;exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    public function check_driver_not_updated($driver_id)
    {
        //$sql = "SELECT count(id) as total FROM ".DRIVER." WHERE driver_id='$driver_id' and (TIME_TO_SEC(TIMEDIFF('$company_timestamp',update_date))) > 15";
        $sql   = "SELECT update_date  FROM " . DRIVER . " WHERE driver_id='$driver_id'";
        $total = Db::query(Database::SELECT, $sql)->execute()->as_array();
        return isset($total[0]['update_date']) ? $total[0]['update_date'] : '0';
    }
    public function check_new_request_tripid($taxi_id = null, $company_id = null, $trip_id, $driver_id, $company_all_currenttimestamp, $driver_reply, $operator_id = 0)
    {
        $datetime    = explode(' ', $company_all_currenttimestamp);
        $currentdate = $datetime[0] . ' 00:00:01';
        //$sql = "SELECT trip_id,available_drivers FROM ".DRIVER_REQUEST_DETAILS." WHERE status = '0' and FIND_IN_SET('$driver_id',available_drivers)  and NOT FIND_IN_SET('$driver_id', rejected_timeout_drivers) and createdate >= '$currentdate' ORDER BY trip_id DESC LIMIT 0 , 1";
        /*$sql = "SELECT trip_id,available_drivers,total_drivers,rejected_timeout_drivers FROM ".DRIVER_REQUEST_DETAILS."
        WHERE trip_id='$trip_id'
        and NOT FIND_IN_SET('$driver_id', rejected_timeout_drivers)
        and createdate >= '$currentdate'		
        ORDER BY trip_id DESC
        LIMIT 0 , 1";*/
        $sql         = "SELECT trip_id,available_drivers,total_drivers,rejected_timeout_drivers FROM " . DRIVER_REQUEST_DETAILS . "
		WHERE trip_id='$trip_id'
		and selected_driver='$driver_id'
		and createdate >= '$currentdate'		
		ORDER BY trip_id DESC
		LIMIT 0 , 1";
        $result      = Db::query(Database::SELECT, $sql)->execute()->as_array();
        //print_r($result);exit;
        //return $result;
        if (count($result) > 0) {
            //echo "1";
            if ($driver_reply != 'C') {
                //echo "here";exit;
                $available_drivers = $result[0]['available_drivers'];
                $exp_drivers       = explode(',', $available_drivers);
                //print_r($exp_drivers);exit;
                $s_array           = array();
                $first_driver      = isset($exp_drivers[0]) ? $exp_drivers[0] : 0;
                for ($i = 1; $i < count($exp_drivers); $i++) {
                    $s_array[]   = $exp_drivers[$i];
                    $temp_driver = isset($exp_drivers[1]) ? $exp_drivers[1] : $exp_drivers[0];
                }
                if ($s_array != "") {
                    $s_driver = implode(',', $s_array);
                }
                $prev_rejected_timeout_drivers = isset($result[0]['rejected_timeout_drivers']) ? $result[0]['rejected_timeout_drivers'] : "";
                if ($prev_rejected_timeout_drivers != "") {
                    $rejected_timeout_drivers = $prev_rejected_timeout_drivers . ',' . $driver_id;
                } else {
                    $rejected_timeout_drivers = $driver_id;
                }
                //to get the usertypes
                if ($operator_id != 0) {
                    $sql_query      = "SELECT user_type FROM " . PEOPLE . " WHERE id = " . $operator_id;
                    $user_type_dets = Db::query(Database::SELECT, $sql_query)->execute()->as_array();
                }
                $temp_driver       = isset($temp_driver) ? $temp_driver : "";
                $update_trip_array = array(
                    "available_drivers" => $s_driver,
                    "selected_driver" => $temp_driver,
                    "status" => "0",
                    "rejected_timeout_drivers" => $rejected_timeout_drivers
                );
                $update_result     = $this->update_table(DRIVER_REQUEST_DETAILS, $update_trip_array, 'trip_id', $trip_id);
                //to update driver request and passenger log if selected driver is empty
                if ($temp_driver == '') {
                    $update_trip_array_one = array(
                        "status" => "4"
                    );
                    $update_result         = $this->update_table(DRIVER_REQUEST_DETAILS, $update_trip_array_one, 'trip_id', $trip_id);
                    //condition checked to null the company id and name only in admin side
                    if ($operator_id != 0 && $user_type_dets[0]['user_type'] == 'A') {
                        $update_log_array_driver = array(
                            "driver_id" => "0",
                            "taxi_id" => "0",
                            "company_id" => "0"
                        );
                    } else {
                        $update_log_array_driver = array(
                            "driver_id" => "0",
                            "taxi_id" => "0"
                        );
                    }
                    $this->update_table(PASSENGERS_LOG, $update_log_array_driver, 'passengers_log_id', $trip_id);
                }
                $driver_details         = $this->get_driver_taxi($temp_driver);
                //print_r($driver_details);exit;
                $drivertaxi             = isset($driver_details[0]['mapping_taxiid']) ? $driver_details[0]['mapping_taxiid'] : $taxi_id;
                $drivercompany          = isset($driver_details[0]['mapping_companyid']) ? $driver_details[0]['mapping_companyid'] : $company_id;
                $driver_profile_details = array();
                if ($temp_driver != '') {
                    //to get the driver profile details and company name
                    $sql                    = "SELECT name,phone FROM " . PEOPLE . " WHERE id = " . $temp_driver;
                    $driver_profile_details = Db::query(Database::SELECT, $sql)->execute()->as_array();
                }
                $driver_name         = (isset($driver_profile_details[0]['name'])) ? $driver_profile_details[0]['name'] : "";
                $driver_phone        = (isset($driver_profile_details[0]['phone'])) ? $driver_profile_details[0]['phone'] : "";
                $driver_reachable_no = (isset($driver_profile_details[0]['phone'])) ? $driver_profile_details[0]['phone'] : "";
                //company Name
                $companyDets         = array();
                if ($drivercompany != '') {
                    $sql         = "SELECT company_name FROM " . COMPANY . " WHERE cid = $drivercompany ";
                    $companyDets = Db::query(Database::SELECT, $sql)->execute()->as_array();
                }
                $companyName = (count($companyDets) > 0) ? $companyDets[0]['company_name'] : "";
                //to update driver,passenger and company details
                /*if($driver_reply=="C"){
                $update_log_array=array("driver_id"=>$temp_driver,"taxi_id"=>$drivertaxi,"company_id"=>$drivercompany,"driver_reply"=>"C");
                }else{ */
                //condition checked to update the company id and name only in admin side
                if ($operator_id != 0 && $user_type_dets[0]['user_type'] == 'A') {
                    $update_log_array = array(
                        "driver_id" => $temp_driver,
                        "taxi_id" => $drivertaxi,
                        "company_id" => $drivercompany
                    );
                } else {
                    $update_log_array = array(
                        "driver_id" => $temp_driver,
                        "taxi_id" => $drivertaxi
                    );
                }
                //}
                $pass_log_update          = $this->update_table(PASSENGERS_LOG, $update_log_array, 'passengers_log_id', $trip_id);
                $update_driver_array      = array(
                    "status" => 'B'
                );
                $driver_tbl_update        = $this->update_table(DRIVER, $update_driver_array, 'driver_id', $driver_id);
                //$driver_status = $this->get_request_status($trip_id);
                $available_drivers        = explode(',', $result[0]['total_drivers']);
                $rejected_timeout_drivers = explode(',', $rejected_timeout_drivers);
                $comp_result              = array_diff($available_drivers, $rejected_timeout_drivers);
                //echo count($comp_result);exit;
                if (count($comp_result) == 0) {
                    $update_trip_array_one = array(
                        "status" => "4"
                    );
                    $update_result         = $this->update_table(DRIVER_REQUEST_DETAILS, $update_trip_array_one, 'trip_id', $trip_id);
                    //condition checked to null the company id and name only in admin side
                    if ($operator_id != 0 && $user_type_dets[0]['user_type'] == 'A') {
                        $update_log_array_driver = array(
                            "driver_id" => "0",
                            "taxi_id" => "0",
                            "company_id" => "0"
                        );
                    } else {
                        $update_log_array_driver = array(
                            "driver_id" => "0",
                            "taxi_id" => "0"
                        );
                    }
                    $result = $this->update_table(PASSENGERS_LOG, $update_log_array_driver, 'passengers_log_id', $trip_id);
                }
            } else {
                //echo "2";exit;
                $drivertaxi    = $taxi_id; //isset($driver_details[0]['mapping_taxiid'])?$driver_details[0]['mapping_taxiid']:"";
                $drivercompany = $company_id; //isset($driver_details[0]['mapping_companyid'])?$driver_details[0]['mapping_companyid']:"";
                if ($driver_reply == "C") {
                    $update_log_array = array(
                        "driver_id" => $temp_driver,
                        "taxi_id" => $drivertaxi,
                        "driver_reply" => "C"
                    );
                } else {
                    $update_log_array = array(
                        "driver_id" => $temp_driver,
                        "taxi_id" => $drivertaxi
                    );
                }
            }
        } else {
            $trip_id = 0;
        }
        return "";
    }
    //Common Function for updation
    public function update_table($table, $arr, $cond1, $cond2)
    {
        $result = DB::update($table)->set($arr)->where($cond1, "=", $cond2)->execute();
        return $result;
    }
    public function get_driver_taxi($driver_id = "")
    {
        //$sql = "SELECT driver_reply,time_to_reach_passen FROM ".PASSENGERS_LOG." WHERE `passengers_log_id` = '".$passenger_log_id."'";
        $sql    = "SELECT `mapping_taxiid`,`mapping_companyid`  FROM " . TAXIMAPPING . " WHERE `mapping_driverid` = '" . $driver_id . "' and `mapping_status`='A'";
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        return isset($result) ? $result : '0';
    }
    public static function model_details()
    {
        $company_id = $_SESSION['company_id'];
        if (FARE_SETTINGS == 2 && !empty($company_id)) {
            $model_base_query = "select distinct " . MOTORMODEL . ".model_id," . COMPANY_MODEL_FARE . ".model_name from " . COMPANY_MODEL_FARE . " left join " . MOTORMODEL . " on " . MOTORMODEL . ".model_id=" . COMPANY_MODEL_FARE . ".model_id where " . COMPANY_MODEL_FARE . ".company_cid='$company_id' and " . COMPANY_MODEL_FARE . ".fare_status='A'";
            $result           = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
            return $result;
        } else {
            $result = DB::select('model_id', 'model_name')->from(MOTORMODEL)->where('model_status', '=', 'A')->order_by('model_name', 'ASC')->execute()->as_array();
            return $result;
        }
    }
    public function updatebooking_logid($data)
    {
        $company_id       = $_SESSION['company_id'];
        $common_model     = Model::factory('commonmodel');
        $current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
        //$current_datetime =	date('Y-m-d H:i:s');	
        $duration         = '+1 minutes';
        $current_datetime = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));
        $updatequery      = " UPDATE " . PASSENGERS_LOG . " SET driver_id='" . $data['driver_id'] . "',taxi_id='" . $data['taxi_id'] . "',company_id='" . $data['company_id'] . "',travel_status='7',driver_reply='',msg_status='U',comments='',dispatch_time='$current_datetime' wHERE passengers_log_id ='" . $data['pass_logid'] . "'";
        $updateresult     = Db::query(Database::UPDATE, $updatequery)->execute();
        return 1;
    }
    public function get_driver_profile_details($id = "")
    {
        $sql = "SELECT name FROM " . PEOPLE . " WHERE id = '$id' ";
        return Db::query(Database::SELECT, $sql)->execute()->as_array();
    }
    //function to get dispatch settings
    public function dispatch_settings($companyid)
    {
        $company_dispatch = DB::select('labelname')->from(TBLALGORITHM)->where('alg_company_id', '=', $companyid)->limit(1)->execute()->as_array();
        return $company_dispatch;
    }
    public function directdispatch($pass_logid, $auto_send_request = 0, $company_id = 0)
    {
        $api        = Model::factory('api');
        $company_id = isset($_SESSION['company_id']) ? $_SESSION['company_id'] : $company_id;
        /** Auto Dispatch **/
        if ($pass_logid != '') {
            $booking_details   = $this->get_bookingdetails($pass_logid, $company_id);
            $latitude          = $booking_details[0]["pickup_latitude"];
            $longitude         = $booking_details[0]["pickup_longitude"];
            $miles             = '';
            $no_passengers     = $booking_details[0]["no_passengers"];
            $taxi_fare_km      = $booking_details[0]["min_fare"];
            $taxi_model        = $booking_details[0]["taxi_modelid"];
            $taxi_type         = '';
            $maximum_luggage   = $booking_details[0]["luggage"];
            $company_id        = $booking_details[0]["company_id"];
            $cityname          = '';
            $search_driver     = '';
            $driver_details    = $this->search_driver_location($latitude, $longitude, $miles, $no_passengers, $_REQUEST, $taxi_fare_km, $taxi_model, $taxi_type, $maximum_luggage, $cityname, $pass_logid, $company_id, $search_driver);
            $nearest_driver    = '';
            $a                 = 1;
            $temp              = '10000';
            $prev_min_distance = '10000~0';
            $taxi_id           = '';
            $temp_driver       = 0;
            $nearest_key       = 0;
            $prev_key          = 0;
            $driver_list       = "";
            $available_drivers = "";
            $nearest_driver_id = $nearest_taxi_id = "";
            $total_count       = count($driver_details);
            if (count($driver_details) > 0) {
                $nearest_count      = 1;
                /*Nearest driver calculation */
                $nearest_driver_ids = array();
                foreach ($driver_details as $key => $value) {
                    $prev_min_distance = explode('~', $prev_min_distance);
                    $prev_key          = $prev_min_distance[1];
                    $prev_min_distance = $prev_min_distance[0];
                    //to check the driver has trip already
                    $driver_has_trip   = $this->check_driver_has_trip_request($value['driver_id']);
                    $current_request   = $this->currently_driver_has_trip_request($value['driver_id']);
                    if ($driver_has_trip == 0 && $current_request == 0) {
                        $nearest_driver_ids[] = $value['driver_id'];
                        if ($nearest_count == 1) {
                            $nearest_driver_id = isset($driver_details[$key]['driver_id']) ? $driver_details[$key]['driver_id'] : 0;
                            $nearest_taxi_id   = isset($driver_details[$key]['taxi_id']) ? $driver_details[$key]['taxi_id'] : 0;
                        }
                        $nearest_count++;
                    }
                    //checking with previous minimum
                    if ($value['distance'] < $prev_min_distance) {
                        //new minimum distance
                        $nearest_key       = $key;
                        $prev_min_distance = $value['distance'] . '~' . $key;
                    } else {
                        //previous minimum
                        $nearest_key       = $prev_key;
                        $prev_min_distance = $prev_min_distance . '~' . $prev_key;
                    }
                }
                $drivers_count = count($nearest_driver_ids);
                if ($nearest_driver_ids != NULL) {
                    $nearest_driver_ids = implode(",", $nearest_driver_ids);
                }
                $miles_or_km       = round(($prev_min_distance), 2);
                $driver_away_in_km = (ceil($miles_or_km * 100) / 100);
                $company_id        = $_SESSION['company_id'];
                $common_model      = Model::factory('commonmodel');
                $current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
                $duration          = '+1 minutes';
                $current_datetime  = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));
                /****** Estimated Arival *************/
                $taxi_speed        = $api->get_taxi_speed($nearest_taxi_id);
                $estimated_time    = $api->estimated_time($driver_away_in_km, $taxi_speed);
                /**************************************/
                //to get nearest driver's company id
                if (!empty($nearest_driver_id)) {
                    $sql                    = "SELECT company_id,name,phone FROM " . PEOPLE . " WHERE id = " . $nearest_driver_id;
                    $driver_company_details = Db::query(Database::SELECT, $sql)->execute()->as_array();
                }
                $companyName         = (isset($driver_company_details[0]['name'])) ? $this->get_company_name($driver_company_details[0]['company_id']) : "";
                $companyid           = (isset($driver_company_details[0]['company_id'])) ? $driver_company_details[0]['company_id'] : "";
                $driver_name         = (isset($driver_company_details[0]['name'])) ? $driver_company_details[0]['name'] : "";
                $driver_phone        = (isset($driver_company_details[0]['phone'])) ? $driver_company_details[0]['phone'] : "";
                $driver_reachable_no = (isset($driver_company_details[0]['phone'])) ? $driver_company_details[0]['phone'] : "";
                //condition checked to update the company id and name only in admin side
                if ($_SESSION['user_type'] == 'A') {
                    $updatequery = " UPDATE " . PASSENGERS_LOG . " SET driver_id='" . $nearest_driver_id . "',taxi_id='" . $nearest_taxi_id . "',company_id='" . $companyid . "',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime',auto_send_request = '".$auto_send_request."' wHERE passengers_log_id ='" . $pass_logid . "'";
                } else {
                    $updatequery = " UPDATE " . PASSENGERS_LOG . " SET driver_id='" . $nearest_driver_id . "',taxi_id='" . $nearest_taxi_id . "',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime',auto_send_request = '".$auto_send_request."' wHERE passengers_log_id ='" . $pass_logid . "'";
                }
                //exit;
                $updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
                /* Create Log */
                $company_id   = $_SESSION['company_id'];
                $userid       = $_SESSION['userid'];
                $log_message  = __('log_message_dispatched');
                $log_message  = str_replace("PASS_LOG_ID", $pass_logid, $log_message);
                $log_booking  = __('log_booking_dispatched');
                $log_booking  = str_replace("DRIVERNAME", $driver_details[0]['name'], $log_booking);
                $log_status   = $this->create_logs($pass_logid, $company_id, $userid, $log_message, $log_booking);
?>

				<script type="text/javascript">load_logcontent();</script>


				<?php
                $exist_request = $this->exist_request($pass_logid);
                if ($exist_request == 1) {
                    $delete_exist_request = $common_model->delete(DRIVER_REQUEST_DETAILS, 'trip_id', $pass_logid);
                }
                /***** Insert the druiver details to driver request table ************/
                $nearest_driver_ids = (!empty($nearest_driver_ids)) ? $nearest_driver_ids : '';
                $insert_array       = array(
                    "trip_id" => $pass_logid,
                    "available_drivers" => $nearest_driver_ids,
                    "total_drivers" => $nearest_driver_ids,
                    "selected_driver" => $nearest_driver_id,
                    "status" => '0',
                    "rejected_timeout_drivers" => "",
                    "createdate" => $current_datetime
                );
                //print_r($insert_array);exit;
                //Inserting to Transaction Table
                $transaction        = $common_model->insert(DRIVER_REQUEST_DETAILS, $insert_array);
                $detail             = array(
                    "passenger_tripid" => $pass_logid,
                    "notification_time" => ""
                );
                $msg                = array(
                    "message" => __('api_request_confirmed_passenger'),
                    "status" => 1,
                    "detail" => $detail
                );
            }
        }
        /** Auto Dispatch **/
        $req_result['send_mail']  = 'N';
        $req_result['pass_logid'] = $pass_logid;
        return $req_result;
    }
    
	public function checkPassengerStatus($trip_id,$company_id)
	{
		$pending_trip_count = $in_trip = 0;
		$sql = "SELECT count(passengers_log_id) as total,passengers_id FROM ".PASSENGERS_LOG." WHERE passengers_id IN (select passengers_id from ".PASSENGERS_LOG." where passengers_log_id = '" . $trip_id . "') and travel_status = '5' and driver_reply = 'A'";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		if(count($result) > 0) {
			$passengers_id = $result[0]["passengers_id"];
			if($passengers_id == "") {
				$passenger_sql = "SELECT passengers_id FROM ".PASSENGERS_LOG." WHERE passengers_log_id = '" . $trip_id . "'";
				$passenger_result = Db::query(Database::SELECT, $passenger_sql)->execute()->as_array();
				$passengers_id = $passenger_result[0]['passengers_id'];
			}
			$pending_trip_count = $result[0]["total"];
			$common_model = Model::factory('commonmodel');
			$start_time = $common_model->getcompany_all_currenttimestamp($company_id);
			$sqltrip = "SELECT count(passengers_log_id) as total FROM ".PASSENGERS_LOG." WHERE `pickup_time` >= '".$start_time."' and `passengers_id` = '".$passengers_id."' and `driver_reply` = 'A' and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3')";
			$result_trip = Db::query(Database::SELECT, $sqltrip)->execute()->as_array();
			if(isset($result_trip[0]["total"])) {
				$in_trip = $result_trip[0]["total"];
			}
		}
		return json_encode(array("pending_payment_count" => $pending_trip_count,"in_trip" => $in_trip));
	}
    
    public function getpickupTimezone($lat,$lng)
	{
		try{
			$url = 'https://maps.googleapis.com/maps/api/timezone/json?location='.trim($lat).','.trim($lng).'&timestamp=1&key='.GOOGLE_TIMEZONE_API_KEY;  
			$json = @file_get_contents($url);
			$data=json_decode($json);
			$status = ($data) ? $data->status : 0;
			if($status=="OK")
				return ($data) ? $data->timeZoneId : TIMEZONE;
			else
				return TIMEZONE;
		} catch (Kohana_Exception $e) {
			return TIMEZONE;
		}
	}
	
	public function getPassengerLocation()
	{
		$company_id        = $_SESSION['company_id'];
		$common_model      = Model::factory('commonmodel');
		$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
		$duration          = '+1 day';
		$minus_duration          = '-1 day';
		$start_date  = date('Y-m-d H:i:s', strtotime($minus_duration, strtotime($current_datetime)));
		$end_date  = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));
                
                
		 $query  = "SELECT " . PASSENGERS . ".name as name,CONCAT(" . PASSENGERS . ".country_code," . PASSENGERS . ".phone) as mobile_number, pickup_latitude,passengers_log_id,travel_status,pickup_longitude, ". DRIVER . ".latitude,". DRIVER . ".longitude, ". PASSENGERS . ".latitude as passenger_lat,". PASSENGERS . ".longitude as passenger_long FROM " . PASSENGERS_LOG . "  
		
		JOIN  " . PASSENGERS . " ON (  " . PASSENGERS . ".`id` =  " . PASSENGERS_LOG . ".`passengers_id` )
        LEFT JOIN  " . DRIVER . " ON (  " . DRIVER . ".`driver_id` =  " . PASSENGERS_LOG . ".`driver_id` )
	
		 where (createdate between '$start_date' and  '$end_date' )  and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '0') group by " . PASSENGERS . ".`id` 
		 order by passengers_log_id desc";


        //echo $query;
        //exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        
        
        return $result;
	}
}
?>
