<?php

define('GT3PB_SETTINGS_', plugins_url('/', __FILE__));

#main pb block settings
$GLOBALS["pbconfig"]['slider_and_bg_area'] = true;
$GLOBALS["pbconfig"]['slider_and_bg_area_enable_for'] = array('gallery', 'post', 'port', 'page');

#background / slider settings
$GLOBALS["pbconfig"]['enable_fullscreen_slider'] = false;
$GLOBALS["pbconfig"]['enable_fullwidth_slider'] = false;
$GLOBALS["pbconfig"]['enable_background_image'] = false;
$GLOBALS["pbconfig"]['enable_background_color'] = false;

#For this post types we enable page builder
$GLOBALS["pbconfig"]['page_builder_enable_for_posts'] = array('page', 'gallery', 'team', 'testimonials', 'partners', 'port');

#detail settings for page customization
$GLOBALS["pbconfig"]['pb_modules_enabled_for'] = array('page', 'port');
$GLOBALS["pbconfig"]['page_settings_enabled_for'] = array('page');
$GLOBALS["pbconfig"]['fullcreen_slider_enabled_for'] = array('gallery');
$GLOBALS["pbconfig"]['fullwidth_slider_enabled_for'] = array();
$GLOBALS["pbconfig"]['bg_image_enabled_for'] = array('post', 'port', 'page');
$GLOBALS["pbconfig"]['bg_color_enabled_for'] = array('post', 'port', 'page');

#List bg types for pages
$GLOBALS["pbconfig"]['page_bg_available_types'] = array('stretched', 'repeat');

#all_available_headers_for_module
$GLOBALS["pbconfig"]['all_available_headers_for_module'] = array("h1", "h2", "h3", "h4", "h5", "h6");

#all_available_headers_for_module
$GLOBALS["pbconfig"]['all_available_headers_alignment'] = array("left", "center", "right");

#default heading in module
$GLOBALS["pbconfig"]['default_heading_in_module'] = "h4";

#available quote types
$GLOBALS["pbconfig"]['all_available_quote_types'] = array("" => "Default", "type1" => "Type1", "type2" => "Type2", "type3" => "Type3", "type4" => "Type4", "type5" => "Type5");

#gallery
$GLOBALS["pbconfig"]['gallery_module_default_width'] = "100px";
$GLOBALS["pbconfig"]['gallery_module_default_height'] = "150px";

#blog default posts per page
$GLOBALS["pbconfig"]['blog_default_posts_per_page'] = 7;
$GLOBALS["pbconfig"]['blog_masonry_default_posts_per_page'] = 7;
$GLOBALS["pbconfig"]['all_blogpost_date_types'] = array("date-inside_meta" => "Inside blog post meta", "date-global_left" => "Global left", "date-top_left" => "Above featured image", "date-content_left" => "Below featured image");
$GLOBALS["pbconfig"]['all_blogpost_title_types'] = array("title-top" => "Above featured image", "title-content" => "Below featured image");
$GLOBALS["pbconfig"]['all_blogpost_meta_types'] = array("meta-top" => "Above featured image", "meta-content" => "Below featured image", "meta-bottom" => "Below content");

#portfolio default posts per page
$GLOBALS["pbconfig"]['posts_per_page'] = 4;
$GLOBALS["pbconfig"]['all_available_portfolio_types'] = array("1 column", "2 columns", "3 columns", "4 columns");

#featured posts number of posts (not main blog module!)
$GLOBALS["pbconfig"]['featured_posts_default_number_of_posts'] = 12;
$GLOBALS["pbconfig"]['featured_posts_default_posts_per_line'] = 4;
$GLOBALS["pbconfig"]['featured_posts_letters_in_excerpt'] = 130;
$GLOBALS["pbconfig"]['featured_posts_available_post_types'] = array(
    "post" => "Post",
    "port" => "Portfolio",
);
$GLOBALS["pbconfig"]['featured_posts_available_sorting_type'] = array("new", "random");

