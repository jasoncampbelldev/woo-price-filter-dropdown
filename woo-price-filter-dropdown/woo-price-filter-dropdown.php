<?php
/*
 * Plugin Name:       WooCommerce Price Filter Dropdown
 * Plugin URI:        https://jtc-art.com
 * Description:       Adds Price Filter Dropdown to WooCommerce
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Jason campbell
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://jtc-art.com
 * Text Domain:       woo-price-filter-dropdown
 * Domain Path:       /languages
 */

function create_price_range_table() {
    // Create table to store price range settings
    global $wpdb;
    $table_name = $wpdb->prefix . "woo_price_filter_dropdown_ranges";
    $sql = 'CREATE TABLE ' . $table_name . ' (
        id INTEGER NOT NULL AUTO_INCREMENT,
        name VARCHAR(50),
        min VARCHAR(10),
        max VARCHAR(10),
        PRIMARY KEY  (id))';

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option($table_name . "_version", "1.0");
}
register_activation_hook( __FILE__, 'create_price_range_table');


include 'includes/woo-price-filter-dropdown-admin.php';


$isShortcodeOnly = get_option('woo_price_filter_dropdown_shortcode_only');

if (empty($isShortcodeOnly)) {
    add_action( 'woocommerce_before_shop_loop', 'woo_price_filter_dropdown');
    add_action( 'woocommerce_after_shop_loop', 'woo_price_filter_dropdown');
}

function woo_price_filter_dropdown () {

    // store preexisting params if they exist
    $preexistingParams = "";
    $allGetPrams = $_GET;

    if ($allGetPrams) {
        $preexistingParamsArray = array();
        foreach($allGetPrams as $getParamKey => $getParamVal) {
            if ($getParamKey != 'min_price'  && $getParamKey != 'max_price') {
                array_push($preexistingParamsArray, $getParamKey . "=" . $getParamVal);
            }
        }
        $preexistingParams = join("&", $preexistingParamsArray) . "&";
    }

    // update default price range widget if it exists
    $min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : apply_filters( 'woocommerce_price_filter_widget_min_amount', $_GET['min_price'] );
    $max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : apply_filters( 'woocommerce_price_filter_widget_max_amount', $_GET['max_price'] );



    global $wpdb;
    $rows = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "woo_price_filter_dropdown_ranges" );
    if ($rows) {

        $label = get_option('woo_price_filter_dropdown_label');
        if (empty($label)) {
            $label = "Price Filter";
        }

        ?>
        <div class="woo-price-filter-dropdown-wrapper woocommerce-ordering">
            <select class="woo-price-filter-dropdown orderby" onchange="if (this.value) window.location.href=this.value">
                <option value="?<?php echo $preexistingParams ?>">
                    <?php echo esc_attr( $label ); ?>
                </option>

                <?php
                foreach($rows as $row){ 
                ?>

                    <option value="?<?php echo $preexistingParams ?>min_price=<?php echo $row->min ?>&max_price=<?php echo $row->max ?>"
                        <?php 
                            $isSelected = $_GET['min_price'] == $row->min && $_GET['max_price'] == $row->max ? "selected" : ''; 
                            echo $isSelected;
                        ?>
                    >
                        <?php echo $row->name ?></td>
                    </option>

                <?php
                }
                ?>
            </select>
        </div>
        <?php

        $isShortcodeOnly = get_option('woo_price_filter_dropdown_shortcode_only');
        $isShortcodeOnly = true;

        if (isset($isShortcodeOnly)) {
            remove_action( 'woocommerce_before_shop_loop', 'woo_price_filter_dropdown', 100);
        }
    }
}


add_shortcode('woo_price_filter_dropdown', 'woo_price_filter_dropdown');

