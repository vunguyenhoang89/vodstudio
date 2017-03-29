jQuery(document).ready(function($) {
    $(".box_close").click(function(){
        $(this).parents(".module_messageboxes").slideUp("fast");
    });
});
