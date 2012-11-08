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
	'username'          => 'admin@example.net', // admin@example.net or home.example.net\\admin
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
	/**
	 * Groups as id => array(name => <string>, roles => <array>)
	 */
	'groups' => array(
		/**
		 * Examples
		 * ---
		 *
		 * -1   => array('name' => 'Banned', 'roles' => array('banned')),
		 * 0    => array('name' => 'Guests', 'roles' => array()),
		 * 1    => array('name' => 'Users', 'roles' => array('user')),
		 * 50   => array('name' => 'Moderators', 'roles' => array('user', 'moderator')),
		 * 100  => array('name' => 'Administrators', 'roles' => array('user', 'moderator', 'admin')),
		 */
	),

	/**
	 * Roles as name => array(location => rights)
	 */
	'roles' => array(
		/**
		 * Examples
		 * ---
		 *
		 * Regular example with role "user" given create & read rights on "comments":
		 *   'user'  => array('comments' => array('create', 'read')),
		 * And similar additional rights for moderators:
		 *   'moderator'  => array('comments' => array('update', 'delete')),
		 *
		 * Wildcard # role (auto assigned to all groups):
		 *   '#'  => array('website' => array('read'))
		 *
		 * Global disallow by assigning false to a role:
		 *   'banned' => false,
		 *
		 * Global allow by assigning true to a role (use with care!):
		 *   'super' => true,
		 */
	),
);