#default video height
$GLOBALS["pbconfig"]['default_video_height'] = "450px";

#default number of workers for team module
$GLOBALS["pbconfig"]['team_default_numbers'] = 20;

#testimonials
$GLOBALS["pbconfig"]['all_available_testimonial_display_type'] = array("type1", "type2");

#all available testimonial sorting type
$GLOBALS["pbconfig"]['all_available_testimonial_sorting_type'] = array("new", "random");

#all available iconboxes
$GLOBALS["pbconfig"]['all_available_iconboxes'] = array("a", "b", "c");

#iconboxes img preview
$GLOBALS["pbconfig"]['iconboxes_img_preview_url'] = "/core/admin/img/available_iconboxes.jpg";

#all available custom list types
$GLOBALS["pbconfig"]['all_available_custom_list_types'] = array(
    "ordered" => "Ordered",
    "list_type1" => "Arrow",
    "list_type2" => "Plus",
    "" => "Normal",
    "list_type3" => "Download",
    "list_type4" => "Print",
    "list_type5" => "Edit",
    "list_type6" => "Attach"
);

#all available custom buttons
$GLOBALS["pbconfig"]['all_available_custom_buttons'] = array(
    "btn_small btn_type1" => "Small Dark",
    "btn_small btn_type2" => "Small Gray",
    "btn_small btn_type3" => "Small Light Gray",
    "btn_small btn_type4" => "Small Light",
    "btn_small btn_type5" => "Small Colored",
    "btn_small btn_type6" => "Small Sea Blue",
    "btn_small btn_type7" => "Small Green",
    "btn_small btn_type8" => "Small Lime",
    "btn_small btn_type9" => "Small Yellow",
    "btn_small btn_type10" => "Small Orange",
    "btn_small btn_type11" => "Small Red",
    "btn_small btn_type12" => "Small Pink",
    "btn_small btn_type13" => "Small Magenta",
    "btn_small btn_type14" => "Small Violet",
    "btn_small btn_type15" => "Small Purple",
    "btn_small btn_type16" => "Small Blue",
    "btn_small btn_type17" => "Small Light Blue",
    "btn_normal btn_type1" => "Medium Dark",
    "btn_normal btn_type2" => "Medium Gray",
    "btn_normal btn_type3" => "Medium Light Gray",
    "btn_normal btn_type4" => "Medium Light",
    "btn_normal btn_type5" => "Medium Colored",
    "btn_normal btn_type6" => "Medium Sea Blue",
    "btn_normal btn_type7" => "Medium Green",
    "btn_normal btn_type8" => "Medium Lime",
    "btn_normal btn_type9" => "Medium Yellow",
    "btn_normal btn_type10" => "Medium Orange",
    "btn_normal btn_type11" => "Medium Red",
    "btn_normal btn_type12" => "Medium Pink",
    "btn_normal btn_type13" => "Medium Magenta",
    "btn_normal btn_type14" => "Medium Violet",
    "btn_normal btn_type15" => "Medium Purple",
    "btn_normal btn_type16" => "Medium Blue",
    "btn_normal btn_type17" => "Medium Light Blue",
    "btn_large btn_type1" => "Large Dark",
    "btn_large btn_type2" => "Large Gray",
    "btn_large btn_type3" => "Large Light Gray",
    "btn_large btn_type4" => "Large Light",
    "btn_large btn_type5" => "Large Colored",
    "btn_large btn_type6" => "Large Sea Blue",
    "btn_large btn_type7" => "Large Green",
    "btn_large btn_type8" => "Large Lime",
    "btn_large btn_type9" => "Large Yellow",
    "btn_large btn_type10" => "Large Orange",
    "btn_large btn_type11" => "Large Red",
    "btn_large btn_type12" => "Large Pink",
    "btn_large btn_type13" => "Large Magenta",
    "btn_large btn_type14" => "Large Violet",
    "btn_large btn_type15" => "Large Purple",
    "btn_large btn_type16" => "Large Blue",
    "btn_large btn_type17" => "Large Light Blue"
);

