After reading all of this little document (5 minutes) you will be able to create
Frog CMS site in a minute, ok maybe in 10 minutes if you are really slow ;)

If you have any questions, suggestions, feedback or whatever, there is a forum
at this address: http://forum.madebyfrog.com

The official website address is: http://www.madebyfrog.com

If you need a Frog CMS expert you can contact me at: info@madebyfrog.com

# HOW-TO #



## HOW-TO: Create a Page ##

You have two options for doing this:

  1. Create a page by using the “New Page” button on the right, and then fill all the information (Title, body, tags, ...), and save it by clicking the “Save” button below the editing area. Then click on the small link **reorder** (at the left of the header line above the list of pages), and then just drag and drop your new page where you want it.
  1. The second method is to click on the small green plus button (it has the tooltip “Add child” when you hover the mouse pointer over it) at the right of the "parent" page that you want your new page to be created “under”. Fill all necessary information, and save it (as above).

## HOW-TO: Create a Layout ##

  1. In the **Layouts** tab, click the "New Layout" button.
  1. Write a name for this layout (must be unique).
  1. Write the Content-Type for it (default: text/html).
  1. Write the content "Body" of this layout.
  1. Click on the "Save" button.

## HOW-TO: Create a Snippet ##

A “snippet” is bit of layout that might be used across many pages. For example, Frog CMS comes with “header” and “footer” snippets.

  1. In the **Snippets** tab, click the "New Snippet" button.
  1. Write a name for this snippet (must be unique).
  1. Write the content "Body" of this snippet.
  1. You can decide to use or not a filter for this snippet by selecting it from the dropdown filter menu.
  1. Click on the "Save" button.

## HOW-TO: Create a User ##

