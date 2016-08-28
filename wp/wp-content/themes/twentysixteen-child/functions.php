<?php

function proof_theme_enqueue_styles() {

  $parent_style = 'parent-style';
  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style',
    get_stylesheet_directory_uri() . '/style.css',
    array( $parent_style )
  );

  wp_register_script( 'bootstrap-js', get_stylesheet_directory_uri() . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '3.0.1', true );
  wp_enqueue_script( 'bootstrap-js' );

  wp_register_style( 'bootstrap-css', get_stylesheet_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), '3.0.1', 'all' );
  wp_enqueue_style( 'bootstrap-css' );

  wp_register_script( 'functions-js', get_stylesheet_directory_uri() . '/js/functions.js', array( 'jquery' ), '3.0.1', true );
  wp_enqueue_script( 'functions-js' );

}
add_action( 'wp_enqueue_scripts', 'proof_theme_enqueue_styles' );

function proof_email_list_post() {
  global $wpdb;
  $name = fix_input($_GET['name']);
  $email = fix_input($_GET['email']);
  $date = date('Y-m-d H:i:s');

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('<h1>Please <a href="/">go back</a> and enter a valid email<h1>');
  }

  $success = $wpdb->insert( 'proof_email_list', array('name' => $name, 'email' => $email, 'added_timestamp' => $date) );

  if($success) {
    header('Location: https://proofconsulting.com/visitor');
  } else {
    echo 'db insert failure - please email rick@proofconsulting.com for access';
  }

}
add_action( 'admin_post_proof_email_list_post', 'proof_email_list_post' );
add_action( 'admin_post_nopriv_proof_email_list_post', 'proof_email_list_post' );

function fix_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
