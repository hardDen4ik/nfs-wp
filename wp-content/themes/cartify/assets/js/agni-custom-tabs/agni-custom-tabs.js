"use strict";

(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.agni-product-shipping-info').each(function () {
      var $this = $(this),
        popup = $('.agni-product-shipping-info-popup');
      $this.on('click', '.agni-product-shipping-info-link', function (e) {
        e.preventDefault();
        if (!popup.hasClass('active')) {
          popup.addClass('active');
        }
      });
    });
    $(document).on('click', '.agni-product-shipping-info-popup__close, .agni-product-shipping-info-popup__overlay', function (e) {
      e.preventDefault();
      var popup = $(this).closest('.agni-product-shipping-info-popup');
      if (popup.hasClass('active')) {
        popup.removeClass('active');
      }
    });
  });
})(jQuery);