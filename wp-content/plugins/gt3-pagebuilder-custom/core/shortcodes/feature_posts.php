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
                'number_of_posts' => $GLOBALS["pbconfig"]['featured_posts_default_number_of_posts'],
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
            <div class="items' . $posts_per_line . ' featured_posts" data-count="' . $posts_per_line . '">
                <ul class="item_list">
        ';

            $wp_query = new WP_Query();
            $args = array(
                'posts_per_page' => $number_of_posts,
                'post_type' => 'post',
                'post_status' => 'publish',
                'cat' => $selected_categories,
                'orderby' => $sort_type,
                'order' => 'DESC'
            );

            $wp_query->query($args);

            while ($wp_query->have_posts()) : $wp_query->the_post();
				$all_likes = gt3pb_get_option("likes");
                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
				$featured_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);

				if(get_the_category()) $categories = get_the_category();
				$post_categ = '';
				$separator = ', ';
				if ($categories) {						
					foreach($categories as $category) {
						$post_categ = $post_categ .'<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
					}
				}

                if (strlen($featured_image[0]) > 0) {
                    $featured_image_url = aq_resize($featured_image[0], "540", "540", true, true, true);
                    $full_image_url = $featured_image[0];
                    $featured_image_full = '
					<div class="img_block wrapped_img">
						<a href="' . get_permalink(get_the_ID()) . '"><img alt="'.$featured_alt.'" src="' . $featured_image_url . '" /><div class="featured_item_fadder"></div><span class="featured_items_ico"></span></a>
					</div>';
                } else {
                    $featured_image_full = '';
                }

                $post = get_post();
                $post_excerpt = ((strlen($post->post_excerpt) > 0) ? smarty_modifier_truncate($post->post_excerpt, 80, "") : smarty_modifier_truncate(get_the_content(), 80, ""));

                $compile .= '
                    <li>
                        <div class="item">
							<div class="item_wrapper">
								' . $featured_image_full . '
								<div class="featured_items_body featured_posts_body">
									<div class="featured_items_title">
										<h5><a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h5>
										<div class="preview_categ">
											<span class="preview_meta_data">' . get_the_time("F d, Y") . '</span>
											<span class="middot">&middot;</span>
											<span class="preview_meta_comments"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' '. __('comments', 'gt3_builder') .'</a></span>
										</div>										
									</div>
									<div class="featured_item_content">
									   ' . esc_html($post_excerpt) . '										
									</div>
									<div class="featured_item_footer">
										<div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
										<div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_post">
											<i class="stand_icon '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
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

        add_shortcode($shortcodeName, 'shortcode_feature_posts');
    }
}

#Shortcode name
$shortcodeName = "feature_posts";
$shortcode_feature_posts = new feature_posts();
$shortcode_feature_posts->register_shortcode($shortcodeName);
?>