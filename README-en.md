Fuel OUI search package
=======================

[![Build Status](https://travis-ci.org/sharkpp/fuel-ouisearch.png?branch=master)](https://travis-ci.org/sharkpp/fuel-ouisearch)

What is this?
-------------

This package performs acquisition of a systematic name, or its contrary
from OUI(Organizationally Unique Identifier) using the list of OUI currently exhibited
by [IEEE-SA - Registration Authority OUI Public Listing](http://standards.ieee.org/develop/regauth/oui/public.html). 

Whom does it really gain?

Requirement
--------

* FuelPHP 1.5 or later
* Need [oui.txt](http://standards.ieee.org/develop/regauth/oui/oui.txt) for data registration.

Install
-------

1. Save to ``` PKGPATH ``` (See [Packages - General - FuelPHP Documentation](http://fuelphp.com/docs/general/packages.html))
2. Add a package to ``` 'always_load' => array('packages' => array()) ``` of ``` APPPATH/config/config.php ```.
3. Add ``` PKGPATH ``` to ``` 'package_paths' => array() ``` of ``` APPPATH/config/config.php ```. (Migration does not run and you do not do this)
4. Initializes the table by executing ``` php oil refine migrate --packages=ouisearch ```.
5. Download the [oui.txt](http://standards.ieee.org/develop/regauth/oui/oui.txt), put in the package directory(Where there is bootstrap.php).
6. Run the import definitions ``` php oil refine importoui ``` (slowly)

Test
----

1. Run the ``` php oil test --group=OuiSearchPackage ```

Group can be specified individually ``` Package ``` or ``` OuiSearchPackage ```.

How to use
----------

    $name = OuiSearch::lookup('00:00:00');

    $lists = OuiSearch::search_organization('00-00', 10);

    $lists = OuiSearch::search_oui('CORPORATION');

For example, you can use in.

License
-------

Copyright(c) 2013 sharkpp All rights reserved.
This program is published under The MIT License.
