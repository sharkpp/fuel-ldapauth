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
class Tests_Login extends \TestCase
{
	const DEFAULT_TABLE_NAME = 'users';

	protected static $username_post_key;
	protected static $password_post_key;

	protected static $config = array();

	public function setup()
	{
		// load configuration
		\Config::load('ldapauth', true);
		\Config::load('simpleauth', true);

		self::$config     = empty(self::$config) ? \Config::get('ldapauth') : self::$config;
		$table_name       = \Config::get('ldapauth.db.table_name', self::DEFAULT_TABLE_NAME);

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

		\Session::create();
	}

	public function teardown()
	{
	//	\Auth::unload('');

		\Config::set('ldapauth', self::$config);
	}

	/**
	 * Tests LdapAuth::validate_user()
	 *
	 * @test
	 */
	public function test_validate_user()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'guest_login'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$_POST[self::$username_post_key] = 'xxx';
		$_POST[self::$password_post_key] = 'yyy';

		// is login failed?
		$this->assertFalse($auth->validate_user('john', 'aaaa'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));

		// is login failed?
		$this->assertFalse($auth->validate_user('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));

		// is login failed?
		$this->assertFalse($auth->validate_user('smith', 'test'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));

		// is login successful?
		$this->assertNotEquals(false, $auth->validate_user('john', 'test'));
		$this->assertNotEquals(false, $auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));

		// is login failed?
		$this->assertFalse($auth->validate_user('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));
	}

	/**
	 * Tests LdapAuth::login()
	 *
	 * @test
	 */
	public function test_login()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'guest_login'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$_POST[self::$username_post_key] = 'xxx';
		$_POST[self::$password_post_key] = 'yyy';

		// is login failed?
		$this->assertFalse($auth->login('john', 'aaaa'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));

		// is login failed?
		$this->assertFalse($auth->login('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));

		// is login failed?
		$this->assertFalse($auth->login('smith', 'test'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));

		// is login successful?
		$this->assertTrue($auth->login('john', 'test'));
		$this->assertNotEquals(false, $auth->get_user_id());
		$this->assertNotEquals(null, \Session::get('username'));
		$this->assertNotEquals(null, \Session::get('login_hash'));

		// is login failed?
		$this->assertFalse($auth->login('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('username'));
		$this->assertEquals(null, \Session::get('login_hash'));
	}

	/**
	 * Tests LdapAuth::force_login()
	 *
	 * @test
	 */
	public function test_force_login()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'guest_login'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$_POST[self::$username_post_key] = 'xxx';
		$_POST[self::$password_post_key] = 'yyy';

		// is login failed?
		$this->assertFalse($auth->login('john', 'aaaa'));
		$this->assertTrue($auth->force_login('john'));
	}

}
