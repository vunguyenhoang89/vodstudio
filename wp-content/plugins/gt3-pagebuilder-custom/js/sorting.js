/* SORTING */ 

jQuery(function(){
  var $container = jQuery('.fs_blog_module');

  $container.isotope({
	itemSelector : '.element'
  });
    
  var $optionSets = jQuery('.optionset'),
	  $optionLinks = $optionSets.find('a');

  $optionLinks.click(function(){
	var $this = jQuery(this);
	// don't proceed if already selected
	if ( $this.parent('li').hasClass('selected') ) {
	  return false;
	}
	var $optionSet = $this.parents('.optionset');
	$optionSet.find('.selected').removeClass('selected');
	$this.parent('li').addClass('selected');

	// make option object dynamically, i.e. { filter: '.my-filter-class' }
	var options = {},
		key = $optionSet.attr('data-option-key'),
		value = $this.attr('data-option-value');
	// parse 'false' as false boolean
	value = value === 'false' ? false : value;
	options[ key ] = value;
	if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
	  // changes in layout modes need extra logic
	  changeLayoutMode( $this, options )
	} else {
	  // otherwise, apply new options
	  $container.isotope(options);	  
	}	
	return false;	
  });
	jQuery('.masonry').find('img').load(function(){
		$container.isotope('reLayout');
	}); 	
});

jQuery(window).resize(function(){
	jQuery('.fs_blog_module').isotope('reLayout');
});
jQuery(window).load(function(){
	jQuery('.fs_blog_module').isotope('reLayout');
});
