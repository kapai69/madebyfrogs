# HOWTO create a Sitemap #

_Originally posted to the [Frog forum](http://forum.madebyfrog.com/forum/topic/103.html) by Philippe Archambault._

1. Create a `snippet` called **sitemap** with the filter set to `none` and having this code in the body:

```
<?php
function snippet_sitemap($parent)
{
    $out = '';
    $childs = $parent->children();
    if (count($childs) > 0)
    {
        $out = '<ul>';
        foreach ($childs as $child)
            $out .= '<li>'.$child->link().snippet_sitemap($child).'</li>';
        $out.= '</ul>';
    }
    return $out;
}
?>
<div id="sitemap">
<?php echo snippet_sitemap($this->find('/')); ?>
</div>
```

2. Create a `page` called "sitemap" by clicking on the green "plus" icon beside the Home page; the `body` of the page should simply be: `<?php $this->includeSnippet('sitemap'); ?>`. Use your `Normal` template.

**Note**: set your `Sitemap` page to `hidden` if you don't want it to appear in the sitemap itself.

3. You can reference the Sitemap link with this code: `<a href="<?php echo BASE_URL; ?>sitemap">Sitemap</a>` which can be included in a footer, sidebar, or wherever!