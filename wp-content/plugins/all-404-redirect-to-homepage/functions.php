<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function P404REDIRECT_HideMsg()
	{  
		add_option( 'P404REDIRECT_upgrade_msg','hidemsg');
	}

function P404REDIRECT_HideAlert()
	{  
		
		update_option( 'P404_alert_msg','hidemsg');
	}

function sample_admin_notice__error() {
    $class = 'notice notice-error';
	$links_count = P404REDIRECT_read_option_value('links',0);
	
	
	if(get_option('P404_alert_msg')!='hidemsg' && $links_count>10)
	{
	
    $message = __( '<h3>All 404 Redirect to Homepage</h3><b>Warning</b>, You have many broken links that hurt your site\'s rank in search engines, <a target="_blank" href="https://www.wp-buy.com/product/seo-redirection-premium-wordpress-plugin/#fix404links">UPGRADE</a> your plugin and empower your site\'s SEO.&nbsp; <span id="Hide404Alert" style="cursor:pointer" ><a href="javascript:void(0)"><strong> Dismiss</strong></a> this message</span> or check the plugin <a href="'.admin_url( 'admin.php?page=all-404-redirect-to-homepage.php').'"><b>settings</b></a>.', 'sample-text-domain' );
 
    printf( '<div id="all404upgradeMsg" class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 

	
	?>
	<script type="text/javascript">
			jQuery(document).ready(function() {
				
				jQuery("#Hide404Alert").click(function(){ 
				  jQuery.ajax({
						type: 'POST',  
						url: '<?php echo admin_url();?>/admin-ajax.php',  
						data: {  
							action: 'P404REDIRECT_HideAlert'
						},
						success: function(data, textStatus, XMLHttpRequest){
							
							jQuery("#all404upgradeMsg").hide();
							
						},  
						error: function(MLHttpRequest, textStatus, errorThrown){
							alert(errorThrown);
						}
					});
			  });

			});
			</script>
			
	<?php
	}
}
add_action( 'admin_notices', 'sample_admin_notice__error' );

function P404REDIRECT_after_plugin_row($plugin_file, $plugin_data, $status)
{
			if(get_option('P404REDIRECT_upgrade_msg') !='hidemsg')
			{
				$class_name = isset($plugin_data['slug']) ? $plugin_data['slug'] : 'all-404-redirect-to-homepage'; // $plugin_data is an array retrived by default when you action this function after_plugin_row
				
		        echo '<tr id="' .esc_attr($class_name). '-plugin-update-tr" class="plugin-update-tr active">';
		        echo '<td  colspan="6" class="plugin-update">';
		        echo '<div id="' .esc_attr($class_name). '-upgradeMsg" class="update-message" style="background:#FFF8E5; padding-left:10px; border-left:#FFB900 solid 4px" >';

				echo '<span style="color:red">Have many broken links?</span>.<br />keep track of 404 errors using our powerfull <a target="_blank" href="https://www.wp-buy.com/product/seo-redirection-premium-wordpress-plugin/">SEO Redirection Plugin</a> to show and fix all broken links & 404 errors that occur on your site. or ';
				        
				echo '<span id="HideMe" style="cursor:pointer" ><a href="javascript:void(0)"><strong> Dismiss</strong></a> this message</span>';
		        echo '</div>';
		        echo '</td>';
		        echo '</tr>';
			}
		        ?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				var row = jQuery('#<?php echo esc_attr($class_name);?>-plugin-update-tr').closest('tr').prev();
				jQuery(row).addClass('update');
				
				jQuery("#HideMe").click(function(){ 
				  jQuery.ajax({
						type: 'POST',  
						url: '<?php echo admin_url();?>/admin-ajax.php',  
						data: {  
							action: 'P404REDIRECT_HideMsg'
						},
						success: function(data, textStatus, XMLHttpRequest){
							
							jQuery("#<?php echo esc_attr($class_name);?>-upgradeMsg").hide();
							
						},  
						error: function(MLHttpRequest, textStatus, errorThrown){
							alert(errorThrown);
						}
					});
			  });

			});
			</script>
<?php
}

