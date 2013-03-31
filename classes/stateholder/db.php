<?php
/**
 * LdapAuth is Ldap authentication package for FuelPHP.
 *
 * @package    LdapAuth
 * @version    1.0
 * @author     sharkpp
 * @license    MIT License
 * @copyright  2012+ sharkpp
 * @link       https://www.sharkpp.net/
 */

namespace LdapAuth;

/**
 * LdapAuth database state holder driver
 *
 * @package     LdapAuth
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
	public function create_hash($user, $create_when_not_found = false)
	{
		$last_login = \Date::forge()->get_timestamp();
		$login_hash = sha1(self::g('login_hash_salt').$user.$last_login);
//\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.'$last_login='.$last_login.',$login_hash='.$login_hash);

		$r = \DB::update(self::g('table_name'))
				->set(array(
			    		self::g('login_hash_field', 'login_hash') => $login_hash,
				    	self::g('last_login_field', 'last_login') => $last_login,
					))
				->where(self::g('username_field', 'username'), '=', $user)
				->execute(self::g('db_connection'));
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));

		if (!$r &&
			$create_when_not_found) {
			$r = \DB::insert(self::g('table_name'))
					->set(array(
				    		self::g('username_field',   'username')   => $user,
				    		self::g('group_field',      'group')      => 1,
				    		self::g('login_hash_field', 'login_hash') => $login_hash,
					    	self::g('last_login_field', 'last_login') => $last_login,
						))
					->execute(self::g('db_connection'));
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));
		}

		return $r ? $login_hash : false;
	}

	// ユーザー情報取得
	// ユーザー名
	public function search($user)
	{
		$result = \DB::select()
			->from(self::g('table_name'))
				->where(self::g('username_field', 'username'), $user)
				->execute(self::g('db_connection'));
//\Log::debug(print_r($result,true));
		return empty($result)
				? false
				: array(
						'id'         => $user,
						'username'   => $result[0][self::g('username_field',   'username')],
						'group'      => $result[0][self::g('group_field',      'group')],
						'email'      => $result[0][self::g('email_field',      'email')],
						'last_login' => $result[0][self::g('last_login_field', 'last_login')],
						'login_hash' => $result[0][self::g('login_hash_field', 'login_hash')],
					);
	}

	// ユーザー情報更新
	// ユーザー名
	public function update($user_info)
	{
$r=
		\DB::update(self::g('table_name'))
			->set(array(
					self::g('username_field',   'username')   => $user_info['id'],
				//	self::g('group_field',      'group')      => $user_info['group'], // Ldapサーバーからは取得できない情報なので更新しない
					self::g('email_field',      'email')      => $user_info['email'],
					self::g('login_hash_field', 'login_hash') => $user_info['login_hash'],
				))
			->where_open()
				->where(self::g('username_field', 'username'), $user_info['id'])
				->where_close()
			->execute(self::g('db_connection'));
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));
	}

	//ハッシュ検証
	// ユーザー名、ハッシュ
	public function validate_hash($user, $hash)
	{
//\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.',$hash='.$hash);
		$result = \DB::select()
			->from(self::g('table_name'))
				->where_open()
					->where(self::g('username_field', 'username'), $user)
					->where_and(self::g('last_login_field', 'last_login'), $hash)
					->where_close()
			->execute(self::g('db_connection'));
//\Log::debug(__FILE__.'('.__LINE__.'):count($result)='.count($result));

		return !empty($result);
	}

	//ハッシュクリア
	// ユーザー名、ハッシュ
	public function clear_hash($user, $hash)
	{
//\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.',$hash='.$hash);
//		$result = \DB::delete(g('table_name'))
//				->where_open()
//					->where(g('username_field', 'username'), $user)
//					->where_and(g('last_login_field', 'last_login'), $hash)
//					->where_close()
//			->execute();

$r=
		\DB::update(self::g('table_name'))
			->set(array(
		    		self::g('login_hash_field', 'login_hash') => "",
				))
			->where_open()
				->where(self::g('username_field', 'username'), $user)
			//	->where_and(self::g('last_login_field', 'last_login'), $hash)
				->where_close()
			->execute(self::g('db_connection'));
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));

		return true;
	}
}

// end of file ldapauth.php
