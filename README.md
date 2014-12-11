![Micro Logo](logo.png)

[![Build Status](https://travis-ci.org/HatMedia/Micro.svg?branch=master)](https://travis-ci.org/HatMedia/Micro)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c5231522-8df6-468e-8661-f248e16ee68a/mini.png)](https://insight.sensiolabs.com/projects/c5231522-8df6-468e-8661-f248e16ee68a)
#### Micro CMS
Serve the web.

##### What is micro?
Micro is a absolute barebone CMS (In case you need something full-stack check out great open source alternatives or OpalCMS).

Micro is able to do the simplest tasks for a cms.

##### How is Micro build?
Micro is build on Silex (the micro version of Symphony) And is fully extendable. All dependancies come via Composer.


##### Installing WIP

There will be a simple installer soon( when the CMS is usable ).

Setting up your settings:

	php micro.php settings

Create a backup from your current database:

	php micro.php backup

##### Templating

For templates we are using Twig, go out there and read the docs.
All needed vars are parsed into the template so you can use them there.

Our advise is simply start of with the default themes, modify them the way you like.


[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c5231522-8df6-468e-8661-f248e16ee68a/big.png)](https://insight.sensiolabs.com/projects/c5231522-8df6-468e-8661-f248e16ee68a)

##### .HTACCESSS

Set up an .htaccess file into the root folder.

	<IfModule mod_rewrite.c>
    	Options -MultiViews

    	RewriteEngine On
    	#RewriteBase /path/to/app
    	RewriteCond %{REQUEST_FILENAME} !-f
		RewriteRule ^ index.php [QSA,L]
	</IfModule>