#all available custom buttons positions
$GLOBALS["pbconfig"]['all_available_positions_for_custom_buttons'] = array(
    "" => "Default",
    "btnpos_left" => "Left",
    "btnpos_right" => "Right",
    "btnpos_center" => "Center"
);

#all available custom buttons
$GLOBALS["pbconfig"]['all_available_target_for_custom_buttons'] = array(
    "_blank" => "Blank",
    "_self" => "Self"
);

#all available dropcaps
$GLOBALS["pbconfig"]['all_available_dropcaps'] = array(
    "" => "Type1",
    "type1" => "Type2"
);

#all available messageboxes
$GLOBALS["pbconfig"]['messagebox_available_types'] = array(
    "box_type1" => "Type 1",
    "box_type2" => "Type 2",
    "box_type3" => "Type 3",
    "box_type4" => "Type 4",
    "box_type5" => "Type 5"
);

#all available highlighters
$GLOBALS["pbconfig"]['all_available_highlighters'] = array(
    "colored" => "Colored",
    "dark" => "Dark",
    "light" => "Light"
);

#all available dividers
$GLOBALS["pbconfig"]['all_available_dividers'] = array(
    "type1" => "Type1",
    "type2" => "Type2",
    "type3" => "Type3"
);

#all available tabs types
$GLOBALS["pbconfig"]['available_tabs_types'] = array(
    "type1" => "Horizontal",
    "type2" => "Vertical"
);

#all available social icons
$GLOBALS["pbconfig"]['all_available_social_icons'] = array();

#all available social icon type
$GLOBALS["pbconfig"]['all_available_social_icons_type'] = array(
    "type1" => "Square",
    "type2" => "Rounded",
    "type3" => "Circle",
    "type4" => "Empty",
);

#partners number
$GLOBALS["pbconfig"]['partners_default_number'] = 6;

#Padding top for bg start
$GLOBALS["pbconfig"]['available_padding_top_for_bg_start'] = array(
    "top_padding_normal" => "Default (45px)",
    "top_padding_medium" => "20px",
    "top_padding_small" => "15px",
    "top_padding_none" => "0px",
);

#Padding after modules
$GLOBALS["pbconfig"]['default_padding_after_module'] = "40px";

#View type for Meta module
$GLOBALS["pbconfig"]['available_postinfo_module_view_types'] = array(
    "portfolio_type1" => "Vertical",
    "portfolio_type2" => "Horizontal"
);

#how many images from media library show on one page
$GLOBALS["pbconfig"]['images_from_media_library'] = 30;

#How many items in OUR TEAM per line
$GLOBALS["pbconfig"]['available_workers_per_line'] = array(
    "1" => "1",
    "2" => "2",
    "3" => "3",
    "4" => "4",
);

#How many items in FEATURED POSTS per line
$GLOBALS["pbconfig"]['available_posts_per_line'] = array(
    "1" => "1",
    "2" => "2",
    "3" => "3",
    "4" => "4",
);

#How many images in a row (in gallery)
$GLOBALS["pbconfig"]['gallery_images_in_a_row'] = array(
    "1" => "1",
    "2" => "2",
    "3" => "3",
    "4" => "4"
);

