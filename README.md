Fuel LDAP authentication パッケージ
===================================

[![Build Status](https://travis-ci.org/sharkpp/fuel-ldapauth.png?branch=master)](https://travis-ci.org/sharkpp/fuel-ldapauth)

これはなに？
------------

このパッケージはFuelPHP標準の認証パッケージを拡張しLDAP認証機能を追加します。

要件
----

* FuelPHP 1.4 以降
* LDAP拡張が有効になっている必要があります(参考：[PHP: LDAP - Manual](http://www.php.net/manual/ja/book.ldap.php))
* OpenLDAPなどが動作しているのサーバーが必要です(Windows 2012 Server 付属の Active Directory で動作確認しています)

インストール
------------

1. ``` PKGPATH ``` に展開([Packages - General - FuelPHP Documentation](http://fuelphp.com/docs/general/packages.html)を参照)
2. ``` APPPATH/config/config.php ``` の ``` 'always_load' => array('packages' => array()) ``` にパッケージを追加
3. ``` APPPATH/config/config.php ``` の ``` 'package_paths' => array() ``` に ``` PKGPATH ``` を追加(これをしないとマイグレーションが実行されない)
4. ``` php oil refine migrate --packages=ldapauth ``` を実行してテーブルを初期化

テスト
------

1. ``` php oil test --group=LdapAuthPackage ``` を実行してテスト

グループは、``` Package ``` もしくは ``` LdapAuthPackage ``` で個別に指定できます。

使い方
------

注意点
------

* ユーザーの登録(``` Auth::instance()->create_user() ```)・更新(``` Auth::instance()->update_user() ```)・削除(``` Auth::instance()->delete_user() ```)処理は
  LDAPサーバーに対して作用しません。
  このパッケージで管理するデータベースを更新するのみです。
* 関連して、パスワードの変更(``` Auth::instance()->change_password() ```)メソッドや
  リセット(``` Auth::instance()->reset_password() ```)メソッドは常にFalseを返します。

ライセンス
----------

Copyright(c) 2012-2013 sharkpp All rights reserved.
このプログラムは、The MIT License の元で公開されています。
