<?php
/**
 * Plugin Name: Fill Murray Thumbnails
 * Description: Should you try to get a thumbnail for a post without one, this will make sure something shows up.
 * Version: 1.0.0
 * Author: Ethan Clevenger
 * Author URI: http://ethanclevenger.com
 * GPL2
 */

//TODO: Spoof metadata?

class FillMurrayThumbnails {
        function __construct() {
                add_filter('attachment_image_src', array($this, 'fill_murray_src'), 10, 4);
                add_filter('post_thumbnail_html', array($this, 'fill_murray_thumbnail'), 10, 5);
        }

        /**
         * Filter wp_get_attachment_image_src when image is empty
         * @param array $image 
         * @param id $post_thumbnail_id 
         * @param string $size 
         * @param string $icon 
         * @return array
         */
        function fill_murray_src($image, $post_thumbnail_id, $size, $icon) {
                if(!$image) {
                        $image = $this->get_src($size);
                        return array($image['src'], $image['width'], $image['height']);
                }
                return $image;
        }

        /**
         * Filter the get_the_post_thumbnail function and replace HTML if empty
         * @param string $html 
         * @param int $post_id 
         * @param int $post_thumbnail_id 
         * @param string $size 
         * @param array $attr 
         * @return string
         */
        function fill_murray_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr ) {
                if(!$html) {
                    $image = $this->get_src($size);
                    $default_attr = array(
                         'src'=>$image['src'],
                         'class'=>'attachment-'.$size,
                         'alt' => ''
                    );
                    $attr = wp_parse_args($attr, $default_attr);
                    $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, null, $size );
                    $attr = array_map('esc_attr', $attr);
                    $html = '<img width="'.$img['width'].'" height="'.$img['height'].'"';
                    foreach($attr as $name=>$value) {
                         $html .= " $name=".'"'.$value.'"';
                    }
                    $html .= ' />';
                }
                return $html;
        }

        /**
         * Get an image object array from FillMurray based on the size
         * @param string $size 
         * @return array
         */
        function get_src($size) {
            global $_wp_additional_image_sizes;
            $width = ''; $height = '';
            if(in_array($size, array('thumbnail', 'medium', 'large'))) {
                    $width = get_option($size.'_size_w');
                    $height = get_option($size.'_size_h');
            } else if(isset($_wp_additional_image_sizes[$size])) {
                    $width = $_wp_additional_image_sizes[$size]['width'];
                    $height = $_wp_additional_image_sizes[$size]['height'];
            } else {
                return array('src'=>'Undefined image size', 'width'=>0, 'height'=>0);
            }
            return array('src'=>'http://fillmurray.com/'.$width.'/'.$height, 'width'=>$width, 'height'=>$height);
        }
}
$FillMurray = new FillMurrayThumbnails();