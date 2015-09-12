# Page Status Definition #

## Draft ##

constant = **Page::STATUS\_DRAFT**

this one is use when you wrote a page, but it is not terminated and only want to saved it for later.

  * it will **NOT** be listed by `$this->children()`
  * it will **NOT** be finded by `$this->find('is_url')`
  * it is **NOT** possible to have access to it directly with is full url

## Reviewed ##

constant = **Page::STATUS\_REVIEWED**

this one is use when you want to add a special status to a page that have been reviewed. You need to code a spacial condition in the children param like array('where' => 'status\_id = '.Page::STATUS\_REVIEWED')

like that you will get only reviewed pages

  * it will be listed by `$this->children()`
  * it will be finded by `$this->find('is_url')`
  * it is possible to have access to it directly with is full url

## Published ##

constant = **Page::STATUS\_PUBLISHED**

  * it will be listed by `$this->children()`
  * it will be finded by `$this->find('is_url')`
  * it is possible to have access to it directly with is full url

## Hidden ##

constant = **Page::STATUS\_HIDDEN**

this one is use when you wrote a page, but don't want it lister automatically.

  * it will **NOT** be listed by `$this->children()`
  * it will be finded by `$this->find('is_url')`
  * it is possible to have access to it directly with is full url

_note: it can be listed with $this->children(array(), array(), **true**), because you can ask children to include hidden pages (3th param need to set to **true**_