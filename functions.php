function addtocart_scripts() {
        wp_enqueue_script('singleAddToCartjs', get_template_directory_uri() . '/js/single-add-to-cart.js',array('jquery'), '1.0.0', true);
        wp_localize_script('singleAddToCartjs', 'crispshop_ajax_object',array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'addtocart_scripts');
