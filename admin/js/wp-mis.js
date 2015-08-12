jQuery(document).ready(function($) {

  $('.add-image, #insert-media-button').on('click', function(event) {
    event.preventDefault();

    var field = $(this).parents('.field'),
        acf = false,
        fieldKey = false;

    // Input is an ACF...
    if (field) {
      acf = true;
      fieldKey = $(field).data('field_name');
    }

    $.ajax({
      url:  ajax_object.ajax_url,
      type: 'POST',
      data: {
        action: 'update_user_mod_field',
        acf:acf,
        fieldKey:fieldKey
      },
      success:function(data){
        console.log(data);
      }
    });

  });

  $('#mis-options .clear-vals').on('click', function() {
    $(this).siblings('input').val('');
  });

});