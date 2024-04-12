"use strict";

(function ($) {
  $(function () {
    console.log("Product Activation Admin");
    $('form.agni-product-registration-form').each(function () {
      var $this = $(this);
      $this.find('input').on('input', function () {
        $this.find('.error').remove();
        $this.find('#product-registration-submit').removeClass('btn-error');
        $this.find('#product-registration-submit').html('Register this site');
      });
    });
    $('form.agni-product-registration-form').on('submit', function (e) {
      e.preventDefault();
      var $this = $(this);
      var purchase_code_format = /^[A-Za-z0-9]{8}\-[A-Za-z0-9]{4}\-[A-Za-z0-9]{4}\-[A-Za-z0-9]{4}\-[A-Za-z0-9]{12}?$/;
      var error_text = '';
      var $data = {};
      var $dataArray = $(this).serializeArray();
      $dataArray.forEach(function (field) {
        $data[field.name] = field.value;
      });
      $data['domain'] = agni_product_registration.domain_name;
      $data['action'] = 'agni_product_activation';
      $data['security'] = agni_product_registration.security;
      console.log("Data", $data);
      console.log("valid", purchase_code_format.test($data['purchase_code']));
      if ($data['purchase_code'].length != 36) {
        error_text = 'Purchase code must be 36 characters';
      } else if (!purchase_code_format.test($data['purchase_code'])) {
        error_text = 'Purchase code must follow XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX this format';
      }
      if (error_text.length !== 0) {
        $this.prepend('<span class="error">' + error_text + '</span>');
        $this.find('#product-registration-envato-purchase-code').val('');
        return false;
      }
      $('#product-registration-submit').html('Sending details');
      $.ajax({
        url: agni_product_registration.ajaxurl,
        type: 'POST',
        data: $data,
        success: function success(res) {
          console.log("Response:", res);
          if (res.error) {
            $this.prepend('<span class="error">' + res.error + '</span>');
            $this.find('#product-registration-envato-purchase-code').val('');
            $('#product-registration-submit').html('Error found');
            $('#product-registration-submit').addClass('btn-error');
          }
          if (res.success) {
            $('#product-registration-submit').html('Thank you!');
            $('#product-registration-submit').addClass('btn-success');
            location.reload();
          }
        },
        error: function error(err) {
          console.log("error", err.responseText);
          $this.prepend('<span class="error">Ajax error occured, check the connection.</span>');
          $('#product-registration-submit').html('Error found');
          $('#product-registration-submit').addClass('btn-error');
        }
      });
    });
    $.fn.activationAjax = function (url_params_array) {
      var $this = $(this);
      var $plugin = $this.closest('.agni-plugin');
      var $plugin_action = $plugin.find('.agni-plugin-action');
      console.log("parm araay", url_params_array);
      $.ajax({
        url: agni_product_registration.ajaxurl,
        type: 'POST',
        data: url_params_array,
        success: function success(res) {
          console.log("Activate Response: ", res);
          if (res.error) {
            console.log(res.error);
            if (url_params_array['update']) {
              $plugin_action.removeClass('updating').addClass('error');
            }
            if (url_params_array['install'] == '1') {
              $plugin_action.removeClass('loading');
              $('<span class="error">' + res.error + '</span>').insertBefore($plugin_action);
              $plugin_action.find('.loading-text').remove();
            }
          }
          if (res.success) {
            console.log(res.success);
            var url = $this.attr('href');
            var url_params = url.split('?')[0];
            delete url_params_array['action'];
            delete url_params_array['security'];
            delete url_params_array['token'];
            delete url_params_array['premium'];
            if (url_params_array['update']) {
              $plugin_action.removeClass('updating').addClass('updated');
            }
            $this.removeClass('loading');
            if (url_params_array['install']) {
              $this.html('<span>Activate</span>');
              url_params_array['activate'] = 1;
              delete url_params_array['install'];
            } else if (url_params_array['activate']) {
              $this.html('<span>Deactivate</span>');
              url_params_array['deactivate'] = 1;
              delete url_params_array['activate'];
            } else if (url_params_array['deactivate']) {
              $this.html('<span>Activate</span>');
              url_params_array['activate'] = 1;
              delete url_params_array['deactivate'];
            }
            var url_params_string = Object.keys(url_params_array).map(function (key) {
              return "" + key + "=" + url_params_array[key];
            }).join("&");
            $this.attr('href', url_params + '?' + url_params_string);
          }
        },
        error: function error(err) {
          console.log("error", err.responseText);
        }
      });
    };
    $('.agni-plugins').on('click', 'a', function (e) {
      e.preventDefault();
      var $this = $(this);
      var $plugin = $this.closest('.agni-plugin');
      var $plugin_disabled = $plugin.hasClass('disabled');
      var $plugin_action = $plugin.find('.agni-plugin-action');
      if ($plugin_disabled) {
        return;
      }
      var url = $this.attr('href');
      var url_params = url.split('?')[1];
      var url_params_str = url_params.split('&');
      var url_params_array = {};
      url_params_str.forEach(function (param) {
        var result = param.split('=');
        url_params_array[result[0]] = result[1];
      });
      url_params_array['action'] = 'activation';
      url_params_array['security'] = agni_product_registration.security;
      if (url_params_array['update']) {
        $plugin_action.addClass('updating');
      } else {
        $this.addClass('loading');
        if (url_params_array['install']) {
          $this.append('<span class="loading-text">Installing</span>');
        } else if (url_params_array['activate']) {
          $this.append('<span class="loading-text">Activating</span>');
        } else if (url_params_array['deactivate']) {
          $this.append('<span class="loading-text">Deactivating</span>');
        }
      }
      $this.activationAjax(url_params_array);
    });
  });
})(jQuery);