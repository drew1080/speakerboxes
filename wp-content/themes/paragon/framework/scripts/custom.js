// Included because Prototype and jQuery are being used
jQuery.noConflict();

jQuery(document).ready(function($){
	
	$('.scroll-pane').jScrollPane();
	
	$('#related-projects-list').pajinate({
		item_container_id : '.related-projects-content',
		items_per_page : 2,
		nav_label_prev : '<img src="/mixedpaintmurals/wp-content/themes/paragon/framework/images/previous-button.png" />',
		nav_label_next : '<img src="/mixedpaintmurals/wp-content/themes/paragon/framework/images/next-button.png" />',
		show_first_last : false,
		num_page_links_to_display : 0
	});
	
	/*$('#project-large-images-paging-wrapper .page_navigation').smartpaginator({ 
		totalrecords: 5, recordsperpage: 1, length: 1, next: 'Next', prev: 'Prev', 
		first: 'First', last: 'Last', theme: 'red', controlsalways: true, 
		onchange: function (newPage) {
      //$('#r').html('Page # ' + newPage);
  	}
  });*/
	
	
	$('#project-large-images-paging-wrapper').pajinate({
		item_container_id : '.project-large-images',
		items_per_page : 1,
		nav_label_prev : '<img src="/mixedpaintmurals/wp-content/themes/paragon/framework/images/previous-button.png" />',
		nav_label_next : '<img src="/mixedpaintmurals/wp-content/themes/paragon/framework/images/next-button.png" />',
		show_first_last : false,
		num_page_links_to_display : 0,
		start_page: 0
	});
	
	$('div.slideshow img').each(function() { 
		$(this).css({left: '50%', marginLeft: -$(this).width()/2 + 285}); 
	});
	
	$('.slideshow').cycle({
			fx: 'fade' // choose your transition type, ex: fade, scrollUp, shuffle, etc...
	});
	
	//$("#project-large-images-paging-wrapper .next_link img").click(function(){	
	//$("#large-image-14").click(function(){
	$("#project-large-images-paging-wrapper .next_link").click(function(){
		var new_page_index = 
			parseInt(jQuery("#project-large-images-paging-wrapper .page_link.active_page").attr("longdesc"));
		var image_id = jQuery(".project-images [longdesc='" + new_page_index + "']").attr("id");
		
		updateTitleAndDescription(image_id);
		updatePajinate(new_page_index);
		alignPagination("#project-large-images-paging-wrapper", "left-margin-large-images");
	});
	
	$("#project-large-images-paging-wrapper .previous_link").click(function(){
		var new_page_index = 
			parseInt(jQuery("#project-large-images-paging-wrapper .page_link.active_page").attr("longdesc"));
		var image_id = jQuery(".project-images [longdesc='" + new_page_index + "']").attr("id");	
		
		updateTitleAndDescription(image_id);
		updatePajinate(new_page_index);
		alignPagination("#project-large-images-paging-wrapper", "left-margin-large-images");
	});
	
	$("#related-projects-list .previous_link").click(function(){
		alignPagination("#related-projects-list", "left-margin-related-projects");
	});
	
	$("#related-projects-list .next_link").click(function(){
		alignPagination("#related-projects-list", "left-margin-related-projects");
	});
	
//////////////////////////////////////////////////////// ad Gallery
	/*
	var galleries = $('.ad-gallery').adGallery({
		loader_image: 'wp-content/themes/paragon/framework/css/loader.gif',
		slideshow: { enable: false },
		width: 600, // Width of the image, set to false and it will read the CSS width
	  height: 450 // Height of the image, set to false and it will read the CSS height
	});
	
	$('.ad-gallery').each(function(){
		$(this).hide();
	});*/
	
//////////////////////////////////////////////////////// Custom code
	
	// Capture a click on the first cute image
	/*
	$('#first-cuteness').click(function(){
		resetGalleries();
		$(this).hide();
		$('#cuteness-gallery').show('slow');
		
		// Prevent default href functionality
		return false;
	});
	
	// Capture a click on the first unique image
	$('#first-uniqueness').click(function(){
		resetGalleries();
		$(this).hide();
		$('#uniqueness-gallery').show('slow');
		
		// Prevent default href functionality
		return false;
	});
	
	
	function resetGalleries() {
		$('.ad-gallery').each(function(){
			$(this).hide('slow');
		});
		
		$('.first').each(function(){
			$(this).show('slow');
		});
	}
	*/

//end
});

