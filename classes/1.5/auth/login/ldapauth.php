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
 * LdapAuth basic login driver
 *
 * @package     Fuel
 * @subpackage  Auth
 */
class Auth_Login_LdapAuth extends \Auth\Auth_Login_Driver
{

	public static function _init()
	{
		\Fuel::$env == \Fuel::TEST ?: \Autoloader::add_class('Ldap', __DIR__.'/../../../ldap.php');

		\Config::load('ldapauth', true, true, true);

		//
		$driver_name = self::g('driver', 'Db');
		$class = 'Stateholder_'.ucfirst(\Inflector::denamespace($driver_name));
		self::$driver = new $class();
	}

	/**
	 * @var  array  value for guest login
	 */
	protected static $guest_login = array(
		'id'         => 'guest',
		'group'      => '0',
		'login_hash' => false,
		'email'      => 'john@example.net',
		'lastname'   => 'Doe',
		'firstname'  => 'John',
	);

	protected static $group_class_name = 'LdapGroup';

	protected $impl = null;

	private static $driver = null;

	private static function g($key, $default = null)
	{
		return \Config::get('ldapauth.'.$key, $default);
	}

	function __construct(Array $config)
	{
		parent::__construct($config);

		$this->impl = new \LdapAuth\Impl_Auth_Login_Ldapauth($this, $config);
	}

	/**
	 * Check for login
	 *
	 * @return  bool
	 */
	protected function perform_check()
	{
		return $this->impl->perform_check();
	}

	/**
	 * Check the user exists before logging in
	 *
	 * @return  bool
	 */
	public function validate_user($username_or_email = '', $password = '')
	{
		return $this->impl->validate_user($username_or_email, $password);
	}

	/**
	 * Login user
	 *
	 * @param   string
	 * @param   string
	 * @return  bool
	 */
	public function login($username_or_email = '', $password = '')
	{
		return $this->impl->login($username_or_email, $password);
	}

	/**
	 * Force login user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function force_login($user_id = '')
	{
		return $this->impl->force_login($user_id);
	}

	/**
	 * Logout user
	 *
	 * @return  bool
	 */
	public function logout()
	{
		return $this->impl->logout();
	}

	/**
	 * Create new user
	 *
	 * @param   string
	 * @param   string
	 * @param   string  must contain valid email address
	 * @param   int     group id
	 * @param   Array
	 * @return  bool
	 */
	public function create_user($username, $password, $email, $group = 1, Array $profile_fields = array())
	{
		return $this->impl->create_user($username, $password, $email, $group, $profile_fields);
	}

	/**
	 * Update a user's properties
	 * Note: Username cannot be updated, to update password the old password must be passed as old_password
	 *
	 * @param   Array  properties to be updated including profile fields
	 * @param   string
	 * @return  bool
	 */
	public function update_user($values, $username = null)
	{
		return $this->impl->update_user($values, $username);
	}

	/**
	 * Change a user's password
	 *
	 * @param   string
	 * @param   string
	 * @param   string  username or null for current user
	 * @return  bool
	 */
	public function change_password($old_password, $new_password, $username = null)
	{
		return $this->impl->change_password($old_password, $new_password, $username);
	}

	/**
	 * Generates new random password, sets it for the given username and returns the new password.
	 * To be used for resetting a user's forgotten password, should be emailed afterwards.
	 *
	 * @param   string  $username
	 * @return  string
	 */
	public function reset_password($username)
	{
		return $this->impl->reset_password($username);
	}

	/**
	 * Deletes a given user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function delete_user($username)
	{
		return $this->impl->delete_user($username);
	}

	/**
	 * Creates a temporary hash that will validate the current login
	 *
	 * @return  string
	 */
	public function create_login_hash()
	{
		return $this->impl->create_login_hash();
	}

	/**
	 * Get the user's ID
	 *
	 * @return  Array  containing this driver's ID & the user's ID
	 */
	public function get_user_id()
	{
		return $this->impl->get_user_id();
	}

	/**
	 * Get the user's groups
	 *
	 * @return  Array  containing the group driver ID & the user's group ID
	 */
	public function get_groups()
	{
		return $this->impl->get_groups();
	}

	/**
	 * Get the user's emailaddress
	 *
	 * @return  string
	 */
	public function get_email()
	{
		return $this->impl->get_email();
	}

	/**
	 * Get the user's screen name
	 *
	 * @return  string
	 */
	public function get_screen_name()
	{
		return $this->impl->get_screen_name();
	}

	/**
	 * Get the user's profile fields
	 *
	 * @return  Array
	 */
	public function get_profile_fields($field = null, $default = null)
	{
		return $this->impl->get_profile_fields($field, $default);
	}

	/**
	 * Extension of base driver method to default to user group instead of user id
	 */
	public function has_access($condition, $driver = null, $user = null)
	{
		return $this->impl->has_access($condition, $driver, $user);
	}

	/**
	 * Extension of base driver because this supports a guest login when switched on
	 */
	public function guest_login()
	{
		return $this->impl->guest_login();
	}
}

// end of file ldapauth.php
