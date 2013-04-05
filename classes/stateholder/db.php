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
	private $db_connection    = null;
	private $table_name       = null;
	private $login_hash_salt  = null;

	private $username_field   = null;
	private $group_field      = null;
	private $email_field      = null;
	private $last_login_field = null;
	private $login_hash_field = null;
	private $profile_fields   = null;

	private $firstname_field  = null;
	private $lastname_field   = null;

	private static function g($key, $default = null)
	{
		if( '../' == substr($key, 0, 3) ) {
			$key = substr($key, 3);
		} else {
			$key = 'db.'.$key;
		}
		return \Config::get('ldapauth.'.$key, $default);
	}

	public function __construct()
	{
		$this->db_connection    = self::g('db_connection');
		$this->table_name       = self::g('table_name');
		$this->login_hash_salt  = self::g('login_hash_salt');

		$this->username_field   = self::g('username_field',   'username');
		$this->group_field      = self::g('group_field',      'group');
		$this->email_field      = self::g('email_field',      'email');
		$this->last_login_field = self::g('last_login_field', 'last_login');
		$this->login_hash_field = self::g('login_hash_field', 'login_hash');
		$this->profile_fields   = self::g('profile_fields',   'profile_fields');

		$this->firstname_field  = self::g('firstname_field',  'firstname');
		$this->lastname_field   = self::g('lastname_field',   'lastname');
	}

	// ハッシュ作成
	// ユーザー名
	public function create_hash($user, $create_when_not_found = false)
	{
		$last_login = \Date::forge()->get_timestamp();
		$login_hash = sha1($this->login_hash_salt.$user.$last_login);
//\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.'$last_login='.$last_login.',$login_hash='.$login_hash);

		$r = \DB::update($this->table_name)
				->set(array(
						$this->login_hash_field => $login_hash,
						$this->last_login_field => $last_login,
					))
				->where($this->username_field, '=', $user)
				->execute($this->db_connection);
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));

		if (!$r &&
			$create_when_not_found) {
			$r = \DB::insert($this->table_name)
					->set(array(
							$this->username_field   => $user,
							$this->group_field      => 1,
							$this->login_hash_field => $login_hash,
							$this->last_login_field => $last_login,
						))
					->execute($this->db_connection);
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));
		}

		return $r ? $login_hash : false;
	}

	// ユーザー情報取得
	// ユーザー名
	public function search($user)
	{
		$result = \DB::select()
			->from($this->table_name)
				->where($this->username_field, $user)
				->execute($this->db_connection);
//\Log::debug(print_r($result,true));
		$profile_fields = empty($result) ?: @unserialize($result[0][$this->profile_fields]);
		return empty($result)
				? false
				: array(
						'id'         => $user,
						'username'   => $result[0][$this->username_field],
						'group'      => $result[0][$this->group_field],
						'email'      => $result[0][$this->email_field],
						'last_login' => $result[0][$this->last_login_field],
						'login_hash' => $result[0][$this->login_hash_field],
						'firstname'  => $profile_fields[$this->firstname_field],
						'lastname'   => $profile_fields[$this->lastname_field],
					);
	}

	// ユーザー情報更新
	// ユーザー名
	public function update($user_info)
	{
		$profile_fields = array();

		$stored = \DB::select(array($this->username_field, $this->profile_fields))
			->from($this->table_name)
				->where($this->username_field, $user_info['id'])
				->execute($this->db_connection);
		if ($stored)
		{
			$profile_fields = @unserialize($stored->get($this->profile_fields));
			isset($user_info['firstname']) and $profile_fields[$this->firstname_field] = $user_info['firstname'];
			isset($user_info['lastname'])  and $profile_fields[$this->lastname_field]  = $user_info['lastname'];
		}

$r=
		\DB::update($this->table_name)
			->set(array(
					$this->username_field   => $user_info['id'],
				//	$this->group_field      => $user_info['group'], // Ldapサーバーからは取得できない情報なので更新しない
					$this->email_field      => $user_info['email'],
					$this->login_hash_field => $user_info['login_hash'],
					$this->profile_fields   => serialize($profile_fields),
				))
			->where($this->username_field, $user_info['id'])
			->execute($this->db_connection);
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));
	}

	//ハッシュ検証
	// ユーザー名、ハッシュ
	public function validate_hash($user, $hash)
	{
//\Log::debug(__FILE__.'('.__LINE__.'):'.'$user='.$user.',$hash='.$hash);
		$result = \DB::select()
			->from($this->table_name)
				->where_open()
					->where($this->username_field, $user)
					->where_and($this->last_login_field, $hash)
					->where_close()
			->execute($this->db_connection);
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
		\DB::update($this->table_name)
			->set(array(
					$this->login_hash_field => "",
				))
			->where_open()
				->where($this->username_field, $user)
			//	->where_and($this->last_login_field, $hash)
				->where_close()
			->execute($this->db_connection);
//\Log::debug(__FILE__.'('.__LINE__.'):'.print_r($r,true));

		return true;
	}
}

// end of file ldapauth.php
