# Introduction #

Frog does not use a "templating" engine. Rather, it relies on simple combintations of CSS plus an (X)HTML "layout".


# Overview #

It works together this way:

  1. a **Layout** which gives the HTML skeleton of the page;
  1. an accompanying **CSS** (style sheet) which is called by the Layout; and
  1. (optional) any **snippets** (headers/footers, etc.) that the layout requires.

The reason that "snippets" are optional, is that they can of course be included in the "Layout" itself.

# Activating a Theme #

Frog does not use "automatic" theme switching. Once a Layout/CSS/snippets combination has been installed, it can be activated from the "Layout" dropdown found just below the "Body" textarea for editing a page. Pick the desired Layout from that list.

The "children" of that page will inherit the layout unless they are assigned a new one. If the "Layout" is chosen on the Homepage page, then all pages in the site set to "inherit" will get that Layout with a single mouse click. "Layouts" can, then, be assigned on a per-page basis.