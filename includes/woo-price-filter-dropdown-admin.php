<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// add styling
function include_styles() {
    if ( isset( $_GET['page'] ) && $_GET['page'] == 'woo-price-filter-dropdown-admin' ) {
        wp_enqueue_style( 'style', plugins_url('/style.css', __FILE__), false, '1.0.0', 'all');
    }
}
add_action('admin_enqueue_scripts', 'include_styles');



function register_settings() {
    // register settings
    register_setting( 'woo-price-filter-dropdown-group', 'woo_price_filter_dropdown_label' );
    $booleanArgs = array( 'type' => 'boolean');
    register_setting( 'woo-price-filter-dropdown-group', 'woo_price_filter_dropdown_shortcode_only', $booleanArgs );
}

function woo_price_filter_dropdown_menu() {
    // add admin page to settings tab
    add_options_page( 'WooCommerce Price Filters', 'WooCommerce Price Filters', 'manage_options', 'woo-price-filter-dropdown-admin', 'woo_price_filter_dropdown_admin' );
    // init registering settings 
    add_action( 'admin_init', 'register_settings' );
}

add_action( 'admin_menu', 'woo_price_filter_dropdown_menu' );

function woo_price_filter_dropdown_admin(){
    // Create admin page //

    global $wpdb;
    $price_range_table_name = $wpdb->prefix . "woo_price_filter_dropdown_ranges";

    // save price range
    if(isset($_POST['save'])){
        $wpdb->insert($price_range_table_name,
            array(
                'name' => $_POST['name'],
                'min' => $_POST['min'],
                'max' => $_POST['max']            
            ),
            array(
                '%s',
                '%s',
                '%s'
            )
        );
        echo '<div id="update" class="notice notice-success settings-error is-dismissible"><p><strong>Range Added.</strong></p></div>';
    }

    // edit price range
    if(isset($_POST['edit'])){
        global $wpdb;
        $wpdb->update($price_range_table_name,
            array(
                'name' => $_POST['name'],
                'min' => $_POST['min'],
                'max' => $_POST['max']       
            ),
            array(
                'id' => $_POST['id']
            )
        );
        echo '<div id="update" class="notice notice-success settings-error is-dismissible"><p><strong>Range Edited.</strong></p></div>';
    }

    // delete price range
    if(isset($_POST['delete'])){
        global $wpdb;
        $wpdb->delete($price_range_table_name,
            array(
                'id' => $_POST['id']          
            )
        );
        echo '<div id="update" class="notice notice-success settings-error is-dismissible"><p><strong>Range Deleted.</strong></p></div>';
    }

    ?>

    <div id="woo-price-filter-dropdown-admin">

        <h1>Woo Price Filter Dropdown Settings</h1>


        <h2>Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'woo-price-filter-dropdown-group' ); ?>
            <?php do_settings_sections( 'woo-price-filter-dropdown-group' ); ?>
            <label for="dropdown-label">Default Label</label><br />
            <input type="text" id="dropdown-label" name="woo_price_filter_dropdown_label" value="<?php echo esc_attr( get_option('woo_price_filter_dropdown_label') ); ?>" placeholder="Price Filter" />
            <br /><br />
            <label for="dropdown-shortcode">Short Code Only</label><br />
            <input type="checkbox" id="ropdown-shortcode" name="woo_price_filter_dropdown_shortcode_only" 
            <?php 
                $shortcode_only_value = get_option('woo_price_filter_dropdown_shortcode_only'); 
                if ($shortcode_only_value) { 
                    echo " checked"; 
                }
            ?>
             />
            <?php submit_button(); ?>
        </form>

        <hr />

        <h2>Price Ranges</h2>

        <?php
        $rows = $wpdb->get_results( "SELECT * FROM " . $price_range_table_name );
        if ($rows) {
            echo '<div class="woo-price-ranges">';
            echo '<div class="woo-price-ranges-row border-none"><div>Name</div><div>Min</div><div>Max</div><div></div></div>';
            foreach($rows as $row){ 
                ?>
                    <div class="woo-price-ranges-row">
                        <div><?php echo $row->name ?></div>
                        <div><?php echo $row->min ?></div>
                        <div><?php echo $row->max ?></div>
                        <div class="buttons">
                            <form action="options-general.php?page=woo-price-filter-dropdown-admin" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row->id ?>" />
                                <button class="button button-primary" type="submit" name="delete">Delete</button>
                            </form>
                            <button class="button button-primary" onClick="jQuery('.edit-range-<?php echo $row->id ?>').toggle()">Edit</button>
                        </div>
                    </div>
                    <div class="woo-price-ranges-row edit-range-<?php echo $row->id ?>" colspan="4" style="display:none;">
                        <form action="options-general.php?page=woo-price-filter-dropdown-admin" method="POST">
                            <input type="text" name="name" value="<?php echo $row->name ?>" size="10" maxlength=50 required />
                            <input type="number" name="min" value="<?php echo $row->min ?>" placeholder="min" size="5" maxlength=10 required />
                            <input type="number" name="max" value="<?php echo $row->max ?>" placeholder="max" size="5" maxlength=10 required />
                            <input type="hidden" name="id" value="<?php echo $row->id ?>" />
                            <button class="button button-primary" type="submit" name="edit">Save</button>
                        </form>
                    </div>
                <?php
            }
            echo "</table>";
        } else {
            echo "<p class='warning'>Enter Price Ranges</p>";
        }
        ?>

        </div>

        <form class="woo-price-ranges-form" action="options-general.php?page=woo-price-filter-dropdown-admin" method="POST">
            <input type="text" name="name" placeholder="label" size="10" maxlength=50 required />
            <input type="number" name="min" placeholder="min" size="5" maxlength=10 required />
            <input type="number" name="max" placeholder="max" size="5" maxlength=10 required />

            <button class="button button-primary" type="submit" name="save">Add</button>
        </form>

    </div>
  <?php

}