#All font icons
$GLOBALS["pbconfig"]['all_available_font_icons'] = array(
	"icon-glass",
	"icon-music",
	"icon-search",
	"icon-envelope-o",
	"icon-heart",
	"icon-star",
	"icon-star-o",
	"icon-user",
	"icon-film",
	"icon-th-large",
	"icon-th",
	"icon-th-list",
	"icon-check",
	"icon-times",
	"icon-search-plus",
	"icon-search-minus",
	"icon-power-off",
	"icon-signal",
	"icon-cog",
	"icon-trash-o",
	"icon-home",
	"icon-file-o",
	"icon-clock-o",
	"icon-road",
	"icon-download",
	"icon-arrow-circle-o-down",
	"icon-arrow-circle-o-up",
	"icon-inbox",
	"icon-play-circle-o",
	"icon-repeat",
	"icon-refresh",
	"icon-list-alt",
	"icon-lock",
	"icon-flag",
	"icon-headphones",
	"icon-volume-off",
	"icon-volume-down",
	"icon-volume-up",
	"icon-qrcode",
	"icon-barcode",
	"icon-tag",
	"icon-tags",
	"icon-book",
	"icon-bookmark",
	"icon-print",
	"icon-camera",
	"icon-font",
	"icon-bold",
	"icon-italic",
	"icon-text-height",
	"icon-text-width",
	"icon-align-left",
	"icon-align-center",
	"icon-align-right",
	"icon-align-justify",
	"icon-list",
	"icon-outdent",
	"icon-indent",
	"icon-video-camera",
	"icon-picture-o",
	"icon-pencil",
	"icon-map-marker",
	"icon-adjust",
	"icon-tint",
	"icon-pencil-square-o",
	"icon-share-square-o",
	"icon-check-square-o",
	"icon-arrows",
	"icon-step-backward",
	"icon-fast-backward",
	"icon-backward",
	"icon-play",
	"icon-pause",
	"icon-stop",
	"icon-forward",
	"icon-fast-forward",
	"icon-step-forward",
	"icon-eject",
	"icon-chevron-left",
	"icon-chevron-right",
	"icon-plus-circle",
	"icon-minus-circle",
	"icon-times-circle",
	"icon-check-circle",
	"icon-question-circle",
	"icon-info-circle",
	"icon-crosshairs",
	"icon-times-circle-o",
	"icon-check-circle-o",
	"icon-ban",
	"icon-arrow-left",
	"icon-arrow-right",
	"icon-arrow-up",
	"icon-arrow-down",
	"icon-share",
	"icon-expand",
	"icon-compress",
	"icon-plus",
	"icon-minus",
	"icon-asterisk",
	"icon-exclamation-circle",
	"icon-gift",
	"icon-leaf",
	"icon-fire",
	"icon-eye",
	"icon-eye-slash",
	"icon-exclamation-triangle",
	"icon-plane",
	"icon-calendar",
	"icon-random",
	"icon-comment",
	"icon-magnet",
	"icon-chevron-up",
	"icon-chevron-down",
	"icon-retweet",
	"icon-shopping-cart",
	"icon-folder",
	"icon-folder-open",
	"icon-arrows-v",
	"icon-arrows-h",
	"icon-bar-chart-o",
	"icon-twitter-square",
	"icon-facebook-square",
	"icon-camera-retro",
	"icon-key",
	"icon-cogs",
	"icon-comments",
	"icon-thumbs-o-up",
	"icon-thumbs-o-down",
	"icon-star-half",
	"icon-heart-o",
	"icon-sign-out",
	"icon-linkedin-square",
	"icon-thumb-tack",
	"icon-external-link",
	"icon-sign-in",
	"icon-trophy",
	"icon-github-square",
	"icon-upload",
	"icon-lemon-o",
	"icon-phone",
	"icon-square-o",
	"icon-bookmark-o",
	"icon-phone-square",
	"icon-twitter",
	"icon-facebook",
	"icon-github",
	"icon-unlock",
	"icon-credit-card",
	"icon-rss",
	"icon-hdd-o",
	"icon-bullhorn",
	"icon-bell",
	"icon-certificate",
	"icon-hand-o-right",
	"icon-hand-o-left",
	"icon-hand-o-up",
	"icon-hand-o-down",
	"icon-arrow-circle-left",
	"icon-arrow-circle-right",
	"icon-arrow-circle-up",
	"icon-arrow-circle-down",
	"icon-globe",
	"icon-wrench",
	"icon-tasks",
	"icon-filter",
	"icon-briefcase",
	"icon-arrows-alt",
	"icon-users",
	"icon-link",
	"icon-cloud",
	"icon-flask",
	"icon-scissors",
	"icon-files-o",
	"icon-paperclip",
	"icon-floppy-o",
	"icon-square",
	"icon-bars",
	"icon-list-ul",
	"icon-list-ol",
	"icon-strikethrough",
	"icon-underline",
	"icon-table",
	"icon-magic",
	"icon-truck",
	"icon-pinterest",
	"icon-pinterest-square",
	"icon-google-plus-square",
	"icon-google-plus",
	"icon-money",
	"icon-caret-down",
	"icon-caret-up",
	"icon-caret-left",
	"icon-caret-right",
	"icon-columns",
	"icon-sort",
	"icon-sort-desc",
	"icon-sort-asc",
	"icon-envelope",
	"icon-linkedin",
	"icon-undo",
	"icon-gavel",
	"icon-tachometer",
	"icon-comment-o",
	"icon-comments-o",
	"icon-bolt",
	"icon-sitemap",
	"icon-umbrella",
	"icon-clipboard",
	"icon-lightbulb-o",
	"icon-exchange",
	"icon-cloud-download",
	"icon-cloud-upload",
	"icon-user-md",
	"icon-stethoscope",
	"icon-suitcase",
	"icon-bell-o",
	"icon-coffee",
	"icon-cutlery",
	"icon-file-text-o",
	"icon-building-o",
	"icon-hospital-o",
	"icon-ambulance",
	"icon-medkit",
	"icon-fighter-jet",
	"icon-beer",
	"icon-h-square",
	"icon-plus-square",
	"icon-angle-double-left",
	"icon-angle-double-right",
	"icon-angle-double-up",
	"icon-angle-double-down",
	"icon-angle-left",
	"icon-angle-right",
	"icon-angle-up",
	"icon-angle-down",
	"icon-desktop",
	"icon-laptop",
	"icon-tablet",
	"icon-mobile",
	"icon-circle-o",
	"icon-quote-left",
	"icon-quote-right",
	"icon-spinner",
	"icon-circle",
	"icon-reply",
	"icon-github-alt",
	"icon-folder-o",
	"icon-folder-open-o",
	"icon-smile-o",
	"icon-frown-o",
	"icon-meh-o",
	"icon-gamepad",
	"icon-keyboard-o",
	"icon-flag-o",
	"icon-flag-checkered",
	"icon-terminal",
	"icon-code",
	"icon-reply-all",
	"icon-star-half-o",
	"icon-location-arrow",
	"icon-crop",
	"icon-code-fork",
	"icon-chain-broken",
	"icon-question",
	"icon-info",
	"icon-exclamation",
	"icon-superscript",
	"icon-subscript",
	"icon-eraser",
	"icon-puzzle-piece",
	"icon-microphone",
	"icon-microphone-slash",
	"icon-shield",
	"icon-calendar-o",
	"icon-fire-extinguisher",
	"icon-rocket",
	"icon-maxcdn",
	"icon-chevron-circle-left",
	"icon-chevron-circle-right",
	"icon-chevron-circle-up",
	"icon-chevron-circle-down",
	"icon-html5",
	"icon-css3",
	"icon-anchor",
	"icon-unlock-alt",
	"icon-bullseye",
	"icon-ellipsis-h",
	"icon-ellipsis-v",
	"icon-rss-square",
	"icon-play-circle",
	"icon-ticket",
	"icon-minus-square",
	"icon-minus-square-o",
	"icon-level-up",
	"icon-level-down",
	"icon-check-square",
	"icon-pencil-square",
	"icon-external-link-square",
	"icon-share-square",
	"icon-compass",
	"icon-caret-square-o-down",
	"icon-caret-square-o-up",
	"icon-caret-square-o-right",
	"icon-eur",
	"icon-gbp",
	"icon-usd",
	"icon-inr",
	"icon-jpy",
	"icon-rub",
	"icon-krw",
	"icon-btc",
	"icon-file",
	"icon-file-text",
	"icon-sort-alpha-asc",
	"icon-sort-alpha-desc",
	"icon-sort-amount-asc",
	"icon-sort-amount-desc",
	"icon-sort-numeric-asc",
	"icon-sort-numeric-desc",
	"icon-thumbs-up",
	"icon-thumbs-down",
	"icon-youtube-square",
	"icon-youtube",
	"icon-xing",
	"icon-xing-square",
	"icon-youtube-play",
	"icon-dropbox",
	"icon-stack-overflow",
	"icon-instagram",
	"icon-flickr",
	"icon-adn",
	"icon-bitbucket",
	"icon-bitbucket-square",
	"icon-tumblr",
	"icon-tumblr-square",
	"icon-long-arrow-down",
	"icon-long-arrow-up",
	"icon-long-arrow-left",
	"icon-long-arrow-right",
	"icon-apple",
	"icon-windows",
	"icon-android",
	"icon-linux",
	"icon-dribbble",
	"icon-skype",
	"icon-foursquare",
	"icon-trello",
	"icon-female",
	"icon-male",
	"icon-gittip",
	"icon-sun-o",
	"icon-moon-o",
	"icon-archive",
	"icon-bug",
	"icon-vk",
	"icon-weibo",
	"icon-renren",
	"icon-pagelines",
	"icon-stack-exchange",
	"icon-arrow-circle-o-right",
	"icon-arrow-circle-o-left",
	"icon-caret-square-o-left",
	"icon-dot-circle-o",
	"icon-wheelchair",
	"icon-vimeo-square",
	"icon-try",
	"icon-plus-square-o",
	"icon-space-shuttle",
	"icon-slack",
	"icon-envelope-square",
	"icon-wordpress",
	"icon-openid",
	"icon-university",
	"icon-graduation-cap",
	"icon-yahoo",
	"icon-google",
	"icon-reddit",
	"icon-reddit-square",
	"icon-stumbleupon-circle",
	"icon-stumbleupon",
	"icon-delicious",
	"icon-digg",
	"icon-pied-piper",
	"icon-pied-piper-alt",
	"icon-drupal",
	"icon-joomla",
	"icon-language",
	"icon-fax",
	"icon-building",
	"icon-child",
	"icon-paw",
	"icon-spoon",
	"icon-cube",
	"icon-cubes",
	"icon-behance",
	"icon-behance-square",
	"icon-steam",
	"icon-steam-square",
	"icon-recycle",
	"icon-car",
	"icon-taxi",
	"icon-tree",
	"icon-spotify",
	"icon-deviantart",
	"icon-soundcloud",
	"icon-database",
	"icon-file-pdf-o",
	"icon-file-word-o",
	"icon-file-excel-o",
	"icon-file-powerpoint-o",
	"icon-file-image-o",
	"icon-file-archive-o",
	"icon-file-audio-o",
	"icon-file-video-o",
	"icon-file-code-o",
	"icon-vine",
	"icon-codepen",
	"icon-jsfiddle",
	"icon-life-ring",
	"icon-circle-o-notch",
	"icon-rebel",
	"icon-empire",
	"icon-git-square",
	"icon-git",
	"icon-hacker-news",
	"icon-tencent-weibo",
	"icon-qq",
	"icon-weixin",
	"icon-paper-plane",
	"icon-paper-plane-o",
	"icon-history",
	"icon-circle-thin",
	"icon-header",
	"icon-paragraph",
	"icon-sliders",
	"icon-share-alt",
	"icon-share-alt-square",
	"icon-bomb",
);

$GLOBALS["pbconfig"]['featured_port_default_number_of_posts'] = 2;
$GLOBALS["pbconfig"]['featured_port_default_posts_per_line'] = 2;

$GLOBALS["pbconfig"]['featured_portfolio_default_number_of_posts'] = 2;


global $compileShortcodeUI, $defaultUI;
$compileShortcodeUI = "";
$defaultUI = "";

?>