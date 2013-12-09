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
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$_POST[self::$username_post_key] = 'xxx';
		$_POST[self::$password_post_key] = 'yyy';

		// is login failed?
		$this->assertFalse($auth->validate_user('john', 'aaaa'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		// is login failed?
		$this->assertFalse($auth->validate_user('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		// is login failed?
		$this->assertFalse($auth->validate_user('smith', 'test'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		// is login successful?
		$this->assertNotEquals(false, $auth->validate_user('john', 'test'));
		$this->assertNotEquals(false, $auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		// is login failed?
		$this->assertFalse($auth->validate_user('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));
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
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$_POST[self::$username_post_key] = 'xxx';
		$_POST[self::$password_post_key] = 'yyy';

		// is login failed?
		$this->assertFalse($auth->login('john', 'aaaa'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		// is login failed?
		$this->assertFalse($auth->login('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		// is login failed?
		$this->assertFalse($auth->login('smith', 'test'));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		// is login successful?
		$this->assertTrue($auth->login('john', 'test'));
		$this->assertNotEquals(false, $auth->get_user_id());
		$this->assertNotEquals(null, \Session::get('ldapauth.username'));
		$this->assertNotEquals(null, \Session::get('ldapauth.login_hash'));

		// is login failed?
		$this->assertFalse($auth->login('john', ''));
		$this->assertFalse($auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));

		\Config::set('ldapauth.guest_login', true);

		// is login failed?
		$this->assertFalse($auth->login('john', 'aaaa'));
		$this->assertNotEquals(false, $auth->get_user_id());
		$this->assertEquals(null, \Session::get('ldapauth.username'));
		$this->assertEquals(null, \Session::get('ldapauth.login_hash'));
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
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$_POST[self::$username_post_key] = 'xxx';
		$_POST[self::$password_post_key] = 'yyy';

		$this->assertFalse($auth->force_login());

		$this->assertFalse($auth->force_login('smith'));

		$this->assertTrue($auth->login('john', 'test'));
		$this->assertTrue($auth->logout());

		// is login failed?
		$this->assertFalse($auth->login('john', 'aaaa'));
		$this->assertTrue($auth->force_login('john'));

		$this->assertFalse($auth->force_login());

		\Config::set('ldapauth.guest_login', true);
	}

	/**
	 * Tests LdapAuth::logout()
	 *
	 * @test
	 */
	public function test_logout()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$this->assertTrue($auth->logout());

		$this->assertFalse($auth->get_user_id());
		$this->assertTrue($auth->login('john', 'test'));

		$this->assertTrue($auth->logout());
		$this->assertFalse($auth->get_user_id());

		\Config::set('ldapauth.guest_login', true);

		$this->assertFalse($auth->get_user_id());
		$this->assertTrue($auth->login('john', 'test'));
		$this->assertTrue($auth->logout());
		$this->assertNotEquals(false, $auth->get_user_id());

		\Config::set('ldapauth.guest_login', true);
	}

	/**
	 * Tests LdapAuth::create_user()
	 *
	 * @test
	 */
//	public function test_create_user()
//	{
//		\Ldap::set_test_data(array(
//				'secure'=> false,
//				'users' => array(
//					'john' => array('email' => 'foo@example.net', 'firstname' => 'John', 'lastname' => 'Smith', 'password' => 'test'),
//				),
//			));
//		\Config::set('ldapauth.secure', false);
//		\Config::set('ldapauth.guest_login', false);
//
//		// is instance load successful?
//		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
//		$this->assertNotEquals(null, $auth);
//
//		$this->assertTrue($auth->create_user('john', '', 'test@example.net', 100));
//
//		$this->assertTrue($auth->login('john', 'test'));
//		$this->assertEquals(array(array('LdapGroup', 100)), $auth->get_groups());
//
//		$this->setExpectedException('LdapUserUpdateException');
//		$this->assertFalse($auth->create_user('john', '', 'test@example.net', 100));
//	}

	/**
	 * Tests LdapAuth::change_password()
	 *
	 * @test
	 */
	public function test_change_password()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => 'foo@example.net', 'firstname' => 'John', 'lastname' => 'Smith', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$this->assertFalse($auth->change_password('test', '1234'));
		$this->assertFalse($auth->change_password('test', '1234', 'john'));

		$this->assertTrue($auth->login('john', 'test'));
		$this->assertFalse($auth->change_password('test', '1234'));
		$this->assertFalse($auth->change_password('test', '1234', 'john'));

		$this->assertTrue($auth->logout());
		$this->assertFalse($auth->change_password('test', '1234'));
		$this->assertFalse($auth->change_password('test', '1234', 'john'));
	}

	/**
	 * Tests LdapAuth::reset_password()
	 *
	 * @test
	 */
	public function test_reset_password()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => 'foo@example.net', 'firstname' => 'John', 'lastname' => 'Smith', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$this->assertEquals('', $auth->reset_password('john'));

		$this->assertTrue($auth->login('john', 'test'));
		$this->assertEquals('', $auth->reset_password('john'));

		$this->assertTrue($auth->logout());
		$this->assertEquals('', $auth->reset_password('john'));
	}

	/**
	 * Tests LdapAuth::get_screen_name()
	 *
	 * @test
	 */
	public function test_get_screen_name()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => '', 'firstname' => 'John', 'lastname' => 'Smith', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$this->assertFalse($auth->get_screen_name());

		$this->assertFalse($auth->login('john', 'xxxx'));
		$this->assertFalse($auth->get_screen_name());

		$this->assertTrue($auth->login('john', 'test'));
		$this->assertEquals('John Smith', $auth->get_screen_name());

		$this->assertTrue($auth->logout());
		$this->assertFalse($auth->get_screen_name());

		\Config::set('ldapauth.guest_login', true);

		$this->assertFalse($auth->login('john', 'xxxx'));
		$this->assertEquals('John Doe', $auth->get_screen_name());
	}

	/**
	 * Tests LdapAuth::get_email()
	 *
	 * @test
	 */
	public function test_get_email()
	{
		\Ldap::set_test_data(array(
				'secure'=> false,
				'users' => array(
					'john' => array('email' => 'foo@example.net', 'firstname' => 'John', 'lastname' => 'Smith', 'password' => 'test'),
				),
			));
		\Config::set('ldapauth.secure', false);
		\Config::set('ldapauth.guest_login', false);

		// is instance load successful?
		$auth = \Auth::forge(array('driver' => 'LdapAuth', 'id' => uniqid('',true)));
		$this->assertNotEquals(null, $auth);

		$this->assertFalse($auth->get_email());

		$this->assertFalse($auth->login('john', 'xxxx'));
		$this->assertFalse($auth->get_email());

		$this->assertTrue($auth->login('john', 'test'));
		$this->assertEquals('foo@example.net', $auth->get_email());

		$this->assertTrue($auth->logout());
		$this->assertFalse($auth->get_email());

		\Config::set('ldapauth.guest_login', true);

		$this->assertFalse($auth->login('john', 'xxxx'));
		$this->assertEquals('john@example.net', $auth->get_email());
	}

	/**
	 * Tests LdapAuth::guest_login()
	 *
	 * @test
	 */
	public function test_guest_login()
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

		\Config::set('ldapauth.guest_login', true);
		$this->assertTrue($auth->guest_login());

		\Config::set('ldapauth.guest_login', false);
		$this->assertFalse($auth->guest_login());
	}

}
