"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }
(function ($) {
  'use strict';

  $(document).ready(function () {
    console.log("Hello Addons");
    $.agni_addon_products_contents = function (product_data) {
      $.ajax({
        url: cartify_addon_products.ajaxurl_wc.toString().replace('%%endpoint%%', 'agni_addon_products_contents'),
        type: 'POST',
        data: product_data,
        success: function success(response) {
          $('.agni-addon-products__container').html(response);
        }
      });
    };
    $.fn.agni_add_all_to_cart = function (product_data) {
      var $this = $(this);
      var $adding_to_cart_text = "Adding to Cart!";
      $.cart_loader_show($adding_to_cart_text);
      $.ajax({
        url: cartify_addon_products.ajaxurl_wc.toString().replace('%%endpoint%%', 'agni_addon_products_add_all_to_cart'),
        type: 'POST',
        data: product_data,
        success: function success(response) {
          $.cart_loader_hide();
          $.cart_show();
          $(document.body).trigger('wc_fragments_loaded');
          if (!response) {
            return;
          }
          if (response.fragments) {
            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $this]);
          } else {
            console.log(response);
          }
        }
      });
    };
    $("form.variations_form").on("show_variation", function (event, variation) {
      var product_data = {
        current_product_id: cartify_addon_products.current_product_id,
        product_ids: cartify_addon_products.product_ids,
        variation_id: variation.variation_id
      };
      $.agni_addon_products_contents(product_data);
    });
    $('form.variations_form').on('click', '.reset_variations', function () {
      var product_data = {
        current_product_id: cartify_addon_products.current_product_id,
        product_ids: cartify_addon_products.product_ids
      };
      $.agni_addon_products_contents(product_data);
    });
    $('form.variations_form').on('reset_image', function () {
      var product_data = {
        current_product_id: cartify_addon_products.current_product_id,
        product_ids: cartify_addon_products.product_ids
      };
      $.agni_addon_products_contents(product_data);
    });
    $('.agni-addon-products').on('click', '.agni-addon-products__button--add-all-to-cart', function (e) {
      e.preventDefault();
      var product_data = {
        products_to_cart: $(this).data('product-ids')
      };
      $(this).agni_add_all_to_cart(product_data);
    });
    $('.agni-addon-products').on('click', 'input[type="checkbox"]', function () {
      var $this = $(this),
        product_id = $this.data('product-id').toString(),
        variation_id = $this.data('variation-id').toString(),
        revised_product_ids = $this.data('product-ids'),
        checked = $this.is(':checked');
      var product_ids_array = revised_product_ids.length ? revised_product_ids.split(',') : [revised_product_ids];
      if (!checked) {
        product_ids_array.splice(product_ids_array.indexOf(product_id), 1);
      } else {
        product_ids_array.push(product_id);
      }
      product_ids_array = _toConsumableArray(new Set(product_ids_array));
      var product_data = {
        current_product_id: cartify_addon_products.current_product_id,
        product_ids: cartify_addon_products.product_ids,
        revised_product_ids: product_ids_array.join(',')
      };
      if (variation_id != '') {
        product_data['variation_id'] = variation_id;
      }
      $.agni_addon_products_contents(product_data);
    });
  });
})(jQuery);