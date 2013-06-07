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

Autoloader::add_namespace('LdapAuth', __DIR__.'/classes/');

Autoloader::add_core_namespace('LdapAuth');

Autoloader::add_classes(array(
	// for FuelPHP 1.5
	'LdapAuth\\Auth_Login_LdapAuth'     => __DIR__.'/classes/1.5/auth/login/ldapauth.php',
	'LdapAuth\\Auth_Group_LdapGroup'    => __DIR__.'/classes/1.5/auth/group/ldapgroup.php',
	'LdapAuth\\Auth_Acl_LdapAcl'        => __DIR__.'/classes/1.5/auth/acl/ldapacl.php',
	// for FuelPHP 1.6 or latar
	'LdapAuth\\Auth_Login_Ldapauth'     => __DIR__.'/classes/auth/login/ldapauth.php',
	'LdapAuth\\Auth_Group_Ldapgroup'    => __DIR__.'/classes/auth/group/ldapgroup.php',
	'LdapAuth\\Auth_Acl_Ldapacl'        => __DIR__.'/classes/auth/acl/ldapacl.php',
	//
	'LdapAuth\\Stateholder_Driver'      => __DIR__.'/classes/stateholder/driver.php',
	'LdapAuth\\Stateholder_Db'          => __DIR__.'/classes/stateholder/db.php',
	'LdapAuth\\LdapUserUpdateException' => __DIR__.'/classes/auth/exceptions.php',
	'LdapAuth\\LdapUserWrongPassword'   => __DIR__.'/classes/auth/exceptions.php',
));


/* End of file bootstrap.php */