"use strict";

(function ($) {
  'use strict';

  $(document).ready(function () {
    var repeatable_container = $('.agni_product_data_fields_repeatable');
    repeatable_container.find('div:only-of-type .remove').addClass('button-disabled');
    repeatable_container.on('click', '.remove', function (e) {
      e.preventDefault();
      var $this = $(this),
        container = $this.closest('.agni_product_data_fields_repeatable');
      $this.closest('div').remove();
      container.find('div:only-of-type .remove').addClass('button-disabled');
    });
    repeatable_container.on('click', '.add_field', function (e) {
      e.preventDefault();
      var $this = $(this),
        container = $this.closest('.agni_product_data_fields_repeatable');
      var row = container.find('div:last').clone(true);
      row.insertAfter(container.find('div:last'));
      container.find('div .remove').removeClass('button-disabled');
    });
    $(document).on('click', '.agni_product_data_tab_threesixty_images__button', function (e) {
      e.preventDefault();
      var $this = $(this),
        img_container = $this.siblings('.agni_product_data_tab_threesixty_images__holder'),
        product_id = $this.data('product-id'),
        custom_uploader = wp.media({
          title: 'Choose images',
          library: {
            type: 'image'
          },
          button: {
            text: 'Add images'
          },
          multiple: true
        }).on('select', function () {
          var attachments = custom_uploader.state().get('selection'),
            attachment_ids = [];
          var html = '';
          $this.parent().find('input').remove();
          attachments.map(function (attachment) {
            var $input_hidden = '<input type="hidden" id="agni_product_data_tab_threesixty_images" name="agni_product_data_tab_threesixty_images[]" value="' + attachment.get('id') + "," + attachment.get('url') + '" >';
            $($input_hidden).insertBefore($this);
            html += "<div id=\"agni_product_data_tab_threesixty_image-".concat(attachment.get('id'), "\" class=\"agni_product_data_tab_threesixty_images__image\"><img src=\"").concat(attachment.attributes.sizes ? attachment.attributes.sizes.thumbnail.url : attachment.attributes.url, "\" width=\"150\" height=\"150\"></div>");
          });
          $(img_container).html(html);
        }).open();
    });
  });
})(jQuery);