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
	static private $field_names = array();

	/**
	 * Class initialization
	 */
	public static function _init()
	{
		// load the migrations config
		\Config::load('ldapauth', true);

		foreach (array(
					'username_field',
					'group_field',
					'email_field',
					'login_hash_field',
					'last_login_field',
					'profile_fields',
				) as $field_name)
		{
			self::$field_names[$field_name] =
				\Config::get('ldapauth.'.$field_name,
				//	\Config::get('simpleauth.'.$field_name, 
							preg_replace('/_field$/', '', $field_name)
				//		)
					);
		}
	}

	function up()
	{
		\DBUtil::create_table(
			\Config::get('simpleauth.table_name', 'users'),
			array(
				'id'                                 => array('constraint' => 11,  'type' => 'int', 'auto_increment' => true),
				self::$field_names['username_field'] => array('constraint' => 50,  'type' => 'varchar'),
			//	self::$field_names['password_field'] => array('constraint' => 255, 'type' => 'varchar'),
				self::$field_names['group_field']    => array('constraint' => 11,  'type' => 'int', 'default' => 1),
				self::$field_names['email_field']    => array('constraint' => 255, 'type' => 'varchar'),
				self::$field_names['last_login']     => array('constraint' => 25,  'type' => 'varchar'),
				self::$field_names['login_hash']     => array('constraint' => 255, 'type' => 'varchar'),
				self::$field_names['profile_fields'] => array(                     'type' => 'text'),
				'created_at'                         => array('constraint' => 11,  'type' => 'int', 'default' => 0),
				'updated_at'                         => array('constraint' => 11,  'type' => 'int', 'default' => 0),

		// add a unique index on username and email
	//	\DBUtil::create_index('users', array('username', 'email'), 'username', 'UNIQUE');
	}

	function down()
	{
		\DBUtil::drop_table(\Config::get('simpleauth.table_name', 'users'));
	}
}
