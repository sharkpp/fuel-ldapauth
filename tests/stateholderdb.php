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
 * @group LdapAuthPackage_
 */
class Tests_StateholderDb extends \TestCase
{
//	protected $fixture;
	const DEFAULT_TABLE_NAME       = 'users';
	const DEFAULT_LOGIN_HASH_FIELD = 'login_hash';
	const DEFAULT_LAST_LOGIN_FIELD = 'last_login';
	const DEFAULT_USERNAME_FIELD   = 'username';
	const DEFAULT_GROUP_FIELD      = 'group';

	protected static $username_post_key;
	protected static $password_post_key;

	protected $config = array();

	public function setup()
	{
logger(\Fuel::L_INFO, 'setup()');
	//	\Autoloader::add_classes(array(
	//			'Ldap', __DIR__.'/classes/ldap.php',
	//		));

		// load configuration
		\Config::load('ldapauth', true);
		\Config::load('simpleauth', true);

		$this->ldapauth   = empty($this->ldapauth) ? \Config::get('ldapauth') : $this->ldapauth;
		$table_name       = \Config::get('ldapauth.db.table_name',       self::DEFAULT_TABLE_NAME);
		$login_hash_field = \Config::get('ldapauth.db.login_hash_field', self::DEFAULT_LOGIN_HASH_FIELD);
		$last_login_field = \Config::get('ldapauth.db.last_login_field', self::DEFAULT_LAST_LOGIN_FIELD);
		$username_field   = \Config::get('ldapauth.db.username_field',   self::DEFAULT_USERNAME_FIELD);
		$group_field      = \Config::get('ldapauth.db.group_field',      self::DEFAULT_GROUP_FIELD);

		self::$username_post_key = \Config::get('simpleauth.username_post_key');
		self::$password_post_key = \Config::get('simpleauth.password_post_key');

		$changed_field_names = array(
				self::DEFAULT_LOGIN_HASH_FIELD => $login_hash_field,
				self::DEFAULT_LAST_LOGIN_FIELD => $last_login_field,
				self::DEFAULT_USERNAME_FIELD   => $username_field,
				self::DEFAULT_GROUP_FIELD      => $group_field,
			);

		// load fixture
		$path = dirname(__FILE__) . '/fixture.yml';
		if (!file_exists($path))
		{
			exit('No such file: ' . $path . PHP_EOL);
		}
		$data = file_get_contents($path);
		$fixture = \Format::forge($data, 'yaml')->to_array();

		// truncate table
		if (\DBUtil::table_exists($table_name))
		{
			\DBUtil::truncate_table($table_name);
		}
		else
		{
			\Migrate::latest('ldapauth', 'package');
		}

		// insert data
		foreach ($fixture as $row)
		{
			$row_ = array();
			foreach ($row as $key => $value)
			{
				if (array_key_exists($key, $changed_field_names))
				{
					$row_[$changed_field_names[$key]] = $value;
				}
				else
				{
					$row_[$key] = $value;
				}
			}
			list($insert_id, $rows_affected)
				= \DB::insert($table_name)
					-> set($row_)
					-> execute();
		}

	}

	public function teardown()
	{
		\Auth::unload();

		\Config::set('ldapauth', $this->ldapauth);
	}

	/**
	 * Tests LdapAuth::login() with non secure
	 *
	 * @test
	 */
	public function test_login_non_secure_ok()
	{
	}
}
