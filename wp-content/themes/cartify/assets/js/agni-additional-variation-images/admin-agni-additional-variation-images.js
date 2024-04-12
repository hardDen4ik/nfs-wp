"use strict";

(function ($) {
  $(document).ready(function () {
    $('#woocommerce-product-data, #variable_product_options').on('woocommerce_variations_loaded', function () {
      var custom_uploader, img_container;
      $('.agni_variation-images__button').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this),
          product_id = $(this).data('variation-id');
        img_container = $(this).siblings('.agni-additional-variation-images__holder');
        if (custom_uploader) {
          custom_uploader.open();
          return;
        }
        custom_uploader = wp.media({
          title: 'Choose images',
          library: {
            type: 'image'
          },
          button: {
            text: 'Set variation images'
          },
          multiple: true
        });
        custom_uploader.on('select', function () {
          var attachments = custom_uploader.state().get('selection'),
            attachment_ids = new Array(),
            i = 0;
          var html = attachments.map(function (attachment) {
            attachment_ids[i] = attachment['id'];
            i++;
            return '<div id="agni-variation-image-' + attachment.get('id') + '" class="agni-additional-variation-images__image"><input type="hidden" name="agni_product_variation_images[' + product_id + '][]" value="' + attachment.get('id') + '"/><img src="' + attachment.get('url') + '" width="150" height="150"><a class="agni-additional-variation-images__remove" data-remove-id="agni-variation-image-' + attachment.get('id') + '"></a></div>';
          });
          $(img_container).html(html);
          $.update_varation($btn);
        });
        custom_uploader.open();
      });
      $('.woocommerce_variation').each(function () {
        var upload_field = $(this).find('.agni-additional-variation-images'),
          existing_upload_field = $(this).find('.upload_image');
        upload_field.appendTo(existing_upload_field);
      });
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