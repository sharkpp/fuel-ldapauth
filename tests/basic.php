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

namespace LdapAuthPackage;

/**
 * LdapAuth package tests
 *
 * @group Package
 * @group LdapAuthPackage
 */
class Tests_Basic extends \TestCase
{
//	protected $fixture;
	const DEFAULT_TABLE_NAME       = 'users';
	const DEFAULT_LOGIN_HASH_FIELD = 'login_hash';
	const DEFAULT_LAST_LOGIN_FIELD = 'last_login';
	const DEFAULT_USERNAME_FIELD   = 'username';
	const DEFAULT_GROUP_FIELD      = 'group';

	protected static $username_post_key;
	protected static $password_post_key;

	protected static $config = array();

	public function setup()
	{
		// load configuration
		\Config::load('ldapauth', true);
		\Config::load('simpleauth', true);

		self::$config     = empty(self::$config) ? \Config::get('ldapauth') : self::$config;
		$table_name       = \Config::get('ldapauth.db.table_name',       self::DEFAULT_TABLE_NAME);

		self::$username_post_key = \Config::get('simpleauth.username_post_key');
		self::$password_post_key = \Config::get('simpleauth.password_post_key');

		// truncate table
		if (\DBUtil::table_exists($table_name))
		{
			\DBUtil::truncate_table($table_name);
		}
		else
		{
			\Migrate::latest('ldapauth', 'package');
		}

	}

	public function teardown()
	{
	//	\Auth::unload('');

		\Config::set('ldapauth', self::$config);
	}

	/**
	 * Tests LdapAuth::login() with non secure
	 *
	 * @test
	 */
	public function test_login_non_secure_ok()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'aaaa';
		$this->assertFalse($auth->login());

		// is login successful?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertTrue($auth->login());
	}
	/**
	 * Tests LdapAuth::login() with non secure
	 *
	 * @test
	 */
	public function test_login_non_secure_ok2()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'aaaa';
		$this->assertFalse($auth->login());

		// is login successful?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertTrue($auth->login());
	}

	/**
	 * Tests LdapAuth::login() with non secure and connect failed
	 *
	 * @test
	 */
	public function test_login_non_secure_ng_secure_type_mismatch()
	{
		\Ldap::set_test_data(array(
				'secure'=> true,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertFalse($auth->login());
	}

	/**
	 * Tests LdapAuth::login() with non secure and connect failed
	 *
	 * @test
	 */
	public function test_login_non_secure_ng_host_mismatch()
	{
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host').'_',
				'port'  => \Config::get('ldapauth.port'),
				'secure'=> true,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertFalse($auth->login());
	}

	/**
	 * Tests LdapAuth::login() with non secure and connect failed
	 *
	 * @test
	 */
	public function test_login_non_secure_ng_port_mismatch()
	{
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host'),
				'port'  => \Config::get('ldapauth.port')+1,
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertFalse($auth->login());
	}

	/**
	 * Tests LdapAuth::login() with non secure and connect failed
	 *
	 * @test
	 */
	public function test_login_non_secure_ng_bind_error()
	{
		\Ldap::set_test_data(array(
				'password'=> '1234',
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertFalse($auth->login());
	}

	/**
	 * Tests LdapAuth::login() with non secure and connect failed
	 *
	 * @test
	 */
	public function test_login_non_secure_ng_user_not_found()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'smith' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertFalse($auth->login());
	}

	/**
	 * Tests LdapAuth::login() with secure
	 *
	 * @test
	 */
	public function test_login_secure_ok()
	{
		\Ldap::set_test_data(array(
				'secure'=> true,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', true);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'aaaa';
		$this->assertFalse($auth->login());

		// is login successful?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertTrue($auth->login());
	}

	/**
	 * Tests LdapAuth::login() with secure and connect failed
	 *
	 * @test
	 */
	public function test_login_secure_ng_secure_type_mismatch()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', true);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		// is login failed?
		$_POST[self::$username_post_key] = 'john';
		$_POST[self::$password_post_key] = 'test';
		$this->assertFalse($auth->login());
	}

}
