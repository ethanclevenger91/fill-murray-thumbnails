<?php
/**
 * Plugin Name: Fill Murray Thumbnails
 * Description: Should you try to get a thumbnail for a post without one, this will make sure something shows up.
 * Version: 1.0.0
 * Author: Ethan Clevenger
 * Author URI: http://ethanclevenger.com
 * GPL2
 */

class FillMurrayThumbnails {
        function __construct() {
                add_filter('post_thumbnail_html', array($this, 'fillmurray'), 10, 5);
        }
        function fillmurray($html, $post_id, $post_thumbnail_id, $size, $attr) {

                if($html == '') {
                        global $_wp_additional_image_sizes;
                        $sizes = array();
                        $width = ''; $height = '';
                        if(in_array($size, array('thumbnail', 'medium', 'large'))) {
                                $width = get_option($size.'_size_w');
                                $height = get_option($size.'_size_h');
                        } else if(isset($_wp_additional_image_sizes[$size])) {
                                $width = $_wp_additional_image_sizes[$size]['width'];
                                $height = $_wp_additional_image_sizes[$size]['height'];
                        }
                        $default_attr = array(
                                'src'=>'http://fillmurray.com/'.$width.'/'.$height,
                                'class'=>'attachment-'.$size,
                                'alt' => ''
                                );
                        $attr = wp_parse_args($attr, $default_attr);
                        $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, null, $size );
                        $attr = array_map('esc_attr', $attr);
                        $html = '<img width="'.$width.'" height="'.$height.'"';
                        foreach($attr as $name=>$value) {
                                $html .= " $name=".'"'.$value.'"';
                        }
                        $html .= ' />';
                }
                return $html;
        }
}
$FillMurray = new FillMurrayThumbnails();