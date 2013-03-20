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

namespace Fuel\Migrations;

class Auth_Create_Ldapauth_Table
{
	/**
	 * Class initialization
	 */
	public static function _init()
	{
		// load the migrations config
		\Config::load('ldapauth', true);
	}

	function up()
	{
		\DBUtil::create_table(
			\Config::get('simpleauth.table_name', 'users'),
			array(
				'id'             => array('constraint' => 11,  'type' => 'int', 'auto_increment' => true),
				'username'       => array('constraint' => 50,  'type' => 'varchar'),
				'password'       => array('constraint' => 255, 'type' => 'varchar'),
				'group'          => array('constraint' => 11,  'type' => 'int', 'default' => 1),
				'email'          => array('constraint' => 255, 'type' => 'varchar'),
				'last_login'     => array('constraint' => 25,  'type' => 'varchar'),
				'login_hash'     => array('constraint' => 255, 'type' => 'varchar'),
				'profile_fields' => array(                     'type' => 'text'),
				'created_at'     => array('constraint' => 11,  'type' => 'int', 'default' => 0),
				'updated_at'     => array('constraint' => 11,  'type' => 'int', 'default' => 0),
		), array('id'));

		// add a unique index on username and email
	//	\DBUtil::create_index('users', array('username', 'email'), 'username', 'UNIQUE');
	}

	function down()
	{
		\DBUtil::drop_table(\Config::get('simpleauth.table_name', 'users'));
	}
}
