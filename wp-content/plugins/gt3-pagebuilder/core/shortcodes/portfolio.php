<?php

class portfolio_shortcode
{
    public function register_shortcode($shortcodeName)
    {
        function shortcode_portfolio($atts, $content = null)
        {
			if (!isset($compile)) {$compile='';}
			
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
            }

            $post_type_terms = array();
            if (strlen($selected_categories) > 0) {
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
                $post_type_terms = $_GET['slug'];
            }
            if (count($post_type_terms) > 0) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'portcat',
                        'field' => 'id',
                        'terms' => $post_type_terms
                    )
                );
            }

            $wp_query_in_shortcodes->query($args);

            $i = 1;

            while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();

                $pf = get_post_format();
                if (empty($pf)) $pf = "text";
                $pagebuilder = get_plugin_pagebuilder(get_the_ID());

                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'single-post-thumbnail');
                if (strlen($featured_image[0]) < 1) {
                    $featured_image[0] = "";
                }

                if (isset($pagebuilder['page_settings']['portfolio']['work_link']) && strlen($pagebuilder['page_settings']['portfolio']['work_link']) > 0) {
                    $linkToTheWork = $pagebuilder['page_settings']['portfolio']['work_link'];
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
                }


                #Portfolio 1
                if ($view_type == "1 column") {

					$port_content_show = ((strlen(get_the_excerpt()) > 0) ? get_the_excerpt() : smarty_modifier_truncate(get_the_content(), 470));
					
                    $compile .= '
            <div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element gt3_row-fluid portfolio_item">
                <div class="portfolio_item_img gt3_col6">
                    <a ' . $target . ' href="' . $linkToTheWork . '">
                        <img src="' . aq_resize($featured_image[0], "570", "380", true, true, true) . '" alt="">
                    </a>
                </div>
                <div class="portfolio_dscr gt3_col6">
					<div class="portfolio_preview_topline">
                    	<a class="portfolio_1col_title" href="' . $linkToTheWork . '">' . get_the_title() . '</a>
						<div class="port_preview_meta">
							<span class="preview_meta_author">'.__('by', 'gt3_builder').' <a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_the_author_meta('display_name') . '</a></span>
							<span class="preview_categ">'.__('in', 'gt3_builder').' ' . trim($tempname, ', ') . '</span>';

                    if (isset($pagebuilder['page_settings']['portfolio']['skills']) && is_array($pagebuilder['page_settings']['portfolio']['skills'])) {
                        foreach ($pagebuilder['page_settings']['portfolio']['skills'] as $skillkey => $skillvalue) {
                            $compile .= '<span class="preview_skills">' . $skillvalue['name'] . ': ';
                            $compile .= $skillvalue['value'] . '</span>';
                        }
                    }

                    $compile .= '
						</div>						
					</div>
					' . $port_content_show . ' <a href="'.get_permalink(get_the_id()).'">'. __('Read more!', 'gt3_builder') .'</a>
                </div>
            </div>
            ';
                }
                #END Portfolio 1


                #Portfolio 2
                if ($view_type == "2 columns") {
                    $compile .= '
            <div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item">
				<div class="portfolio_item_wrapper">
						<div class="portfolio_item_img portfolio_item_img_fx gallery_item_wrapper">
							<img src="' . aq_resize($featured_image[0], "570", "380", true, true, true) . '" alt="" width="570" height="380">
							<div class="gallery_fadder"></div>
							<a href="' . $featured_image[0] . '" class="prettyPhoto portfolio_zoom" rel="prettyPhoto[gallery1]"><i class="icon-search"></i></a>
							<a href="' . $linkToTheWork . '" class="portfolio_link"><i class="icon-link"></i></a>
						</div>
						<div class="portfolio_content">
							<div class="portfolio_content_wrapper">
								<a class="port_item_title" href="' . $linkToTheWork . '">' . get_the_title() . '</a>
							</div>
						</div>
				</div>
            </div>
            ';
                }
                #END Portfolio 2


                #Portfolio 3
                if ($view_type == "3 columns") {
                    $compile .= '
            <div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item">
				<div class="portfolio_item_wrapper">
						<div class="portfolio_item_img portfolio_item_img_fx  gallery_item_wrapper">
							<img src="' . aq_resize($featured_image[0], "570", "380", true, true, true) . '" alt="" width="570" height="380">
							<div class="gallery_fadder"></div>
							<a href="' . $featured_image[0] . '" class="prettyPhoto portfolio_zoom" rel="prettyPhoto[gallery1]"><i class="icon-search"></i></a>
							<a href="' . $linkToTheWork . '" class="portfolio_link"><i class="icon-link"></i></a>
						</div>
						<div class="portfolio_content">
							<div class="portfolio_content_wrapper">
								<a class="port_item_title" href="' . $linkToTheWork . '">' . get_the_title() . '</a>
							</div>
						</div>
				</div>
            </div>
            ';
                }
                #END Portfolio 3


                #Portfolio 4
                if ($view_type == "4 columns") {
                    $compile .= '
            <div data-category="' . $echoallterm . '" class="' . $echoallterm . ' element portfolio_item">
				<div class="portfolio_item_wrapper">
						<div class="portfolio_item_img portfolio_item_img_fx  gallery_item_wrapper">
							<img src="' . aq_resize($featured_image[0], "570", "380", true, true, true) . '" alt="" width="570" height="380">
							<div class="gallery_fadder"></div>
							<a href="' . $featured_image[0] . '" class="prettyPhoto portfolio_zoom" rel="prettyPhoto[gallery1]"><i class="icon-search"></i></a>
							<a href="' . $linkToTheWork . '" class="portfolio_link"><i class="icon-link"></i></a>
						</div>
						<div class="portfolio_content">
							<div class="portfolio_content_wrapper">
								<a class="port_item_title" href="' . $linkToTheWork . '">' . get_the_title() . '</a>
							</div>
						</div>
				</div>
            </div>
            ';
                }
                #END Portfolio 4

                $i++;
                unset($echoallterm, $pf);
            endwhile;
            $compile .= '<div class="clear"></div></div>';

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