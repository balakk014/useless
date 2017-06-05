<?php 

defined('SYSPATH') or die('No direct script access.');
/**
 * Contains commonly used queries

 * @author     Ndot Team
 * @copyright  (c) 2008-2011 Ndot Team
 * @license    http://ndot.in/license

 */
 
class Model_Managemodel extends Model 
{
	public function __construct()
    {
		$this->session = Session::instance();
    }
    
	public function addcountry_validate($arr)
	{
		$validate=Validation::factory($arr)
					->rule('country','not_empty',array(':value','Country'));
		return $validate;
	}
	
	public function update($table,$arr,$cond1,$cond2)
	{
		$result=DB::update($table)->set($arr)->where($cond1,"=",$cond2)->execute();
		return $result;
	}
	
	public function select_country($id="",$status=ACTIVE,$limit="",$offset="")
	{
		$query=DB::select()->from(COUNTRY);
		if($id!="")
		{
			if($limit!="" || $offset!="")
			{
				$result=$query->where('id','=',$id);
				$result=($status!="all")?$query->where('status','=',$status):"";
				$result=$query->limit($limit)->offset($offset)->order_by('id','DESC')
						->execute()->as_array();
			}
			else
			{
				$result=$query->where('id','=',$id);
				$result=($status!="all")?$query->where('status','=',$status):"";
				$result=$query->order_by('id','DESC')
						->execute()->as_array();
			}
		}
		else
		{
			if($limit!="" || $offset!="")
			{
				$result=($status!="all")?$query->where('status','=',$status):"";
				$result=$query->limit($limit)->offset($offset)->order_by('id','DESC')
						->execute()->as_array();
			}
			else
			{
				$result=($status!="all")?$query->where('status','=',$status):"";
				$result=$query->order_by('id','DESC')
						->execute()->as_array();
			}
		}
		return $result;
	}
	
	public function select_city($id="",$countryid="",$status=ACTIVE,$limit="",$offset="")
	{
		$query=DB::select(array(CITY.'.id' ,'cityid'),CITY.'.city_name',CITY.'.countryid',array(CITY.'.status','citystatus'),
						array(COUNTRY.'.id','countryid'),COUNTRY.'.country',COUNTRY.'.country_url',array(COUNTRY.'.status','countrystatus'))->from(CITY)
							->join(COUNTRY,'left')
							->on(COUNTRY.'.id','=',CITY.'.countryid');
		if(isset($countryid) && $countryid!="")
		{
			if($limit!="" || $offset!="")
			{
				$result=$query->where(CITY.'.countryid','=',$countryid);
				$result=($status!=ALL)?$query->and_where(CITY.'.status','=',$status):"";
				$result=$query->limit($limit)->offset($offset)->order_by(CITY.'.id','DESC')
						->execute()->as_array();
			}
			else
			{
				$result=$query->where(CITY.'.countryid','=',$countryid);
				$result=($status!=ALL)?$query->and_where(CITY.'.status','=',$status):"";
				$result=$query->order_by(CITY.'.id','DESC')
						->execute()->as_array();
			}
		}
		else if(isset($id) && $id!="")
		{
			if($limit!="" || $offset!="")
			{
				$result=$query->where(CITY.'.id','=',$id);
				$result=($status!=ALL)?$query->and_where(CITY.'.status','=',$status):"";
				$result=$query->limit($limit)->offset($offset)->order_by(CITY.'.id','DESC')
						->execute()->as_array();
			}
			else
			{
				$result=$query->where(CITY.'.id','=',$id);
				$result=($status!=ALL)?$query->and_where(CITY.'.status','=',$status):"";
				$result=$query->order_by(CITY.'.id','DESC')
						->execute()->as_array();
			}
		}
		else
		{
			if($limit!="" || $offset!="")
			{
				$result=($status!=ALL)?$query->where(CITY.'.status','=',$status):"";
				$result=$query->limit($limit)->offset($offset)->order_by(CITY.'.id','DESC')
						->execute()->as_array();
			}
			else
			{
				$result=($status!=ALL)?$query->where(CITY.'.status','=',$status):"";
				$result=$query->order_by(CITY.'.id','DESC')
						->execute()->as_array();				
			}
		}
		//print_r($result);exit;
		return $result;
	}
	
	public function select_category($id="",$status=ACTIVE,$limit="",$offset="")
	{
		$query=DB::select()->from(CATEGORY);
		if($id!="")
		{
			if($limit!="" || $offset!="")
			{
				$result=$query->where('category_id','=',$id);
				$result=($status!=ALL)?$query->where('category_status','=',$status):"";
				$result=$query->limit($limit)->offset($offset)->order_by('category_id','DESC')
						->execute()->as_array();
			}
			else
			{
				$result=$query->where('category_id','=',$id);
				$result=($status!=ALL)?$query->where('category_status','=',$status):"";
				$result=$query->order_by('category_id','DESC')
						->execute()->as_array();
			}
		}
		else
		{
			if($limit!="" || $offset!="")
			{
				$result=($status!="all")?$query->where('category_status','=',$status):"";
				$result=$query->limit($limit)->offset($offset)->order_by('category_id','DESC')
						->execute()->as_array();
			}
			else
			{
				$result=($status!="all")?$query->where('category_status','=',$status):"";
				$result=$query->order_by('category_id','DESC')
						->execute()->as_array();
			}
		}
		return $result;
	}
	
