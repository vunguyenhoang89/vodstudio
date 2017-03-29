<?php

class blog_shortcode
{

    public function register_shortcode($shortcodeName)
    {
        function shortcode_blog($atts, $content = null)
        {
            if (!isset($compile)) {
                $compile = '';
            }
            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
                'posts_per_page' => '10',
                'posts_per_line' => '3',
                'masonry' => 'no',
                'cat_ids' => 'all',
                'blogpost_date' => '',
                'blogpost_title' => '',
                'blogpost_meta' => '',
            ), $atts));

            $masonry_width = 100 / $posts_per_line;

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            } else {
                $custom_color = '';
            }
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><" . $heading_size . " style='" . $custom_color . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:' . $heading_alignment . ';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "></div>";
            }

            global $wp_query_in_shortcodes, $paged;

            if (empty($paged)) {
                $paged = (get_query_var('page')) ? get_query_var('page') : 1;
            }

            $wp_query_in_shortcodes = new WP_Query();
            $args = array(
                'post_type' => 'post',
                'paged' => $paged,
                'posts_per_page' => $posts_per_page,
            );

            if ($cat_ids !== "all" && $cat_ids !== "") {
                $args['cat'] = $cat_ids;
            }

            $wp_query_in_shortcodes->query($args);

            $set_date_class = '';
            $set_meta_date = '';

            while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();

                $set_meta_date = '<span class="preview_meta_data">' . get_the_time("d M Y") . '</span>';
                $pagebuilder = get_post_meta(get_the_ID(), "pagebuilder", true);

                if (get_the_category()) $categories = get_the_category();
                $post_categ = '';
                $separator = ', ';
                if ($categories) {
                    foreach ($categories as $category) {
                        $post_categ = $post_categ . '<a href="' . get_category_link($category->term_id) . '">' . $category->cat_name . '</a>' . $separator;
                    }
                }

                if (get_the_tags() !== '') {
                    $posttags = get_the_tags();

                }
                if ($posttags) {
                    $post_tags = '';
                    $post_tags_compile = '<span class="preview_meta_tags">tags:';
                    foreach ($posttags as $tag) {
                        $post_tags = $post_tags . '<a href="?tag=' . $tag->slug . '">' . $tag->name . '</a>' . ', ';
                    }
                    $post_tags_compile .= ' ' . trim($post_tags, ', ') . '</span>';
                } else {
                    $post_tags_compile = '';
                }

                $compile .= '
					<div class="blog_post_preview">
						<div class="preview_wrapper">
					';
                $compile .= '<div class="global_preview">';

                // Top Elements
                $compile .= '<div class="preview_topblock">';
                $compile .= '<h2><a class="blogpost_title" href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                $compile .= '<div class="preview_meta">
                                    ' . $set_meta_date . '
                                    <span class="preview_meta_author">by <a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_the_author_meta('display_name') . '</a></span>
                                    <span class="preview_categ">in ' . trim($post_categ, ', ') . '</span>
                                    <span class="preview_meta_comments"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' comments</a></span>
                                    ' . $post_tags_compile . '
                                </div>';
                $compile .= '</div>';

                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
                if (strlen($featured_image[0]) > 0) {
                    $featured_image_url = aq_resize($featured_image[0], "900", null, true, true, true);
                    $compile .= '<img class="gt3pb_fimage" src="' . $featured_image_url . '" />';
                }

                $compile .= '<div class="preview_content">';
                $compile .= '<article class="contentarea">
								    ' . ((strlen(get_the_excerpt()) > 0) ? get_the_excerpt() : get_the_content()) . '
								</article>';
                $compile .= '</div><!-- .preview_content -->
							</div><!-- .global_preview -->
						</div>
					</div><!--.blog_post_preview -->
					';

            endwhile;

            $compile .= get_plugin_pagination("10", "show_in_shortcodes");

            wp_reset_query();

            return $compile;
        }

        add_shortcode($shortcodeName, 'shortcode_blog');
    }
}

#Shortcode name
$shortcodeName = "blog";
$blog = new blog_shortcode();
$blog->register_shortcode($shortcodeName);

?>