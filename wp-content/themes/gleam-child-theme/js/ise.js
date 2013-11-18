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
  var form_valid = true;
  jQuery(e.target).parents('form').find('select').each(function () {
    if (jQuery(this).find(":selected").val() == "*") {
      jQuery(this).after('<span style="color:red"> ** Please Select **</span>');
      form_valid = false;
    }
  });
  
  if ( !form_valid ) {
    e.preventDefault();
  }
}
