<?php

class sitemap_shortcode
{
    public function register_shortcode($shortcodeName)
    {
        function shortcode_sitemap($atts, $content = null)
        {
            if (!isset($compile)) {
                $compile = '';
            }
            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
                'show_posts' => 'yes',
                'show_site_feeds' => 'yes',
            ), $atts));

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            }
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><" . $heading_size . " style='" . (isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:' . $heading_alignment . ';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "></div>";
            }


            $compile .= '
			<div class="gt3_row-fluid">
    <div class="gt3_col4 sitemap_firstblock">';

            if ($show_site_feeds == "yes") {
                $compile .= '
            <div class="bg_title"><h5 class="sitemap_block_title">' . __('Site Feeds', 'gt3_builder') . '</h5></div>
            <div class="sitemap_list">
                <div class="sitemap_item"><a href="' . get_bloginfo('rss_url') . '">' . __('Main RSS Feed', 'gt3_builder') . '</a></div>
                <div class="sitemap_item"><a href="' . get_bloginfo('comments_rss2_url') . '">' . __('Comments RSS Feed', 'gt3_builder') . '</a></div>
            </div>
            <div class="sitemap_margin"></div>
            ';
            }


            /* PAGES */
            $compile .= '<div class="bg_title"><h5 class="sitemap_block_title">' . __('Pages', 'gt3_builder') . '</h5></div>';
            $args = array(
                'depth' => 2,
                'sort_column' => 'menu_order, post_title',
                'post_type' => 'page',
                'post_status' => 'publish'
            );

            $pages = get_pages($args);

            if (count($pages) > 0) {
                $compile .= '<div class="sitemap_list">';
                foreach ($pages as $postObject) {
                    $compile .= '<div ' . (($postObject->post_parent > 0) ? 'class="sitemap_with_parent"' : '') . '><a href="' . get_permalink($postObject->ID) . '">' . $postObject->post_title . '</a></div>';
                }
                $compile .= '</div>';
            }


            $compile .= '</div>
    <div class="gt3_col4">';
            /* PORTFOLIO */
            $compile .= '<div class="bg_title"><h5 class="sitemap_block_title">' . __('Portfolio', 'gt3_builder') . '</h5></div>';
            $args = array(
                'depth' => 2,
                'sort_column' => 'menu_order, post_title',
                'post_type' => 'port',
                'post_status' => 'publish'
            );

            $pages = get_pages($args);

            if (count($pages) > 0) {
                $compile .= '<div class="sitemap_list">';
                foreach ($pages as $postObject) {
                    $compile .= '<div ' . (($postObject->post_parent > 0) ? 'class="sitemap_with_parent"' : '') . '><a href="' . get_permalink($postObject->ID) . '">' . $postObject->post_title . '</a></div>';
                }
                $compile .= '</div>';
            }
            $compile .= '</div>';


            /* POSTS */
            if ($show_posts == "yes") {
                $compile .= '
    <div class="gt3_col4">
        <div class="bg_title"><h5 class="sitemap_block_title">' . __('Posts', 'gt3_builder') . '</h5></div>
        <div class="sitemap_list">';

                $wp_query_in_shortcodes = new WP_Query();
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                    'post_status' => 'publish'
                );

                $wp_query_in_shortcodes->query($args);

                while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();
                    $compile .= '
                <div class="ol_sitemap_item">
                    <a href="' . get_permalink() . '">' . get_the_title() . '</a><br>
                    <span class="sitemap_date">' . get_the_time("d M Y") . '</span>
                    <span class="sitemap_author"><a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_the_author() . '</a></span>
                    <span class="sitemap_comments"><a href="' . get_comments_link() . '">' . __('Comments', 'gt3_builder') . ": "
                        . get_comments_number(get_the_ID()) . '</a></span>
                </div>
                ';
                endwhile;
                wp_reset_query();
                $compile .= '
        </div>
    </div>
    ';
            }
			$compile .= "</div>";
            return $compile;
        }

        add_shortcode($shortcodeName, 'shortcode_sitemap');
    }
}

$shortcodeName = "sitemap";
$sitemap = new sitemap_shortcode();
$sitemap->register_shortcode($shortcodeName);
?>