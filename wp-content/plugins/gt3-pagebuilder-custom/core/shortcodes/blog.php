<?php

class blog_shortcode
{

    public function register_shortcode($shortcodeName)
    {
        function shortcode_blog($atts, $content = null)
        {
			wp_enqueue_script('gt3_nivo_js', get_template_directory_uri() . '/js/nivo.js', array(), false, true);			
			wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
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
				'view_type' => '',
                'masonry' => 'no',
                'cat_ids' => 'all',
            ), $atts));
			
			$masonry_width = 100/$posts_per_line;
			
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
			
			$GLOBALS['showOnlyOneTimeJS']['BlogListing'] = "
				<script>				
					jQuery(document).ready(function($) {
						iframe16x9(jQuery('.module_blog'));
						jQuery('.pf_output_container').each(function(){
							if (jQuery(this).html() == '') {
								jQuery(this).parents('.post_preview_wrapper').addClass('no_pf');
							} else {
								jQuery(this).parents('.post_preview_wrapper').addClass('has_pf');
							}
						});
					});
					jQuery(window).resize(function(){
						iframe16x9(jQuery('.module_blog'));
					});
					jQuery(window).load(function(){
						iframe16x9(jQuery('.module_blog'));
					});
				</script>
			";
            while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();				
                $gt3_theme_pagebuilder = get_post_meta(get_the_ID(), "pagebuilder", true);
				$all_likes = gt3pb_get_option("likes");

				if(get_the_category()) $categories = get_the_category();
				$post_categ = '';
				$separator = ', ';
				if ($categories) {						
					foreach($categories as $category) {
						$post_categ = $post_categ .'<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
					}
				}
				
				if(get_the_tags() !== '') {
					$posttags = get_the_tags();
					
				}			
				if ($posttags) {
					$post_tags = '';
					$post_tags_compile = '<span class="middot">&middot;</span><span class="preview_meta_tags">';
					foreach($posttags as $tag) {
						$post_tags = $post_tags . '<a href="?tag='.$tag->slug.'">'.$tag->name .'</a>'. ', ';					
					}
					$post_tags_compile .= ' '.trim($post_tags, ', ').'</span>';
				} else {
					$post_tags_compile = '';
				}
				$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
				$featured_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
				
				if (strlen($featured_image[0])>0) {
					$pf_url = $featured_image[0];
				} else {
					$pf_url = gt3_get_theme_option("logo");
				}

					if ($view_type == "fimage-left") {
						if (strlen($featured_image[0]) > 0) {
							$hasImage = 'hasImage';							
							$featured_img = '<div class="preview_image"><img src="'. aq_resize($featured_image[0], "570", "490", true, true, true) .'" alt="'.$featured_alt.'" /></div>';
						} else {
							$hasImage = 'noImage';
							$featured_img = '';
						}
						$compile .= '
						<div class="preview_type1 blog_post_preview '. $hasImage .'">';
						// Top Elements
						$compile .= $featured_img .'
								<div class="preview_content">
									<div class="preview_top_wrapper">
										<h4 class="blogpost_title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>
										<div class="listing_meta">
											'. __('by', 'gt3_builder') .' <a href="'.get_author_posts_url( get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a><span class="middot">&middot;</span>
											<span>'. get_the_time("F d, Y") .'</span><span class="middot">&middot;</span>
											<span>'. __('in', 'gt3_builder') .' '.trim($post_categ, ', ').'</span><span class="middot">&middot;</span>
											<span><a href="' . get_comments_link() . '">'. get_comments_number(get_the_ID()) .' '. __('comments', 'gt3_builder') .'</a></span>
											'.$post_tags_compile.'
										</div>
									</div>';		
						$compile .= '<article class="contentarea">
										' . ((strlen(get_the_excerpt()) > 0) ? get_the_excerpt() : get_the_content())   . '
										<br class="clear">
										<a href="'. get_permalink() .'" class="preview_read_more">'. __('Read More', 'gt3_builder') .'</a>
									</article>
								</div>
						</div><!--.blog_post_preview -->';
					} else {

						$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
						if (strlen($featured_image[0])>0) {
							$pf_url = $featured_image[0];
						} else {
							$pf_url = gt3_get_theme_option("logo");
						}
						$compile .= '
						<div class="blog_post_preview preview_type2">';
						// Top Elements										
						$compile .= '<div class="blog_content">
									<div class="preview_top">
										<div class="preview_title">
											<h4 class="blogpost_title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>
											<div class="listing_meta">
												'. __('by', 'gt3_builder') .' <a href="'.get_author_posts_url( get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a><span class="middot">&middot;</span>
												<span>'. get_the_time("F d, Y") .'</span><span class="middot">&middot;</span>
												<span>'. __('in', 'gt3_builder') .' '.trim($post_categ, ', ').'</span><span class="middot">&middot;</span>
												<span><a href="' . get_comments_link() . '">'. get_comments_number(get_the_ID()) .' '. __('comments', 'gt3_builder') .'</a></span>
												'.$post_tags_compile.'
											</div>
										</div><!-- .preview_title -->
										<div class="gallery_likes_add preview_likes '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_post">
											<i class="stand_icon '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
											<span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
										</div><!-- .preview_likes -->
									</div>';
						$compile .= get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder));
						//Featured Image
						$compile .= '<article class="contentarea">
										' . ((strlen(get_the_excerpt()) > 0) ? get_the_excerpt() : get_the_content())   . '
										<br class="clear">
										<a href="'. get_permalink() .'" class="preview_read_more">'. __('Read More', 'gt3_builder') .'</a>										
									</article>
							</div>
						</div><!--.blog_post_preview -->';
					}
            endwhile;

            $GLOBALS['showOnlyOneTimeJS']['nivo_slider'] = "
			<script>
				jQuery(document).ready(function($) {
					jQuery('.nivoSlider').each(function(){
						jQuery(this).nivoSlider({
							directionNav: false,
							controlNav: false,
							effect:'fade',
							pauseTime:4000,
							slices: 1
						});
					});
				});
			</script>
			";

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