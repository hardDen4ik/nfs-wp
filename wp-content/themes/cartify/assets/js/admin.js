"use strict";

(function ($) {
  'use strict';

  $('.agni-welcome').on("click", ".status", function () {
    var trigger = $(this);
    var copyType = trigger.data("system-status");
    navigator.clipboard.writeText(copyType);
    trigger.html("System status copied");
  });
  $('.agni-welcome').on('click', '.check-update', function () {
    console.log("check for updates");
    var $this = $(this);
    $.ajax({
      url: agni_dashboard.ajaxurl,
      type: 'GET',
      data: {
        action: 'agni_check_theme_update',
        security: agni_dashboard.security
      },
      success: function success(res) {
        console.log("Response:", res);
        if (res.new) {
          $this.html(res.message);
        }
      }
    });
  });
  $('.agni-welcome').on('click', '.download-update', function (e) {
    e.preventDefault();
    var $this = $(this);
    $this.html('Updating theme');
    $.ajax({
      url: agni_dashboard.ajaxurl,
      type: 'POST',
      data: {
        action: 'agni_download_theme_update',
        security: agni_dashboard.security
      },
      success: function success(res) {
        if (res.success) {
          $this.html('Finished Update');
          $this.addClass('btn-success');
          $this.prop('disabled', true);
        }
        if (res.error) {
          $this.html('Error found');
          $('<span class="error">' + res.error + '</span>').insertBefore($this);
          $this.addClass('btn-error');
        }
      }
    });
  });
})(jQuery);