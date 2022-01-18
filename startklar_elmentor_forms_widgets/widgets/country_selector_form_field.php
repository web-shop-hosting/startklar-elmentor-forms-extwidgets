<?php
namespace StartklarElmentorFormsExtWidgets;
use ElementorPro\Modules\Forms\Classes;
use ElementorPro\Modules\Forms\Fields\Field_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class StartklarCountruySelectorFormField extends Field_Base {


	public function get_type() {
		return 'phone_number_prefix_selector_form_field';
	}

	public function get_name() {
		return __( 'Phone number prefix', 'startklar-elmentor-forms-extwidgets' );
	}

	/**
	 * @param      $item
	 * @param      $item_index
	 * @param Form $form
	 */
	public function render( $item, $item_index, $form ) {
        echo '<select class="" id="startklar_country_selector" name="form_fields['.$item['custom_id'].']">
        <option selected value></option>'; //
        $content = file_get_contents(__DIR__.'/../assets/country_selector/counties_arr.json');
        $cntr_arr = json_decode($content, true);
        $slctd_country = false;
        if (isset($item["field_value"]) && !empty($item["field_value"])){
            if (preg_match("/(\(.*\))?(.+)/ism", $item["field_value"], $matches)){
                $slctd_country = trim($matches[2]);
            }
        }
        foreach ($cntr_arr  as $country) {
            $temp = "";
            if(isset($country['phone_code']) && !empty($country['phone_code'])){
                $temp = "(".$country['phone_code'].") ";
            }
            $t_val = $temp.__($country['country_name_en'], "startklar-elmentor-forms-extwidgets");
            $selected  = '';
            if ($slctd_country == $country['country_name_en']){
                $selected  = 'selected="selected"';
            }
            $icon_code ="";
            if(isset($country['icon']) && !empty($country['icon'])){
                $icon_code = '" data-icon="'.plugin_dir_url(__DIR__).'assets/country_selector'.$country['icon'];
            }
            echo '<option  data-country_en="'.esc_html($country['country_name_en']).'" 
                value="'.$temp.$icon_code.'" '.$selected.'>'.esc_html($t_val).'</option>';
        }
        echo '
        </select>';
        wp_enqueue_style( "startklar_select2_styles", plugin_dir_url(__DIR__)."assets/country_selector/select2.min.css");
        echo <<<EOT
        <style>
            .select2-container .select2-selection img,
            .select2-container .select2-results__option img { width: 50px; vertical-align: middle; padding: 0px;  -webkit-box-shadow: 2px 2px 7px 1px rgba(34, 60, 80, 0.2);
                            -moz-box-shadow: 2px 2px 7px 1px rgba(34, 60, 80, 0.2); box-shadow: 2px 2px 7px 1px rgba(34, 60, 80, 0.2); margin: 0px 12px 0 0;}
            .select2-container .selection  { padding: 0px; display: inherit;    width: 100%; } 
            .select2-container .select2-selection,
            .select2-container .select2-results__option{  padding: 0 6px; color:#777; }
            select#startklar_country_selector { width: 100%; } 
            .elementor-field-type-country_selector_form_field { display: block; }
            .select2.select2-container--default .select2-selection--single .select2-selection__arrow b {
                    border-color: #c7c7c7 transparent transparent transparent; border-style: solid; border-width: 16px 10px 0 10px;
                    height: 0; left: 50%;  margin-left: -22px; margin-top: 3px; position: absolute;  top: 50%;  width: 0; }
            .select2.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
                    border-color: transparent transparent #c7c7c7 transparent;   border-width: 0 10px 16px 10px;}
            .select2-selection--single   #select2-startklar_country_selector-container { height: 34px; }
               .select2-results__options li { margin: 10px 0 0 0; }  
            .select2.select2-container--default .select2-search--dropdown .select2-search__field { margin:0; }
            .select2 .select2-selection.select2-selection--single { padding: 5px 0 0 0;  height: 48px;}  
            .select2-container > .select2-dropdown { width: 370px !important; }
        </style>
EOT;

	}


	public function __construct() {
		parent::__construct();
		add_action( 'wp_footer', [ $this, 'drawStartklarJsScript' ] );
        wp_enqueue_script( 'select2_min', plugin_dir_url(__DIR__).'assets/country_selector/select2.min.js', array(), false, true);
        load_theme_textdomain( 'startklar-elmentor-forms-extwidgets', __DIR__. '/../lang' );
	}
    public function drawStartklarJsScript(){
            $site_abs_url = get_site_url();
            ?>
            <script>
                jQuery( document ).ready( function() {
                    window.loop_cntr = 0;
                    searchContrySelectorContainer();
                } );
                function searchContrySelectorContainer(){
                    if (window.loop_cntr>100){ return; }
                    if ( jQuery("#startklar_country_selector").length){
                        //debugger;
                        jQuery('#startklar_country_selector').select2({
                                //allowClear: true,
                                templateSelection: startklarCountrySelectorformatText,
                                templateResult: startklarCountrySelectorRsltformatText,
                        });

                        jQuery.post( "<?php echo $site_abs_url; ?>/wp-admin/admin-ajax.php?action=startklar_country_selector_process", function( data ) {
                            //debugger;
                            if(typeof data["country"] !== "undefined"){
                                jQuery('#startklar_country_selector  > option').each(function() {
                                    var country_en = jQuery(this).data("country_en");
                                    if ( typeof country_en !=="undefined" && country_en.includes(data["country"])){
                                        jQuery('#startklar_country_selector').val(this.value).trigger('change');
                                    }
                                });
                            }
                        },"json");

                    }else{
                        setTimeout(searchContrySelectorContainer, 100);
                        window.loop_cntr++;
                    }
                }
                function startklarCountrySelectorformatText (icon) {
                    var str = "";
                    if(typeof icon.element !== "undefined") {
                        var phone_code = /\(.+\)/g.exec(icon.text);
                        var icon_src = jQuery(icon.element).data('icon');
                        var icon_code = '';
                        if (typeof icon_src !== "undefined" && icon_src.length){
                            icon_code = '<img src="'+icon_src+'">';
                        }
                        if (typeof phone_code !== "undefined" && phone_code != null && phone_code.length ) {
                            str = '<span>' + icon_code + phone_code[0] + '</span>';
                        }
                    }
                    return jQuery(str);
                };

                function startklarCountrySelectorRsltformatText (icon) {
                    if(typeof icon.element !== "undefined") {
                        var icon_src = jQuery(icon.element).data('icon');
                        var icon_code = '';
                        if (typeof icon_src !== "undefined" && icon_src.length){
                            icon_code = '<img src="'+icon_src+'">';
                        }
                        var str = '<span>'+icon_code+icon.text+'</span>';
                    }
                    return jQuery(str);
                };
            </script>
            <?php

    }
}