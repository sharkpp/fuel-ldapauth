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
class Tests_LdapAuthBasic extends \TestCase
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
//logger(\Fuel::L_INFO, 'setup()');
	//	\Autoloader::add_classes(array(
	//			'Ldap', __DIR__.'/classes/ldap.php',
	//		));

		// load configuration
		\Config::load('ldapauth', true);
		\Config::load('simpleauth', true);

		self::$config = empty($this->ldapauth) ? \Config::get('ldapauth') : self::$config;

		self::$username_post_key = \Config::get('simpleauth.username_post_key');
		self::$password_post_key = \Config::get('simpleauth.password_post_key');

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
				'host'  => \Config::get('ldapauth.host'),
				'port'  => \Config::get('ldapauth.port'),
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);
//ob_start();debug_zval_dump($auth);$s=explode("\n",ob_get_contents());$s=array_shift($s);ob_end_clean();logger(\Fuel::L_INFO, __METHOD__.' : '.$s);

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
				'host'  => \Config::get('ldapauth.host'),
				'port'  => \Config::get('ldapauth.port'),
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);
//ob_start();debug_zval_dump($auth);$s=explode("\n",ob_get_contents());$s=array_shift($s);ob_end_clean();logger(\Fuel::L_INFO, __METHOD__.' : '.$s);

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
	public function test_login_non_secure_ng1()
	{
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host'),
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
//ob_start();debug_zval_dump($auth);$s=explode("\n",ob_get_contents());$s=array_shift($s);ob_end_clean();logger(\Fuel::L_INFO, __METHOD__.' : '.$s);

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
	public function test_login_non_secure_ng2()
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
	public function test_login_non_secure_ng3()
	{
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host'),
				'port'  => \Config::get('ldapauth.port')+1,
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
	 * Tests LdapAuth::login() with secure
	 *
	 * @test
	 */
	public function test_login_secure_ok()
	{
//logger(\Fuel::L_INFO, ''.__METHOD__.'('.__LINE__.')');
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host'),
				'port'  => \Config::get('ldapauth.port'),
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
	public function test_login_secure_ng1()
	{
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host'),
				'port'  => \Config::get('ldapauth.port'),
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

	/**
	 * Tests LdapAuth::login() with secure and connect failed
	 *
	 * @test
	 */
	public function test_login_secure_ng2()
	{
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host').'_',
				'port'  => \Config::get('ldapauth.port'),
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

	/**
	 * Tests LdapAuth::login() with secure and connect failed
	 *
	 * @test
	 */
	public function test_login_secure_ng3()
	{
		\Ldap::set_test_data(array(
				'host'  => \Config::get('ldapauth.host'),
				'port'  => \Config::get('ldapauth.port')+1,
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
