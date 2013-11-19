(function ($) {
  // DOES NOT WORK BECAUSE GLEAM IS RELOADING THE PAGE SOMEHOW
  //$(window).load(function() {
    // Check for the Store page
  //});
  
  // if ( $('body').hasClass('page-id-52') ) {
  //   alert("hello");
  // }
  
  // $('form').submit(function( event ) {
  // if ( $('body').hasClass('page-id-52') ) {
  // }
  // });
}(jQuery));

function validateForm(e) {
  var form_submit = event.target.name == "submit" ? true : false;
  
  if ( form_submit ) {
    processForm(e);
  } else {
    processDropDown(e);
  }
}

function processForm(e){
  var form_valid = true;
  
  jQuery(e.target).parents('form').find('select').each(function () {
    var current_select_id = jQuery(this).attr('id');
    
    if (jQuery(this).find(":selected").val() == "*") {
      if ( jQuery('.form-errors.' + current_select_id).length == 0 ) {
        displayError(this, current_select_id);
      }
      
      form_valid = false;
    } else {
      clearError(current_select_id);
    }
  });
  
  if ( !form_valid ) {
    e.preventDefault();
  } else {
    jQuery('.form-errors').html('');
  }
}

function processDropDown(e){
  var current_select_id = jQuery(e.target).attr('id');
  
  if (jQuery(e.target).find(":selected").val() != "*") {
    clearError(current_select_id);
  }
}

function displayError(current_element, current_select_id){
  jQuery(current_element).after('<span class="form-errors ' + current_select_id + 
    '" style="color:red"> ** Please Select **</span>');
}

function clearError(current_select_id){
  jQuery('.form-errors.' + current_select_id).remove();
}
