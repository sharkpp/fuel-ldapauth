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

namespace LdapAuth;

/**
 * LdapAuth state holder driver
 *
 * @package     LdapAuth
 * @subpackage  Auth
 */
abstract class Stateholder_Driver
{

	// ハッシュ作成
	// ユーザー名
	abstract function create($user, $create_when_not_found = false);

	// ハッシュ取得
	// ユーザー名
	abstract function search($user);

	//ハッシュ検証
	// ユーザー名、ハッシュ
	abstract function validate($user, $hash);
	
	//ハッシュクリア
	// ユーザー名、ハッシュ
	abstract function clear($user, $hash);
}

// end of file simpleauth.php
