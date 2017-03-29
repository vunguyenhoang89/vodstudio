<?php

class counter_shortcode {

	public function register_shortcode($shortcodeName) {
		function shortcode_counter($atts, $content = null) {

            if (!isset($compile)) {$compile='';}

			extract( shortcode_atts( array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
				'stat_title' => '',
				'stat_text' => '',
                 'icon_type' => '',
			), $atts ) );

			wp_enqueue_script('gt3_waypoint_js', GT3PBPLUGINROOTURL . '/js/waypoint.js', array(), false, true);

            #heading
            if (strlen($heading_color)>0) {$custom_color = "color:#{$heading_color};";}
            if (strlen($heading_text)>0) {
                $compile .= "<div class='bg_title'><".$heading_size." style='".(isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:'.$heading_alignment.';' : '')."' class='headInModule'>{$heading_text}</".$heading_size."></div>";
            }

            $compile .= "		
			<div class='module_content shortcode_counter'>
				<div class='counter_wrapper'>
					<div class='counter_ico_wrapper'>
						<span class='ico'><i class=".$icon_type."></i></span>						
					</div>
					<div class='counter_content'>
						<div class='counter_body'>
							<span class='counter_title'>".$stat_title."</span>
							<span class='stat_count' data-count='".$content."'>0</span>
							<div class='stat_temp'></div>
						</div>
					</div>
				</div>
			</div>
			";

            $compile .= gt3Helper::getInstance()->getOneTimeCode($uniquid = "counter", $code = "
			<script>
				jQuery(document).ready(function($) {
					if (jQuery(window).width() > 760) {						
						jQuery('.shortcode_counter').each(function(){							
							if (jQuery(this).offset().top < jQuery(window).height()) {
								if (!jQuery(this).hasClass('done')) {
									var set_count = jQuery(this).find('.stat_count').attr('data-count');
									jQuery(this).find('.stat_temp').stop().animate({width: set_count}, {duration: 3000, step: function(now) {
											var data = Math.floor(now);
											jQuery(this).parents('.counter_wrapper').find('.stat_count').html(data);
										}
									});	
									jQuery(this).addClass('done');
									jQuery(this).find('.stat_count');
								}							
							} else {
								jQuery(this).waypoint(function(){
									if (!jQuery(this).hasClass('done')) {
										var set_count = jQuery(this).find('.stat_count').attr('data-count');
										jQuery(this).find('.stat_temp').stop().animate({width: set_count}, {duration: 3000, step: function(now) {
												var data = Math.floor(now);
												jQuery(this).parents('.counter_wrapper').find('.stat_count').html(data);
											}
										});	
										jQuery(this).addClass('done');
										jQuery(this).find('.stat_count');
									}
								},{offset: 'bottom-in-view'});								
							}														
						});
					} else {
						jQuery('.shortcode_counter').each(function(){							
							var set_count = jQuery(this).find('.stat_count').attr('data-count');
							jQuery(this).find('.stat_temp').animate({width: set_count}, {duration: 3000, step: function(now) {
									var data = Math.floor(now);
									jQuery(this).parents('.counter_wrapper').find('.stat_count').html(data);
								}
							});
							jQuery(this).find('.stat_count');
						},{offset: 'bottom-in-view'});	
					}
				});
			</script>
			");
				
			return $compile;
		}
		add_shortcode($shortcodeName, 'shortcode_counter');
	}
}

#Shortcode name
$shortcodeName="counter";
#Register shortcode & set parameters
$counter = new counter_shortcode();
$counter->register_shortcode($shortcodeName);

?>