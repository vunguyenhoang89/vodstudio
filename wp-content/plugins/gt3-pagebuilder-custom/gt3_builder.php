<?php
/*
Plugin Name: GT3 Page Builder (For SOHO Theme Only)
Plugin URI: http://www.gt3themes.com/
Description: GT3 Page Builder is a powerful WordPress plugin that allows you to create the unlimited number of custom page layouts in WordPress themes. This special drag and drop plugin will save your time when building the pages.
Version: 1.9
Author: GT3 Themes
Author URI: http://www.gt3themes.com/
*/

define('GT3PBPLUGINROOTURL', plugins_url('/', __FILE__));
define('GT3PBPLUGINPATH', plugin_dir_path(__FILE__));
define('PBIMGURL', GT3PBPLUGINROOTURL . "img/");

add_action('plugins_loaded', 'gt3pb_locale');
function gt3pb_locale()
{
	load_plugin_textdomain('gt3_builder', false, dirname( plugin_basename( __FILE__ ) ) . '/core/languages/');
}

/*Load files*/
require_once(GT3PBPLUGINPATH . "core/loader.php");

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

    wp_nonce_field(null, 'pagebuilder_noncename');
    $gt3_theme_pagebuilder = get_plugin_pagebuilder($post->ID);
    if (!is_array($gt3_theme_pagebuilder)) {
        $gt3_theme_pagebuilder = array();
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

        if (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules'])) {
            foreach ($gt3_theme_pagebuilder['modules'] as $moduleid => $module) {
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
                echo get_pb_module($module['name'], $module['caption'], $moduleid, $gt3_theme_pagebuilder, $module['size'], $size_caption, $tinymce_activation_class);
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
#END BUILDER AREA


#POSTFORMATS FOR POST. VISIBLE ONLY ON GT3 THEMES.
    if (GT3THEME_INSTALLED == true && $now_post_type == "post") {
        echo "
<div class='pb-cont page-settings-container'>
    <div class='pb10'>
        <div class='hideable-content'>
            <div class='post-formats-container'>
                <!-- Video post format -->
                <div id='video_sectionid_inner'>
                    <h2>Post Format Video URL:</h2>
                    <input type='text' class='medium textoption type1' name='pagebuilder[post-formats][videourl]' value='" . (isset($gt3_theme_pagebuilder['post-formats']['videourl']) ? $gt3_theme_pagebuilder['post-formats']['videourl'] : "") . "'>
                    <div class='example'>Examples:<br>Youtube - http://www.youtube.com/watch?v=6v2L2UGZJAM<br>Vimeo - http://vimeo.com/47989207</div>
                    <div class='video_height' style='margin-top:15px;'>
                        <div class='enter_option_row'>
                            <h2>Video height</h2>
                            <input type='text' class='medium textoption type1' name='pagebuilder[post-formats][video_height]' value='" . (isset($gt3_theme_pagebuilder['post-formats']['video_height']) ? $gt3_theme_pagebuilder['post-formats']['video_height'] : "") . "' style='width:70px;text-align:center;'>
                        </div>
                    </div>
                </div>
				<!-- Audio post format -->
                <div id='audio_sectionid_inner'>
                    <h2>Post Format Audio Code:</h2>";
					echo '<textarea class="enter_text1 audio_textarea" name="pagebuilder[post-formats][audiourl]">'. (isset($gt3_theme_pagebuilder['post-formats']['audiourl']) ? $gt3_theme_pagebuilder['post-formats']['audiourl'] : "") .'</textarea>';
					
                    echo "
                    <div class='example'>Examples:<br>
						&lt;iframe src='https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/141816093&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true' width='100%' height='166' frameborder='no' scrolling='no'&gt;&lt;/iframe&gt;
					</div>
                </div>				
                <!-- Image post format -->
                <div id='portslides_sectionid_inner'>
                    <div class='portslides_sectionid_title'><h2>Slider Images</h2></div>
                    <div class='selected-images-for-pf'>
                        " . get_selected_pf_images_for_admin($gt3_theme_pagebuilder) . "
                    </div>
					<hr class='img_seperator'>
                    <div class='available-images-for-pf available_media'>
                        <div class='ajax_cont'>
                            " . get_media_html($media_for_this_post, "small") . "
                        </div>
                        <div class='for_post_fomrats img-item style_small add_image_to_sliders_available_media cboxElement'>
                            <div class='img-preview'>
                                <img alt='' src='" . PBIMGURL . "/add_image.png'>
                            </div>
                        </div><!-- .img-item -->
                    </div>
                </div>
            </div>
            <div class='clear'></div>
        </div>
    </div>
</div>";
}

#POSTFORMATS FOR PORT. VISIBLE ONLY ON GT3 THEMES.
    if (GT3THEME_INSTALLED == true && $now_post_type == "port") {
        echo "
		<div class='pb-cont page-settings-container port-pf'>
			<div class='pb10'>
				<div class='hideable-content'>
					<div class='post-formats-container'>
						<!-- Video post format -->
						<div id='video_sectionid_inner'>
							<h2>Post Format Video URL:</h2>
							<input type='text' class='medium textoption type1' name='pagebuilder[post-formats][videourl]' value='" . (isset($gt3_theme_pagebuilder['post-formats']['videourl']) ? $gt3_theme_pagebuilder['post-formats']['videourl'] : "") . "'>
							<div class='example'>Examples:<br>Youtube - http://www.youtube.com/watch?v=6v2L2UGZJAM<br>Vimeo - http://vimeo.com/47989207</div>
							<div class='video_height' style='margin-top:15px;'>
								<div class='enter_option_row'>
									<h2>Video height</h2>
									<input type='text' class='medium textoption type1' name='pagebuilder[post-formats][video_height]' value='" . (isset($gt3_theme_pagebuilder['post-formats']['video_height']) ? $gt3_theme_pagebuilder['post-formats']['video_height'] : "") . "' style='width:70px;text-align:center;'>
								</div>
							</div>
						</div>
						<!-- Audio post format -->
						<div id='audio_sectionid_inner'>
							<h2>Post Format Audio Code:</h2>";
							echo '<textarea class="enter_text1 audio_textarea" name="pagebuilder[post-formats][audiourl]">'. (isset($gt3_theme_pagebuilder['post-formats']['audiourl']) ? $gt3_theme_pagebuilder['post-formats']['audiourl'] : "") .'</textarea>';
							
							echo "
							<div class='example'>Examples:<br>
								&lt;iframe src='https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/141816093&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true' width='100%' height='166' frameborder='no' scrolling='no'&gt;&lt;/iframe&gt;
							</div>
						</div>				
						<!-- Image post format -->


                <div class='portfolio_addImg stand-s pt_" . $now_post_type . "' id='portslides_sectionid_inner'>
					<!-- With Video -->
					<div id='gallery_type'>
						<div class='bg_or_slider_option slider_type active'>
							<input type='hidden' name='settings_type' value='fullscreen' class='settings_type'>
							<div class='hideable-area' style='padding-top:0'>
								<div class='padding-cont' style='padding-bottom:0px; padding-top:0px;'>
									<div class='selected_media'>
										<div class='append_block'>
											 <ul class='sortable-img-items'>
											   " . get_slider_items("fullscreen", (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] : '')) . "
											 </ul>
										</div>
										<div class='clear'></div>
									</div>
								</div>
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
											</div><!-- .available_media -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Without Video -->
					<div id='defpost_type'>
						<div class='portslides_sectionid_title'><h2>Slider Images</h2></div>
						<div class='selected-images-for-pf'>
							" . get_selected_pf_images_for_admin($gt3_theme_pagebuilder) . "
						</div>
						<hr class='img_seperator'>
						<div class='available-images-for-pf available_media'>
							<div class='ajax_cont'>
								" . get_media_html($media_for_this_post, "small") . "
							</div>
							<div class='for_post_fomrats img-item style_small add_image_to_sliders_available_media cboxElement'>
								<div class='img-preview'>
									<img alt='' src='" . PBIMGURL . "/add_image.png'>
								</div>
							</div><!-- .img-item -->
						</div>					
					</div>";


			//Slider Option
			echo "
                <div class='stand-s portfolio_options-slider'>
                    <div class='bg_or_slider_option slider_type active'>
                        <input type='hidden' name='settings_type' value='fullscreen' class='settings_type'>
                        <div class='hideable-area' style='padding-top:0'>
                            <div class='padding-cont' style='padding-top:12px;'>
								<div class='gt3settings_box no-margin'>									
									<div class='gt3settings_box_title'><h2>" . __('Fullscreen Slider Options', 'gt3_builder') . "</h2></div>
									<div class='gt3settings_box_content'>

										<div class='fs_fit_select'><h2>" . __('Fit Style:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][fit_style]' class='strip_select'>";
            $fs_fit_style = array("no_fit" => "Cover Slide", "fit_always" => "Fit Always", "fit_width" => "Fit Horizontal", "fit_height" => "Fit Vertical");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select></div>';

            echo "<div class='fs_fit_select'><h2>" . __('Show Controls:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][controls]' class='strip_select'>";
            $fs_fit_style = array("yes" => "Yes", "no" => "No");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['controls']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['controls'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo "</select></div>";

            echo "<div class='fs_fit_select'><h2>" . __('Autoplay:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][autoplay]' class='strip_select'>";
            $fs_fit_style = array("yes" => "Yes", "no" => "No");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo "</select></div>";

            echo "<div class='fs_fit_select'><h2>" . __('Cover Video Slides:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][video_cover]' class='strip_select'>";
            $fs_fit_style = array("yes" => "Yes", "no" => "No");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['video_cover']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['video_cover'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo "</select></div>";

			echo "
										<div class='fs_fit_select'><h2>" . __('Slide Interval In Milliseconds:', 'gt3_builder') . "</h2>
											<input type='text' class='medium textoption type1' name='pagebuilder[sliders][fullscreen][interval]' value='" . (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['interval']) ? absint($gt3_theme_pagebuilder['sliders']['fullscreen']['interval']) : "3000") . "'>
										</div>
										
									</div>
								</div>
                            </div>
						</div>
					</div>
                </div>";

			//Simple Option
			echo "
                <div class='stand-s portfolio_options-simple'>
                    <div class='bg_or_slider_option slider_type active'>
                        <input type='hidden' name='settings_type' value='fullscreen' class='settings_type'>
                        <div class='hideable-area' style='padding-top:0'>
                            <div class='padding-cont' style='padding-top:12px;'>
								<div class='gt3settings_box no-margin'>									
									<div class='gt3settings_box_title'><h2>" . __('Simple Post Options', 'gt3_builder') . "</h2></div>
									<div class='gt3settings_box_content'>

										<div class='fs_fit_select'><h2>" . __('Simple Post Style:', 'gt3_builder') . "</h2><select name='pagebuilder[settings][sp-style]' class='strip_select'>";
            $spStyle = array("def-simple" => "Default Post", "fw-simple" => "Fullwidth Post");
            foreach ($spStyle as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['settings']['sp-style']) && $gt3_theme_pagebuilder['settings']['sp-style'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo "</select></div>
									</div>
								</div>
                            </div>
						</div>
					</div>
                </div>";

			//Flow Option
			echo "
                <div class='stand-s portfolio_options-flow'>
                    <div class='bg_or_slider_option slider_type active'>
                        <input type='hidden' name='settings_type' value='fullscreen' class='settings_type'>
                        <div class='hideable-area' style='padding-top:0'>
                            <div class='padding-cont' style='padding-top:12px;'>
								<div class='gt3settings_box no-margin'>									
									<div class='gt3settings_box_title'><h2>" . __('Flow Portfolio Options', 'gt3_builder') . "</h2></div>
									<div class='gt3settings_box_content'>
									
										<div class='fs_fit_select'><h2>" . __('Autoplay:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][flow][autoplay]' class='strip_select'>";
				$fs_fit_style = array("true" => "Yes", "false" => "No");
				foreach ($fs_fit_style as $var_data => $var_caption) {
					echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['flow']['autoplay']) && $gt3_theme_pagebuilder['sliders']['flow']['autoplay'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
				}
				echo "</select></div>";
				echo "
										<div class='fs_fit_select'><h2>" . __('Slide Interval In Milliseconds:', 'gt3_builder') . "</h2>
											<input type='text' class='medium textoption type1' name='pagebuilder[sliders][flow][interval]' value='" . (isset($gt3_theme_pagebuilder['sliders']['flow']['interval']) ? absint($gt3_theme_pagebuilder['sliders']['flow']['interval']) : "3000") . "'>
										</div>
										
									</div>
								</div>
                            </div>
						</div>
					</div>
                </div>";

				echo '
				<style>
					/*.edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
						display:none;
					}
					#postdivrich, #postexcerpt {display:none!important}*/
					.selected_media .img-item .img-preview img {
						width:auto!important;
						height:auto!important;
					}

					.this-option .hr_double,
					.this-option .padding-cont:last-child,
					.this-option .right_block {
						display:none!important;
					}
					.this-option .w9 {
						width:720px!important;
					}
					
				</style>
                <!-- END SETTINGS -->

							
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>';
	}

	#RIBBON GALLERY AREA
    if ($now_post_type == "page" && get_page_template_slug() == "page-gallery-ribbon.php") {
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
                                           " . get_slider_items("fullscreen", (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] : '')) . "
                                         </ul>
                                    </div>
                                    <div class='clear'></div>
                                </div>
                            </div>
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
											<div class='clear'></div>
										</div>
									</div>
								</div>
                            </div>";        
            echo "<div class='padding-cont'>
						<div class='radio_block'></div>";
					echo "
						<style>
							.img-in-slider .right_block {
								display:none!important;
							}
							.edit_popup.nowOpen .hr_double,
							.edit_popup.nowOpen .this-option.img-in-slider .padding-cont {
								display:none!important;
							}
							.edit_popup.nowOpen .this-option.img-in-slider .padding-cont:first-child {
								display:block!important;
							}
						</style>
						</div>
                    </div>
                </div>
					<style>
						.edit-form-section, .page-builder-container {
							display:none;
						}
						#postdivrich, #postexcerpt {display:none!important}
						.selected_media .img-item .img-preview img {
							width:auto!important;
							height:auto!important;
						}
					</style>";
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
                                           " . get_slider_items("fullscreen", (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] : '')) . "
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
									</div>";

            				echo "
							<div class='gallery_options-slider'>
								<div class='padding-cont'>
									<div>
										<div class='fs_fit_select'><h2>" . __('Fit Style:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][fit_style]' class='strip_select'>";
            $fs_fit_style = array("default" => "Default", "no_fit" => "Cover Slide", "fit_always" => "Fit Always", "fit_width" => "Fit Horizontal", "fit_height" => "Fit Vertical");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select></div></div></div>';

            echo "<div class='padding-cont'>
									<div>
										<div class='fs_fit_select'><h2>" . __('Show Controls:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][controls]' class='strip_select'>";
            $fs_fit_style = array("default" => "Default", "yes" => "Yes", "no" => "No");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['controls']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['controls'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select></div></div></div><br />
			';

            echo "<div class='padding-cont'>
									<div class='padding-cont' style='margin-bottom:20px'>
										<div class='fs_fit_select'><h2>" . __('Autoplay:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][autoplay]' class='strip_select'>";
            $fs_fit_style = array("default" => "Default", "yes" => "Yes", "no" => "No");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo "</select></div>";

            echo "<div class='fs_fit_select'><h2>" . __('Cover Video Slides:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][video_cover]' class='strip_select'>";
            $fs_fit_style = array("yes" => "Yes", "no" => "No");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['video_cover']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['video_cover'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo "</select></div>";

			echo "
				</div><!-- .padding-cont -->
			</div><!-- .gallery_options-slider -->";

									
								echo "</div>
                            </div>
                        </div>
                    </div>
                </div>
				<style>
					/*.preview_img_video_cont .select_image_root {display:none!important}*/
				
					.this-option .hr_double,
					.this-option .padding-cont:last-child,
					.this-option .right_block,
					.custom_select_bcarea.title_bcarea {
						display:none!important;
					}
					.this-option .w9 {
						width:720px!important;
					}
				</style>
                <!-- END SETTINGS -->";
    }

#TESTIMONIALS AREA
    if ($now_post_type == "testimonials") {
        echo "
            <!-- TESTIMONIALS SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "'>

            <div class='testimonials_cont'>
                <div class='append_items'>
                    <label for='testimonials_author' class='label_type1'>" . __('Author:', 'gt3_builder') . "</label> <input type='text' value='" . (isset($gt3_theme_pagebuilder['page_settings']['testimonials']['testimonials_author']) ? $gt3_theme_pagebuilder['page_settings']['testimonials']['testimonials_author'] : '') . "' id='testimonials_author' name='pagebuilder[page_settings][testimonials][testimonials_author]' class='testimonials_author itt_type1'><br>
                    <label for='testimonials_position' class='label_type1'>" . __('Company:', 'gt3_builder') . "</label> <input type='text' value='" . (isset($gt3_theme_pagebuilder['page_settings']['testimonials']['company']) ? $gt3_theme_pagebuilder['page_settings']['testimonials']['company'] : '') . "' id='testimonials_company' name='pagebuilder[page_settings][testimonials][company]' class='testimonials_company itt_type1'>
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
						<label for='partners_link' class='label_type1'>" . __('External Link:', 'gt3_builder') . "</label> <input type='text' value='" . (isset($gt3_theme_pagebuilder['page_settings']['partners']['partners_link']) ? $gt3_theme_pagebuilder['page_settings']['partners']['partners_link'] : '') . "' id='partners_link' name='pagebuilder[page_settings][partners][partners_link]' class='partners_link itt_type1'>
					</div>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

#COUNTDOWN AREA
    if ($now_post_type == "page" && get_page_template_slug() == "page-countdown.php") {
        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box countdown'>
				<div class='gt3settings_box_title'><h2>Strip Page Options</h2></div>
				<div class='gt3settings_box_content'>";

        echo "<div class='fs_fit_select'>
						<h2>" . __('Enter Date:', 'gt3_builder') . "</h2>
						<input type='text' placeholder='". __('Enter year. Ex.:2020', 'gt3_builder') ."' class='medium textoption type1 date_input' name='pagebuilder[countdown][year]' value='" . (isset($gt3_theme_pagebuilder['countdown']['year']) ? esc_attr($gt3_theme_pagebuilder['countdown']['year']) : "") . "'>
						<input type='text' placeholder='". __('Enter day. Ex.:27', 'gt3_builder') ."' class='medium textoption type1 date_input' name='pagebuilder[countdown][day]' value='" . (isset($gt3_theme_pagebuilder['countdown']['day']) ? esc_attr($gt3_theme_pagebuilder['countdown']['day']) : "") . "'>
						<input type='text' placeholder='". __('Enter month. Ex.:11', 'gt3_builder') ."' class='medium textoption type1 date_input' name='pagebuilder[countdown][month]' value='" . (isset($gt3_theme_pagebuilder['countdown']['month']) ? esc_attr($gt3_theme_pagebuilder['countdown']['month']) : "") . "'>
						
						<hr class='date_hr'>
						<h2>" . __('Notify Text:', 'gt3_builder') . "</h2>
						<input type='text' class='medium textoption type1' name='pagebuilder[countdown][notify_text]' value='" . (isset($gt3_theme_pagebuilder['countdown']['notify_text']) ? esc_attr($gt3_theme_pagebuilder['countdown']['notify_text']) : "") . "'>
						<h2>" . __('Form Shortcode:', 'gt3_builder') . "</h2>
						<input type='text' class='medium textoption type1' name='pagebuilder[countdown][shortcode]' value='" . (isset($gt3_theme_pagebuilder['countdown']['shortcode']) ? esc_attr($gt3_theme_pagebuilder['countdown']['shortcode']) : "") . "'>
						<hr class='date_hr'>



						<div class='append_items'>
							<div class='hleft' style='vertical-align:top;'>" . __('Social Icons', 'gt3_builder') . "</div>
							<div class='hright'>
								<div class='added_icons sortable_icons_list count_cont'>";

        if (isset($gt3_theme_pagebuilder['page_settings']['icons']) && is_array($gt3_theme_pagebuilder['page_settings']['icons'])) {
            foreach ($gt3_theme_pagebuilder['page_settings']['icons'] as $key => $value) {
                echo "
										<div class='stand_iconsweet ui-state-default'>
											<span class='stand_icon-container'><i class='stand_icon " . $value['data-icon-code'] . "'></i></span>
											<input type='hidden' name='pagebuilder[page_settings][icons][" . $key . "][data-icon-code]' value='" . $value['data-icon-code'] . "'>
											<input class='icon_name' type='text' name='pagebuilder[page_settings][icons][" . $key . "][name]' value='" . $value['name'] . "' placeholder='" . __('Give Some Name', 'gt3_builder') . "'>
											<input class='icon_link' type='text' name='pagebuilder[page_settings][icons][" . $key . "][link]' value='" . $value['link'] . "' placeholder='" . __('Give Some Link', 'gt3_builder') . "'>
											<span class='remove_me'><i class='stand_icon icon-times'></i></span>
										</div>";
            }
        }

        echo "
								</div>
								<div class='social_list_for_select2'>";

        foreach ($GLOBALS["pbconfig"]['all_available_font_icons'] as $icon) {
            echo "<div class='stand_social'><i data-icon-code='" . $icon . "' class='stand_icon " . $icon . "'></i></div>";
        }

        echo "
						</div>
					</div>
					<style>
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }
                    </style>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

#STRIP AREA
    if ($now_post_type == "page" && get_page_template_slug() == "page-strip.php") {
        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Strip Page Options</h2></div>
				<div class='gt3settings_box_content'>";
        echo "
		<script>
			jQuery(document).ready(function(){
				if (jQuery('.strip_select').val() == 'vertical') {
					jQuery('.strip_input2').hide();
				} else {
					jQuery('.strip_input2').show();
				}
				jQuery('.strip_select').change(function(){
					if (jQuery(this).val() == 'vertical') {
						jQuery('.strip_input2').hide();
					} else {
						jQuery('.strip_input2').show();
					}
				});				
			});
		</script>
		<ul class='append_items'>";

        if (isset($gt3_theme_pagebuilder['strips']) && is_array($gt3_theme_pagebuilder['strips'])) {
            foreach ($gt3_theme_pagebuilder['strips'] as $stripid => $stripdata) {
                echo '
            <li class="strip_block">
                <div class="sort_drug strip_head">' . (!empty($stripdata['striptitle1']) ? $stripdata['striptitle1'] : "Strip Item") . '</div>
				<div class="strip_block_container">
					<input type="text" placeholder="Title" name="pagebuilder[strips]['. $stripid .'][striptitle1]" value="' . (!empty($stripdata['striptitle1']) ? $stripdata['striptitle1'] : "") . '" class="strip_input">
					<input type="text" placeholder="Link" name="pagebuilder[strips]['. $stripid .'][link]" value="' . (!empty($stripdata['link']) ? $stripdata['link'] : "") . '" class="strip_input">
					<input type="text" placeholder="Image" name="pagebuilder[strips]['. $stripid .'][image]" value="' . (!empty($stripdata['image']) ? $stripdata['image'] : "") . '" class="gt3UploadImg strip_input">
					<input type="text" placeholder="Background Image" name="pagebuilder[strips]['. $stripid .'][bgimage]" value="' . (!empty($stripdata['bgimage']) ? $stripdata['bgimage'] : "") . '" class="gt3UploadImg strip_input">

					<div class="strip_color_box">
						<span>Text Color:</span> 
						<input class="cpicker" type="text" name="pagebuilder[strips][' . $stripid . '][tcolor]" value="' . (!empty($stripdata['tcolor']) ? $stripdata['tcolor'] : "ffffff") . '" placeholder="' . __('Text Color', 'gt3_builder') . '">
						<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($stripdata['tcolor']) ? $stripdata['tcolor'] : "ffffff") . '">						

						<span style="padding-left:20px">Overlay settings:</span> 
						<input class="cpicker" type="text" name="pagebuilder[strips][' . $stripid . '][fcolor]" value="' . $stripdata['fcolor'] . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
						<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . $stripdata['fcolor'] . '">
						<input type="text" placeholder="Overlay Opacity (0-1)" name="pagebuilder[strips]['. $stripid .'][opacity]" value="' . (!empty($stripdata['opacity']) ? $stripdata['opacity'] : "") . '" class="strip_input_opacity">

						<div class="clear"></div>
					</div>
					<span class="remove_strip">[x]</span>
				</div>
            </li>';
            }
        }

        echo "
					</ul>
					<input class='button button-primary button-large add-new-strip' type='button' value='Add New Strip'>
					<style>
                        .edit-form-section, .page-builder-container {
                            display:none;
                        }
						#side_sidebar_settings_meta_box {
							display:none!important;
						}
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }						
                    </style>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

#LANDING
    if ($now_post_type == "page" && get_page_template_slug() == "page-landing1.php") {
        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Landing Page Options</h2></div>
				<div class='gt3settings_box_content'>";
                echo '
					<input type="text" placeholder="Loading Time in milliseconds" name="pagebuilder[landing][time]" value="' . (!empty($gt3_theme_pagebuilder['landing']['time']) ? $gt3_theme_pagebuilder['landing']['time'] : "") . '" class="strip_input">
				';
                echo '
					<input type="text" placeholder="Home page URL" name="pagebuilder[landing][url]" value="' . (!empty($gt3_theme_pagebuilder['landing']['url']) ? $gt3_theme_pagebuilder['landing']['url'] : "") . '" class="strip_input">
				';
				echo "<div class='radio_block landing_select_block'>
						<div class='fs_fit_select landing_select'>
							<h2>" . __('Logo Shape:', 'gt3_builder') . "</h2>
							<select name='pagebuilder[landing][shape]' class='strip_select'>";
            $gallery_style = array("circle" => "Circle", "square" => "Square");
            foreach ($gallery_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['landing']['shape']) && $gt3_theme_pagebuilder['landing']['shape'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select>
			</div>
				<div class="landing_color">
					<h2>' . __("Logo Background Color:", "gt3_builder") . '</h2>
					<input class="cpicker" type="text" name="pagebuilder[landing][color]" value="' . (!empty($gt3_theme_pagebuilder['landing']['color']) ? $gt3_theme_pagebuilder['landing']['color'] : "151516") . '" placeholder="' . __('Logo Color', 'gt3_builder') . '">
					<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['landing']['color']) ? $gt3_theme_pagebuilder['landing']['color'] : "151516") . '">			
				</div>
			</div>';
				
        echo "
					<style>
                        .edit-form-section, .page-builder-container {
                            display:none;
                        }
						#side_sidebar_settings_meta_box {
							display:none!important;
						}
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }						
                    </style>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

#STRIP LANDING
    if ($now_post_type == "page" && get_page_template_slug() == "page-landing2.php") {
        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Strip Page Options</h2></div>
				<div class='gt3settings_box_content'>";
        echo "
		<script>
			jQuery(document).ready(function(){
				if (jQuery('.strip_select').val() == 'vertical') {
					jQuery('.strip_input2').hide();
				} else {
					jQuery('.strip_input2').show();
				}
				jQuery('.strip_select').change(function(){
					if (jQuery(this).val() == 'vertical') {
						jQuery('.strip_input2').hide();
					} else {
						jQuery('.strip_input2').show();
					}
				});				
			});
		</script>";
				echo "<div class='radio_block landing_select_block'>
						<div class='fs_fit_select landing_select'>
							<h2>" . __('Logo Shape:', 'gt3_builder') . "</h2>
							<select name='pagebuilder[landing][shape]' class='strip_select'>";
            $gallery_style = array("circle" => "Circle", "square" => "Square");
            foreach ($gallery_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['landing']['shape']) && $gt3_theme_pagebuilder['landing']['shape'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select>
			</div>
				<div class="landing_color">
					<h2>' . __("Logo Background Color:", "gt3_builder") . '</h2>
					<input class="cpicker" type="text" name="pagebuilder[landing][color]" value="' . (!empty($gt3_theme_pagebuilder['landing']['color']) ? $gt3_theme_pagebuilder['landing']['color'] : "151516") . '" placeholder="' . __('Logo Color', 'gt3_builder') . '">
					<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['landing']['color']) ? $gt3_theme_pagebuilder['landing']['color'] : "151516") . '">			
				</div>
			</div>';
		
		echo '<input type="text" placeholder="Home page URL" name="pagebuilder[landing][url]" value="' . (!empty($gt3_theme_pagebuilder['landing']['url']) ? $gt3_theme_pagebuilder['landing']['url'] : "") . '" class="strip_input">
';					
		echo "
		<ul class='append_items' style='margin:0;'>";

                echo '
            <li class="strip_block">
                <div class="sort_drug strip_head">' . (!empty($gt3_theme_pagebuilder['strips']['side1']['striptitle1']) ? $gt3_theme_pagebuilder['strips']['side1']['striptitle1'] : "Strip Item") . '</div>
				<div class="strip_block_container">
					<input type="text" placeholder="Title" name="pagebuilder[strips][side1][striptitle1]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side1']['striptitle1']) ? $gt3_theme_pagebuilder['strips']['side1']['striptitle1'] : "") . '" class="strip_input">
					<input type="text" placeholder="Text" name="pagebuilder[strips][side1][striptitle2]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side1']['striptitle2']) ? $gt3_theme_pagebuilder['strips']['side1']['striptitle2'] : "") . '" class="strip_input">
					<input type="text" placeholder="Link" name="pagebuilder[strips][side1][link]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side1']['link']) ? $gt3_theme_pagebuilder['strips']['side1']['link'] : "") . '" class="strip_input">
					<input type="text" placeholder="Background Image" name="pagebuilder[strips][side1][bgimage]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side1']['bgimage']) ? $gt3_theme_pagebuilder['strips']['side1']['bgimage'] : "") . '" class="gt3UploadImg strip_input">
					<div class="strip_color_box">
						<span>Overlay settings:</span> 
						<input class="cpicker" type="text" name="pagebuilder[strips][side1][fcolor]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side1']['fcolor']) ? $gt3_theme_pagebuilder['strips']['side1']['fcolor'] : "cccb7e") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
						<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['strips']['side1']['fcolor']) ? $gt3_theme_pagebuilder['strips']['side1']['fcolor'] : "cccb7e") . '">
						<input type="text" placeholder="Overlay Opacity (0-1)" name="pagebuilder[strips][side1][opacity]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side1']['opacity']) ? $gt3_theme_pagebuilder['strips']['side1']['opacity'] : "") . '" class="strip_input_opacity">
						<div class="clear"></div>
					</div>
					<span class="remove_strip">[x]</span>
				</div>
            </li>

            <li class="strip_block" style="margin-bottom:20px">
                <div class="sort_drug strip_head">' . (!empty($gt3_theme_pagebuilder['strips']['side2']['striptitle1']) ? $gt3_theme_pagebuilder['strips']['side2']['striptitle1'] : "Strip Item") . '</div>
				<div class="strip_block_container">
					<input type="text" placeholder="Title" name="pagebuilder[strips][side2][striptitle1]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side2']['striptitle1']) ? $gt3_theme_pagebuilder['strips']['side2']['striptitle1'] : "") . '" class="strip_input">
					<input type="text" placeholder="Text" name="pagebuilder[strips][side2][striptitle2]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side2']['striptitle2']) ? $gt3_theme_pagebuilder['strips']['side2']['striptitle2'] : "") . '" class="strip_input">
					<input type="text" placeholder="Link" name="pagebuilder[strips][side2][link]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side2']['link']) ? $gt3_theme_pagebuilder['strips']['side2']['link'] : "") . '" class="strip_input">
					<input type="text" placeholder="Background Image" name="pagebuilder[strips][side2][bgimage]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side2']['bgimage']) ? $gt3_theme_pagebuilder['strips']['side2']['bgimage'] : "") . '" class="gt3UploadImg strip_input">
					<div class="strip_color_box">
						<span>Overlay settings:</span> 
						<input class="cpicker" type="text" name="pagebuilder[strips][side2][fcolor]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side2']['fcolor']) ? $gt3_theme_pagebuilder['strips']['side2']['fcolor'] : "7c8589") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
						<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['strips']['side2']['fcolor']) ? $gt3_theme_pagebuilder['strips']['side2']['fcolor'] : "7c8589") . '">
						<input type="text" placeholder="Overlay Opacity (0-1)" name="pagebuilder[strips][side2][opacity]" value="' . (!empty($gt3_theme_pagebuilder['strips']['side2']['opacity']) ? $gt3_theme_pagebuilder['strips']['side2']['opacity'] : "") . '" class="strip_input_opacity">
						<div class="clear"></div>
					</div>
					<span class="remove_strip">[x]</span>
				</div>
            </li>';

        echo "
					</ul>
				<style>
                        .edit-form-section, .page-builder-container {
                            display:none;
                        }
						#side_sidebar_settings_meta_box {
							display:none!important;
						}
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }						
                    </style>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

#FS BLOG
    if ($now_post_type == "page" && get_page_template_slug() == "page-blog-fullscreen.php") {

        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Select Category</h2></div>
				<div class='gt3settings_box_content'>

										<div class='gt3settings_box_content portfolio_chkbox'>";

        $compile = "";
        $checked_isset = "checked";
        $cathided_state = "cat_hided";
        if (isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") {
            $cathided_state = "cat_hided";
        }

        $args = array('type' => 'post');
		$categories = get_categories($args);

		if (count($categories) > 0) {
            foreach ($categories as $cat) {
                if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
                    $checked_isset = "";
                    $cathided_state = "";
                    continue;
                }
            }

            $compile .= "<input class='all_part' " . $checked_isset . " " . ((isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") ? "checked" : "") . " type='checkbox' name='pagebuilder[settings][show_all_categories]'> <span>" . __("All", "gt3_builder") . "</span>";

            foreach ($categories as $cat) {
                if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
                    $selectedstate = "checked";
                } else {
                    $selectedstate = "";
                }
                $compile .= "<input class='category_part " . $cathided_state . "' " . $selectedstate . " type='checkbox' name='pagebuilder[settings][cat_ids][" . $cat->term_id . "]'> <span class='" . $cathided_state . "'>" . $cat->name . "</span>";
            }
        } else {
            $compile .= __("No category available. Please add new category in the portfolio section.", "gt3_builder");
        }

        echo $compile."

										</div>";		

        echo "		<style>
						#side_sidebar_settings_meta_box {
							display:none!important;
						}
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }
                    </style>
				</div>
            </div>

            </div>

            <!-- END SETTINGS -->";
    }

#FS PORTFOLIO
    if ($now_post_type == "page" && (get_page_template_slug() == "page-portfolio-grid.php" || get_page_template_slug() == "page-portfolio-masonry.php")) {

        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Select Category</h2></div>
				<div class='gt3settings_box_content'>

										<div class='gt3settings_box_content portfolio_chkbox'>";

        $compile = "";
        $checked_isset = "checked";
        $cathided_state = "cat_hided";
        if (isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") {
            $cathided_state = "cat_hided";
        }
        $args = array('taxonomy' => 'Category');
        $terms = get_terms('portcat', $args);
        if (is_array($terms) && count($terms) > 0) {

            foreach ($terms as $cat) {
                if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
                    $checked_isset = "";
                    $cathided_state = "";
                    continue;
                }
            }
            $compile .= "<input class='all_part' " . $checked_isset . " " . ((isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") ? "checked" : "") . " type='checkbox' name='pagebuilder[settings][show_all_categories]'> <span>" . __("All", "gt3_builder") . "</span>";
            foreach ($terms as $cat) {
                if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
                    $selectedstate = "checked";
                } else {
                    $selectedstate = "";
                }
                $compile .= "<input class='category_part " . $cathided_state . "' " . $selectedstate . " type='checkbox' name='pagebuilder[settings][cat_ids][" . $cat->term_id . "]'> <span class='" . $cathided_state . "'>" . $cat->name . "</span>";
            }
        } else {
            $compile .= __("No category available. Please add new category in the portfolio section.", "gt3_builder");
        }

        echo $compile."

										</div>";

        echo "<div style='width: 190px;' class='caption'>
				<h2 style='color:#A1A1A1;' class='text-shadow2'>" . __('Show Filter:', 'gt3_builder') . "</h2>
			</div>
			<div class='radio_selector'>
				" . toggle_radio_on_off('pagebuilder[fs_portfolio][filter]', (isset($gt3_theme_pagebuilder['fs_portfolio']['filter']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter'] : ''), 'on') . "
			</div><br style='clear:both'>
			<div class='clear'></div>";

		echo '
			<div class="filter_options">
				<span class="color_label">Filter Text Color:</span>
				<input class="cpicker" type="text" name="pagebuilder[fs_portfolio][filter_color]" value="' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_color']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_color'] : "8b8e90") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
				<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_color']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_color'] : "8b8e90") . '">		
				<span>&nbsp;&nbsp;</span>
				<span class="color_label">Filter Divider Color:</span>
				<input class="cpicker" type="text" name="pagebuilder[fs_portfolio][filter_divider]" value="' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_divider']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_divider'] : "bfc0c1") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
				<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_divider']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_divider'] : "bfc0c1") . '">		
				<span>&nbsp;&nbsp;</span>
				<span class="color_label">Filter Selected Color:</span>
				<input class="cpicker" type="text" name="pagebuilder[fs_portfolio][filter_selected]" value="' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_selected']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_selected'] : "ef969a") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
				<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_selected']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_selected'] : "ef969a") . '">		
			</div>
		';
			
			if (get_page_template_slug() == "page-portfolio-masonry.php" || get_page_template_slug() == "page-portfolio-grid.php") {
				echo "<h2 style='float:left; padding-right:10px'>" . __('Please use this option to add paddings around the images. Recommended size in pixels 0-10. (Ex.: 5px):', 'gt3_builder') . "</h2>
				<input style='width:80px; float:left; text-align:center' type='text' class='medium textoption type1' name='pagebuilder[fs_portfolio][interval]' value='" . (isset($gt3_theme_pagebuilder['fs_portfolio']['interval']) ? esc_attr($gt3_theme_pagebuilder['fs_portfolio']['interval']) : "0px") . "'>
				";
			}
			
            echo "<div class='padding-cont'>
									<div class='radio_block'>
										<div class='fs_fit_select'><h2 style='color:#A1A1A1;' class='text-shadow2'>" . __('Portfolio Type:', 'gt3_builder') . "</h2><select name='pagebuilder[portfolio][port_type]' class='strip_select'>";
            $gallery_style = array("port_standart" => "Auto Load - load the items on page scroll", "port_isotope" => "Isotope - filter the items without page reload");
            foreach ($gallery_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select></div></div>';
			

        echo "		<style>
						#side_sidebar_settings_meta_box {
							display:none!important;
						}
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }
                    </style>
				</div>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }
	
