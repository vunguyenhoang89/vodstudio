<?php

class portfolio_shortcode
{
    public function register_shortcode($shortcodeName)
    {
        function shortcode_portfolio($atts, $content = null)
        {
			if (!isset($compile)) {$compile='';}

            wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
			
            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
                'posts_per_page' => '4',
                'view_type' => '1 column',
                'filter' => 'on',
                'selected_categories' => '',
            ), $atts));

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            } else {
				$custom_color =  "";
			}
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><" . $heading_size . " style='" . $custom_color . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:' . $heading_alignment . ';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "></div>";
            }

            switch ($view_type) {
                case "1 column":
                    $view_type_class = "columns1";
                    BREAK;
                case "2 columns":
                    $view_type_class = "columns2";
                    BREAK;
                case "3 columns":
                    $view_type_class = "columns3";
                    BREAK;
                case "4 columns":
                    $view_type_class = "columns4";
                    BREAK;
                case "Fullwidth":
                    $view_type_class = "fw";
                    BREAK;
                case "Masonry":
                    $view_type_class = "masonry";
                    BREAK;
            }						

            $post_type_terms = array();
			$post_type_field = '';
            if (strlen($selected_categories) > 0) {
				$post_type_field = 'id';
                $post_type_terms = explode(",", $selected_categories);				
            }

            #Filter
            if ($filter == "on") {
                $compile .= showPortCats($post_type_terms);
            }

            $compile .= '<div class="portfolio_block image-grid ' . $view_type_class . '" id="list">';
            global $wp_query_in_shortcodes;
            $wp_query_in_shortcodes = new WP_Query();
            global $paged;
            $args = array(
                'post_type' => 'port',
                'order' => 'DESC',
                'paged' => $paged,
                'posts_per_page' => $posts_per_page,
            );

            if (isset($_GET['slug']) && strlen($_GET['slug']) > 0) {
				$post_type_field = 'slug';
                $post_type_terms = $_GET['slug'];
            }			
            if (count($post_type_terms) > 0) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'portcat',
						'field' => $post_type_field,
						'terms' => $post_type_terms
					)
				);
            }
            $wp_query_in_shortcodes->query($args);

            $i = 1;
			
			$all_likes = gt3pb_get_option("likes");

            while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();

                $pf = get_post_format();
                if (empty($pf)) $pf = "text";
                $gt3_theme_pagebuilder = get_plugin_pagebuilder(get_the_ID());

                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'single-post-thumbnail');
				$featured_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
                if (strlen($featured_image[0]) < 1) {
                    $featured_image[0] = "";
                }

                if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_pagebuilder['page_settings']['portfolio']['work_link']) > 0) {
                    $linkToTheWork = $gt3_theme_pagebuilder['page_settings']['portfolio']['work_link'];
                    $target = "target='_blank'";
                } else {
                    $linkToTheWork = get_permalink();
                    $target = "";
                }

                if (!isset($echoallterm)) {
                    $echoallterm = '';
                }
                $new_term_list = get_the_terms(get_the_id(), "portcat");
                if (is_array($new_term_list)) {
                    foreach ($new_term_list as $term) {
                        $tempname = strtr($term->name, array(
                            ' ' => ', ',
                        ));
                        $echoallterm .= strtolower($tempname) . " ";
                        $echoterm = $term->name;
                    }
                } else {
                    $tempname = 'Uncategorized';
					$echoterm = 'Uncategorized';
                }

                $GLOBALS['showOnlyOneTimeJS']['port_content'] = "
                <script>
                    jQuery(document).ready(function($) {
						jQuery('.portfolio_title').each(function(){
							jQuery(this).css('margin-top', -1*jQuery(this).height()/2+'px');
						});
                    });
                </script>
                ";


                #Portfolio 1
                if ($view_type == "1 column") {
					if ( !has_excerpt() ) {
						  $port_content_show =  smarty_modifier_truncate(get_the_content(), 470);
					} else { 
						  $port_content_show = get_the_excerpt();
					}					
					$porftolio_pb = gt3_get_theme_pagebuilder(get_the_ID());
                    $compile .= '
            <div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item">
                <div class="portfolio_item_img gallery_item_wrapper">
					<img src="' . aq_resize($featured_image[0], "560", "450", true, true, true) . '" alt="'.$featured_alt.'">
                </div>
                <div class="portfolio_dscr">
                   	<div class="portfolio_dscr_top">
						<h3><a target="' . $target . '" href="' . $linkToTheWork . '">' . get_the_title() . '</a></h3>
						<div class="listing_meta">
							<span class="pf_meta_categ">'. $tempname .'</span><span class="middot">&middot;</span>';
							$compile .= '
							<span class="pf_meta_comments"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' ' . __('comments', 'gt3_builder') .'</a></span>';

							if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['skills']) && is_array($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'])) {
								foreach ($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'] as $skillkey => $skillvalue) {
									$compile .= "<span class='middot'>&middot;</span><span class='preview_skills'>".esc_attr($skillvalue['name'])." ".esc_attr($skillvalue['value'])."</span>";
								}
							}
							
						$compile .= '</div>
					</div>
					' . $port_content_show . '
					<br class="clear">
					<a href="'. get_permalink() .'" class="preview_read_more">'. __('Read More', 'gt3_builder') .'</a>					
                </div>
            </div>
            ';
                }
                #END Portfolio 1


                #Portfolio 2
                if ($view_type == "2 columns") {
                    $compile .= '
				<div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item">
					<div class="portfolio_item_block">
						<div class="portfolio_item_wrapper">
							<div class="img_block wrapped_img">
								<a target="' . $target . '" href="' . $linkToTheWork . '"><img src="' . aq_resize($featured_image[0], "570", "440", true, true, true) . '" alt="'.$featured_alt.'"></a>
							</div>
							<div class="bottom_box">
								<div class="bc_content">
									<h5 class="bc_title"><a target="' . $target . '" href="' . $linkToTheWork . '">' . get_the_title() . '</a></h5>
									<div class="featured_items_meta">
											' . trim($echoterm, ', ') . '
											<span class="middot">&middot;</span>
											<span class="preview_meta_comments"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' '. __('comments', 'gt3_builder') .'</a></span>					
									</div>									
								</div>
								<div class="bc_likes gallery_likes_add '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_port">
									<i class="stand_icon '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
									<span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
								</div>
							</div>
						</div>						
					</div>
				</div>';
                }
                #END Portfolio 2


                #Portfolio 3
                if ($view_type == "3 columns") {
                    $compile .= '
				<div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item">
					<div class="portfolio_item_block">
						<div class="portfolio_item_wrapper">
							<div class="img_block wrapped_img">
								<a target="' . $target . '" href="' . $linkToTheWork . '"><img src="' . aq_resize($featured_image[0], "570", "440", true, true, true) . '" alt="'.$featured_alt.'"></a>
							</div>
							<div class="bottom_box">
								<div class="bc_content">
									<h5 class="bc_title"><a target="' . $target . '" href="' . $linkToTheWork . '">' . get_the_title() . '</a></h5>
									<div class="featured_items_meta">
											' . trim($echoterm, ', ') . '
											<span class="middot">&middot;</span>
											<span class="preview_meta_comments"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' '. __('comments', 'gt3_builder') .'</a></span>					
									</div>									
								</div>
								<div class="bc_likes gallery_likes_add '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_port">
									<i class="stand_icon '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
									<span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
								</div>
							</div>
						</div>						
					</div>
				</div>';
                }
                #END Portfolio 3


                #Portfolio 4
                if ($view_type == "4 columns") {
                    $compile .= '
				<div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item">
					<div class="portfolio_item_block">
						<div class="portfolio_item_wrapper">
							<div class="img_block wrapped_img">
								<a target="' . $target . '" href="' . $linkToTheWork . '"><img src="' . aq_resize($featured_image[0], "570", "440", true, true, true) . '" alt="'.$featured_alt.'"></a>
							</div>
							<div class="bottom_box">
								<div class="bc_content">
									<h5 class="bc_title"><a target="' . $target . '" href="' . $linkToTheWork . '">' . get_the_title() . '</a></h5>
									<div class="featured_items_meta">
											' . trim($echoterm, ', ') . '
											<span class="middot">&middot;</span>
											<span class="preview_meta_comments"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' '. __('comments', 'gt3_builder') .'</a></span>					
									</div>									
								</div>
								<div class="bc_likes gallery_likes_add '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_port">
									<i class="stand_icon '.(isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
									<span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
								</div>
							</div>
						</div>						
					</div>
				</div>';
                }
                #END Portfolio 4

                $i++;
                unset($echoallterm, $pf);
            endwhile;
            $compile .= '<div class="clear"></div></div>';

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
					
					if (jQuery('.pagerblock').size() > 0) {
						jQuery('.pagerblock').addClass('portfolio_pager');
					}
				});
			</script>
			";		
            $compile .= get_plugin_pagination(10, "show_in_shortcodes");

            wp_reset_query();
            return $compile;
        }

        add_shortcode($shortcodeName, 'shortcode_portfolio');
    }
}

#Shortcode name
$shortcodeName = "portfolio";
$portfolio = new portfolio_shortcode();
$portfolio->register_shortcode($shortcodeName);
?>