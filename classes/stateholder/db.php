<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    LdapAuth
 * @version    1.0
 * @author     sharkpp
 * @license    MIT License
 * @copyright  2012+ sharkpp
 * @link       https://www.sharkpp.net/
 */

namespace Ldap;


//class SimpleUserUpdateException extends \FuelException {}

//class SimpleUserWrongPassword extends \FuelException {}

/**
 * SimpleAuth basic login driver
 *
 * @package     Fuel
 * @subpackage  Auth
 */
class Stateholder_Db extends Stateholder_Driver
{
	private static function g($key, $default = null)
	{
		if( '../' == substr($key, 0, 3) ) {
			$key = substr($key, 3);
		} else {
			$key = 'db.'.$key;
		}
		return \Config::get('ldapauth.'.$key, $default);
	}

	// ハッシュ作成
	// ユーザー名
	public function create($user) 
	{
		$last_login = \Date::forge()->get_timestamp();
		$login_hash = sha1(self::g('login_hash_salt').$user.$last_login);
\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.'$last_login='.$last_login.',$login_hash='.$login_hash);

$r=
		\DB::update(self::g('table_name'))
			->set(array(
		    		self::g('login_hash_field', 'login_hash') => $login_hash,
			    	self::g('last_login_field', 'last_login') => $last_login,
				))
			->where(self::g('username_field', 'username'), '=', $user)
			->execute(self::g('db_connection'));
\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));

		return $login_hash;
	}

	// ハッシュ取得
	// ユーザー名
	public function search($user)
	{
		$result = \DB::select()
			->from(self::g('table_name'))
				->where(self::g('username_field', 'username'), $user)
				->execute(self::g('db_connection'));
\Log::debug(print_r($result,true));
		return !empty($result) ? $result[0][self::g('login_hash_field', 'login_hash')] : false;
	}

	//ハッシュ検証
	// ユーザー名、ハッシュ
	public function validate($user, $hash)
	{
\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.',$hash='.$hash);
		$result = \DB::select()
			->from(self::g('table_name'))
				->where_open()
					->where(self::g('username_field', 'username'), $user)
					->where_and(self::g('last_login_field', 'last_login'), $hash)
					->where_close()
			->execute(self::g('db_connection'));
\Log::debug(__FILE__.'('.__LINE__.'):count($result)='.count($result));

		return !empty($result);
	}
	
	//ハッシュクリア
	// ユーザー名、ハッシュ
	public function clear($user, $hash)
	{
\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.',$hash='.$hash);
//		$result = \DB::delete(g('table_name'))
//				->where_open()
//					->where(g('username_field', 'username'), $user)
//					->where_and(g('last_login_field', 'last_login'), $hash)
//					->where_close()
//			->execute();

$r=
		\DB::update(g('table_name'))
			->set(array(
		    		self::g('login_hash_field', 'login_hash') => "",
				))
			->where_open()
				->where(self::g('username_field', 'username'), $user)
				->where_and(self::g('last_login_field', 'last_login'), $hash)
				->where_close()
			->execute(self::g('db_connection'));
\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));

		return true;
	}
}

// end of file simpleauth.php