#GALLERY ALBUMS
    if ($now_post_type == "page" && get_page_template_slug() == "page-albums.php" ) {

        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Select Category</h2></div>
				<div class='gt3settings_box_content'>

										<div class='gt3settings_box_content portfolio_chkbox'>";

        $compile = "";
        $checked_isset = "checked";
        $cathided_state = "cat_hided";
        if (isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") {
            $cathided_state = "cat_hided";
        }
        $args = array('taxonomy' => 'Category');
        $terms = get_terms('gallerycat', $args);
        if (is_array($terms) && count($terms) > 0) {

            foreach ($terms as $cat) {
                if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
                    $checked_isset = "";
                    $cathided_state = "";
                    continue;
                }
            }
            $compile .= "<input class='all_part' " . $checked_isset . " " . ((isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") ? "checked" : "") . " type='checkbox' name='pagebuilder[settings][show_all_categories]'> <span>" . __("All", "gt3_builder") . "</span>";
            foreach ($terms as $cat) {
                if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
                    $selectedstate = "checked";
                } else {
                    $selectedstate = "";
                }
                $compile .= "<input class='category_part " . $cathided_state . "' " . $selectedstate . " type='checkbox' name='pagebuilder[settings][cat_ids][" . $cat->term_id . "]'> <span class='" . $cathided_state . "'>" . $cat->name . "</span>";
            }
        } else {
            $compile .= __("No category available. Please add new category in the portfolio section.", "gt3_builder");
        }

        echo $compile."
										</div>";

        echo "<div style='width: 190px;' class='caption'>
				<h2 style='color:#A1A1A1;' class='text-shadow2'>" . __('Show Filter:', 'gt3_builder') . "</h2>
			</div>
			<div class='radio_selector'>
				" . toggle_radio_on_off('pagebuilder[fs_portfolio][filter]', (isset($gt3_theme_pagebuilder['fs_portfolio']['filter']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter'] : ''), 'on') . "
			</div><br style='clear:both'>
			<div class='clear'></div>";

		echo '
			<div class="filter_options">
				<span class="color_label">Filter Text Color:</span>
				<input class="cpicker" type="text" name="pagebuilder[fs_portfolio][filter_color]" value="' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_color']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_color'] : "8b8e90") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
				<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_color']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_color'] : "8b8e90") . '">		
				<span>&nbsp;&nbsp;</span>
				<span class="color_label">Filter Divider Color:</span>
				<input class="cpicker" type="text" name="pagebuilder[fs_portfolio][filter_divider]" value="' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_divider']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_divider'] : "bfc0c1") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
				<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_divider']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_divider'] : "bfc0c1") . '">		
				<span>&nbsp;&nbsp;</span>
				<span class="color_label">Filter Selected Color:</span>
				<input class="cpicker" type="text" name="pagebuilder[fs_portfolio][filter_selected]" value="' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_selected']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_selected'] : "ef969a") . '" placeholder="' . __('Overlay Color', 'gt3_builder') . '">
				<input type="text" value="" class="cpicker_preview" disabled="disabled" style="background-color:#' . (!empty($gt3_theme_pagebuilder['fs_portfolio']['filter_selected']) ? $gt3_theme_pagebuilder['fs_portfolio']['filter_selected'] : "ef969a") . '">		
			</div>
		';

        echo "		<style>
						#side_sidebar_settings_meta_box {
							display:none!important;
						}
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }
                    </style>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }
	
