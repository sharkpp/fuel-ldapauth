Fuel LDAP authentication パッケージ
===================================

[![Build Status](https://travis-ci.org/sharkpp/fuel-ldapauth.png?branch=master)](https://travis-ci.org/sharkpp/fuel-ldapauth)

これはなに？
------------

このパッケージはFuelPHP標準の認証パッケージにLDAPでの認証の機能を追加するパッケージです。

要件
----

* FuelPHP 1.4 以降
* Ldapのサーバーが必要です(Windows 2012 Server 付属の Active Directory で動作確認しています)

インストール
------------

1. ``` PKGPATH ``` に展開([Packages - General - FuelPHP Documentation](http://fuelphp.com/docs/general/packages.html)を参照)
2. ``` APPPATH/config/config.php ``` の ``` 'always_load' => array('packages' => array()) ``` にパッケージを追加
3. ``` APPPATH/config/config.php ``` の ``` 'package_paths' => array() ``` に ``` PKGPATH ``` を追加(これをしないとマイグレーションが実行されない)
4. ``` APPPATH/config/config.php ``` を実行してテーブルを初期化
5. ``` php oil refine migrate --packages=ldapauth ``` を実行してテーブルを初期化

テスト
------

1. ``` php oil test --group=LdapAuthPackage ``` を実行してテスト

グループは、``` Package ``` もしくは ``` LdapAuthPackage ``` で個別に指定できます。

使い方
------


など、で使用できます。

ライセンス
----------

Copyright(c) 2012-2013 sharkpp All rights reserved.
このプログラムは、The MIT License の元で公開されています。
