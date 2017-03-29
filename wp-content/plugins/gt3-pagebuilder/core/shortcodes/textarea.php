<?php

class textarea {

	public function register_shortcode($shortcodeName) {
		function shortcode_textarea($atts, $content = null) {

            if (!isset($compile)) {$compile='';}

			extract( shortcode_atts( array(
              'heading_alignment' => 'left',
			  'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
			  'heading_color' => '',
			  'heading_text' => '',
			  'module' => '',
			  'fullwidth_map' => 'no',
			  'text' => '',
			), $atts ) );

            #heading
            if (strlen($heading_color)>0) {$custom_color = "color:#{$heading_color};";}
            if (strlen($heading_text)>0) {
			    $compile .= "<div class='bg_title'><".$heading_size." style='".(isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:'.$heading_alignment.';' : '')."' class='headInModule'>{$heading_text}</".$heading_size."></div>";
            }

            if ($module=="map") {
                $compile .= "
                <div class='module_content'>
                    <div class='".(($fullwidth_map == "yes") ? "fullwidth_map" : "")."'>
                        ".do_shortcode($content)."
                    </div>
                </div>";
            } elseif ($module=="html") {
                $compile .= "
                <div class='module_content'>
                    ".do_shortcode($content)."
                </div>";
            } elseif ($module=="js") {
                $compile .= "
                <div class='module_content'>
                    ".do_shortcode($content)."
                </div>";
            }
            else {
                $compile .= "
                <div class='module_content'>
                    ".do_shortcode($content)."
                </div>";
            }

            return $compile;
		}
		add_shortcode($shortcodeName, 'shortcode_textarea');
	}
}

#Shortcode name
$shortcodeName="textarea";
$shortcode_textarea = new textarea();
$shortcode_textarea->register_shortcode($shortcodeName);

?>