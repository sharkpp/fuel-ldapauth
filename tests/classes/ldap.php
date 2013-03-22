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

if (!function_exists('ldap_connect'))
{
	define('LDAP_DEREF_NEVER',          0);
	define('LDAP_DEREF_SEARCHING',      1);
	define('LDAP_DEREF_FINDING',        2);
	define('LDAP_DEREF_ALWAYS',         3);
	define('LDAP_OPT_DEREF',            4);
	define('LDAP_OPT_SIZELIMIT',        5);
	define('LDAP_OPT_TIMELIMIT',        6);
	define('LDAP_OPT_NETWORK_TIMEOUT',  7);
	define('LDAP_OPT_PROTOCOL_VERSION', 8);
	define('LDAP_OPT_ERROR_NUMBER',     9);
	define('LDAP_OPT_REFERRALS',        10);
	define('LDAP_OPT_RESTART',          11);
	define('LDAP_OPT_HOST_NAME',        12);
	define('LDAP_OPT_ERROR_STRING',     13);
	define('LDAP_OPT_MATCHED_DN',       14);
	define('LDAP_OPT_SERVER_CONTROLS',  15);
	define('LDAP_OPT_CLIENT_CONTROLS',  16);
	define('LDAP_OPT_DEBUG_LEVEL',      17);
	define('GSLC_SSL_NO_AUTH',          18);
	define('GSLC_SSL_ONEWAY_AUTH',      19);
	define('GSLC_SSL_TWOWAY_AUTH',      20);
}

class Ldap
{
	protected $conn = null;
	protected $binded = false;
	protected $last_err = '';

	const TEST_LINK_ID   = 123456;
	const TEST_SEARCH_ID = 123456;

	protected $users = array(
				'john' => array('email' => '', 'firstname' => '', 'lastname' => '', 'password' => 'test'),
			);

	public static function connect($hostname = NULL, $port = 389)
	{
		return new Ldap(self::TEST_LINK_ID);
	}

	public function __construct($link_identifier)
	{
		\Config::load('ldapauth', true);

		$this->conn = $link_identifier;
	}

	public function error()
	{
		if (self::TEST_LINK_ID != $this->conn) {
			return 'not connected';
		}
		return $this->last_err;
	}

	public function set_option($option, $newval)
	{
		if (self::TEST_LINK_ID != $this->conn) {
			return false;
		}
		$this->last_err = '';
		return true;
	}

	public function bind($bind_rdn = NULL, $bind_password = NULL)
	{
		if (self::TEST_LINK_ID != $this->conn) {
			$this->last_err = 'not connected';
			return false;
		}
	//	if ($this->binded) {
	//		$this->last_err = 'bind error #1';
	//		return false;
	//	}

		$username = str_replace(\Config::get('ldapauth.basedn', '').',USER=', '', $bind_rdn);

		if ($bind_rdn == \Config::get('ldapauth.username', ''))
		{
			$this->binded = true;
			$this->last_err = '';
		}
		else if (array_key_exists($username, $this->users) &&
		         $bind_password === $this->users[$username]['password']) {
			$this->binded = true;
			$this->last_err = '';
		}
		else {
			$this->last_err = 'bind error #2';
			return false;
		}
		return true;
	}

	public function unbind()
	{
		if (self::TEST_LINK_ID != $this->conn) {
			$this->last_err = 'not connected';
			return false;
		}
		if (!$this->binded) {
			return false;
		}
		$this->binded = false;
		return true;
	}

	public function search($base_dn, $filter, $attributes = 0, $attrsonly = 0, $sizelimit = 0, $timelimit = 0, $deref = LDAP_DEREF_NEVER)
	{

		if (self::TEST_LINK_ID != $this->conn) {
			return false;
		}
		if (!$this->binded) {
			return false;
		}
		if ($base_dn != \Config::get('ldapauth.basedn', '')) {
			return false;
		}
		if (!preg_match('/'.\Config::get('ldapauth.account', 'sAMAccountName').'=([^)]+)/', $filter, $m)) {
			return false;
		}
		$username = $m[1];

		if (!array_key_exists($username, $this->users)) {
			return false;
		}

		return new LdapSearch($this->conn,
		                      array_merge($this->users[$username],
		                                  array('account' => $username,
		                                        'dn' => \Config::get('ldapauth.basedn', '').',USER='.$username)));
	}
}

class LdapSearch
{
	protected $conn   = null;
	protected $result = null;

	const TEST_LINK_ID   = 123456;
	const TEST_SEARCH_ID = 123456;

	public function __construct($link_identifier, $result_identifier)
	{
		$this->conn   = $link_identifier;
		$this->result = $result_identifier;
	}

	public function get_entries()
	{
		if (self::TEST_LINK_ID != $this->conn || empty($this->result)) {
			return false;
		}
		return array($this->result);
	}
}