function P404REDIRECT_get_current_URL()
{
	$prt = $_SERVER['SERVER_PORT'];
	$sname = $_SERVER['SERVER_NAME'];
	
	if (array_key_exists('HTTPS',$_SERVER) && $_SERVER['HTTPS'] != 'off' && $_SERVER['HTTPS'] != '')
	$sname = "https://" . $sname; 
	else
	$sname = "http://" . $sname; 
	
	if($prt !=80 && $prt !=443)
	{
	$sname = $sname . ":" . $prt;
	} 
	
	$path = $sname . $_SERVER["REQUEST_URI"];
	
	return $path ;

}

//----------------------------------------------------

function P404REDIRECT_init_my_options()
{
	add_option(OPTIONS404);
	$options = array();
	$options['p404_redirect_to']= site_url();
	$options['p404_status']= '1';
	$options['img_p404_status']= '2';
	$options['p404_execlude_media']= '1';
    $options['links']= 0;
    $options['install_date'] = date("Y-m-d h:i a");
    $options['redirected_links'] = array();
	update_option(OPTIONS404,$options);
} 

//---------------------------------------------------- 

function P404REDIRECT_update_my_options($options)
{	
	update_option(OPTIONS404,$options);
}

//---------------------------------------------------- 

function P404REDIRECT_get_my_options()
{	
	$options=get_option(OPTIONS404);
	if(!$options)
	{
		P404REDIRECT_init_my_options();
		$options=get_option(OPTIONS404);
	}
	return $options;			
}

/* read_option_value -------------------------------------------------  */
 function P404REDIRECT_read_option_value($key,$default='')
{
    $options=P404REDIRECT_get_my_options();
    if(array_key_exists($key,$options))
    {
        return $options[$key];
    }else
    {
        P404REDIRECT_save_option_value($key,$default);
        return $default;
    }
}

/* save_option_value -------------------------------------------------  */
 function P404REDIRECT_save_option_value($key,$value)
{
    $options=P404REDIRECT_get_my_options();
    $options[$key]=$value;
    P404REDIRECT_update_my_options($options);
}

/* add link -------------------------------------------------  */
 function P404REDIRECT_add_redirected_link($link)
{
    $links = P404REDIRECT_read_option_value('redirected_links',array());
    $new_links[0]['link'] = $link;
    $new_links[0]['date'] = date("Y-m-d h:i a");
    
    $c=count($links);
    if($c>=20)
    {
        $c=19;
    }
    
    for($i=0;$i<$c;$i++)
    {
       array_push($new_links,$links[$i]);
    }
   
    P404REDIRECT_save_option_value('redirected_links',$new_links);
    unset($links);
    unset($new_links);
}

//---------------------------------------------------- 
function P404REDIRECT_option_msg($msg)
{	
	echo '<div id="message" class="updated"><p>' . esc_attr($msg) . '</p></div>';		
}

//---------------------------------------------------- 
function P404REDIRECT_info_option_msg($msg)
{	
	echo '<div id="message" class="updated"><p><div class="info_icon"></div> ' . esc_attr($msg) . '</p></div>';		
}

//---------------------------------------------------- 
function P404REDIRECT_warning_option_msg($msg) 
{	
	echo '<div id="message" class="error"><p><div class="warning_icon"></div> ' . esc_attr($msg) . '</p></div>';		
}

//---------------------------------------------------- 

function P404REDIRECT_success_option_msg($msg)
{	
	echo '<div id="message" class="updated"><p><div class="success_icon"></div> ' . esc_attr($msg) . '</p></div>';		
}

//---------------------------------------------------- 

function P404REDIRECT_failure_option_msg($msg)
{	
	echo '<div id="message" class="error"><p><div class="failure_icon"></div> ' . esc_attr($msg) . '</p></div>';		
}


//----------------------------------------------------
//** updated 2/2/2020
function P404REDIRECT_there_is_cache()
{	
    $plugins=get_site_option( 'active_plugins' );
    if(is_array($plugins)){
        foreach($plugins as $the_plugin)
        {
            if (stripos($the_plugin,'cache')!==false)
            {
                return $the_plugin;
            }
        }
    }
    return '';
}

