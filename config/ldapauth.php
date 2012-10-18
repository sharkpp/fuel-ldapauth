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
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return array(
	'host'              => 'localhost',
	'port'              => 389,
	'secure'            => false,
	'username'          => 'admin@example.net',
	'password'          => 'password',
	'basedn'            => 'CN=Users,DC=home,DC=example,DC=net',
	'account'           => 'sAMAccountName',
	'email'             => 'email',
	'firstname'         => 'firstname',
	'lastname'          => 'lastname',
	'guest_login'       => true,
	'login_hash_salt'   => '0123456789',
	'username_post_key' => 'username',
	'password_post_key' => 'password',
	'stateholder' => 'Db',
	'db' => array(
			'table_name'       => 'users',
			'login_hash_field' => 'login_hash',
			'last_login_field' => 'last_login',
			'username_field'   => 'username',
			'db_connection'     => null,
		),
);
