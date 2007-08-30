-- 
-- Table structure for table `layouts`
-- 

CREATE TABLE `{TABLEPREFIX}layouts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `content_type` varchar(80) default NULL,
  `content` text,
  `created_on` datetime default NULL,
  `updated_on` datetime default NULL,
  `created_by_id` int(11) default NULL,
  `updated_by_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `layouts`
-- 

INSERT INTO `{TABLEPREFIX}layouts` (`id`, `name`, `content_type`, `content`, `created_on`, `updated_on`, `created_by_id`, `updated_by_id`) VALUES 
(1, 'none', 'text/html', '<?php $this->content(); ?>', '{DATETIME}', '{DATETIME}', 1, 1),
(2, 'Normal', 'text/html', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"\r\n"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml">\r\n<head>\r\n  <title><?php $this->title(); ?></title>\r\n\r\n  <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />\r\n  <meta name="robots" content="index, follow" />\r\n  <meta name="description" content="A RadiantCMS like writen with PHP" />\r\n  <meta name="keywords" content="php,cms,radian,design,theme,template,layout" />\r\n  <meta name="author" content="Philippe Archambault" />\r\n\r\n  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css" media="all" type="text/css" />\r\n\r\n</head>\r\n<body>\r\n<div id="page">\r\n<?php $this->includeSnippet(''header''); ?>\r\n<div id="content">\r\n  <?php $this->content(); ?> \r\n</div> <!-- end #content -->\r\n<div id="sidebar">\r\n  <?php $this->content(''sidebar'', true); ?> \r\n</div> <!-- end #sidebar -->\r\n<?php $this->includeSnippet(''footer''); ?>\r\n</div> <!-- end #page -->\r\n</body>\r\n</html>', '{DATETIME}', '{DATETIME}', 1, 1),
(3, 'Stylesheet', 'text/css', '<?php $this->content(); ?>', '{DATETIME}', '{DATETIME}', 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `page_parts`
-- 

CREATE TABLE `{TABLEPREFIX}page_parts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `filter_id` varchar(25) default NULL,
  `content` longtext,
  `page_id` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `page_parts`
-- 

INSERT INTO `{TABLEPREFIX}page_parts` (`id`, `name`, `filter_id`, `content`, `page_id`) VALUES 
(1, 'body', 'Textile', 'Homepage content with "Textile":http://www.textism.com/tools/textile/ filter', 1),
(2, 'body', '', 'body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,input,textarea,p,blockquote,th,td { margin:0; padding:0; }\r\ntable { border-collapse:collapse; border-spacing:0; }\r\nfieldset,img { border:0; }\r\naddress,caption,cite,code,dfn,em,strong,th,var { font-style:normal; font-weight:normal; }\r\nol,ul { list-style:none; }\r\ncaption,th { text-align:left; }\r\nh1,h2,h3,h4,h5,h6 { font-size:100%; font-weight:normal; }\r\nq:before,q:after { content:''''; }\r\nabbr,acronym { border:0; }\r\n\r\nbody {\r\n  background: #221f20;\r\n  color: #324F6A;\r\n  font: 62.5% Tahoma, Verdana, Arial, Helvetica, sans-serif;	\r\n  text-align: center;\r\n}\r\n\r\n/* links  */\r\n\r\na {\r\n  color: #7C90A1;\r\n  text-decoration: underline;\r\n}\r\na:hover { color: #234466; }\r\n\r\n/* headings */\r\n\r\nh1 {font-size:2em}  /* displayed at 24px */\r\nh2 {font-size:1.5em}  /* displayed at 18px */\r\nh3 {font-size:1.25em}  /* displayed at 15px */\r\nh4 {font-size:1em}  /* displayed at 12px */\r\n\r\n/* strong em */\r\nstrong { font-weight: bolder; }\r\nem { font-style: italic; }\r\n\r\n/* tables & forms */\r\n\r\ninput, select, th, td {font-size:1em}\r\n\r\n/* page structure & layout */\r\n\r\n#page {\r\n  background: #f8fdfe;\r\n  border-left: 1px solid #000;\r\n  border-right: 1px solid #000;\r\n  margin: 0 auto;\r\n  text-align: left;\r\n  width: 760px;\r\n}\r\n#header {	\r\n  background: #012345;\r\n  border-bottom: 3px solid #3C6787;\r\n  height: 118px;\r\n  position: relative;\r\n}\r\n#nav {\r\n  bottom: 0;\r\n  font-size: 1.1em;\r\n  position: absolute;\r\n  right: 25px;	\r\n}\r\n#breadcrumb {\r\n  background: #87BCD8;\r\n  border-bottom: 2px solid #90C7E4;\r\n  color: #4C7897;\r\n  font-size: 1.2em;\r\n  height: 46px;\r\n  position: relative;\r\n}\r\n#info {\r\n  background: #76AAC9; \r\n  border-bottom: 3px solid #93CCEA;\r\n  color: #E4EEF4; 	\r\n  font-size: 1.2em;\r\n  line-height: 2.0em;	\r\n  position: relative;\r\n  padding: 20px 230px 20px 30px;\r\n}\r\n#content {\r\n  float: left;\r\n  font-size: 1.2em;\r\n  line-height: 1.5em;\r\n  padding: 30px 20px 30px 30px;\r\n  width: 448px;\r\n}\r\n#sidebar {\r\n  color: #4B798B;\r\n  float: right;\r\n  font-size: 1.1em;\r\n  line-height: 1.4em;\r\n  padding: 10px 30px 20px 20px;\r\n  width: 206px;\r\n}\r\n#footer {\r\n  border-top: 1px solid #DBDFE0;\r\n  clear: both;\r\n  color: #aaa;\r\n  font-size: 1.1em;\r\n  height: 70px;\r\n}\r\n\r\n/* header */\r\n\r\n#header h1 {\r\n  font-size: 4.0em;\r\n  position: absolute;\r\n  margin: 30px 0 0 45px;\r\n}\r\n#header h1 a {\r\n  color: #9ca9b7;\r\n  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;\r\n  font-weight: normal;\r\n  letter-spacing: -1px;\r\n  text-decoration: none;\r\n}\r\n#header h1 a:hover {color: #fff;}\r\n#header h1 span {\r\n  color: #fff;\r\n  font-size: 18px;\r\n}\r\n\r\n/* navigation */\r\n\r\n#nav ul {\r\n  display: inline;\r\n}\r\n#nav li {\r\n  display: block;\r\n  float: left;\r\n  list-style: none;\r\n}\r\n#nav li a {\r\n  background: #234466;\r\n  color: #fff;\r\n  display: block;\r\n  margin: 0 1px 0 1px;\r\n  line-height: 1.6em;\r\n  padding: 7px 20px 7px 20px;\r\n  text-align: center;\r\n  text-decoration: none;\r\n}\r\n#nav li a:hover,\r\n#nav li.current { background: #3C6787; }\r\n\r\n/* breadcrumb */\r\n\r\n#breadcrumb a {\r\n  color: #4c7897;\r\n  text-decoration: none;\r\n}\r\n\r\n/* info */\r\n\r\n#info a {\r\n  color: #E4EEF4;\r\n}\r\n\r\n/* content */\r\n\r\n#content h2 {\r\n  color: #76aac9;\r\n  font-family: Arial, Helvetica, sans-serif;\r\n  font-size: 22px;\r\n  font-weight: normal;\r\n  letter-spacing: -1px;\r\n  padding: 0 0 17px 0;\r\n}\r\n#content h2 a {\r\n  color: #76aac9;\r\n  text-decoration: none;\r\n}\r\n#content h2 a:hover {\r\n  color: #7C90A1;\r\n}\r\n#content h3, #content h4 {\r\n  background: none;\r\n  border-bottom: 1px solid #dfe3e4;\r\n  font-size: 1.2em;\r\n  font-weight: bold;\r\n  margin-bottom: 10px;\r\n  padding: 5px;\r\n}\r\n#content p {\r\n  line-height: 1.5em;\r\n  margin: 0 0 20px 0;\r\n}\r\n#content ol {\r\n  line-height: 1.8em;\r\n  margin: 0 30px 20px 50px;\r\n}\r\n#content ul {\r\n  line-height: 1.8em;\r\n  margin: 0 30px 20px 30px;\r\n}\r\n#content ul li {\r\n  line-height: 1.8em;\r\n  list-style: square;\r\n  padding-left: 2px;\r\n}\r\n\r\n/* secondary content */\r\n\r\n#sidebar h2 {\r\n  color: #444;\r\n  font-size: 1.5em;\r\n  font-weight: normal;\r\n  margin: 20px 0 5px 0;\r\n  padding: 7px 0 7px 0;\r\n}\r\n#sidebar p { margin: 0; }\r\n#sidebar ul {\r\n  margin: 7px 0 20px 20px;\r\n}\r\n#sidebar ul li {\r\n  height: 18px;\r\n  list-style: square;\r\n}	\r\n#sidebar a {\r\n  color: #4B798B;\r\n}\r\n#sidebar a:hover { color: #231f20; }\r\n\r\n\r\n/* footer */\r\n\r\n#footer p {\r\n  line-height: 1.5em;\r\n  margin-top: 15px;\r\n  text-align: center;\r\n}\r\n#footer a {\r\n  color: #aaa;\r\n  text-decoration: underline;\r\n}\r\n\r\n/* misc */\r\n\r\na img { border: none; }\r\nacronym { cursor: help; }\r\nblockquote {\r\n  background: url(quote.gif) no-repeat 10px 0;\r\n  color: #76aac9;\r\n  border-left: 2px solid #76aac9;\r\n  line-height: 1.5em;\r\n  margin: 0 10px 20px 10px;	\r\n  padding: 0 10px 0 10px;\r\n}\r\nhr, .hide { display: none; }\r\n.show { display: inline; }\r\n\r\npre, code { font-size: 1.1em; line-height: normal; background: #eee; color: #444; }\r\n\r\n/* class''s */\r\n\r\n.right, .left {\r\n  background: #fff;\r\n  border: 1px solid #e3e7e8;\r\n  float: left;\r\n  margin: .5em 12px 6px 0;\r\n  padding: 6px;\r\n}\r\n.right {\r\n  float: right;\r\n  margin: .5em 0  6px 12px;\r\n}\r\n.left {\r\n  float: left;\r\n  margin: .5em 12px 6px 0;\r\n}', 2),
(3, 'body', 'Textile', 'This is my site. I''m living in ... I''m doing some nice thing, like that and that ...', 3),
(4, 'body', 'Markdown', 'Articles\r\n========\r\n\r\nSome text about you section name Articles ...\r\n\r\n<?php $last_articles = $this->children(array(''limit''=>10, ''order''=>''created_on desc'')); ?>\r\n<?php foreach ($last_articles as $article): ?>\r\n<div class="article">\r\n <h2 class="article-title"><?php $article->link($article->title); ?></h2>\r\n <p class="article-content"><?php $article->content(); ?></p>\r\n</div>\r\n<?php endforeach; ?>', 4),
(5, 'body', 'Markdown', 'My **first** tet of my first article that use Markdown', 5),
(7, 'body', 'Markdown', 'This is you second article', 6);

-- --------------------------------------------------------

-- 
-- Table structure for table `pages`
-- 

CREATE TABLE `{TABLEPREFIX}pages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `slug` varchar(100) default NULL,
  `breadcrumb` varchar(160) default NULL,
  `parent_id` int(11) unsigned default NULL,
  `layout_id` int(11) unsigned default NULL,
  `behavior_id` varchar(25) NOT NULL,
  `status_id` int(11) unsigned NOT NULL default '1',
  `created_on` datetime default NULL,
  `updated_on` datetime default NULL,
  `created_by_id` int(11) default NULL,
  `updated_by_id` int(11) default NULL,
  `nleft` int(11) unsigned NOT NULL,
  `nright` int(11) unsigned NOT NULL,
  `nlevel` tinyint(4) unsigned NOT NULL,
  `position` mediumint(6) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `pages`
-- 

INSERT INTO `{TABLEPREFIX}pages` (`id`, `title`, `slug`, `breadcrumb`, `parent_id`, `layout_id`, `behavior_id`, `status_id`, `created_on`, `updated_on`, `created_by_id`, `updated_by_id`, `nleft`, `nright`, `nlevel`) VALUES 
(1, 'Home', '/', 'Home', 0, 2, '', 100, '{DATETIME}', '{DATETIME}', 1, 1, 1, 12, 1),
(2, 'Styles', 'styles.css', 'Styles', 1, 3, '', 101, '{DATETIME}', '{DATETIME}', 1, 1, 10, 11, 2),
(3, 'About us', 'about_us', 'About us', 1, 0, '', 100, '{DATETIME}', '{DATETIME}', 1, 1, 2, 3, 2),
(4, 'Articles', 'articles', 'Articles', 1, 0, '', 100, '{DATETIME}', '{DATETIME}', 1, 1, 4, 9, 2),
(5, 'My first article', 'my_first_article', 'My first article', 4, 0, '', 100, '{DATETIME}', '{DATETIME}', 1, 1, 5, 6, 3),
(6, 'My second article', 'my_second_article', 'My second article', 4, 0, '', 100, '{DATETIME}', '{DATETIME}', 1, 1, 7, 8, 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `snippets`
-- 

CREATE TABLE `{TABLEPREFIX}snippets` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `filter_id` varchar(25) default NULL,
  `content` text,
  `created_on` datetime default NULL,
  `updated_on` datetime default NULL,
  `created_by_id` int(11) default NULL,
  `updated_by_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `snippets`
-- 

INSERT INTO `{TABLEPREFIX}snippets` (`id`, `name`, `filter_id`, `content`, `created_on`, `updated_on`, `created_by_id`, `updated_by_id`) VALUES 
(1, 'header', '', '<div id="header">\r\n  <h1><a href="/">Frog</a> <span>content management simplified</span></h1>\r\n  <div id="nav">\r\n    <ul>\r\n      <li><a href="<?php echo BASE_URL; ?>/">home</a></li>\r\n<?php \r\n  $home = $this->find(''/''); \r\n  $home_childs = $home->children(); \r\n\r\n  foreach($home_childs as $menu): \r\n?>\r\n      <li><?php $menu->link(); ?></li>\r\n<?php endforeach; ?>\r\n    </ul>\r\n  </div> <!-- end #navigation -->\r\n</div> <!-- end #header -->', '{DATETIME}', '{DATETIME}', 1, 1),
(2, 'footer', '', '<div id="footer"><div id="footer-inner">\r\n  <p>&copy; Copyright <?php echo date(''Y''); ?> Philworks.com - <a href="http://validator.w3.org/check?uri=referer" title="Validate XHTML">XHTML</a> \r\n  - <a href="http://jigsaw.w3.org/css-validator/check/referer" title="Validate CSS">CSS</a><br />\r\n  Powered by <a href="http://www.philworks.com/frog/" alt="Frog">Frog</a>.\r\n  </p>\r\n</div></div><!-- end #footer -->', '{DATETIME}', '{DATETIME}', 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `{TABLEPREFIX}users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `email` varchar(255) default NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) default NULL,
  `is_admin` tinyint(1) NOT NULL default '0',
  `is_developer` tinyint(1) NOT NULL default '0',
  `created_on` datetime default NULL,
  `updated_on` datetime default NULL,
  `created_by_id` int(11) default NULL,
  `updated_by_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `{TABLEPREFIX}users` (`id`, `name`, `email`, `username`, `password`, `is_admin`, `is_developer`, `created_on`, `updated_on`, `created_by_id`, `updated_by_id`) VALUES 
(1, 'Administrator', 'admin@yoursite.com', 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 1, 0, '{DATETIME}', '{DATETIME}', 1, 1);
