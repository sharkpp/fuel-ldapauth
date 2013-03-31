Fuel LDAP authentication package
=======================

[![Build Status](https://travis-ci.org/sharkpp/fuel-ldapauth.png?branch=master)](https://travis-ci.org/sharkpp/fuel-ldapauth)

What is this?
-------------

This package adds LDAP authentication feature to extend the standard authentication packages FuelPHP.

Requirement
--------

* FuelPHP 1.4 or later
* Enabled LDAP extension(Ref.:[PHP: LDAP - Manual](http://www.php.net/manual/ja/book.ldap.php))
* You need a server, such as OpenLDAP is the running(Tested on the Active Directory in Windows 2012 Server)

Install
-------

1. Save to ``` PKGPATH ``` (See [Packages - General - FuelPHP Documentation](http://fuelphp.com/docs/general/packages.html))
2. Add a package to ``` 'always_load' => array('packages' => array()) ``` of ``` APPPATH/config/config.php ```.
3. Add ``` PKGPATH ``` to ``` 'package_paths' => array() ``` of ``` APPPATH/config/config.php ```. (Migration does not run and you do not do this)
4. Initializes the table by executing ``` php oil refine migrate --packages=ldapauth ```.

Test
----

1. Run the ``` php oil test --group=LdapAuthPackage ```

Group can be specified individually ``` Package ``` or ``` LdapAuthPackage ```.

How to use
----------


License
-------

Copyright(c) 2012-2013 sharkpp All rights reserved.
This program is published under The MIT License.
