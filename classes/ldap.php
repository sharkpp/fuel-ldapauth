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

	public static function connect($hostname = NULL, $port = 389)
	{
		$result = @ldap_connect($hostname, $port);

		if ($result)
		{
			return new Ldap($result);
		}
		return false;
	}

	public function __construct($link_identifier)
	{
		$this->conn = $link_identifier;
	}

	public function error()
	{
		return @ldap_error($this->conn);
	}

	public function set_option($option, $newval)
	{
		return @ldap_set_option($this->conn, $option, $newval);
	}

	public function bind($bind_rdn = NULL, $bind_password = NULL)
	{
		return @ldap_bind($this->conn, $bind_rdn, $bind_password);
	}

	public function unbind()
	{
		return @ldap_bind($this->conn);
	}

	public function search($base_dn, $filter, $attributes = 0, $attrsonly = 0, $sizelimit = 0, $timelimit = 0, $deref = LDAP_DEREF_NEVER)
	{
		$result = @ldap_search($this->conn, $base_dn, $filter, attributes);

		if ($result)
		{
			return new LdapSearch($this->conn, $result);
		}
		return false;
	}
}

class LdapSearch
{
	protected $conn   = null;
	protected $result = null;

	public function __construct($link_identifier, $result_identifier)
	{
		$this->conn   = $link_identifier;
		$this->result = $result_identifier;
	}

	public function get_entries()
	{
		return @ldap_get_entries($this->conn, $this->result);
	}
}
