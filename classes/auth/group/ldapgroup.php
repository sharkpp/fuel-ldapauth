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


class Auth_Group_LdapGroup extends \Auth\Auth_Group_SimpleGroup
{

	public static $_valid_groups = array();

	public static function _init()
	{
		\Config::load('simpleauth', true, false, true);
		static::$_valid_groups = array_keys(\Config::get('simpleauth.groups', array()));
	}

	protected $config = array(
		'drivers' => array('acl' => array('LdapAcl'))
	);
}

/* end of file ldapgroup.php */