	/**
	* Check City exists or not for server side validation
	* @return TRUE or FALSE
	*/	
	public static function unique_city($city,$countryid,$cityid)
	{
		$query=  DB::select(array(DB::expr('COUNT(city_name)'), 'total'))
					->from(CITY)->where('city_name', '=', $city);
		if($cityid)
		{
			$result=$query->and_where('id','!=',$cityid);
		}
		$result=$query	->and_where('countryid', '=', $countryid)
						->execute()
						->get('total');
		return ! $result;
	}

	public function city_validate($arr,$cityid="")
	{
		$validate= Validation::factory($arr)
			->rule('country','not_empty',array(':value','Country'))
			->rule('city','not_empty',array(':value','City'))
			->rule('city','Model_Managemodel::unique_city',array(":value",$arr['country'],$cityid));
		return $validate;
	}
	
	/**
	* Check Category exists or not for server side validation
	* @return TRUE or FALSE
	*/	
	public static function unique_category($category,$catid)
	{
		$query=DB::select(array(DB::expr('COUNT(category_name)'), 'total'))
								->from(CATEGORY);
		if($catid!="")
		{
			$result=$query->where('category_id','!=',$catid);
		}
		$result=$query->where('category_name', '=', $category)
						->execute()
						->get('total');
		return ! $result;
	}
	
	public function category_validate($arr,$catid="")
	{
		$validate= Validation::factory($arr)
			->rule('category','not_empty',array(':value','Category'))
			->rule('category','Model_Managemodel::unique_category',array(":value",$catid));		
		return $validate;
	}
	
	/**
	* Check Country exists or not for server side validation
	* @return TRUE or FALSE
	*/	
	public static function unique_country($country,$cid)
	{
		$query= DB::select(array(DB::expr('COUNT(country)'), 'total'))
					->from(COUNTRY);
		if($cid!="")
		{
			$result=$query->where('id','!=',$cid);
		}
		$result=$query->where('country', '=', $country)
						->execute()
						->get('total');
		return ! $result;
	}
	
	
	public function country_validate($arr,$cid="")
	{
		$validate= Validation::factory($arr)
			->rule('country','not_empty',array(':value','Country'))
			->rule('country','Model_Managemodel::unique_country',array(":value",$cid));		
		return $validate;
	}
	
	public function userslist($search="",$limit="",$offset="")
	{
		$query=DB::select()->from(USERS)
							->where('user_type','!=',ADMIN);
		if($search!="")
		{
			if($limit!="" || $offset!="")
			{
				$result=$query->where_open()
							->where('firstname','like','%'.$search.'%')
							->or_where('email','like','%'.$search.'%')
							->or_where('mobile','like','%'.$search.'%')
							->where_close()
							->limit($limit)->offset($offset)
							->order_by('id','DESC')
							->execute()->as_array();
			}
			else
			{
				$result=$query->where_open()
							->where('firstname','like','%'.$search.'%')
							->or_where('email','like','%'.$search.'%')
							->or_where('mobile','like','%'.$search.'%')
							->where_close()
							->order_by('id','DESC')
							->execute()->as_array();
			}
		}
		else
		{
			if($limit!="" || $offset!="")
			{
				$result=$query->limit($limit)->offset($offset)
							->order_by('id','DESC')
							->execute()->as_array();
			}
			else
			{
				$result=$query->order_by('id','DESC')
							->execute()->as_array();
			}
		}
							
		return $result;
	}
	
	public function orderlists($limit="",$offset="")
	{
		
		$query=DB::select()->from(PURCHASEORDERS);
		if($limit!="" || $offset!="")
		{
			$result=$query->limit($limit)->offset($offset)
					->order_by('order_id','DESC')->execute()->as_array();
		}
		else
		{
			$result=$query->order_by('order_id','DESC')->execute()->as_array();
		}

		return $result;
	}
	
	/**
	* Check Email exists or not for server side validation
	* @return TRUE or FALSE
	*/	
	public static function unique_email($email)
	{
		return ! DB::select(array(DB::expr('COUNT(email)'), 'total'))
					->from(USERS)
					->where('email', '=', $email)
					->and_where('email','!=',DELETED)
					->execute()
					->get('total');
	}
	
	
	  	