#FULLSCREEN GALLERY AREA
    if ($now_post_type == "page" && get_page_template_slug() == "page-fullscreen-slider.php") {
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
                                           " . get_slider_items("fullscreen", (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] : '')) . "
                                         </ul>
                                    </div>
                                    <div class='clear'></div>
                                </div>
                            </div>
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
                            </div>";        
            echo "<div class='padding-cont'>
									<div class='radio_block'>
										<div class='fs_fit_select'><h2 style='color:#A1A1A1;' class='text-shadow2'>" . __('Fit Style:', 'gt3_builder') . "</h2><select name='pagebuilder[sliders][fullscreen][fit_style]' class='strip_select'>";
            $fs_fit_style = array("no_fit" => "Cover Slide", "fit_always" => "Fit Always", "fit_width" => "Fit Horizontal", "fit_height" => "Fit Vertical");
            foreach ($fs_fit_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select></div>';

            echo "<div style='width: 190px;' class='caption'><h2 style='color:#A1A1A1;' class='text-shadow2'>" . __('Show Controls:', 'gt3_builder') . "</h2></div>
										<div class='radio_selector'>
											" . toggle_radio_on_off('pagebuilder[sliders][fullscreen][controls]', (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['controls']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['controls'] : ''), 'on') . "
										</div><br style='clear:both'>
										<div class='clear'></div>
<br />

										<div style='width: 190px;' class='caption'><h2 style='color:#A1A1A1;' class='text-shadow2'>" . __('Autoplay:', 'gt3_builder') . "</h2></div>
										<div class='radio_selector'>
											" . toggle_radio_on_off('pagebuilder[sliders][fullscreen][autoplay]', (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay'] : ''), 'on') . "
										</div><br style='clear:both'>
										<div class='clear'></div>
										<br />
										
										<div style='width: 190px;' class='caption'><h2 style='color:#A1A1A1;' class='text-shadow2'>" . __('Cover Video Slides:', 'gt3_builder') . "</h2></div>
										<div class='radio_selector'>
											" . toggle_radio_on_off('pagebuilder[sliders][fullscreen][video_cover]', (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['video_cover']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['video_cover'] : ''), 'on') . "
										</div><br style='clear:both'>

										<div class='clear'></div>
										<br />
										<h2>" . __('Slide Interval In Milliseconds:', 'gt3_builder') . "</h2>
										<input type='text' class='medium textoption type1' name='pagebuilder[sliders][fullscreen][interval]' value='" . (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['interval']) ? absint($gt3_theme_pagebuilder['sliders']['fullscreen']['interval']) : "") . "'>
									</div>
								</div>
						</div>
                    </div>
                </div>
				<style>
					.edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
						display:none;
					}
					#postdivrich, #postexcerpt {display:none!important}
					.selected_media .img-item .img-preview img {
						width:auto!important;
						height:auto!important;
					}

					.this-option .hr_double,
					.this-option .padding-cont:last-child,
					.this-option .right_block {
						display:none!important;
					}
					.this-option .w9 {
						width:720px!important;
					}
					
				</style>
                <!-- END SETTINGS -->";
    }

