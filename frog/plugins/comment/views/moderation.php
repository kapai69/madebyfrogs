<h1><?php echo __('Moderation'); ?></h1>
<?php error_reporting(E_ALL); ?>
<div id="comments-def">
    <div class="comment"><?php echo __('Comments'); ?></div>
    <div class="modify"><?php echo __('Modify'); ?></div>
</div>
<?php
global $__FROG_CONN__;
$sql = "SELECT * FROM " . TABLE_PREFIX . "comment WHERE is_approved = 0";
$stmt = $__FROG_CONN__->prepare($sql);
$stmt->execute();

$comments_count = $stmt->rowCount();

if (isset($page)) {
    $CurPage = $page;
} else {
    $CurPage = 0;
}
$sql = "SELECT * FROM " . TABLE_PREFIX . "setting WHERE name = 'rowspage'";
$stmt = $__FROG_CONN__->prepare($sql);
$stmt->execute();
$rowspage = $stmt->fetchObject();
$rowspage = $rowspage->value;
$start = $CurPage * $rowspage;

$totalrecords = $comments_count;
$sql = "SELECT comment.is_approved, comment.id, comment.page_id, comment.author_name, comment.body, comment.created_on, page.title FROM " .
    TABLE_PREFIX . "comment AS comment, " . TABLE_PREFIX .
    "page AS page WHERE comment.is_approved = 0 AND comment.page_id = page.id LIMIT " .
    $start . "," . $rowspage;

$stmt = $__FROG_CONN__->prepare($sql);
$stmt->execute();
$lastpage = ceil($totalrecords / $rowspage);
if($comments_count <= $rowspage) { $lastpage = 0; } else { $lastpage = abs($lastpage - 1); }
?>
<?php if (count($comments)): ?>
<ol id="comments">

<?php while ($comment = $stmt->fetchObject()): ?>
    <li class="<?php echo odd_even(); ?> moderate">
          <a href="<?php echo get_url('plugin/comment/edit/' . $comment->id); ?>"><b><?php echo
        $comment->title; ?></b></a>
          <p><?php echo $comment->body; ?></p>
          <div class="infos">
              <?php echo date('D, j M Y', strtotime($comment->created_on)); ?> &#8212; 
              <a href="<?php echo get_url('plugin/comment/delete/' . $comment->
        id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete it?'); ?>');"><?php echo __('Delete'); ?></a> | 
<?php if ($comment->is_approved): ?>
              <a href="<?php echo get_url('plugin/comment/unapprove/' . $comment->
            id); ?>"><?php echo __('Reject'); ?></a>
<?php else: ?>
              <a href="<?php echo get_url('plugin/comment/approve/' . $comment->
            id); ?>"><?php echo __('Approve'); ?></a>
<?php endif; ?>
          </div>
      </li>
<?php endwhile; ?>
</ol>
<?php else: ?>
<h3><?php echo __('No comments found for moderation.'); ?></h3>
<?php endif; ?>
<br />
<div class="pagination">
<?php
            if ($CurPage == $lastpage) {
                $next = '<span class="disabled">Next Page</span>';
            } else {
                $nextpage = $CurPage + 1;
                $next = '<a href="' . get_url('plugin/comment/moderation/') . '' . $nextpage .
                    '">Next Page</a>';

            }
            if ($CurPage == 0) {
                $prev = '<span class="disabled">Previous Page</span>';
            } else {
                $prevpage = $CurPage - 1;
                $prev = '<a href="' . get_url('plugin/comment/moderation/') . '' . $prevpage .
                    '">Previous Page</a>';
            }
            if ($CurPage != 0) {
                echo "<a href=" . get_url('plugin/comment/moderation/') . "0>First Page</a>\n ";
            } else {
                echo "<span class=\"disabled\">First Page</span>";
            }
            echo $prev;
            for ($i = 0; $i <= $lastpage; $i++) {
                if ($i == $CurPage)
                    echo '<span class="current">' . $i . '</span';
                else
                    echo " <a href=" . get_url('plugin/comment/moderation/') . "$i>$i</a>\n";
            }
            echo $next;
            if ($CurPage != $lastpage) {
                echo "\n<a href=" . get_url('plugin/comment/moderation/') . "$lastpage>Last Page</a>&nbsp&nbsp;";
            } else {
                echo "<span class=\"disabled\">Last Page</span>";
            }
?>
</div>