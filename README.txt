RFX is a lightweight web framework for PHP.

== Features ==

* Loosely follows the MVC organisation
* Support for MySQL and PostgreSQL databases with abstracted API
* URI routing/dispatching (nice / seo URI support thru mod-rewrite)
* Localization support
* Support for memcached
* Simple user authentication (without sessions)
* Templating engine is PHP itself
* Doesn't needlesly complicate things by trying to use OOP in PHP

== Philosophy ==

RFX is not all-puprose batteries-included infinitely-pluggable or opinionated 
framework. It is a small set of useful routines that would've been built in PHP 
itself if it were designed during Web2.0 boom.

RFX is moldable. It's small, easy to hold in your head, and easy to modify to 
suit a particular need or project, without having to provide explicit 
extensibility features. If your project needs part of the framework to work a 
bitt different, the preferred thing to do is to just modify it where it makes 
sense. Forks galore!


== Framework organisation ==

Files:
  * README.txt, LICENSE.txt and RFX.kpf are not needed for the project itself,
    you should probably remove them when putting the code in production

  * index.php - the main entry point to your web project. You probably won't
    need to modify it, but it's a good place to put project-wide initialisation
    code if you need some

  * config.php - the framework configuration, in which you specify where the
    database is (or disable DB entirely), configure URI routing, translations
    and simple user authentication

  * .htaccess - mod_rewrite configuration for the project

Directories:
  * fx - the framework itself; look at the comments for API reference
  * static - where you should keep all static data in the project (images,
    css, js, etc), so that the framework doesn't intercept static URLs
  * lang - language translation files, one file per language
  * model, view, controller - MVC implementation in RFX


== MVC in RFX ==

RFX follows MVC loosely. The model consists of functions for manipulating the 
data in your web application (including storing/retrieving to/from database, 
but also any other well-defined operation over data).

The controllers are functions called by URI dispatchers based on the URI 
routing configuration. Usually it's beneficial to group multiple related 
functions to a single file in a controller and have additional "cmd" argument 
in URL pattern to select the exact function.

The view folder contains templates for pages/content that will be 
shown/returned to user. Besides HTML templates, it could also contain templates 
for XML, E-Mail or other kind of content. Each template is a normal PHP file.

Normally, controller sets content page (the template name) and title 
information and calls the master template ('page.php'), which then embeds the 
chosen template in the correct location of the master one. If there's a need, 
more elaborate system of master pages and subpages can be organised by 
extending the default one.

Also, the model or controller directory structure can be extended to 
subdirectories, to accomodate for larger projects or other needs.


== URL routing ==

URL routes configured in config.php map simple patterns to controller files.
The URL patterns support named parameters of integer (denoted by prefix ":") 
and string (denoted by prefix "$") types. Values of params can be retrieved by 
using uri_get_int() and uri_get_string() methods (see more in fx/uri.php).


== Database API abstraction ==

RFX doesn't attempt to abstract the SQL database itself. It just abstracts the 
API used, so you can easily switch between different DB engines. On top of the 
API abstraction, it adds a few higher-level functions useful for storing, 
querying, updating and removing records ("objects") in the database. The two 
layers of abstractions should be used in parallel, each where appropriate.


== Author, copyright, bugfixing ==

Initial author and copyright holder is Senko Rasic. RFX was used and developed 
for several REI web projects, and several people contributed to the framework.  
Thanks go to Davor Kapac, Neven Falica and Lucijan Blagonic for their 
contributions.

This software is licensed under MIT license. Basically, this means you can do 
whatever you want with it as long as you preserve the original copyright. See 
the LICENSE.txt file for details.

Main public repository for this is on github: http://github.com/senko/rfx

Bugfixes, feature additions (as long as they're in the spirit of RFX 
philosophy) and other comments are welcome! You can contact me either through 
github or message @senkorasic on Twitter.


