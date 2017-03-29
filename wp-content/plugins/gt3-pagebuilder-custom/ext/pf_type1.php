<?php

global $gt3_current_page_sidebar;
if (isset($pf)) {
	if ($pf == "image") { $compile .= '<div class="featured_image_full wrapped_img blog_post_image">';}
	if ($pf == "video") { $compile .= '<div class="pf_video_container wrapped_video blog_post_image">';}
	
	/* IMAGE FORMAT */
	if ($pf == "image")  {
		$compile .= get_selected_pf_images($gt3_theme_pagebuilder);
	}
	
	/* VIDEO FORMAT */
	if ($pf == "video")  {
		$video_url = $gt3_theme_pagebuilder['post-formats']['videourl'];
		if (isset($gt3_theme_pagebuilder['post-formats']['video_height'])) {
			$video_height = $gt3_theme_pagebuilder['post-formats']['video_height'];
		} else {
			$video_height = $pbconfig['default_video_height'];
		}
	
	
	/* only for demo */
	global $this_is_bloglisting;
	if (function_exists ( "gt3_get_theme_option" ) && gt3_get_theme_option("demo_server") == "true" && ($gt3_current_page_sidebar == "left-sidebar" || $gt3_current_page_sidebar == "right-sidebar") && $this_is_bloglisting == true) {
			$video_height = 436;
		}
	if (function_exists ( "gt3_get_theme_option" ) && gt3_get_theme_option("demo_server") == "true" && $this_is_bloglisting == true && ($gt3_current_page_sidebar !== "left-sidebar" && $gt3_current_page_sidebar !== "right-sidebar")) {
		$video_height = 552;
	}
	/* end only for demo */
	
		#YOUTUBE
		$is_youtube = substr_count($video_url, "youtu");
		if ($is_youtube > 0) {
			$videoid = substr(strstr($video_url, "="), 1);
			$compile .= "
				<iframe width=\"100%\" height=\"".$video_height."\" src=\"http://www.youtube.com/embed/" . $videoid . "?wmode=opaque\" frameborder=\"0\" allowfullscreen></iframe>
			";
		}
	
		#VIMEO
		$is_vimeo = substr_count($video_url, "vimeo");
		if ($is_vimeo > 0) {
			$videoid = substr(strstr($video_url, "m/"), 2);
			$compile .= "
				<iframe src=\"http://player.vimeo.com/video/" . $videoid . "\" width=\"100%\" height=\"".$video_height."\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			";
		}
	
	}
	
	if ($pf == "image" || $pf == "video") { $compile .= '</div>';}
}
?>
