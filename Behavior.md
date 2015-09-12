# What is a “behavior” in Frog CMS #

  * A behavior is what you can apply to a page to make it different!
  * A behavior is passed on to all children (descendent) pages
  * A behavior **can** redefine all page functions by _overwriting_ them by extending the Page class

## HOWTO create a new behavior ##

This is “work in progress” documentation for the implementation of the “behavior” feature in the next and first version, **1.0**.

First, create a folder in the `frog/behaviors` folder. (The name of the folder must be underscored, i.e.: **`my_archive_version`**.)

Then you have to create a file with the name of the class, and this filename must be camelized, i.e.: **`MyArchiveVersion.php`**

The content of this file must start with this model:

```
<?php

class MyArchiveVersion
{
    function __construct(&$page, $params)
    {
        $this->page =& $page;
        $this->params = $params;
    }
}

?>
```

As an optional next step, you can then extend the page class by creating a file with the same name as the behavior but with “Page” before it, i.e.: `*PageMyArchiveVersion.php*`

This version must look like this:

```
<?php

class PageMyArchiveVersion extends Page
{
    // ...
}
```

Then, overwrite the method (function) that you want to be different.

This is the basics, anyway, of what you need to know ... and don't forget when you apply a behavior (page type) to a page, all child pages are now dependent on it. Frog gives the control to the behavior, so watch out!!  ;)

One other important thing to be aware of is that the page is passed by reference, so if you redefine it, be sure that it is an instance of a Page and has an `_executeLayout()` function and `_saveComment()` function. These are the two functions that will be called automatically by Frog CMS.