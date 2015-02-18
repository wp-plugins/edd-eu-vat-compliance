<?php
/**
* Scripts
*
* @package     EDD EU VAT COMPLIANCE
* @subpackage  Functions/Scripts
* @copyright   Copyright (c) 2014, Conrado Maggi
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
* @author      Conrado Maggi - VGS Global
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function vgs_edd_eu_vat_load_scripts() {
  $js_dir = VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_URL . 'assets/js/';
  
  if ( edd_is_checkout() ) {
    wp_enqueue_script( 'edd-eu-vat-compliance_js', $js_dir . 'edd-eu-vat-compliance.js', array( 'jquery' ), EDD_VERSION );
  }

}
add_action( 'wp_enqueue_scripts', 'vgs_edd_eu_vat_load_scripts' );

?>