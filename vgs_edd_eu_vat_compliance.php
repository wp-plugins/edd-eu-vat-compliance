<?php

/*
  Plugin Name: EDD EU VAT Compliance
  Plugin URL: http://mokawebapps.com/edd-eu-vat
  Description: Automatically <strong>calculate the EU VAT</strong> on your Easy Digital Downloads checkout. Comply with EU VAT regulations for digital goods: Charge your customers the VAT rate according to their location and save evidence required by EU Laws
  Version: 1.1
  Author: Mokawebapps
  Author URI: http://mokawebapps.com
*/


// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('VGS_EDD_EU_VAT_Compliance')):
    
    final class VGS_EDD_EU_VAT_Compliance
    {
        
        private static $instance;
        
        public static function instance() {
            if (!isset(self::$instance) && !(self::$instance instanceof VGS_EDD_EU_VAT_Compliance)) {
                self::$instance = new VGS_EDD_EU_VAT_Compliance;
                self::$instance->setup_constants();
                self::$instance->includes();
            }
            return self::$instance;
        }
        
        private function setup_constants() {
            
            // Plugin Folder
            if (!defined('VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR')) {
                define('VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR', plugin_dir_path(__FILE__));
            }
            
            // Plugin Folder URL
            if (!defined('VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_URL')) {
                define('VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_URL', plugin_dir_url(__FILE__));
            }
            
            //Geo Database name (If exists)
            if(!defined('VGS_EDD_EU_VAT_GEODB_FILENAME')){
                define('VGS_EDD_EU_VAT_GEODB_FILENAME', 'GeoLite2-Country.mmdb');
            }
        }
        
        private function includes() {
            require_once VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . 'includes/scripts.php';
            require_once VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . 'includes/class-vgs-edd-eu-vat-compliance-checks.php';
            require_once VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . 'includes/class-vgs-edd-eu-vat-compliance-geoip.php';
            require_once VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . 'includes/class-vgs-edd-eu-vat-settings.php';
            require_once VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . 'includes/class-vgs-edd-eu-vat-taxes.php';
            require_once VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . 'includes/class-vgs-eed-eu-vat-rates.php';
        }
        
    }
endif;
 // End if class_exists check

function VGS_EDD_VC() {
    
    return VGS_EDD_EU_VAT_Compliance::instance();
}

// Get EDD EU VAT Compliance Running
VGS_EDD_VC();

?>