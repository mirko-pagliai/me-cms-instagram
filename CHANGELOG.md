# 1.x branch
## 1.3 branch
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