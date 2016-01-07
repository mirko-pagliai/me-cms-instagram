# MeInstagram, plugin for MeCms plugin

This plugin allows you to manage Instagram photos on the [//github.com/mirko-pagliai/cakephp-for-mecms](MeCms platform).

To install:

    $ composer require --prefer-dist mirko-pagliai/me-instagram
    $ bin/cake me_instagram.install all -v

Then you need to get an [API access token for Instagram](https://www.instagram.com/developer/clients/manage) and edit `APP/config/instagram_keys.php`.