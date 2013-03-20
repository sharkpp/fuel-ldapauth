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

class Ldap
{
	protected $conn = null;

	private const TEST_LINK_ID   = 123456;
	private const TEST_SEARCH_ID = 123456;

	public static function connect($hostname = NULL, $port = 389)
	{
		return new Ldap(self::TEST_LINK_ID);
	}

	public function __construct($link_identifier)
	{
		$this->conn = $link_identifier;
	}

	public function error()
	{
		if (self::TEST_LINK_ID != $this->conn) {
			return '';
		}
		return '';
	}

	public function set_option($option, $newval)
	{
		if (self::TEST_LINK_ID != $this->conn) {
			return false;
		}
		return true;
	}

	public function bind($bind_rdn = NULL, $bind_password = NULL)
	{
		if (self::TEST_LINK_ID != $this->conn) {
			return false;
		}
		return false;
	}

	public function unbind()
	{
		if (self::TEST_LINK_ID != $this->conn) {
			return false;
		}
		return false;
	}

	public function search($base_dn, $filter, $attributes = 0, $attrsonly = 0, $sizelimit = 0, $timelimit = 0, $deref = LDAP_DEREF_NEVER)
	{
		if (self::TEST_LINK_ID != $this->conn) {
			return false;
		}
		return false;
	}
}

class LdapSearch
{
	protected $conn   = null;
	protected $result = null;

	private const TEST_LINK_ID   = 123456;
	private const TEST_SEARCH_ID = 123456;

	public function __construct($link_identifier, $result_identifier)
	{
		$this->conn   = $link_identifier;
		$this->result = $result_identifier;
	}

	public function get_entries()
	{
		if (self::TEST_LINK_ID != $this->conn && self::TEST_SEARCH_ID != $this->result) {
			return false;
		}
		return false;
	}
}
