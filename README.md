# Instagram plugin for MeCms

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://travis-ci.org/mirko-pagliai/me-cms-instagram.svg?branch=master)](https://travis-ci.org/mirko-pagliai/me-cms-instagram)
[![codecov](https://codecov.io/gh/mirko-pagliai/me-cms-instagram/branch/master/graph/badge.svg)](https://codecov.io/gh/mirko-pagliai/me-cms-instagram)
[![Build status](https://ci.appveyor.com/api/projects/status/7wedj1h6bxe7m399/branch/master?svg=true)](https://ci.appveyor.com/project/mirko-pagliai/me-cms-instagram/branch/master)
[![CodeFactor](https://www.codefactor.io/repository/github/mirko-pagliai/me-cms-instagram/badge)](https://www.codefactor.io/repository/github/mirko-pagliai/me-cms-instagram)

*me-cms-instagram* plugin allows you to manage Instagram photos with
[MeCms platform](//github.com/mirko-pagliai/cakephp-for-mecms).

To install:

    $ composer require --prefer-dist mirko-pagliai/me-cms-instagram
    $ bin/cake me_instagram.install all -v

Then you need to get an
[API access token for Instagram](//www.instagram.com/developer/clients/manage)
and edit `APP/config/instagram_keys.php`.

For widgets provided by this plugin, see
[here](//github.com/mirko-pagliai/me-cms-instagram/wiki/Widgets).

## Versioning
For transparency and insight into our release cycle and to maintain backward
compatibility, *me-cms-instagram* will be maintained under the
[Semantic Versioning guidelines](http://semver.org).
