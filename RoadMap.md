# How to read this roadmap #

This document contains the roadmap for the Frog project. It is important to know that the roadmap is **not set in stone**. This means that we will probably follow the roadmap very accurately, it may also mean that we might decide to do something else entirely due to changing priorities for example.

# Details #

0.9.5 - Split Frog (updated with changelog items)

  * Evaluate performance for Frog without frontend/backend split.
  * If we decide to go ahead with the splitless version; work on the performance
  * Add "addScript" funtion for plugin developers
  * Expand the number of events.
  * Add frontend login through plugin
  * Add plugin specific settings pages
  * Fix reorder javascript
  * Provide detailed textual upgrade instructions

From the current changelog:
  * Added MySQL port to install screen. Thanks gilles.doge - [issue 107](https://code.google.com/p/madebyfrogs/issues/detail?id=107)
  * Added new events - [issue 94](https://code.google.com/p/madebyfrogs/issues/detail?id=94)
  * Added site identifiers to login screens (both login.php and forgot.php) based on Admin screen title setting.
  * Added front-end login feature similar to layout selector. (login required, not required, inherit)
  * Added drag-to-copy feature for better multi-language support. Thanks tuupola.
  * Added a new core plugin called skeleton to help out new plugin developers.
  * Added translation template generator to help out translators with translating Frog core and plugins.
  * Added Norwegian and Swedish core translations.
  * Added updated Chinese translation.
  * Removed split between frontend and backend.
  * Added an ID to the "view this page" link so it can be styled.
  * Fixed issue with filename lowercasing during upload of files. Thanks david - [issue 113](https://code.google.com/p/madebyfrogs/issues/detail?id=113)
  * Fixed previous()/next() functions to work for all levels. Thanks rid - [issue 109](https://code.google.com/p/madebyfrogs/issues/detail?id=109)
  * Fixed "Add child" tooltip for browsers not picking up the alt value of the icon.
  * Fixed broken View Site link for TLDs starting with 'a' - [issue 116](https://code.google.com/p/madebyfrogs/issues/detail?id=116)
  * Fixed XHTML compatibility problem with Comment plugin.
  * Fixed issue where only the last addJavascript was accepted.
  * Updated readme.txt with requirements detail for MySQL server.
  * Updated file\_manager plugin translations to conform to new template.
  * Updated comments in preparation for PHPDoc documentation.
  * Renamed root dir text files to avoid name conflicts and accomodate Windows users.
  * Renamed style.css into screen.css for more consistency with print.css in Normal layout.

1.0.0 - Frogtastic

  * Complete UTF-8 support
  * A mechanism for content in multiple languages
  * Make sure plugin tabs and parts tabs work without javascript
  * Perform (semi) automated upgrades ???

1.0.1 - Bugtastic Frog

  * Fixing bugs ONLY
  * No new functionality
  * No rewrites

1.1.0 - ???

  * Add OpenID support through plugin