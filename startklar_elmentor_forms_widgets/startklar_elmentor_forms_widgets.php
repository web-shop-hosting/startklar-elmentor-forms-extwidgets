<?php
namespace StartklarElmentorFormsExtWidgets;
/*
Plugin Name: Startklar Elmentor Forms ExtWidgets
Plugin URI: https://startklar.app/
Description: Plugin adds additional fields to Elementor Forms.
Version: 1.0
*/

use StartklarElmentorFormsExtWidgets\StartklarCountruySelectorFormField;

register_activation_hook( __FILE__, array( "StartklarElmentorFormsExtWidgets\StartklarCheck_Plugin_Dependencies", 'on_activation' ));


require_once(__DIR__."/plugin_admin_page.php");
require_once(__DIR__."/startklarCountrySelectorProcess.php");

$admin_page  =  new StartklarPluginAdminPage();

add_action( 'wp_ajax_startklar_country_selector_process',array( "StartklarElmentorFormsExtWidgets\startklarCountrySelectorProcess", 'process' ) );
add_action( "wp_ajax_nopriv_startklar_country_selector_process",array( "StartklarElmentorFormsExtWidgets\startklarCountrySelectorProcess", 'process' ) );
add_action( 'elementor_pro/init', function(){
    include_once( __DIR__.'/widgets/country_selector_form_field.php' );
    new StartklarCountruySelectorFormField();
} );



class StartklarCheck_Plugin_Dependencies{
    public static function on_activation()
    {

        if ( current_user_can( 'activate_plugins' )){
            if (!class_exists('ElementorPro\Plugin') || !class_exists('Elementor\Plugin')) {
                // Deactivate the plugin.
                deactivate_plugins(plugin_basename(__FILE__));
                // Throw an error in the WordPress admin console.
                $error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',
                    sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__('This plugin requires ', 'startklar-elmentor-forms-extwidgets') .
                    '<a href="' . esc_url('https://elementor.com/pricing/#features') . '">"ELEMENTOR" AND "ELEMENTOR PRO"</a>' .
                    esc_html__(' plugins to be active.', 'startklar-elmentor-forms-extwidgets') . '</p>';
                die($error_message); // WPCS: XSS ok.
            }
        }
    }
}

