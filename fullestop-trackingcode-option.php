<?php 
/*
Plugin Name: Fullestop Analytics Code Option
Description: It's provide option to add Analytic Code in header or footer.
Version: 1.0
Author: Fullestop
Author URI: http://www.fullestop.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
/*
Fullestop Tracking Code Option is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Fullestop Tracking Code Option is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*ADDING MENU OPTION IN THE ADMIN PANEL*/
    add_action('admin_menu','ftco_admin_actions');
    if (!function_exists('ftco_admin_actions')) {
        function ftco_admin_actions() { 
            add_menu_page('Fullestop Analytics Code Option', 'Fullestop Analytics', 'administrator', 'fullestop-trackingcode-option','ftco_fullestop_trackingcode_option'); 
        }
    }
/*ADDING MENU OPTION IN THE ADMIN PANEL*/



/*TACKING CODE FROM ADMIN USER WHICH NEEDS TO PLACE*/

if (!function_exists('ftco_fullestop_trackingcode_option')) {
    function ftco_fullestop_trackingcode_option(){
    echo '<div class="wrap"><h1>Fullestop Analytics Code</h1>';
        
        
        if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'fullestop-trackingcode')) {  
        	//Form data sent  
            
            if(check_admin_referer( 'fullestop-trackingcode' )){
                
                    $fullestop_tracking_code        =   '';
                    if(isset($_POST['fullestop_tracking_code'])){
                       $fullestop_tracking_code       =    sanitize_text_field($_POST['fullestop_tracking_code']);
                	   update_option('fullestop_tracking_code', $fullestop_tracking_code);     
                    }
                    
                    $fullestop_where_script_inserted =  '';
                    if(isset($_POST['fullestop_where_script_inserted']) && $_POST['fullestop_where_script_inserted']!=''){
                       $fullestop_where_script_inserted = sanitize_text_field($_POST['fullestop_where_script_inserted']);  
                	   update_option('fullestop_where_script_inserted', $fullestop_where_script_inserted);
                    }
                
                		?>  
                		<div class="updated"><p><strong><?php _e('Options saved.' ,'ftco' ); ?></strong></p></div>  
        <?php 
                }//if(check_admin_referer( 'fullestop-trackingcode' )){
            
                     
      		} 
    		else {
    		  
    			//Normal page display  
    			$fullestop_tracking_code	            =	get_option('fullestop_tracking_code');   
    			$fullestop_where_script_inserted		=	get_option('fullestop_where_script_inserted'); 
    
    		} 
            
            
    ?>
    <form class="theme_form" name="ftco_form" method="post" action=""> 
    			<table class="form-table">
    				<tr>
    				<th><label for="fullestop_tracking_code"><?php _e("Analytics ID: ",'ftco' ); ?></label></th>
    				<td><input type="text" id="fullestop_tracking_code" name="fullestop_tracking_code" value="<?php echo stripslashes($fullestop_tracking_code); ?>" placeholder="UA-XXXXX-Y" />
    				<p class="description"><?php _e('Paste your Google Analytics ID here using format example: UA-XXXXX-Y .','ftco' ); ?></p>
    				</td>
    				</tr>
    				<tr>
    				<th><label for="fullestop_where_script_inserted"><?php _e("Where to place code: " ); ?></label></th>
    				<td>
    					<select name="fullestop_where_script_inserted" id="fullestop_where_script_inserted"> 
    						<option value="header" <?php if($fullestop_where_script_inserted=='header'){ echo 'selected = "selected"'; }?>><?php _e('Header','ftco');?></option> 
    						<option value="footer" <?php if($fullestop_where_script_inserted=='footer'){ echo 'selected = "selected"'; }?>><?php _e('Footer','ftco');?></option> 
    					</select>
    				</td>
    				</tr>
    				
    				<tr>
    				<td>
                    <?php wp_nonce_field( 'fullestop-trackingcode');?>
                    <input type="submit" id="Submitbtn" class="button-primary" name="Submit" value="<?php _e('Update Options','ftco') ?>" />  
    				</td>
    				</tr>
    			</table>
    
    	  </form>
    <?php 
    }
}//  if (!function_exists('ftco_fullestop_trackingcode_option')) {
/*TACKING CODE FROM ADMIN USER WHICH NEEDS TO PLACE*/



/*ADDING TRACKING CODE TO FRONT WEBSITE STARTUP*/
add_action('init','ftco_add_conversion_code_front');

if (!function_exists('ftco_add_conversion_code_front')) {
    
    function ftco_add_conversion_code_front(){
    
    
    	$fullestop_tracking_code		=	get_option('fullestop_tracking_code');   
    	if(!empty($fullestop_tracking_code)){
    	
    		$value_in = get_option( 'fullestop_where_script_inserted', '' );	
    		if($value_in=='header'){
    			add_action('wp_head','ftco_display_conversion_code');
    		}elseif($value_in=='footer'){
    			add_action('wp_footer','ftco_display_conversion_code');
    		}
    	}
    }
}//if (!function_exists('ftco_add_conversion_code_front')) {
/*ADDING TRACKING CODE TO FRONT WEBSITE STARTUP*/


/*SHOWING THE CODE ON RELATED PLACE IN FRONT*/
if (!function_exists('ftco_display_conversion_code')) {
    
    function ftco_display_conversion_code(){
    	
    	$fullestop_tracking_code		=	get_option('fullestop_tracking_code');  
        if(!empty($fullestop_tracking_code)){
        ?>
<!-- Fullestop Tracking Code Plugin -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo stripslashes($fullestop_tracking_code);?>', 'auto');
ga('send', 'pageview');
</script>
<!-- Fullestop Tracking Code Plugin -->
        <?php
           
        }
    	
    	
    }
}// if (!function_exists('ftco_display_conversion_code')) {
/*SHOWING THE CODE ON RELATED PLACE IN FRONT*/



/*DELETING THE DATA OF THIS PLUGIN WHEN ITS UNINSTALLED*/
register_uninstall_hook(__FILE__, 'ftco_delete_tracking_code_plugin_data');
if (!function_exists('ftco_delete_tracking_code_plugin_data')) {
    function ftco_delete_tracking_code_plugin_data(){
    	$delete_option=array('fullestop_tracking_code','fullestop_where_script_inserted');
    	foreach($delete_option as $deletdata) {
    		delete_option( $deletdata);
        } 
    }
}//if (!function_exists('ftco_delete_tracking_code_plugin_data')) {
/*DELETING THE DATA OF THIS PLUGIN WHEN ITS UNINSTALLED*/