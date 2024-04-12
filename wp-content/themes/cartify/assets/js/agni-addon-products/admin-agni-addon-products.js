"use strict";

(function ($) {
  'use strict';

  $(document).ready(function () {
    console.log("Hello Addon Admin");
    $('#agni-addon-products select').select2({
      ajax: {
        url: ajaxurl,
        dataType: 'json',
        delay: 250,
        data: function data(params) {
          return {
            q: params.term,
            ignore_id: $(this).data('ignore-id'),
            action: 'agni_addon_products_search_results'
          };
        },
        processResults: function processResults(data) {
          var options = [];
          if (data) {
            $.each(data, function (index, text) {
              options.push({
                id: text[0],
                text: text[1]
              });
            });
          }
          return {
            results: options
          };
        },
        cache: true
      },
      minimumInputLength: 3
    });
  });
})(jQuery);