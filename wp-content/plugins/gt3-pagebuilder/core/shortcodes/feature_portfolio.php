<?php

class feature_portfolio
{

    public function register_shortcode($shortcodeName)
    {
        function shortcode_feature_portfolio($atts, $content = null)
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
            <div class="items' . $posts_per_line . ' featured_portfolio" data-count="' . $posts_per_line . '">
                <div class="item_list">
        ';

            if (strlen($selected_categories) > 0) {
                $post_type_terms = explode(",", $selected_categories);
            } else {
                $post_type_terms = array();
            }
            $wp_query = new WP_Query();
            $args = array(
                'post_type' => 'port',
                'posts_per_page' => $posts_per_line,
                'orderby' => $sort_type,
            );

            if (count($post_type_terms) > 0) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'portcat',
                        'field' => 'id',
                        'terms' => $post_type_terms
                    )
                );
            }

            $wp_query->query($args);

            while ($wp_query->have_posts()) : $wp_query->the_post();

                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');

                if (strlen($featured_image[0]) > 0) {
                    $featured_image_url = aq_resize($featured_image[0], "570", "400", true, true, true);
                    $full_image_url = $featured_image[0];
                    $featured_image_full = '<div class="img_block wrapped_img gallery_item_wrapper"><a href="' . get_permalink(get_the_ID()) . '"><img width="570" height="400" src="' . $featured_image_url . '" /><div class="gallery_fadder"></div><span class="gallery_ico"><i class="stand_icon icon-link"></i></span></a></div>';
                } else {
                    $featured_image_full = '';
                }

                $post = get_post();

                $post_excerpt = ((strlen($post->post_excerpt) > 0) ? $post->post_excerpt : smarty_modifier_truncate(get_the_content(), 100));

                $compile .= '
                    <div class="featured-item gallery_item">
                        <div class="item">
                            ' . $featured_image_full . '
                            <div class="featured_items_body">
                                <div class="featured_items_title">
                                    <a class="featured_portfolio_title featured_title" href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a>
                                </div>
                                <div class="carousel_desc">
                                    <div class="exc">
                                        ' . smarty_modifier_truncate($post_excerpt, 100) . '
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
            </div>
        </div>
        ';
            wp_reset_query();

            return $compile;

        }

        add_shortcode($shortcodeName, 'shortcode_feature_portfolio');
    }
}

#Shortcode name
$shortcodeName = "feature_portfolio";
$shortcode_feature_portfolio = new feature_portfolio();
$shortcode_feature_portfolio->register_shortcode($shortcodeName);
?>