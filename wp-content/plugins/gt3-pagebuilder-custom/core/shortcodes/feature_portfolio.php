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
                'number_of_posts' => $GLOBALS["pbconfig"]['featured_portfolio_default_number_of_posts'],
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

		wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);		
			$compile .= '
        <div class="featured_items">
            <div class="items' . $posts_per_line . ' featured_portfolio" data-count="' . $posts_per_line . '">
                <ul class="item_list">
        ';

            if (strlen($selected_categories) > 0) {
                $post_type_terms = explode(",", $selected_categories);
            } else {
                $post_type_terms = array();
            }
			 
            $wp_query = new WP_Query();
            $args = array(
                'post_type' => 'port',
                'posts_per_page' => $number_of_posts,
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
				$all_likes = gt3pb_get_option("likes");
                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
				$featured_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);

                if (strlen($featured_image[0]) > 0) {
                    $featured_image_url = aq_resize($featured_image[0], "540", "540", true, true, true);
                    $full_image_url = $featured_image[0];
                    $featured_image_full = '
					<div class="img_block wrapped_img">
						<a class="featured_ico_link" href="' . get_permalink(get_the_ID()) . '"><img alt="'.$featured_alt.'" width="540" height="392" src="' . $featured_image_url . '" /><div class="featured_item_fadder"></div><span class="featured_items_ico"></span></a>
					</div>';
                } else {
                    $featured_image_full = '';
                }

                $gt3_theme_pagebuilder = get_plugin_pagebuilder(get_the_ID());
                if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_pagebuilder['page_settings']['portfolio']['work_link']) > 0) {
                    $linkToTheWork = $gt3_theme_pagebuilder['page_settings']['portfolio']['work_link'];
                    $target = "target='_blank'";
                } else {
                    $linkToTheWork = get_permalink();
                    $target = "";
                }

                $new_term_list = get_the_terms(get_the_id(), "portcat");
				$echoallterm = '';
                if (is_array($new_term_list)) {
                    foreach ($new_term_list as $term) {
                        $tempname = strtr($term->name, array(
                            ' ' => ', ',
                        ));
                        $echoallterm .= $tempname . ", ";
                        $echoterm = $echoallterm;
                    }
                } else {
                    $echoterm = 'Uncategorized';
                }

                $post = get_post();
                $post_excerpt = ((strlen($post->post_excerpt) > 0) ? smarty_modifier_truncate($post->post_excerpt, 80, "") : smarty_modifier_truncate(get_the_content(), 80, ""));

                $compile .= '
                    <li>
                        <div class="item">
							<div class="item_wrapper">
								' . $featured_image_full . '
								<div class="featured_items_body">
									<div class="featured_items_title">
										<h5><a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h5>
										<div class="featured_items_meta">
											<div class="preview_categ">
												' . trim($echoterm, ', ') . '
												<span class="middot">&middot;</span>
												<span class="preview_meta_comments"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' '. __('comments', 'gt3_builder') .'</a></span>
											</div>											
										</div>										
									</div>
									<div class="featured_item_content">
									   ' . esc_html($post_excerpt) . '
									</div>
									<div class="featured_item_footer">
										<div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
										<div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_port">
											<i class="stand_icon '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
											<span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
										</div>
										<a class="morelink" href="' . get_permalink(get_the_ID()) . '">' . __('Read more', 'gt3_builder') . '</a>
									</div>
								</div>
							</div>
                        </div>
                    </li>
                    ';

            endwhile;

            $compile .= '
                </ul>
            </div>
        </div>
        ';
		$GLOBALS['showOnlyOneTimeJS']['gallery_likes'] = "
		<script>
			jQuery(document).ready(function($) {
				jQuery('.gallery_likes_add').click(function(){
				var gallery_likes_this = jQuery(this);
				if (!jQuery.cookie(gallery_likes_this.attr('data-modify')+gallery_likes_this.attr('data-attachid'))) {
					jQuery.post(gt3_ajaxurl, {
						action:'add_like_attachment',
						attach_id:jQuery(this).attr('data-attachid')
					}, function (response) {
						jQuery.cookie(gallery_likes_this.attr('data-modify')+gallery_likes_this.attr('data-attachid'), 'true', { expires: 7, path: '/' });
						gallery_likes_this.addClass('already_liked');
						gallery_likes_this.find('i').removeClass('icon-heart-o').addClass('icon-heart');
						gallery_likes_this.find('span').text(response);
					});
				}
				});
			});
		</script>
		";		
	
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