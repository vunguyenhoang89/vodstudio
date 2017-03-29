<?php

class feature_posts
{
    public function register_shortcode($shortcodeName)
    {
        function shortcode_feature_posts($atts, $content = null)
        {

            $compile = '';

            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
                'posts_per_line' => '2',
                'selected_categories' => '',
                'sorting_type' => "new",
            ), $atts));

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            }
            if (strlen($heading_text) > 0) {
                $compile = "<div class='bg_title'><" . $heading_size . " style='" . (isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:' . $heading_alignment . ';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "></div>";
            }

            #sort converter
            switch ($sorting_type) {
                case "new":
                    $sort_type = "post_date";
                    break;
                case "random":
                    $sort_type = "rand";
                    break;
            }

            $compile .= '
        <div class="featured_items">
            <div class="items' . $posts_per_line . ' featured_posts" data-count="' . $posts_per_line . '">
                <div class="item_list">
        ';

            $wp_query = new WP_Query();
            $args = array(
                'posts_per_page' => $posts_per_line,
                'post_type' => 'post',
                'post_status' => 'publish',
                'cat' => $selected_categories,
                'orderby' => $sort_type,
                'order' => 'DESC'
            );

            $wp_query->query($args);

            while ($wp_query->have_posts()) : $wp_query->the_post();

                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');

                if (strlen($featured_image[0]) > 0) {
                    $featured_image_url = aq_resize($featured_image[0], "570", "400", true, true, true);
                    $full_image_url = $featured_image[0];
                    $featured_image_full = '<div class=""><a href="' . get_permalink(get_the_ID()) . '"><img src="' . $featured_image_url . '" /></a></div>';
                } else {
                    $featured_image_full = '';
                }

                $post = get_post();
                $post_excerpt = ((strlen($post->post_excerpt) > 0) ? smarty_modifier_truncate($post->post_excerpt, 100, "") : smarty_modifier_truncate(get_the_content(), 100, ""));

                $compile .= '
                    <div class="featured-item">
                        <div class="item">
                            ' . $featured_image_full . '
                            <div class="featured_items_body featured_posts_body">
                                <div class="featured_items_title">
                                    <a class="featured_title" href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a>
									<div class="featured_items_meta">
										<span class="preview_meta_data">' . get_the_time("F d, Y") . '</span>
										<span class="preview_meta_comments"><a href="' . get_comments_link() . '">'.__("Comments", "gt3_builder").': ' . get_comments_number(get_the_ID()) . '</a></span>
									</div>
                                </div>
                                <div class="carousel_desc">
                                    <div class="exc">
                                        ' . $post_excerpt . '
                                    <a href="' . get_permalink(get_the_ID()) . '">' . __('Read more!', 'gt3_builder') . '</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .featured-item -->
                    ';
            endwhile;

            $compile .= '
                </div>
                <div class="clear"></div>
            </div>
        </div>
        ';
            wp_reset_query();

            return $compile;

        }

        add_shortcode($shortcodeName, 'shortcode_feature_posts');
    }
}

#Shortcode name
$shortcodeName = "feature_posts";
$shortcode_feature_posts = new feature_posts();
$shortcode_feature_posts->register_shortcode($shortcodeName);
?>