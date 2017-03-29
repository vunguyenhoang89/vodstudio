<?php

#accordion_item
function accordion_item($atts, $content = null)
{
    global $acctemmpi;
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

    $compile .= "<div data-count='" . $acctemmpi . "' class='shortcode_accordion_item_title expanded_" . $expanded_state . "'>" . $title . "<span class='ico'></span></div><div class='shortcode_accordion_item_body'><div class='ip'>" . $content . "</div></div>";

    $acctemmpi++;

    return $compile;
}

add_shortcode('accordion_item', 'accordion_item');


class accordion_shortcode
{

    public function register_shortcode($shortcodeName)
    {
        function shortcode_accordion_shortcode($atts, $content = null)
        {
            global $acctemmpi;
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

            $acctemmpi = 1;

            #heading
            if (strlen($heading_color) > 0) {
                $custom_color = "color:#{$heading_color};";
            }
            if (strlen($heading_text) > 0) {
                $compile .= "<div class='bg_title'><" . $heading_size . " style='" . (isset($custom_color) ? $custom_color : '') . ((strlen($heading_alignment) > 0 && $heading_alignment !== 'left') ? 'text-align:' . $heading_alignment . ';' : '') . "' class='headInModule'>{$heading_text}</" . $heading_size . "></div>";
            }

            wp_enqueue_script('jquery-ui-accordion');

            $compile .= "
                <div class='shortcode_accordion_shortcode accordion'>" . do_shortcode($content) . "</div>
			";

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

        add_shortcode($shortcodeName, 'shortcode_accordion_shortcode');
    }
}

#Shortcode name
$shortcodeName = "accordion_shortcode";
$accordion_shortcode = new accordion_shortcode();
$accordion_shortcode->register_shortcode($shortcodeName);
?>