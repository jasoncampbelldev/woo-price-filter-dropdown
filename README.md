# Woo Price Filter Dropdown

Use this WordPress plugin to add a simple price range filter dropdown to your WooCommerce storefront. 
It works in tandom with the default filter dropdown.

A shortcode can also be used to add the dropdown in a widget or in your theme.

Shortcode: woo_price_filter_dropdown

## Admin
![Screenshot of plugin admin](https://github.com/jasoncampbelldev/woo-price-filter-dropdown/blob/main/woo-price-filter-dropdown-admin-screenshot.jpg?raw=true)

- **Default Label** sets the pre-selected value of the select dropdown
- **Short Code Only** if checked only the shortcode is used on the front-end. It will not be added through the woocommerce_before_shop_loop or the woocommerce_after_shop_loop
- **Price Ranges** Atleast one price range has to be set for the dropdown to appear on the front end. The label is the value that will appearn in the dropdown

![Screenshot of plugin Front-end](https://github.com/jasoncampbelldev/woo-price-filter-dropdown/blob/main/woo-price-filter-dropdown-screenshot.jpg?raw=true)

Note: Depending on your theme you may need to add CSS to get the filter section looking good. I developed this using the default storefront WooCommerce theme.

CSS Classes: 
- woo-price-filter-dropdown-wrapper
- woo-price-filter-dropdown
