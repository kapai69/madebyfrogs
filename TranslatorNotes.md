## Introduction ##

Frog CMS has been designed to make "internationalization" (i18n), that is, translation of its administration and plugins, as simple as possible.

The following guidance and suggested procedures should help those who wish to (a) translate Frog into their own language, and (b) share their work with the wider Frog CMS community.

## Before you begin ##

  * Consult the [i18n source directory](http://code.google.com/p/madebyfrogs/source/browse/#svn/trunk/frog/app/backend/i18n) to see whether the translation exists!
  * Always ensure you are working with the most recent stable Frog version.
  * Consider translating the i18n files for those [plugins](http://code.google.com/p/madebyfrogs/source/browse/#svn/trunk/frog/plugins) that have them. (At this time of writing, only the [filemanager plugin](http://code.google.com/p/madebyfrogs/source/browse/#svn/trunk/frog/plugins/file_manager/i18n) has an i18n module.)
  * Of course, make sure you use the correct ISO 639-1 two-chacacter code for your particular language: from [Wikipedia](http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) or the [official list](http://www.iana.org/assignments/language-subtag-registry).

## Translating ##

### In 0.9.5 and beyond ###

With the developments in 0.9.5, Frog's admin includes a translation template generator to help out translators with translating the Frog core and plugins.

![http://farm4.static.flickr.com/3058/2954786106_4867ef3e39_o.png](http://farm4.static.flickr.com/3058/2954786106_4867ef3e39_o.png)

Clicking on the "translate Frog" link (circled in red in the image above) will take you to a page with complete instructions for doing your translation, as well as links which will generate the template files both for the Frog core as well as for those plugins which have i18n implemented.

## Sharing your work ##

Once you have finished your translation, then we suggest you:

  1. Announce your work in the [Translation](http://forum.madebyfrog.com/10) section of the Frog forums.
  1. Visit the Google Code Frog [issue tracker](http://code.google.com/p/madebyfrogs/issues/list), and enter your translation work as an "issue".
  1. Include your XX-message.php files as an attachment to your "issue".

## Updating your work ##

Everyone using Frog CMS is grateful for the i18n modules contributed by users. The more widely used Frog is, the larger the community will be. And that's good for everyone!

So thank you for your contribution, and please consider keeping your work up-to-date as Frog continues to develop.

### How to use 'diff' to help update your work ###

Since Frog generates empty translation templates for you, and you always have the most recent translation available from Frog's SVN, you can use tools like diff or beyondCompare to compare the old translation file with the new template.

Here's a rough guideline:

  1. Check out the latest Frog version from SVN [here](http://code.google.com/p/madebyfrogs/source/checkout)
  1. Generate a new translation template through Frog's interface. (direct url example: http://localhost/frog/admin/translate)
  1. Use diff or a tool like beyondCompare to copy the old translation's entries into the new template

Your diff tool should highlight any changes between the two files. Be sure to only change entries on the **right** side of the equals sign ('='). If your diff tool highlights any changes on the left side of the equals sign, that means Frog has changed the original text. In that case, be sure to double check your translation for that entry.

When you're done, compare your new/updated translation file to a freshly (!) generated translation file with your diff tool. There should be no differences on the left side of the equals ('=') sign.