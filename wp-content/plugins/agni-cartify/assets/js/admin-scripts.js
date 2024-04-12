/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  $(document).ready(function () {
    $('.agni-colorpicker').wpColorPicker();
    $('body').on('click', '.agni_product_cat_icon_button', function (e) {
      e.preventDefault();
      // img_container = $(this).siblings('.agni-additional-variation-images__holder'),
      // product_id = $(this).data('variation-id'),
      var frame = wp.media({
        title: 'Choose Icon',
        library: {
          // uncomment the next line if you want to attach image to the current post
          // uploadedTo : wp.media.view.settings.post.id, 
          type: 'image'
        },
        button: {
          text: 'Set Icon' // button label text
        },

        multiple: false // for multiple image selection set to true
      }).on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();

        // console.log(attachment);

        $('#agni_product_cat_icon').html('<img width="120" height="120" src="' + attachment.url + '" />');
        $('#agni_product_cat_icon_id').attr('value', attachment.id);
      }).open();
    });
    $('body').on('click', '.agni_product_cat_banner_image_button', function (e) {
      e.preventDefault();
      // img_container = $(this).siblings('.agni-additional-variation-images__holder'),
      // product_id = $(this).data('variation-id'),
      var frame = wp.media({
        title: 'Choose Icon',
        library: {
          // uncomment the next line if you want to attach image to the current post
          // uploadedTo : wp.media.view.settings.post.id, 
          type: 'image'
        },
        button: {
          text: 'Set Icon' // button label text
        },

        multiple: false // for multiple image selection set to true
      }).on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();

        // console.log(attachment);

        $('#agni_product_cat_banner_image').html('<img width="120" height="120" src="' + attachment.url + '" />');
        $('#agni_product_cat_banner_image_id').attr('value', attachment.id);
      }).open();
    });
    $('body').on('click', '.agni_product_cat_banner_image_remove_button', function (e) {
      $('#agni_product_cat_banner_image img').remove();
      $('#agni_product_cat_banner_image_id').attr('value', '');
    });
    $('body').on('click', '.agni_product_brand_icon_button', function (e) {
      e.preventDefault();
      // img_container = $(this).siblings('.agni-additional-variation-images__holder'),
      // product_id = $(this).data('variation-id'),
      var frame = wp.media({
        title: 'Choose Icon',
        library: {
          // uncomment the next line if you want to attach image to the current post
          // uploadedTo : wp.media.view.settings.post.id, 
          type: 'image'
        },
        button: {
          text: 'Set Icon' // button label text
        },

        multiple: false // for multiple image selection set to true
      }).on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();

        // console.log(attachment);

        $('#agni_product_brand_icon').html('<img width="120" height="120" src="' + attachment.url + '" />');
        $('#agni_product_brand_icon_id').attr('value', attachment.id);
      }).open();
    });
    $('body').on('click', '.agni_product_brand_banner_image_button', function (e) {
      e.preventDefault();
      // img_container = $(this).siblings('.agni-additional-variation-images__holder'),
      // product_id = $(this).data('variation-id'),
      var frame = wp.media({
        title: 'Choose Icon',
        library: {
          // uncomment the next line if you want to attach image to the current post
          // uploadedTo : wp.media.view.settings.post.id, 
          type: 'image'
        },
        button: {
          text: 'Set Icon' // button label text
        },

        multiple: false // for multiple image selection set to true
      }).on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();

        // console.log(attachment);

        $('#agni_product_brand_banner_image').html('<img width="120" height="120" src="' + attachment.url + '" />');
        $('#agni_product_brand_banner_image_id').attr('value', attachment.id);
      }).open();
    });
    $('body').on('click', '.agni_product_brand_banner_image_remove_button', function (e) {
      $('#agni_product_brand_banner_image img').remove();
      $('#agni_product_brand_banner_image_id').attr('value', '');
    });
  });
})(jQuery);
/******/ })()
;