#KENBURNS GALLERY AREA
    if ($now_post_type == "page" && get_page_template_slug() == "page-gallery-kenburns.php") {
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
                                           " . get_slider_items("fullscreen", (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) ? $gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] : '')) . "
                                         </ul>
                                    </div>
                                    <div class='clear'></div>
                                </div>
                            </div>
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
											<div class='clear'></div>
										</div>
									</div>
								</div>
                            </div>";        
            echo "<div class='padding-cont'>";

            echo "
								</div>
								<style>
									.inter_edit {
										display:none!important;
									}
									.hover-container .inter_x {
										left:47px;
									}
									.hover-container .inter_drag {
										left:80px;
									}
								</style>
						</div>
                    </div>
                </div>
				<style>
					.edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
						display:none;
					}
					#postdivrich, #postexcerpt {display:none!important}
					.selected_media .img-item .img-preview img {
						width:auto!important;
						height:auto!important;
					}
				</style>
                <!-- END SETTINGS -->";
    }

#GRID&MASONRY GALLERY AREA
    if ($now_post_type == "page" && (get_page_template_slug() == "page-gallery-grid.php" || get_page_template_slug() == "page-gallery-masonry.php")) {
        echo "
            <!-- STRIP SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px;'>

            <div class='strip_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Gallery Options</h2></div>
				<div class='gt3settings_box_content'>";

            echo "<div class='padding-cont'>
									<div class='radio_block'>
										<div><h2>" . __('Show Gallery By:', 'gt3_builder') . "</h2><select name='pagebuilder[settings][show_by]' class='strip_select gallery_by'>";
            $gallery_style = array("by_gallery" => "Galleries", "by_category" => "Categories");
            foreach ($gallery_style as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['settings']['show_by']) && $gt3_theme_pagebuilder['settings']['show_by'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo '</select></div></div>
			<hr style="margin-top:20px; margin-bottom:-10px;">';
		
		//By Category
		if (isset($gt3_theme_pagebuilder['settings']['show_by']) &&  $gt3_theme_pagebuilder['settings']['show_by'] == 'by_category') {
			$showcat = '';
			$showgal = 'hide_area';
		} else {
			$showcat = 'hide_area';
			$showgal = '';
		}
		echo "<div class='gt3settings_box_content block_by_category portfolio_chkbox ". $showcat ."'><h2>" . __('Select Category:', 'gt3_builder') . "</h2>";

			$compile = "";
			$checked_isset = "checked";
			$cathided_state = "cat_hided";
			if (isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") {
				$cathided_state = "cat_hided";
			}
			$args = array('taxonomy' => 'Category');
			$terms = get_terms('gallerycat', $args);
			if (is_array($terms) && count($terms) > 0) {
	
				foreach ($terms as $cat) {
					if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
						$checked_isset = "";
						$cathided_state = "";
						continue;
					}
				}
				$compile .= "<input class='all_part' " . $checked_isset . " " . ((isset($gt3_theme_pagebuilder['settings']['show_all_categories']) && $gt3_theme_pagebuilder['settings']['show_all_categories'] == "on") ? "checked" : "") . " type='checkbox' name='pagebuilder[settings][show_all_categories]'> <span>" . __("All", "gt3_builder") . "</span>";
				foreach ($terms as $cat) {
					if (isset($gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id]) && $gt3_theme_pagebuilder['settings']['cat_ids'][$cat->term_id] == "on") {
						$selectedstate = "checked";
					} else {
						$selectedstate = "";
					}
					$compile .= "<input class='category_part " . $cathided_state . "' " . $selectedstate . " type='checkbox' name='pagebuilder[settings][cat_ids][" . $cat->term_id . "]'> <span class='" . $cathided_state . "'>" . $cat->name . "</span>";
				}
			} else {
				$compile .= __("No category available. Please add new category in the portfolio section.", "gt3_builder");
			}
	
			echo $compile."
		</div>";		

		echo "<div class='gt3settings_box_content block_by_gallery ". $showgal ."'><h2>" . __('Select Gallery:', 'gt3_builder') . "</h2>
				<select name='pagebuilder[gallery][selected_gallery]' class='strip_select'>";
		
        /* GET ALL GALL'S FOR SELECT */
        if (isset($wp_query)) {
            $temp = $wp_query;
        }
        $wp_query = null;
        $wp_query = new WP_Query();
        $args = array(
            'post_type' => 'gallery',
            'posts_per_page' => -1,
        );

        $compilegal = "";

        $wp_query->query($args);
        while ($wp_query->have_posts()) : $wp_query->the_post();

            if (isset($gt3_theme_pagebuilder['gallery']['selected_gallery']) && $gt3_theme_pagebuilder['gallery']['selected_gallery'] == get_the_ID()) {
                $selectedstate = "selected='selected'";
            } else {
                $selectedstate = "";
            }

            $compilegal .= "<option value='" . get_the_ID() . "' {$selectedstate}>" . get_the_title() . "</option>";

        endwhile;

        echo $compilegal;            
	
        echo "	</select>
			</div>
			<hr style='margin-top:0; margin-bottom:20px;'>
			<div style='padding:0 0 35px 0'>
				<h2 style='float:left; padding-right:10px'>" . __('Please use this option to add paddings around the images. Recommended size in pixels 0-40. (Ex.: 5px):', 'gt3_builder') . "</h2>
				<input style='width:80px; float:left; text-align:center' type='text' class='medium textoption type1' name='pagebuilder[gallery][interval]' value='" . (isset($gt3_theme_pagebuilder['gallery']['interval']) ? esc_attr($gt3_theme_pagebuilder['gallery']['interval']) : "0px") . "'>
			</div>";
		echo "			
				<script>
					jQuery(document).ready(function(){
						jQuery('.gallery_by').change(function(){
							if (jQuery(this).val() == 'by_gallery') {
								jQuery('.block_by_category').addClass('hide_area');
								jQuery('.block_by_gallery').removeClass('hide_area');
							} else {
								jQuery('.block_by_category').removeClass('hide_area');
								jQuery('.block_by_gallery').addClass('hide_area');
							}
						});						
					});
				</script>
					<style>
						#side_sidebar_settings_meta_box {
							display:none!important;
						}
                        .edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
                            display:none;
                        }
                    </style>
            </div>

            </div>
            <!-- END SETTINGS -->";
    }

