<?php
/**
 * Settings
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
define('VGS_EDD_EU_VAT_GEODB_FILENAME', 'GeoLite2-Country.mmdb');

if (!class_exists('VGS_EDD_EU_VAT_Compliance_GeoIP')) {
    
    class VGS_EDD_EU_VAT_Compliance_GeoIP
    {
        
        public function __construct() {
        }
                
        function vgs_edd_eu_vat_download_geodb() {
            
            $geo_db_file = $this->vgs_edd_eu_vat_get_geodb_name();
            
            if (!file_exists($geo_db_file)) {
                
                try {
                    $download_url = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.mmdb.gz';
                    
                    // Download
                    $tmpFile = download_url($download_url);
                    if (is_wp_error($tmpFile)) {
                        return $tmpFile->get_error_message();
                    }
                    
                    // Ungzip File
                    $gzfile = gzopen($tmpFile, 'r');
                    $tempDb = fopen($geo_db_file, 'w');
                    
                    if (!$gzfile) {
                        return __('Downloaded file could not be opened for reading.', 'vgs_edd_eu_vat_compliance');
                    }
                    if (!$tempDb) {
                        return sprintf(__('Database could not be written (%s).', 'vgs_edd_eu_vat_compliance'), $geo_db_file);
                    }
                    
                    while (($string = gzread($gzfile, 4096)) != false) {
                        fwrite($tempDb, $string, strlen($string));
                    }
                    
                    gzclose($gzfile);
                    fclose($tempDb);
                    
                    unlink($tmpFile);
                    
                    return true;
                }
                catch(Exception $e) {
                    return false;
                }
            }
        }
        
        function vgs_edd_eu_vat_get_geodb_name() {
            
            $filename = VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . '/' . VGS_EDD_EU_VAT_GEODB_FILENAME;
            return $filename;
        }
        
        function vgs_edd_eu_vat_addactions() {
            add_action('vgs_edd_eu_vat_geodbupdate', array($this, 'vgs_edd_eu_vat_cronupdates'));
        }
        
        function vgs_edd_eu_vat_register_crons() {
            
            $scheduled_event = wp_next_scheduled('vgs_edd_eu_vat_geodbupdate');
            if ($scheduled_event !== false) {
                wp_unschedule_event($scheduled_event, 'vgs_edd_eu_vat_geodbupdate');
            }
            
            $exp_time = strtotime('first tuesday of next month + 1 day');
            if (is_numeric($exp_time)) {
                wp_schedule_single_event($exp_time, 'vgs_edd_eu_vat_geodbupdate');
            }
        }
        
        function vgs_edd_eu_vat_cronupdates() {
            
            if ($this->vgs_edd_eu_vat_download_geodb()) {
                $this->vgs_edd_eu_vat_register_crons();
            }
        }
                
        public static function vgs_edd_eu_vat_remove_crons() {
            wp_clear_scheduled_hook('vgs_edd_eu_vat_geodbupdate');
        }
        
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
            
            if ($this_country == "XX" && $edd_options['vgs_edd_eu_vat_hostinfo'] == '1') {
                try {
                    $context = stream_context_create(array('http' => array('timeout' => 1,),));
                    
                    $this_country = @file_get_contents('http://api.hostip.info/country.php?ip=' . $this->vgs_edd_eu_vat_get_user_ip(), false, $context);
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
