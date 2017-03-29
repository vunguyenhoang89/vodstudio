<?php
/*
Plugin Name: GT3 Page Builder
Plugin URI: http://www.gt3themes.com/
Description: GT3 Page Builder is a powerful WordPress plugin that allows you to create the unlimited number of custom page layouts in WordPress themes. This special drag and drop plugin will save your time when building the pages.
Version: 2.1 (build 823dbc7)
Author: GT3 Themes
Author URI: http://www.gt3themes.com/

--- THIS PLUGIN AND ALL FILES INCLUDED ARE COPYRIGHT © GT3 Themes 2013.
YOU MAY NOT MODIFY, RESELL, DISTRIBUTE, OR COPY THIS CODE IN ANY WAY. ---

*/

define('GT3PBPLUGINROOTURL', plugins_url('/', __FILE__));
define('GT3PBPLUGINPATH', plugin_dir_path(__FILE__));
define('PBIMGURL', GT3PBPLUGINROOTURL . "img/");

add_action('init', 'gt3pb_locale');
function gt3pb_locale()
{
    load_plugin_textdomain('gt3_builder', false, dirname(plugin_basename(__FILE__)) . '/core/languages/');
}

/*Load files*/
require_once(GT3PBPLUGINPATH . "core/loader.php");

/*add_action('admin_menu', 'gt3pb_add_page');
function gt3pb_add_page()
{
    $page = add_menu_page('GT3 Page Builder', 'GT3 Page Builder', 'manage_options', 'gt3pb', 'gt3pb_settings_page');
}*/

#SAVE
add_action('save_post', 'save_postdata');

#REGISTER PAGE BUILDER
add_action('add_meta_boxes', 'add_custom_box');
function add_custom_box()
{
    if (is_array($GLOBALS["pbconfig"]['page_builder_enable_for_posts'])) {
        foreach ($GLOBALS["pbconfig"]['page_builder_enable_for_posts'] as $post_type) {
            add_meta_box(
                'pb_section',
                __('GT3 Page Builder', 'gt3_builder'),
                'pagebuilder_inner_custom_box',
                $post_type
            );
        }
    }
}

