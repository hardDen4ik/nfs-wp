=== Customize My Account for WooCommerce ===
Contributors: phppoet
Tags: woocommerce,customize,myaccount,account,endpoints,pages,add,edit,links,elementor woocommerce, elementor
Requires at least: 4.0
Tested up to: 6.4.1
WC Tested up to: 8.3.0
WC Requires at least: 4.0
Requires PHP: 5.2
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customize My Account for WooCommerce Plugins helps you to Manage Existing Endpoints as well as add new links.Works with Elementor



== Description ==

<h3>Customize My Account for WooCommerce</h3>

Customize your default my account page. Reorder them , hide existing core endpoints. You will also be able to change the default endpoint. 

<p><a href="https://sysbasics.com/go/customize/" title="Customize My Account for WooCommerce pro" rel="nofollow">Upgrade to pro</a></p>

<p>Check <a href="https://sysbasics.com/go/customize-demo/" title="Customize My Account for WooCommerce pro" rel="nofollow">Live Demo</a> for Pro version features</p>

<p>Check our <a href="https://codecanyon.net/user/sysbasics/portfolio" title="WooCommerce Premium Plugins" rel="nofollow">Premium Plugins</a></p>

<p>Missing a feature or Want to inform us about bug ? <a href="https://bitbucket.org/woomatrix/woomatrix-main/issues/new" rel="nofollow">Contact us here</a></p>

<p>Create your own endpoint templates using elementor free plugin and widgets provided by our plugin and replace them with existing endpoint content.</p>

<h2> Customize My Account for WooCommerce features </h2>
- Modify existing endpoints.
- Add custom links to your my account pages.
- Drag and drop UI.
- Modify WooCommerce My account Navigation background color
- Modify WooCommerce My account Navigation text color
- Modify WooCommerce My account menu item background color
- Easily Customize WooCommerce My Account Navigation CSS Parameters like background , font color , link color or padding, fontsize etc using frontend live customizer.
- Compatible with Elementor , Elementor Pro and Jetwoobuilder
- Easily Create new custom my account page using Customize My Account widgets for free elementor widgets. 

<h3> Customize My Account for WooCommerce free version features </h3>
- Show/hide woocommerce core endpoints
- Reorder core woocommerce my account endpoints
- Add extra class to core endpoint
- Add New link as endpoint on my account page
- Show user avatar on my account page
- Drag and drop UI
- Use Dashicons for WooCommerce My Account Endpoints. Plugin has inbuilt dashicon picker. If somehow font awesome icons not working for you , you can opt for dashicon which has wider compatibility across all themes.
- Optionally replace entire my account with custom elementor template.
- Optionally override endpoint content with elementor template content


== Customize My Account for WooCommerce pro version features ==
- All features of free version
- Support for link/endpoint/group endpoints
- Drage link/endpoints(core/new) into group and reorder them
- Show custom content on endpoints
- Set groups as show by default which will make group menu open on page load
- Hide existing order page columns
- Add new custom columns to order listing page
- Reorder order page columns
- Add new custom order actions button in action column




<p><a href="https://1.envato.market/c/1314780/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fwoocommerce-customize-my-account-pro%2F31059126" title="Customize My Account for WooCommerce pro" rel="nofollow">Upgrade to pro</a></p>

<p>Check <a href="https://sysbasics.com/customize-my-account/wp-admin/" title="Customize My Account for WooCommerce pro" rel="nofollow">Live Demo</a> for Pro version features</p>



== Customize Dokan Dashboard Endpoints features ==
- Add new endpoint to your dokan dashboard
- Hide existing dokan dashboard endpoints
- Shortcode support
- Modify icon of existing dokan dashboard enpoints
- Add new link in dokan dashbaord


== Color Image Variation Swatches For WooCommerce Plugin==


Color Image Variation Swatches For WooCommerce For WooCommerce Plugin allows you to convert your regular woocommerce default dropdown into nicely looking color/image/taxt select

Checkout <a href="https://wordpress.org/plugins/color-image-variation-swatches-for-woocommerce/">Color Image Variation Swatches For WooCommerce</a>


<p>check <a href="https://www.sysbasics.com/downloads/customize-dokan-dashboard-endpoints-pro/" >Customize Dokan Dashboard Endpoints Pro
</a></p>
== Upgrade Notice ==


= 1.0.3 - 09 April 2021 


Version 1.0.3 - added hook to override default endpoint url 
              - fixed issue with accordion tab not loading on backend.
* Initial release

== Screenshots ==

1. Customize My Account for WooCommerce Backend
2. Customize My Account for WooCommerce Elementor
3. Customize My Account for WooCommerce Each Endpoint
4. Customize My Account for WooCommerce Customizer
5. Customize My Account for WooCommerce Free
6. Customize My Account for WooCommerce Frontend
7.
8.


== Frequently Asked Questions ==

= How to override endpoint url? =

Plugin has inbuilt hook which you can use to override my account endpoint url. Only use this if your setup is somehow not returning correct endpoint url. 

<pre>
add_filter('wcmamtx_override_endpoint_url','wcmamtx_override_endpoint_url',10,2);

function wcmamtx_override_endpoint_url($core_url,$key) {
	
	$new_url = ''.site_url().'/my-account/'.$key.'/';
	
	if ($key== "customer-logout") {
		$new_url = wp_nonce_url($new_url);
	}
	return $new_url;
}
</pre>

You may use <a href="https://wordpress.org/plugins/code-snippets/">Code Snippets</a> plugin to inject any extra php code. 

= Is plugin compatible with WPML ? =

yes. you can use this plugin with WPML and locotranslate both.

For WPML visit WPML/Theme and plugins localization menu and search for this plugin and click on “scan the selected plugins for scan” button.

Now visit WPML/string translation and click on “Translate texts in admin screens” link at the bottom.

There search for wcmamtx_advanced_settings and wcmamtx_plugin_options and check the fields you want to translate. Then apply the changes.

Now visit WPML/String translation and translate your strings there.

== Installation ==

Use automatic installer.
