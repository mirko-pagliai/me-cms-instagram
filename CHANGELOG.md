# 1.x branch
## 1.10 branch
### 1.10.0
* updated for `cakephp` 4 and `phpunit` 8;
* fixed I18n translations.

## 1.9 branch
### 1.9.4
* updated for `me-cms` `2.27.3`;
* added tests for lower dependencies.

### 1.9.3
* updated for `me-cms` 2.26.6.

### 1.9.2
* fixed templates;
* fixed a little bug for `InstagramTrait`.

### 1.9.1
* updated for `php-tools` 1.1.12.

### 1.9.0
* `InstallShell` has been replaced with console commands. Every method of the
    previous class is now a `MeCmsInstagram\Command\Install` class;
* removed `ME_CMS_INSTAGRAM` constants. It no longer uses also `ME_CMS` and
    `THUMBER` constants;
* updated for CakePHP 3.7.1 and `php-tools` 1.1.10.

## 1.8 branch
### 1.8.1
* updated for MeCms 2.25.

### 1.8.0
* updated for CakePHP 3.6 and MeCms 2.24;
* now it uses the `mirko-pagliai/php-tools` package. This also replaces
    `mirko-pagliai/reflection`;
* full support for Windows, added Appveyor tests.

## 1.7 branch
### 1.7.3
* updated for MeCms 2.23.

### 1.7.2-RC2
* `InstagramTrait::user()` and `InstagramTrait::media()` methods now return
    `Entity` instances;
* `InstagramTrait::photos()` method now returns an array with entities of photos;
* updated for MeCms 2.22.5-RC2.

### 1.7.1-RC1
* updated for MeCms 2.22.4-RC1.

### 1.7.0-beta
* fixed all templates for Bootstrap 4.

## 1.6 branch
### 1.6.0
* updated for CakePHP 3.5 and MeCms 2.21.

## 1.5 branch
### 1.5.5
* updated for MeCms 2.20.1.

### 1.5.4
* the MIT license has been applied;
* significantly improved all tests.

### 1.5.3
* fixed bug for widgets: they do not show anything if there are no records.

### 1.5.2
* updated for MeCms 2.19.1.

### 1.5.1
* updated for MeCms 2.19.0.

### 1.5.0
* fixed bug, added template for ajax requests;
* fixed bugs to not rewrite existing routes;
* added the `InstagramTrait`, inheriting methods from the `Instagram` utility
    (which continues to exist, using the trait itself);
* added the `InstagramComponent`;
* the `PhotosWidgetsCell` class uses the `__construct()` method to set an
    `Instagram` instance;
* updated for MeCms 2.18.2.

## 1.4 branch
### 1.4.1
* updated for CakePHP 3.4.

### 1.4.0
* the cells that act as widgets now have "Widgets" in the name, for the classes
    and the template directory;
* updated for MeCms 2.15.0.

### 1.3.0
* `Istangram` class does not contain more static methods;
* renamed repository and package. Now is `me-cms-instagram`;
* little fixes for `PhotosCell` and its templates (widgets);
* added tests for all classes.

## 1.2 branch
### 1.2.6
* updated for MeCms 2.14.10.

### 1.2.5
* to generate thumbnails, uses the `fit()` method instead of `crop()`;
* the photos title is truncated, if this is too long.

### 1.2.4
* some fixed for MeCms 2.14.5.

### 1.2.3
* some fixes for MeCms 2.14.4.

### 1.2.2
* updated for Assets 1.1.0;
* fixed code for CakePHP Code Sniffer.

### 1.2.1
* checks if there are already routes with the same name, before declaring new;
* fixed code for CakePHP Code Sniffer;
* now `DashedRoute` is the default route class;
* updated for CakePHP 3.3.

### 1.2.0
* some fixes for MeCms 2.12.0.

## 1.1 branch
### 1.1.6
* added breadcrumb.

### 1.1.5
* fixed messages pluralized.

### 1.1.4
* some fixes for MeCms 2.10.0.

### 1.1.3
* some fixes for MeCms 2.7.3.

### 1.1.2
* when a media (photo) can not be got and an exception is thrown, it redirects
	to the index.

### 1.1.1
* added functions to generate the site sitemap.

### 1.1.0
* the API access token has moved to `me_instagram.php`. Removed
	`instagram_keys.php`;
* by default, 12 photos are shown;
* the code for loading the configuration files has been optimized.

## 1.0 branch
### 1.0.6
* fixed bug on view template;
* cache code moved from utility to controller.

### 1.0.5
* fixed a lot of little bugs and codes.

### 1.0.4
* updated for MeCms.

### 1.0.3
* updated for MeCms.

### 1.0.2
* widgets now use a common view. Rewritten the code of all widgets.

### 1.0.1
* now it shows the biography and the website of the user;
* you can choose to open the photos on Instagram, rather than on the site;
* an exception is thrown when no data is recovered from Instagram.

### 1.0.0
* first release.
