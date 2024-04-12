"use strict";

(function ($) {
  $(function () {
    $('#woocommerce-product-data, #variable_product_options').on('woocommerce_variations_loaded', function () {
      $('.woocommerce_variation').each(function () {
        var upload_field = $(this).find('.agni-additional-variation-images'),
          existing_upload_field = $(this).find('.upload_image');
        upload_field.appendTo(existing_upload_field);
      });
    });
    $('body').on('click', '.agni_variation-images__button', function (e) {
      e.preventDefault();
      var $this = $(this),
        img_container = $(this).siblings('.agni-additional-variation-images__holder'),
        product_id = $(this).data('variation-id'),
        custom_uploader = wp.media({
          title: 'Choose images',
          library: {
            type: 'image'
          },
          button: {
            text: 'Set variation images'
          },
          multiple: true
        }).on('select', function () {
          var attachments = custom_uploader.state().get('selection'),
            attachment_ids = new Array(),
            i = 0;
          var html = attachments.map(function (attachment) {
            attachment_ids[i] = attachment['id'];
            console.log(attachment);
            i++;
            var data = wp.template('agni_product_variation_images__image');
            return data({
              variation_id: product_id,
              id: attachment.get('id'),
              url: attachment.get('url')
            });
          });
          $(img_container).html(html);
          $.update_varation($this);
        }).open();
    });
    $('body').on('click', '.agni-additional-variation-images__remove', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var $this = $(this),
        img_remove_id = $this.data('remove-id');
      $this.parent('#' + img_remove_id).remove();
      $.update_varation($this);
    });
    $.update_varation = function ($this) {
      $($this).closest('.woocommerce_variation').addClass('variation-needs-update');
      $('button.cancel-variation-changes, button.save-variation-changes').removeAttr('disabled');
      $('#variable_product_options').trigger('woocommerce_variations_input_changed');
    };
  });
})(jQuery);