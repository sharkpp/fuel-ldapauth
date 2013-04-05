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

	protected static $link_id = 0;

	const TEST_LINK_ID   = 123456;
	const TEST_SEARCH_ID = 123456;

	protected static $data = array('xxx');

	public static function connect($hostname = NULL, $port = 389)
	{
		self::$link_id++;

		if (!isset(self::$data['host']))
		{
			return false;
		}

		$host_   = self::$data['host'];
		$port_   = self::$data['port'];
		$secure_ = self::$data['secure'];

		if ($hostname != $host_ ||
			$port     != $port_  ||
			$secure_ )
		{
			$url_parts = parse_url($hostname);
			foreach(array('scheme' => '', 'host' => '', 'port' => 389) as $key => $val)
				$url_parts[$key] = isset($url_parts[$key]) ? $url_parts[$key] : $val;
			if ($host_  != $url_parts['host'] ||
			    $port_  != $url_parts['port'] ||
			    ( $secure_ && 'ldap'  == $url_parts['scheme']) ||
			    (!$secure_ && 'ldaps' == $url_parts['scheme']) )
			{
				return null;
			}
		}

		return new Ldap(self::$link_id);
	}

	public static function set_test_data($data)
	{
		foreach(array(
				'host'        => \Config::get('ldapauth.host', ''),
				'port'        => \Config::get('ldapauth.port', 839),
				'secure'      => \Config::get('ldapauth.secure', false),
				'username'    => \Config::get('ldapauth.username', ''),
				'password'    => \Config::get('ldapauth.password', 'password'),
				'basedn'      => \Config::get('ldapauth.basedn', 'xxx'),
				'account'     => \Config::get('ldapauth.account', 'sAMAccountName'),
				'users'       => array()
			) as $key => $val)
		{
			$data[$key] = isset($data[$key]) ? $data[$key] : $val;
		}

		foreach($data['users'] as &$user)
		{
			$user = array_map(function($v){ return array($v); }, $user);
		}

		self::$data = $data;
	}

	public static function is($key)
	{
		return
			isset(self::$data['option']) &&
			isset(self::$data['option'][$key])
				? self::$data['option'][$key]
				: false;
	}

	public function __construct($link_identifier)
	{
		\Config::load('ldapauth', true);

		$this->conn = $link_identifier;
	}

	public function error()
	{
		if (null == $this->conn) {
			return 'not connected';
		}
		return $this->last_err;
	}

	public function set_option($option, $newval)
	{
		if (null == $this->conn) {
			return false;
		}
		$this->last_err = '';
		return true;
	}

	public function bind($bind_rdn = NULL, $bind_password = NULL)
	{
		if (null == $this->conn) {
			$this->last_err = 'not connected';
			return false;
		}
	//	if ($this->binded) {
	//		$this->last_err = 'bind error #1';
	//		return false;
	//	}

		$username = str_replace(self::$data['basedn'].',USER=', '', $bind_rdn);

		if ($bind_rdn == self::$data['username'])
		{
			if ($bind_password != self::$data['password']) {
				$this->last_err = 'bind error #2';
				return false;
			}
			$this->binded = true;
			$this->last_err = '';
		}
		else if (array_key_exists($username, self::$data['users']) &&
		         $bind_password === self::$data['users'][$username]['password'][0]) {
			$this->binded = true;
			$this->last_err = '';
		}
		else {
			$this->last_err = 'bind error #3';
			return false;
		}
		return true;
	}

	public function unbind()
	{
		if (null == $this->conn) {
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

		if (null == $this->conn) {
			return false;
		}
		if (!$this->binded) {
			return false;
		}
		if ($base_dn != self::$data['basedn']) {
			return false;
		}
		if (!preg_match('/'.self::$data['account'].'=([^)]+)/', $filter, $m)) {
			return false;
		}
		$username = $m[1];

		if (!array_key_exists($username, self::$data['users'])) {
			return false;
		}

		return new LdapSearch($this->conn,
		                      array_merge(self::$data['users'][$username],
		                                  array('account' => $username,
		                                        'dn' => self::$data['basedn'].',USER='.$username)));
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
		if (null == $this->conn || empty($this->result)) {
			return false;
		}
		if (Ldap::is('get_entries_failed')) {
			return false;
		}
		if (Ldap::is('get_entries_dn_empty')) {
			$result = $this->result;
			unset($result['dn']);
			return array($result);
		}
		return array($this->result);
	}
}