	public function users_validate($arr,$checkemail=0)
	{
		$validate=Validation::factory($arr)
					->rule('firstname','not_empty',array(':value','First Name'))
					->rule('firstname','alpha_dash',array(':value','First Name'))					
					->rule('firstname', 'min_length',array(':value','4','Firstname'))	
					->rule('lastname', 'min_length',array(':value','3','Last Name'))
					->rule('email','not_empty',array(':value','Email'))
					->rule('email','email_domain',array(':value','Email'))
					->rule('usertype','not_empty',array(':value','User Type'))
					->rule('mobile','numeric',array(':value','Mobile'));
		if($checkemail==1)
		{
			$validate->rule('email','Model_Managemodel::unique_email',array(":value"))
						->rule('password','not_empty',array(':value','Password'))
						->rule('repassword','not_empty',array(':value','Confirm Password'))
						->rule('repassword','matches',array(':validation','password','repassword','Confirm Password','above Password'));
		}
		return $validate;
	}
	
	public function select_site_settings()
	{
		$query=DB::select()->from(SITEINFO)->limit(1)->execute()->as_array();
		return $query;
	}
	
	public function site_settings_validate($arr)
	{
		$validate=Validation::factory($arr)
					->rule('appname','not_empty',array(':value','App Name'))
					->rule('appdescription','not_empty',array(':value','App Description'))
					->rule('currencyformat','not_empty',array(':value','Currency Format'))
					->rule('currency_symbol','not_empty',array(':value','Currency Symbol'))
					->rule('metakeyword','not_empty',array(':value','Meta Keyword'))
					->rule('metadescription','not_empty',array(':value','Meta Description'))
					->rule('paypalusername','not_empty',array(':value','Paypal Username'))
					->rule('twitterurl','url',array(':value','Field'))
					->rule('facebookurl','url',array(':value','Field'))
					->rule('email','not_empty',array(':value','Email'))
					->rule('email','email_domain',array(':value','Email'));
		return $validate;
	}
	
	public function select_all_users()
	{
		$query=DB::select('email','firstname')->from(USERS)->where('user_type','=',GENERALUSER)->where('user_status','=',ACTIVE)->execute()->as_array();
		return $query;
	}
	
	public function select_all_stores($distinct=TRUE)
	{
		$query=DB::select('store_email');
		if($distinct)
		{
			$result=$query->distinct(TRUE);
		}
		$result=$query->from(STORE)->where('store_email','!=',"")->and_where('store_status','=',ACTIVE)->execute()->as_array();
		return $result;
	}
	
	public function select_stores($userid=ALL)
	{
		$query=DB::select('store_name','store_id')->from(STORE);
		if($userid!=ALL)
		{
			$result=$query->where('userid','=',$userid)->execute()->as_array();
		}		
		else
		{
			$result=$query->execute()->as_array();
		}
		return $result;
	}
	
	public function newsletter_validate($arr)
	{
		$validate=Validation::factory($arr)
			->rule('option','not_empty',array(':value','Option'))
			->rule('subject','not_empty',array(':value','Subject'))
			->rule('message','not_empty',array(':value','Message'));
		return $validate;
	}
	
	public function select_productorder_list($orderid)
	{
		$query=DB::select()->from(PRODUCTORDERS)
							->join(PRODUCTS,'left')
							->on(PRODUCTS.'.product_id','=',PRODUCTORDERS.'.product_id')
							->join(STORE,'left')
							->on(STORE.'.store_id','=',PRODUCTORDERS.'.store_id')
							->join(USERS,'left')
							->on(USERS.'.id','=',PRODUCTORDERS.'.userid')
							->where(PRODUCTORDERS.'.order_id','=',$orderid)
							->execute()
							->as_array();
		return $query;
	}
	
	public function product_orderstatus($porderid)
	{
		$query=DB::select('products_status')->from(PRODUCTORDERS)
							->where(PRODUCTORDERS.'.order_product_id','=',$porderid)
							->execute()
							->as_array();
		return $query;
	}
	
	public function porders($storeid)
	{
		
		$query=DB::select('order_id')->distinct(TRUE)->from(PRODUCTORDERS);
		if($storeid==0)
		{
			$result=$query->where(PRODUCTORDERS.'.store_id','=','undefined')
							->execute()->as_array();
		}
		else
		{
			$result=$query->where(PRODUCTORDERS.'.store_id','=',$storeid)
							->execute()->as_array();
		}
							
		return $result;
	}
	
	public function orders($orderid,$limit="",$offset="")
	{
		$query=DB::select()->from(PURCHASEORDERS);	
		if($limit!="" || $offset!="")
		{
			$result=$query->where('order_id','=',$orderid)
					->limit($limit)->offset($offset)
					->order_by('order_id','DESC')->execute()->as_array();
		}
		else
		{
			$result=$query->where('order_id','=',$orderid)
							->order_by('order_id','DESC')->execute()->as_array();
		}
		return $result;
	}
	
	
	
}       
