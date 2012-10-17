<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    LdapAuth
 * @version    1.0
 * @author     sharkpp
 * @license    MIT License
 * @copyright  2012+ sharkpp
 * @link       https://www.sharkpp.net/
 */

Autoloader::add_namespace('Ldap', __DIR__.'/classes/');

Autoloader::add_core_namespace('Ldap');

Autoloader::add_classes(array(
	'Ldap\\Auth_Login_LdapAuth' => __DIR__.'/classes/auth/login/ldapauth.php',
	'Ldap\\Stateholder_Driver'  => __DIR__.'/classes/stateholder/driver.php',
	'Ldap\\Stateholder_Db'      => __DIR__.'/classes/stateholder/db.php',
));


/* End of file bootstrap.php */