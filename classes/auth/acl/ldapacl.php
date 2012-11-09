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
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace LdapAuth;


class Auth_Acl_LdapAcl extends \Auth\Auth_Acl_SimpleAcl
{

	protected static $_valid_roles = array();

	public static function _init()
	{
		\Config::load('simpleauth', true, false, true);
		static::$_valid_roles = array_keys(\Config::get('simpleauth.roles', array()));
	}
}

/* end of file ldapacl.php */