function pagebuilder_inner_custom_box($post)
{
    isset($_POST['tinymce_activation_class']) ? $tinymce_activation_class = $_POST['tinymce_activation_class'] : $tinymce_activation_class = '';
    $now_post_type = get_post_type();

    wp_nonce_field(plugin_basename(__FILE__), 'pagebuilder_noncename');
    $pagebuilder = get_plugin_pagebuilder($post->ID);
    if (!is_array($pagebuilder)) {
        $pagebuilder = array();
    }

    global $modules;

#get all sidebars
    $media_for_this_post = get_media_for_this_post(get_the_ID());
    $js_for_pb = "
    <script>
        var post_id = " . get_the_ID() . ";
        var show_img_media_library_page = 1;
    </script>";

    echo $js_for_pb;
    echo "
<!-- popup background -->
<div class='popup-bg'></div>
<div class='waiting-bg'><div class='waiting-bg-img'></div></div>
";
#START BUILDER AREA
    if (in_array($now_post_type, $GLOBALS["pbconfig"]['pb_modules_enabled_for'])) {
        echo "
<div class='pb-cont page-builder-container bbg'>
    <div class='padding-cont main_descr'>" . __("You can use this drag and drop page builder to create unlimited custom page layouts. It is too simple, just click any module below, adjust your own settings and preview the page. That's all.", "gt3_builder") . "</div>
    <div>
        <div class='hideable-content'>
            <div class='padding-cont'>
                <div class='available-modules-cont'>
                    " . get_html_all_available_pb_modules($modules) . "
                </div>
                <div class='clear'></div>
            </div>
            <div class='pb-list-active-modules'>
                <div class='padding-cont'>
                    <ul class='sortable-modules'>
                    ";

        if (isset($pagebuilder['modules']) && is_array($pagebuilder['modules'])) {
            foreach ($pagebuilder['modules'] as $moduleid => $module) {
                if ($module['size'] == "block_1_4") {
                    $size_caption = "1/4";
                }
                if ($module['size'] == "block_1_3") {
                    $size_caption = "1/3";
                }
                if ($module['size'] == "block_1_2") {
                    $size_caption = "1/2";
                }
                if ($module['size'] == "block_2_3") {
                    $size_caption = "2/3";
                }
                if ($module['size'] == "block_3_4") {
                    $size_caption = "3/4";
                }
                if ($module['size'] == "block_1_1") {
                    $size_caption = "1/1";
                }
                echo get_pb_module($module['name'], $module['caption'], $moduleid, $pagebuilder, $module['size'], $size_caption, $tinymce_activation_class);
            }
        }

        echo "
                    </ul>
                    <div class='clear'></div>
                </div>
            </div>
        </div>
    </div>
</div>
";

	}

#GALLERY AREA
    if ($now_post_type == "gallery") {
        echo "
        <!-- FULLSCREEN SLIDER SETTINGS -->
                <div class='padding-cont  stand-s pt_" . $now_post_type . "'>
                    <div class='bg_or_slider_option slider_type active'>
                        <input type='hidden' name='settings_type' value='fullscreen' class='settings_type'>
                        <div class='hideable-area'>
                            <div class='padding-cont help text-shadow2'></div>
                            <div class='padding-cont' style='padding-bottom:11px;'>
                                <div class='selected_media'>
                                    <div class='append_block'>
                                         <ul class='sortable-img-items'>
                                           " . get_slider_items("fullscreen", (isset($pagebuilder['sliders']['fullscreen']['slides']) ? $pagebuilder['sliders']['fullscreen']['slides'] : '')) . "
                                         </ul>
                                    </div>
                                    <div class='clear'></div>
                                </div>
                            </div>
                            <div style='' class='hr_double style2'></div>
                            <div class='padding-cont' style='padding-top:12px;'>
								<div class='gt3settings_box no-margin'>									
									<div class='gt3settings_box_title'><h2>" . __('Select Media', 'gt3_builder') . "</h2></div>
									<div class='gt3settings_box_content'>
										<div class='available_media'>
											<div class='ajax_cont'>
												" . get_media_html($media_for_this_post, "small") . "
											</div>
											<div class='img-item style_small add_image_to_sliders_available_media cboxElement'>
												<div class='img-preview'>
													<img alt='' src='" . PBIMGURL . "/add_image.png'>
												</div>
											</div><!-- .img-item -->
											<div class='img-item style_small add_video_slider'>
												<div class='img-preview'>
													<img alt='' class='previmg' data-full-url='" . PBIMGURL . "/video_item.png' src='" . PBIMGURL . "/add_video.png'>
												</div>
											</div><!-- .img-item -->
											<div class='clear'></div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SETTINGS -->";
    }

#TEAM AREA
    if ($now_post_type == "team") {
        echo "
            <!-- TEAM SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "'>

            <div class='partners_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Advanced Options</h2></div>
				<div class='gt3settings_box_content'>
					<div class='append_items'>
						<label for='position_link' class='label_type1'>Position:</label> <input type='text' value='" . (isset($pagebuilder['page_settings']['team']['position']) ? $pagebuilder['page_settings']['team']['position'] : '') . "' id='position_link' name='pagebuilder[page_settings][team][position]' class='position_link itt_type1'>
						<div>
							<div class='hleft' style='vertical-align:top;'>" . __('Social Icons', 'gt3_builder') . "</div>
							<div class='hright'>
								<div class='added_icons sortable_icons_list'>";

        if (isset($pagebuilder['page_settings']['icons']) && is_array($pagebuilder['page_settings']['icons'])) {
            foreach ($pagebuilder['page_settings']['icons'] as $key => $value) {
                echo "
					<div class='stand_iconsweet ui-state-default'>
						<span class='stand_icon-container'><i class='stand_icon " . $value['data-icon-code'] . "'></i></span>
						<input type='hidden' name='pagebuilder[page_settings][icons][" . $key . "][data-icon-code]' value='" . $value['data-icon-code'] . "'>
						<input class='icon_name' type='text' name='pagebuilder[page_settings][icons][" . $key . "][name]' value='" . $value['name'] . "' placeholder='" . __('Give Some Name', 'gt3_builder') . "'>
						<input class='icon_link' type='text' name='pagebuilder[page_settings][icons][" . $key . "][link]' value='" . $value['link'] . "' placeholder='" . __('Give Some Link', 'gt3_builder') . "'>
						<input class='cpicker' type='text' name='pagebuilder[page_settings][icons][" . $key . "][fcolor]' value='" . $value['fcolor'] . "' placeholder='" . __('Foreground Color', 'gt3_builder') . "'>						
						<input type='text' value='' class='cpicker_preview' disabled='disabled' style='background-color:#" . $value['fcolor'] . "'>
						<input class='cpicker' type='text' name='pagebuilder[page_settings][icons][" . $key . "][bcolor]' value='" . $value['bcolor'] . "' placeholder='" . __('Background Color', 'gt3_builder') . "'>
						<input type='text' value='' class='cpicker_preview' disabled='disabled' style='background-color:#" . $value['bcolor'] . "' >
						<span class='remove_me'><i class='stand_icon icon-times'></i></span>
					</div>";
            }
        }

        echo "
								</div>
								<div class='social_list_for_select'>";

        foreach ($GLOBALS["pbconfig"]['all_available_font_icons'] as $icon) {
            echo "<div class='stand_social'><i data-icon-code='" . $icon . "' class='stand_icon " . $icon . "'></i></div>";
        }

        echo "
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }
	
#TESTIMONIALS AREA
    if ($now_post_type == "testimonials") {
        echo "
            <!-- TESTIMONIALS SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "'>

            <div class='testimonials_cont'>
                <div class='append_items'>
                    <label for='testimonials_author' class='label_type1'>" . __('Author:', 'gt3_builder') . "</label> <input type='text' value='" . (isset($pagebuilder['page_settings']['testimonials']['testimonials_author']) ? $pagebuilder['page_settings']['testimonials']['testimonials_author'] : '') . "' id='testimonials_author' name='pagebuilder[page_settings][testimonials][testimonials_author]' class='testimonials_author itt_type1'><br>
                    <label for='testimonials_position' class='label_type1'>" . __('Company:', 'gt3_builder') . "</label> <input type='text' value='" . (isset($pagebuilder['page_settings']['testimonials']['company']) ? $pagebuilder['page_settings']['testimonials']['company'] : '') . "' id='testimonials_company' name='pagebuilder[page_settings][testimonials][company]' class='testimonials_company itt_type1'>
                </div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

#PARTNERS AREA
    if ($now_post_type == "partners") {
        echo "
            <!-- PARTNERS SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='partners_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Advanced options</h2></div>
				<div class='gt3settings_box_content'>
					<div class='append_items'>
						<label for='partners_link' class='label_type1'>" . __('External Link:', 'gt3_builder') . "</label> <input type='text' value='" . (isset($pagebuilder['page_settings']['partners']['partners_link']) ? $pagebuilder['page_settings']['partners']['partners_link'] : '') . "' id='partners_link' name='pagebuilder[page_settings][partners][partners_link]' class='partners_link itt_type1'>
					</div>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

	
#END BUILDER AREA

#JS FOR AJAX UPLOADER
    ?>
    <script type="text/javascript">

        function reactivate_ajax_image_upload() {
            var admin_ajax = '<?php echo admin_url("admin-ajax.php"); ?>';
            jQuery('.btn_upload_image').each(function () {
                var clickedObject = jQuery(this);
                var clickedID = jQuery(this).attr('id');
                new AjaxUpload(clickedID, {
                    action: '<?php echo admin_url("admin-ajax.php"); ?>',
                    name: clickedID, // File upload name
                    data: { // Additional data to send
                        action: 'mix_ajax_post_action',
                        type: 'upload',
                        data: clickedID },
                    autoSubmit: true, // Submit file after selection
                    responseType: false,
                    onChange: function (file, extension) {
                    },
                    onSubmit: function (file, extension) {
                        clickedObject.text('Uploading'); // change button text, when user selects file
                        this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
                        interval = window.setInterval(function () {
                            var text = clickedObject.text();
                            if (text.length < 13) {
                                clickedObject.text(text + '.');
                            }
                            else {
                                clickedObject.text('Uploading');
                            }
                        }, 200);
                    },
                    onComplete: function (file, response) {

                        window.clearInterval(interval);
                        clickedObject.text('Upload Image');
                        this.enable(); // enable upload button

                        // If there was an error
                        if (response.search('Upload Error') > -1) {
                            var buildReturn = '<span class="upload-error">' + response + '</span>';
                            jQuery(".upload-error").remove();
                            clickedObject.parent().after(buildReturn);

                        }
                        else {
                            var buildReturn = '<a href="' + response + '" class="uploaded-image" target="_blank"><img class="hide option-image" id="image_' + clickedID + '" src="' + response + '" alt="" /></a>';

                            jQuery(".upload-error").remove();
                            jQuery("#image_" + clickedID).remove();
                            clickedObject.parent().next().after(buildReturn);
                            jQuery('img#image_' + clickedID).fadeIn();
                            clickedObject.next('span').fadeIn();
                            clickedObject.parent().prev('input').val(response);
                        }
                    }
                });
            });
        }


        jQuery(document).ready(function () {
            reactivate_ajax_image_upload();
        });
    </script>
    <?php #END JS FOR AJAX UPLOADER ?>

<?php
#DEVELOPER CONSOLE
    if (gt3pb_get_option("dev_console") == "true") {
        echo "<pre style='color:#000000;'>";
        print_r($pagebuilder);
        echo "</pre>";
    }

}

#START SAVE MODULE
function save_postdata($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    #CHECK PERMISSIONS
    if (!current_user_can('edit_post', $post_id))
        return;

    #START SAVING
    if (!isset($_POST['pagebuilder'])) {
        $pbsavedata = array();
    } else {
        $pbsavedata = $_POST['pagebuilder'];
        update_theme_pagebuilder($post_id, "pagebuilder", $pbsavedata);
    }
}

?>