#CONTACTS AREA
	if ($now_post_type == "page" && get_page_template_slug() == "page-contact.php") {
        echo "
            <!-- PARTNERS SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "' style='margin-top:20px'>

            <div class='partners_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Advanced Options</h2></div>
				<div class='gt3settings_box_content'>";
				echo "<div class='fs_fit_select'><h2>" . __('Google Map Code:', 'gt3_builder') . "</h2>";
					echo '<textarea class="enter_text1 gmap_textarea" name="pagebuilder[contacts][mapurl]">'. (isset($gt3_theme_pagebuilder['contacts']['mapurl']) ? $gt3_theme_pagebuilder['contacts']['mapurl'] : "") .'</textarea>';
					echo "
					<div class='example'>Examples:<br>
						&lt;iframe width='100%' height='395' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://maps.google.ca/maps?f=q&source=s_q&hl=en&geocode=&q=New+York&sll=49.891235,-97.15369&sspn=47.259509,86.923828&ie=UTF8&hq=&hnear=New+York,+United+States&ll=40.714867,-74.005537&spn=0.019517,0.018797&z=14&iwloc=near&output=embed'&gt;&lt;/iframe&gt;
					</div>
					
					";			
		
				echo "<style>
						#side_sidebar_settings_meta_box {
							display:none;
						}
					</style>
				</div>
								
					<div class='append_items'>
						<label for='work_link' class='label_type1'>" . __('Map Height:', 'gt3_builder') . "</label>
						<br>
						<input type='text' value='" . (isset($gt3_theme_pagebuilder['contacts']['map_height']) ? $gt3_theme_pagebuilder['contacts']['map_height'] : '375px') . "' id='map_height' name='pagebuilder[contacts][map_height]' class='work_link itt_type1'>
					</div>
				</div>
			</div>

            </div>
            <!-- END SETTINGS -->";
    }

