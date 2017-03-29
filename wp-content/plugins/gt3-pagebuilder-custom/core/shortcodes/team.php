<?php

class ourteam
{
    public function register_shortcode($shortcodeName)
    {
        function shortcode_ourteam($atts, $content = null)
        {
            $compile = '';
            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
                'order' => 'ASC',
                'cpt_ids' => '0',
                'items_per_line' => '1',
            ), $atts));

            if ($items_per_line < 1) {
                $items_per_line = 1;
            }
            $item_width = (100 / $items_per_line);

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            }
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><a href='".esc_js("javascript:void(0)")."' class='btn_carousel_left'></a><" . $heading_size . " style='" . (isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:'.$heading_alignment.';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "><a href='javascript:void(0)' class='btn_carousel_right'></a></div>";
            }

            $compile .= '
        <div class="team_slider">
            <div class="carouselslider teamslider items' . $items_per_line . '" data-count="' . $items_per_line . '">
            	<ul class="item_list">';

            $wp_query = new WP_Query();

            if (strlen($cpt_ids) > 0 && $cpt_ids !== "0") {
                $cpt_ids = explode(",", $cpt_ids);
            }

            if (is_array($cpt_ids) && count($cpt_ids) > 0) {
                $args = array(
                    'post_type' => 'team',
                    'post__in' => $cpt_ids,
                    'order' => $order,
					'posts_per_page' => -1
                );
            } else {
                $args = array(
                    'post_type' => 'team',
                    'order' => $order,
					'posts_per_page' => -1
                );
            }

            $wp_query->query($args);
            while ($wp_query->have_posts()) : $wp_query->the_post();
                $gt3_theme_pagebuilder = get_post_meta(get_the_ID(), "pagebuilder", true);
                $position = $gt3_theme_pagebuilder['page_settings']['team']['position'];

                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
				$featured_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
				
                $compile .= '
					<li style="width:' . $item_width . '%">
						<div class="item_wrapper">
							<div class="item">
								<div class="img_block team_img">';
                if (has_post_thumbnail()) {
                    $compile .= '<img src="' . aq_resize($featured_image[0], "540", "540", true, true, true) . '" alt="' . $featured_alt . '" />';
                }
                $compile .= '
								</div>
								<div class="team_content">
									<div class="team_title">
										<h5>' . get_the_title() . '</h5>
										<div class="op">' . $position . '</div>
									</div>
									<div class="team_desc">' . get_the_content() . '</div>';
					if (isset($gt3_theme_pagebuilder['page_settings']['icons']) ? $socicons = $gt3_theme_pagebuilder['page_settings']['icons'] : $socicons = false);
					if (is_array($socicons)) {
						$compile .= '<div class="team_icons_wrapper">';
						foreach ($socicons as $key => $value) {
							//$compile .= $value['data-icon-code'] . $value['name'] . $value['link'];
							if ($value['link'] == '') $value['link'] = '#';
							$compile .= '<a href="'.$value['link'].'" class="teamlink" title="'.$value['name'].'" style="color:#'.$value['fcolor'].';"><span><i class="'.$value['data-icon-code'].'"></i></span></a>';
						}
						$compile .= '</div>';
					}

					$compile .= '</div>								
							</div>
						</div>
                    </li>
				';

            endwhile;
            wp_reset_query();

            $compile .= '</ul>
             </div>
             <div class="clear"></div>
        </div>
        <div class="clear"></div>';
        return $compile;
        }
        add_shortcode($shortcodeName, 'shortcode_ourteam');
    }
}

#Shortcode name
$shortcodeName = "ourteam";
$shortcode_ourteam = new ourteam();
$shortcode_ourteam->register_shortcode($shortcodeName);

?>