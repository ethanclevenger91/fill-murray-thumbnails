<?php
/**
* Plugin Name: Fill Murray Thumbnails
* Description: Should you try to get a thumbnail for a post without one, this will make sure something shows up.
* Version: 1.1.1
* Author: Ethan Clevenger
* Author URI: http://ethanclevenger.com
* GPL2
*/

class FillMurrayThumbnails {
  function __construct() {
    add_filter('wp_get_attachment_image_src', array($this, 'fillmurray'), 10, 4);
    add_filter('post_thumbnail_html', array($this, 'the_post_thumbnail'), 10, 5);
  }
  function fillmurray($image, $post_thumbnail_id, $size, $icon) {
    if(!$image) {
      global $_wp_additional_image_sizes;
      $width = ''; $height = '';
      if(in_array($size, array('thumbnail', 'medium', 'large'))) {
        $width = get_option($size.'_size_w');
        $height = get_option($size.'_size_h');
      } else if(isset($_wp_additional_image_sizes[$size])) {
        $width = $_wp_additional_image_sizes[$size]['width'];
        $height = $_wp_additional_image_sizes[$size]['height'];
      }
      $image = array('http://fillmurray.com/'.$width.'/'.$height, $width, $height);
    }
    return $image;
  }

  function the_post_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
    if('' == $html) {
      $html = wp_get_attachment_image($post_thumbnail_id, $size, false, $attr);
    }
    return $html;
  }
}
$FillMurray = new FillMurrayThumbnails();