_Note that you need to have “administrator” rights to have access to the Users module._

  1. Click the **Users** link at the top right of your window.
  1. Click the “New User” button. Fill in the details, and choose a role. Email is optional (but is useful if you need to change your password because you didn't remember it).
  1. Click on the "Save" button.

## HOW-TO: Manage my files ##

Note that the web server needs to have access to write in a “public” directory
if you want to use the “Files” module.

  * here you can add, edit and remove files or directories.
  * you can upload local file to a directory directly from the files module.

## HOW-TO: Insert/Add a snippet in a page, a layout or a snippet ##

Including snippets in your page or layout is quite simple. You need to know the exact name of the snippet (the same as it appears under the **Snippets** tab), and then include this code where you want this snippet to appear:

```
    <?php $this->includeSnippet('the_name_of_the_snippet') ?>
```

## HOW-TO: Display a list of subpages (children) in (page, layout or snippet) ##

Here is the example of the header snippet:

```
    <ul>
      <li><a href="<?php echo BASE_URL; ?>">home</a></li>
    <?php 
      $home_childs = $this->find('/')->children(); 
      foreach($home_childs as $menu): 
    ?>
      <li><?php echo $menu->link(); ?></li>
    <?php endforeach; ?>
    </ul>
```

The first `LI` is added to link the Home Page. `BASE_URL` is a constant that Frog
uses to display the `link()` or whole URL in the application. You can use it
without any problem.

Then you have the `$this->find('/')` method that will return the page at the
URL that you send it to, in this case, the Home Page.

The you have the `children()` method that will return all the subpages of the page already found. (You can do `$this->children()` and you will get all subpages of
the current page.)

Then you have a "foreach" loop that will pass each subpage one by one and
execute every line between this line and the `<?php endforeach; ?>`, in this
case the line that will be repeated is:
`<li><?php echo $menu->link(); ?></li>`. (You can see more about making links below.)

If you take a look at the code source of the page header you will see
something like this:

```
    <ul>
      <li><a href="http://localhost/frog/?">Home</a></li>
      <li><a href="http://localhost/frog/?about_us" title="About us">About us</a></li>
      <li><a href="http://localhost/frog/?articles" title="Articles">Articles</a></li>
    </ul>
```

## HOW-TO: Know what can I do with the children method in (page, layout or snippet) ##

This a weird question, but here is the answer ...

  1. You can decide to add a condition to the search (be careful with this one!)
  1. You can limit the number of results returned (limit)
  1. You can change the offset to be returned (offset)
  1. You can determine the order (by created\_on, by position, ASC ascending, DESC descending ...)

That's enough power I think!

**Example 1**: if you want to display the last 5 results only, here is how to do it:

> <?php $last\_result = $this->children(array('limit' => 5, 'order' => 'created\_on DESC')); ?>

Then you need to loop those result like the exemple in the previous HOW-TO.

**Example 2**: if you only want results 3 and 4, ordered by the position (by default):

> <?php $last\_result = $this->children(array('offset' => 2, 'limit' => 2)); ?>

This might appear weird to you but ... the offset starts at 0 not at 1.

## HOW-TO: Display a breadcrumb in (page, layout or snippet) ##

It is already done for you, here is the code:

```
    <?php echo $this->breadcrumbs() ?>
```

You can set a different separator by adding it to the method:

```
    <?php echo $this->breadcrumbs('/') ?>
```

Note: if you want to set a '\' as a separator you will need to write '\\'


## HOW-TO: Display the date of the page in (page, layout or snippet) ##

```
    <?php echo $this->date() ?>
```

If you want to change the format of the date, you can pass it as the first
parameter of the method like this:

```
    <?php echo $this->date('%A, %e %B %Y') ?>
```

If you want to display the last updated time of this page write this:

```
    <?php echo $this->date('%a, %e %b %Y', 'updated') ?>
```

For more infos about the format, check this site: http://php.net/strftime

## HOW-TO: Display a link of the page in (page, layout or snippet) ##

```
    <?php echo $this->link() ?>
```

If you wish to set a different text for the link (the default is the title of the page),
give the text you want in the parameter this way:

```
    <?php echo $this->link('here is my text') ?>
```

If you need to set a class or add an `onlick` or anything else you need to add to
the link, you can pass it as the second parameter:

```
    <?php echo $this->link('here is my text', 'class="link"') ?>
```

or

```
    <?php echo $this->link($this->title, 'onlick="toggle(this)"') ?>
```

## HOW-TO: Display content of a part in (page, layout or snippet) ##

The default part content is the `body` part, so if you need to display the `body`, just write this:

```
  <?php echo $this->content() ?>
```

if you need to display any other particular part, just add it to the parameter:

```
  <?php echo $this->content('extended') ?>
```

**Note**: if you write `<?php echo $this->content() ?>` in a page content, you
will get an infinite loop, and let me tell you that you don't want an infinite loop,
so don't do it! ;) (In other words, only use that code in a _layout_, not a _page_.)

## HOW-TO: Determine whether page content exists or not in (page, layout or snippet) ##

By testing it before calling the method content(), just like this:

```
    <?php echo $this->hasContent('sidebar') ? $this->content('sidebar'): '' ?>
```

the other way is with an `if` condition like this:

```
    <?php if ($this->hasContent('sidebar')): ?>
    <div id="sidebar">
        <?php echo $this->content('sidebar') ?>
    </div>
    <?php endif; ?>
```

Don't forget to close the if condition with the `endif;`

## HOW-TO: Display all tags of the page in (page, layout or snippet) ##

By using a PHP function + a Frog page method like this:

```
    <?php echo join(', ', $this->tags()) ?>
```

result:

```
    car, food, sport
```

if you only write `<?php echo $this->tags() ?>` you will always see "Array"
displayed, be this is what is returned by the tags() method.

## HOW-TO: Determine all methods available to display author, id, url, ... ##

Here is the list:

  * id()
  * title()
  * breadcrumb()
  * author()
  * authorId()
  * updator()
  * updatorId()
  * slug()
  * url()
  * level()

## HOW-TO: Display each comments of the page in (page, layout or snippet) ##

If you haven't removed or deleted it, you only have to include:

```
    <?php $this->includeSnippet('comment-form') ?>
```

then customise the snippet to fit your CSS template. If you do have this
snippet you will need to continue reading...

It is almost like the `children()` method, but instead of getting pages, you
will get comments:

```
    <?php $comments = $this->comments(); ?>
    <ol class="comments">
    <?php foreach ($comments as $comment): ?>
      <li class="comment">
        <p><?php echo $comment->body(); ?></p>
        <p> — <?php echo $comment->name(); ?> 
          <small class="comment-date"><?php echo $comment->date(); ?></small>
        </p>
      </li>
    <?php endforeach; // comments ?>
    </ol>
```

here is the list of what you can display from a comment:

  * `name()` the name of the author of the comment
  * `email()` the email of the author of the comment
  * `link()` the website link of the author of the comment
  * `body()` the comment (this comment is HTML safe)
  * `date(['%a, %e %b %Y'])` the date, you can change the format to it

## HOW-TO: Display the number of comments on the page in (page, layout or snippet) ##

The easy way is :

```
    <?php echo $this->commentsCount(); ?> comment(s)
```

This example is more complex, because it will add “s” to the word “comment” if
there is more than one comment hoooooo!!

```
    <?php echo $num_comments = $this->commentsCount(); ?>
      comment<?php if ($num_comments > 1) { echo 's'; } ?>
```

## HOW-TO: Display the form for adding a comment on a page in (page, layout or snippet) ##

If you haven't removed or deleted it, you only have to include:

```
    <?php $this->includeSnippet('comment-form') ?>
```

If you have done this terrible thing of deleting it, here is the form again:

```
    <form action="<?php echo $this->url() ?>" method="post" id="comment_form"> 
    <p>
        <input class="comment-form-name" type="text" name="comment[author_name]" id="comment_form_name" value="" size="22" /> 
        <label for="comment_form_name"> name (require)</label>
    </p>
    <p>
        <input class="comment-form-email" type="text" name="comment[author_email]" id="comment_form_email" value="" size="22" /> 
        <label for="comment_form_email"> email (will not be published) (required)</label>
    </p>
    <p>
        <input class="comment-form-link" type="text" name="comment[author_link]" id="comment_form_link" value="" size="22" /> 
        <label for="comment_form_link"> website</label>
    </p>
    <p>
        <textarea class="comment-form-body" id="comment_form_body" name="comment[body]" cols="100%" rows="10"></textarea>
    </p>
    <p>
        <input class="comment-form-submit" type="submit" name="commit-comment" id="comment_form_submit" value="Submit comment" />
    </p>
    </form>
```