function changeProjectDisplayImage(current_element, image_id) {
	
	jQuery('.project-large-images .large-image-wrapper').each(function(){
		jQuery(this).hide();
	});
	
	updateTitleAndDescription(image_id);
	
	var current_longdesc_number = current_element.attributes["longdesc"].value;
	
	updatePajinate(current_longdesc_number);
}

function updateTitleAndDescription(image_id) {
	jQuery('.project-large-images .project-title').each(function(){
		jQuery(this).hide();
	});
	
	jQuery('.project-large-images .image-description').each(function(){
		jQuery(this).hide();
	});

	jQuery('#large-image-'+ image_id).fadeIn();
	jQuery('#title-'+ image_id).fadeIn();
	jQuery('#desc-'+ image_id).fadeIn();
}


function updatePajinate(new_longdesc_number) {
	var current_page_index = 
		jQuery("#project-large-images-paging-wrapper .page_link.active_page").attr("longdesc");
	
	jQuery("#project-large-images-paging-wrapper .page_link.active_page").removeClass("active_page");
	jQuery("#project-large-images-paging-wrapper .page_link[longdesc='" + new_longdesc_number + "']").addClass("active_page");

	if ( jQuery("#project-large-images-paging-wrapper .page_link[longdesc='" + 
		new_longdesc_number + "']").attr('class').indexOf("last") > -1 ) {
		showLastPage();	
	} else if ( 	jQuery("#project-large-images-paging-wrapper .page_link[longdesc='" + 
			new_longdesc_number + "']").attr('class').indexOf("first") > -1 ) {
		showFirstPage();		
	} else {
		showMiddlePage();
	}
	
	alignPagination("#project-large-images-paging-wrapper", "left-margin-large-images");
}

function showLastPage() {
	jQuery("#project-large-images-paging-wrapper .previous_link").show();
	jQuery("#project-large-images-paging-wrapper .previous_link").removeClass("no_more");
	jQuery("#project-large-images-paging-wrapper .next_link").hide();
	jQuery("#project-large-images-paging-wrapper .next_link").addClass("no_more");
}

function showFirstPage() {
	jQuery("#project-large-images-paging-wrapper .previous_link").hide();
	jQuery("#project-large-images-paging-wrapper .previous_link").addClass("no_more");
	jQuery("#project-large-images-paging-wrapper .next_link").show();
	jQuery("#project-large-images-paging-wrapper .next_link").removeClass("no_more");
}

function showMiddlePage() {
	jQuery("#project-large-images-paging-wrapper .previous_link").show();
	jQuery("#project-large-images-paging-wrapper .next_link").show();
	jQuery("#project-large-images-paging-wrapper .previous_link").removeClass("no_more");
	jQuery("#project-large-images-paging-wrapper .next_link").removeClass("no_more");
}

function alignPagination(pagination_wrapper, left_margin_class) {
	var previous_buttom_viewable = 
		jQuery(pagination_wrapper + " .previous_link").attr('class').indexOf("no_more") == -1;
	var has_left_padding = jQuery(pagination_wrapper + " .next_link").attr('class').indexOf(left_margin_class) >= 0;
 
	if (!previous_buttom_viewable && has_left_padding){
		jQuery(pagination_wrapper + " .next_link").removeClass(left_margin_class);
	}else if ( previous_buttom_viewable && !has_left_padding) {
		jQuery(pagination_wrapper + " .next_link").addClass(left_margin_class);
	}
}

//////////////////////////////////////////////////
// Window load event, which is after the document load event
//////////////////////////////////////////////////
jQuery(window).load(function() {
	jQuery('.project-image img').each(function(index) {
		jQuery(this).show('slow');
	});
	
	jQuery('.project-images').css("background", "rgba(245,245,245,0.5)");  
	jQuery('.large-project-image').css("background", "");  
});