#PORTFOLIO AREA
    if ($now_post_type == "port") {
        echo "
            <!-- PARTNERS SETTINGS -->
            <div class='padding-cont pt_" . $now_post_type . "'>

            <div class='partners_cont gt3settings_box'>
				<div class='gt3settings_box_title'><h2>Advanced Options</h2></div>
				<div class='gt3settings_box_content'>
					<div class='append_items'>
						<label for='work_link' class='label_type1'>" . __('Link to the work:', 'gt3_builder') . "</label><br><input type='text' value='" . (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['work_link']) ? esc_url($gt3_theme_pagebuilder['page_settings']['portfolio']['work_link']) : '') . "' id='work_link' name='pagebuilder[page_settings][portfolio][work_link]' class='work_link itt_type1'>
					</div>
					
					<div class='fs_fit_select' style='padding-bottom:20px'><label class='label_type1' style='width:100%;'>" . __('Show Related Works:', 'gt3_builder') . "</label><select name='pagebuilder[settings][pf-related]' class='strip_select'>";
            $spStyle = array("def" => "Default", "on" => "Show", "off" => "Hide");
            foreach ($spStyle as $var_data => $var_caption) {
                echo "<option " . ((isset($gt3_theme_pagebuilder['settings']['pf-related']) && $gt3_theme_pagebuilder['settings']['pf-related'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
            }
            echo "</select></div>
					
					<hr>
					<div class='port_skills_cont'>
						<ul class='all_added_skills sortable_icons_list'>";
        if (isset($gt3_theme_pagebuilder['page_settings']['portfolio']['skills']) && is_array($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'])) {
            foreach ($gt3_theme_pagebuilder['page_settings']['portfolio']['skills'] as $key => $value) {
                echo "
									<li class='stand_iconsweet ui-state-default'> <input type='text' class='itt_type1' name='pagebuilder[page_settings][portfolio][skills][{$key}][name]' placeholder='Field name' value='{$value["name"]}'> <input type='text' class='itt_type1' name='pagebuilder[page_settings][portfolio][skills][{$key}][value]' placeholder='Field value' value='{$value["value"]}'> <span class='remove_skill'><i class='stand_icon icon-times'></i></span></li>
								";
            }
        }
        echo "
						</ul>
						<div class='heading line_option visual_style1 small_type hovered clickable add_new_port_skills'>
							<div class='option_title text-shadow1'>" . __('Add Custom Field', 'gt3_builder') . "</div>
							<div class='some-element cross'></div>
							<div class='pre_toggler'></div>
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
						<label for='position_link' class='label_type1'>Position:</label> <input type='text' value='" . (isset($gt3_theme_pagebuilder['page_settings']['team']['position']) ? $gt3_theme_pagebuilder['page_settings']['team']['position'] : '') . "' id='position_link' name='pagebuilder[page_settings][team][position]' class='position_link itt_type1'>
						<div>
							<div class='hleft' style='vertical-align:top;'>" . __('Social Icons', 'gt3_builder') . "</div>
							<div class='hright'>
								<div class='added_icons sortable_icons_list'>";

        if (isset($gt3_theme_pagebuilder['page_settings']['icons']) && is_array($gt3_theme_pagebuilder['page_settings']['icons'])) {
            foreach ($gt3_theme_pagebuilder['page_settings']['icons'] as $key => $value) {
                echo "
					<div class='stand_iconsweet ui-state-default'>
						<span class='stand_icon-container'><i class='stand_icon " . $value['data-icon-code'] . "'></i></span>
						<input type='hidden' name='pagebuilder[page_settings][icons][" . $key . "][data-icon-code]' value='" . $value['data-icon-code'] . "'>
						<input class='icon_name' type='text' name='pagebuilder[page_settings][icons][" . $key . "][name]' value='" . $value['name'] . "' placeholder='" . __('Give Some Name', 'gt3_builder') . "'>
						<input class='icon_link' type='text' name='pagebuilder[page_settings][icons][" . $key . "][link]' value='" . $value['link'] . "' placeholder='" . __('Give Some Link', 'gt3_builder') . "'>
						<input class='cpicker' type='text' name='pagebuilder[page_settings][icons][" . $key . "][fcolor]' value='" . $value['fcolor'] . "' placeholder='" . __('Foreground Color', 'gt3_builder') . "'>
						<input type='text' value='' class='cpicker_preview' disabled='disabled' style='background-color:#" . $value['fcolor'] . "'>
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

#FULLSCREEN BACKGROUND
    if ($now_post_type == "page" && get_page_template_slug() == "page-background.php") {
        echo "
        <!-- FULLSCREEN SLIDER SETTINGS -->
                <div class='padding-cont  stand-s pt_" . $now_post_type . "'>
                    <div class='bg_or_slider_option slider_type active'>
                        <input type='hidden' name='settings_type' value='fullscreen' class='settings_type'>
                        <div class='hideable-area'>
                            <div class='padding-cont' style='padding-top:12px;'>
								<div class='gt3settings_box no-margin'>									
									<div class='gt3settings_box_title'><h2>" . __('Select Background Options', 'gt3_builder') . "</h2></div>
										<div class='gt3settings_box_content'>
											<select name='pagebuilder[bg_settings][type]' class='strip_select bg_type_select' style='margin-bottom:10px;'>";
        $fs_fit_style = array("bg_image" => "Image background", "bg_video" => "Video Background");
        foreach ($fs_fit_style as $var_data => $var_caption) {
            echo "<option " . ((isset($gt3_theme_pagebuilder['bg_settings']['type']) && $gt3_theme_pagebuilder['bg_settings']['type'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
        }
        echo "</select>
											<div class='clear'></div>";
        $hide_img = "";
        $hide_video = "";
        if (isset($gt3_theme_pagebuilder['bg_settings']['type']) && $gt3_theme_pagebuilder['bg_settings']['type'] == 'bg_video') {
            $hide_img = "style='display:none;'";
        } else {
            $hide_video = "style='display:none;'";
        }

        echo "<div class='bg_type_video' " . $hide_video . ">
												<input type='text' class='medium textoption type1' name='pagebuilder[bg_settings][videourl]' value='" . (isset($gt3_theme_pagebuilder['bg_settings']['videourl']) ? $gt3_theme_pagebuilder['bg_settings']['videourl'] : "") . "' style='margin-bottom:15px;'>
												<div class='example'>Examples:<br>Youtube - http://www.youtube.com/watch?v=6v2L2UGZJAM<br>Vimeo - http://vimeo.com/47989207</div>

												<h2 style='padding:15px 0 5px 0'>" . __('Video Style:', 'gt3_builder') . "</h2>
												<select name='pagebuilder[bg_settings][video_type]' class='strip_select bg_type_select' style='margin-bottom:10px;'>";
														$fs_fit_style = array("video_cover" => "Cover Video Background", "video_fit" => "Fit Video Background");
														foreach ($fs_fit_style as $var_data => $var_caption) {
															echo "<option " . ((isset($gt3_theme_pagebuilder['bg_settings']['video_type']) && $gt3_theme_pagebuilder['bg_settings']['video_type'] == $var_data) ? 'selected="selected"' : '') . " value='" . $var_data . "'>" . $var_caption . "</option>";
														}
											echo "</select>												
											</div>
											<div class='bg_type_image' " . $hide_img . ">
												<input type='text' placeholder='Image' name='pagebuilder[bg_settings][image]' value='" . (isset($gt3_theme_pagebuilder['bg_settings']['image']) ? $gt3_theme_pagebuilder['bg_settings']['image'] : "") . "' class='gt3UploadImg strip_input'>
											</div>";
        echo "
											<script>
												jQuery(document).ready(function() {
													jQuery('.bg_type_select').change(function(){
														if (jQuery(this).val() == 'bg_image') {
															jQuery('.bg_type_video').hide();
															jQuery('.bg_type_image').show();
														} else {
															jQuery('.bg_type_image').hide();
															jQuery('.bg_type_video').show();
														}
													});
												});
											</script>
										</div>
									</div>
								</div>";
        echo "
						</div>
                    </div>
                </div>
				<style>
					.edit-form-section, .postarea.wp-editor-expand, .page-builder-container {
						display:none;
					}
					#postdivrich, #postexcerpt {display:none!important}
					.selected_media .img-item .img-preview img {
						width:auto!important;
						height:auto!important;
					}
				</style>
                <!-- END SETTINGS -->";
    }

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
        print_r($gt3_theme_pagebuilder);
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