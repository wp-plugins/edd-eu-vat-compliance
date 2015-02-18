<?php
/**
 * Settings
 *
 * Forked from  https://wordpress.org/plugins/edd-prevent-eu-checkout/
 *
 * @package     EDD EU VAT COMPLIANCE
 * @subpackage  Functions GeoIP
 * @copyright   Copyright (c) 2014, Conrado Maggi
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @author      Conrado Maggi - VGS Global
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . '/vendor/autoload.php';
use GeoIp2\Database\Reader;

if (!class_exists('VGS_EDD_EU_VAT_Compliance_GeoIP')) {
    
    class VGS_EDD_EU_VAT_Compliance_GeoIP
    {
        
        public function __construct() {
        }
        
        function vgs_edd_eu_vat_get_geodb_name() {
            
            $filename = VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR  . VGS_EDD_EU_VAT_GEODB_FILENAME;
            return $filename;
        }
        
        
        /*
        * Forked from  https://wordpress.org/plugins/edd-prevent-eu-checkout/
        */

        function vgs_edd_get_customer_ip_country() {
            
            global $edd_options;
            
            $geo_db_file = $this->vgs_edd_eu_vat_get_geodb_name();
            $this_country = "XX";
            
            if (file_exists($geo_db_file)) {
                try {
                    $reader = new Reader($geo_db_file);
                    $record = $reader->country($this->vgs_edd_eu_vat_get_user_ip());
                    $this_country = $record->country->isoCode;
                }
                catch(Exception $e) {
                    $this_country = "XX";
                }
            }
            
            if ($this_country == "XX") {
                try {
                    $context = stream_context_create(array('http' => array('timeout' => 1,),));
                    
                    $this_country = @file_get_contents('http://api.hostip.info/country.php?ip=' . $this->vgs_edd_eu_vat_get_user_ip(), false, $context);
                }
                catch(Exception $e) {
                    $this_country = "XX";
                }
            }

            //teleze

            if ($this_country == "XX") {
                try {
                    $context = stream_context_create(array('http' => array('timeout' => 1,),));
                    
                    $geo_ip_json = json_decode(@file_get_contents('http://www.telize.com/geoip/' . $this->vgs_edd_eu_vat_get_user_ip(), false, $context));
                    $this_country = $geo_ip_json->country_code;
                    
                }
                catch(Exception $e) {
                    $this_country = "XX";
                }
            }

            //ipinfo.io

            if ($this_country == "XX") {
                try {
                    $context = stream_context_create(array('http' => array('timeout' => 1,),));
                    
                    $geo_ip_json = json_decode(@file_get_contents('http://ipinfo.io/' . $this->vgs_edd_eu_vat_get_user_ip() . '/json', false, $context));
                    
                    $this_country = $geo_ip_json->country;
                }
                catch(Exception $e) {
                    $this_country = "XX";
                }
            }
            
            if (is_null($this_country) || $this_country == "XX") {
                $this_country = "00";
            }

            return $this_country;
        }

        /*
        * Forked from  https://wordpress.org/plugins/edd-prevent-eu-checkout/
        */
        
        function vgs_edd_eu_vat_get_user_ip() {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            return $ip;
        }
    }
}
