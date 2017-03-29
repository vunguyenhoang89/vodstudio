<?php

#diagramm_item
function diagramm_item($atts, $content = null)
{
    if (!isset($compile)) {$compile='';}

    extract(shortcode_atts(array(
        'heading_alignment' => 'left',
        'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
        'heading_color' => '',
        'heading_text' => '',
		'diag_width' => '',
		'diag_title' => '',
        'percent' => '10',
    ), $atts));

	wp_enqueue_script('gt3_waypoint_js', GT3PBPLUGINROOTURL . '/js/waypoint.js', array(), false, true);
	wp_enqueue_script('gt3_chart_js', GT3PBPLUGINROOTURL . '/js/chart.js', array(), false, true);

    $compile .= '<div class="skill_li" style="width:'.$diag_width.'%"><div class="skill_wrapper"><div class="skill_item"><div class="chart" data-percent="'.$percent.'">'.$percent.'<span>%</span></div><div class="skill_content"><span class="skill_title">'.$diag_title.'</span><div class="skill_descr">'.$content.'</div></div></div></div></div>';
	
    return $compile;
}

add_shortcode('diagramm_item', 'diagramm_item');


class diagramm_shortcode
{

    public function register_shortcode($shortcodeName)
    {
        function shortcode_diagramm_shortcode($atts, $content = null)
        {
            if (!isset($compile)) {$compile='';}

            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
				'diagram_bg' => '#f2f2f2',
				'diagram_color' => '#404040',
				'bar_width' => '8px',
				'diagram_size' => '60px',
				'percent_size' => '14px',				
                'title' => '',
                'expanded_state' => '',
            ), $atts));

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            }
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><" . $heading_size . " style='" . (isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:'.$heading_alignment.';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "></a></div>";
            }

            $compile .= "
                <div class='shortcode_diagramm_shortcode diagramm'><div class='skills_list' data-bg='".$diagram_bg."' data-color='".$diagram_color."' data-width='".$bar_width."' data-size='".$diagram_size."' data-fontsize='".$percent_size."'>" . do_shortcode($content) . "<div class='clear'></div></div></div>
			";

            $compile .= gt3Helper::getInstance()->getOneTimeCode($uniquid = "diagram", $code = "
			<script>
				jQuery(document).ready(function($) {					
					jQuery('.chart').each(function(){
						jQuery(this).css({'font-size' : jQuery(this).parents('.skills_list').attr('data-fontsize'), 'line-height' : jQuery(this).parents('.skills_list').attr('data-size')});
						jQuery(this).find('span').css('font-size' , jQuery(this).parents('.skills_list').attr('data-fontsize'));
					});

					if (jQuery(window).width() > 760) {
						jQuery('.skill_li').waypoint(function(){
							jQuery('.chart').each(function(){
								jQuery(this).easyPieChart({
									barColor: jQuery(this).parents('.skills_list').attr('data-color'),
									trackColor: jQuery(this).parents('.skills_list').attr('data-bg'),
									scaleColor: false,
									lineCap: 'square',
									lineWidth: parseInt(jQuery(this).parents('.skills_list').attr('data-width')),
									size: parseInt(jQuery(this).parents('.skills_list').attr('data-size')),
									animate: 1500
								});
							});
						},{offset: 'bottom-in-view'});
					} else {
						jQuery('.chart').each(function(){
							jQuery(this).easyPieChart({
								barColor: jQuery(this).parents('.skills_list').attr('data-color'),
								trackColor: jQuery(this).parents('.skills_list').attr('data-bg'),
								scaleColor: false,
								lineCap: 'square',
								lineWidth: parseInt(jQuery(this).parents('.skills_list').attr('data-width')),
								size: parseInt(jQuery(this).parents('.skills_list').attr('data-size')),
								animate: 1500
							});
						});
					}
				});
			</script>
			");

            return $compile;
        }

        add_shortcode($shortcodeName, 'shortcode_diagramm_shortcode');
    }
}


#Shortcode name
$shortcodeName = "diagramm";
$diagramm_shortcode = new diagramm_shortcode();
$diagramm_shortcode->register_shortcode($shortcodeName);

?>