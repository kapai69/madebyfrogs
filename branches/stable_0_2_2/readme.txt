About
=====

Frog is a migration of Radiant CMS from ruby to PHP. You are free to 
use, modify or ... read the license.txt to all details.

Requirements
============

PHP (5) and MySQL with InnoDB support. Apache is recommanded.

* PHP    : http://www.php.net/
* MySQL  : http://www.mysql.com/
* Apache : http://www.apache.org/

this is what I WAS using, if there's some probleme juste email me...
now I use a php 5.2 with PDO and it work just better and faster :D
please participate with us to make all peoples upgrading to php 5.2 
beacause php 4 suck alot ;) as Ilia says 

Installation
============

* open your browser go to the frog_path/install/ and go!
* edit file _.htaccess and set your base dir by uncommenting this line: 
  #RewriteBase /
  and add your base directory
* rename _.htaccess to .htaccess
* open both index.php the one at the root dir and the one in the admin dir
  and uncomment the line with the declaration of BASE_URL `define('BASE_URL ...)`
  suppose to be line #12 or really neer this number
* go to your_frog_dir/admin/
* login as admin/password

Notes
=====

* password is in sha1 so if you change it manualy in database you know how !!


and really sorry for my poor english!

(c) 2007 by Philippe Archambault
