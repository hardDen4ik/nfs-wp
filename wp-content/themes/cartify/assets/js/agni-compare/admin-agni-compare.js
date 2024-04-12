"use strict";

(function ($) {
  'use strict';

  $(document).ready(function () {
    console.log("Hello Compare Admin");
    $('#agni-compare-options select').select2({
      ajax: {
        url: ajaxurl,
        dataType: 'json',
        delay: 250,
        data: function data(params) {
          return {
            q: params.term,
            action: 'agni_compare_products_search_results'
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