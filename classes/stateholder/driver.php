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
	abstract function create_hash($user, $create_when_not_found = false);

	// ユーザー情報取得
	// ユーザー名
	abstract function search($user);

	// ユーザー情報更新
	// ユーザー名
	abstract function update($user_info);

	//ハッシュ検証
	// ユーザー名、ハッシュ
	abstract function validate_hash($user, $hash);
	
	//ハッシュクリア
	// ユーザー名、ハッシュ
	abstract function clear_hash($user, $hash);
}

// end of file simpleauth.php
