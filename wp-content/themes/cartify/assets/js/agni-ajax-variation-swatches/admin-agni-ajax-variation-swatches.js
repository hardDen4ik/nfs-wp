"use strict";

(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.agni-tag-color').wpColorPicker();
    $('body').on('click', '.agni-tag-image', function (e) {
      e.preventDefault();
      var $this = $(this);
      var custom_uploader = wp.media({
        title: 'Choose image',
        library: {
          type: 'image'
        },
        button: {
          text: 'Set Image'
        },
        multiple: false
      }).on('select', function () {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        ;
        var input = $this.closest('.term-image-wrap').find('input'),
          placeholder = $this.closest('.term-image-wrap').find('.agni-tag-image-placeholder');
        input.val(attachment.id);
        placeholder.html("<img src=\"".concat(attachment.url, "\" width=\"64\" height=\"64\">"));
      }).open();
    });
  });
})(jQuery);