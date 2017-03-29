<?php

#toggles_item
function toggles_item($atts, $content = null)
{

    global $toggtemmpi;
    if (!isset($compile)) {
        $compile = '';
    }

    extract(shortcode_atts(array(
        'heading_alignment' => 'left',
        'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
        'heading_color' => '',
        'heading_text' => '',
        'title' => '',
        'expanded_state' => '',
    ), $atts));


    $compile .= "<div data-count='" . $toggtemmpi . "' class='shortcode_toggles_item_title expanded_" . $expanded_state . "'>" . $title . "<span class='ico'></span></div><div class='shortcode_toggles_item_body'><div class='ip'>" . do_shortcode($content) . "</div></div>";

    $toggtemmpi++;

    return $compile;

    //wp_enqueue_script('toggle', get_template_directory_uri() . '/js/toggle.js');

}

add_shortcode('toggles_item', 'toggles_item');


class toggles_shortcode
{

    public function register_shortcode($shortcodeName)
    {
        function shortcode_toggles_shortcode($atts, $content = null)
        {

            if (!isset($compile)) {
                $compile = '';
            }

            extract(shortcode_atts(array(
                'heading_alignment' => 'left',
                'heading_size' => $GLOBALS["pbconfig"]['default_heading_in_module'],
                'heading_color' => '',
                'heading_text' => '',
                'title' => '',
            ), $atts));

            $toggtemmpi = 1;

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            }
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><" . $heading_size . " style='" . (isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:' . $heading_alignment . ';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "></div>";
            }

            $compile .= "<div class='shortcode_toggles_shortcode toggles'>" . do_shortcode($content) . "</div>";

            $compile .= gt3Helper::getInstance()->getOneTimeCode($uniquid = "toggles_accordion", $code = "
            <script>
                jQuery(document).ready(function($) {
                    $('.shortcode_accordion_item_title').click(function(){
                        if (!$(this).hasClass('state-active')) {
                            $(this).parents('.shortcode_accordion_shortcode').find('.shortcode_accordion_item_body').slideUp('fast');
                            $(this).next().slideToggle('fast');
                            $(this).parents('.shortcode_accordion_shortcode').find('.state-active').removeClass('state-active');
                            $(this).addClass('state-active');
                        }
                    });
                    $('.shortcode_toggles_item_title').click(function(){
                        $(this).next().slideToggle('fast');
                        $(this).toggleClass('state-active');
                    });

                    $('.shortcode_accordion_item_title.expanded_yes, .shortcode_toggles_item_title.expanded_yes').each(function( index ) {
                        $(this).next().slideDown('fast');
                        $(this).addClass('state-active');
                    });
                });
            </script>
            ");

            return $compile;
        }

        add_shortcode($shortcodeName, 'shortcode_toggles_shortcode');
    }
}

#Shortcode name
$shortcodeName = "toggles_shortcode";
$toggles_shortcode = new toggles_shortcode();
$toggles_shortcode->register_shortcode($shortcodeName);

?>