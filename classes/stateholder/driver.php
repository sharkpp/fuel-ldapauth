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

namespace Ldap;


//class SimpleUserUpdateException extends ¥FuelException {}

//class SimpleUserWrongPassword extends ¥FuelException {}

/**
 * SimpleAuth basic login driver
 *
 * @package     Fuel
 * @subpackage  Auth
 */
abstract class Stateholder_Driver
{

	// ハッシュ作成
	// ユーザー名
	abstract function create($user) ;

	// ハッシュ取得
	// ユーザー名
	abstract function search($user) ;

	//ハッシュ検証
	// ユーザー名、ハッシュ
	abstract function validate($user, $hash);
	
	//ハッシュクリア
	// ユーザー名、ハッシュ
	abstract function clear($user, $hash);
}

// end of file simpleauth.php
