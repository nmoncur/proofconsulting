<?php
/*
  Template Name: Visitor
*/

get_header();
do_action('proof_email_list_post');
?>

<div class="visitor">

  <?php
    $pages = tsc_get_pages(visitor);
    foreach($pages as $page) :
  ?>

    <div class="row <?php echo $page->post_name; ?>">
      <div class="col-sm-8 col-sm-offset-2">
        <div class="content">
          <h3><?php echo $page->post_title; ?></h3>
          <div class="col-sm-10 col-sm-offset-1 content"><p><?php echo $page->post_content; ?></p></div>
        </div>
      </div>
    </div>

  <?php endforeach; ?>

</div>

<?php get_footer(); ?>
