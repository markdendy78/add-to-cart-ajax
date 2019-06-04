function addtocart_scripts() {
        wp_enqueue_script('singleAddToCartjs', get_template_directory_uri() . '/js/single-add-to-cart.js',array('jquery'), '1.0.0', true);
        wp_localize_script('singleAddToCartjs', 'crispshop_ajax_object',array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'addtocart_scripts');

// Add to cart ajax
function crispshop_add_cart_single_ajax() {
    
    global $post, $product, $woocommerce;
    
    $product_id = $_POST['product_id'];
    $variation_id = $_POST['variation_id'];
    $quantity = $_POST['quantity'];

    if ($variation_id) {
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    } else {
        WC()->cart->add_to_cart($product_id, $quantity);
    }

    $items = WC()->cart->get_cart();
    global $woocommerce;
    $item_count = $woocommerce->cart->cart_contents_count;
    
    echo '<span class="item-count">' . $item_count . '</span>' . "\n";

    echo '<h4>Cart</h4>' . "\n";

    foreach ($items as
            $item =>
            $values) {
        $_product = $values['data']->post;
        echo '<div class="dropdown-cart-wrap">' . "\n";
        echo '<div class="dropdown-cart-left">' . "\n";
        $variation = $values['variation_id'];
        if ($variation) {
            echo get_the_post_thumbnail($values['variation_id'], 'catalog');
        } else {
            echo get_the_post_thumbnail($values['product_id'], 'catalog');
        }
        echo '</div>' . "\n";

        echo '<div class="dropdown-cart-right">' . "\n";
        echo '<h5>' . $_product->post_title . '</h5>' . "\n";
        echo '<p><strong>Quantity:</strong>' . $values['quantity'] . '</p>' . "\n";
        global $woocommerce;
        $currency = get_woocommerce_currency_symbol();
        $price = get_post_meta($values['product_id'], '_regular_price', true);
        $sale = get_post_meta($values['product_id'], '_sale_price', true);

        if ($sale) {
            echo '<p class="price"><strong>Price:</strong> <del>' . $currency . $price . '</del>' . $currency . $sale . '</p>' . "\n";
        } elseif ($price) {
            echo '<p class="price"><strong>Price:</strong>' . $currency . $price . '</p>' . "\n";
        }
        echo '</div>' . "\n";

        echo '<div class="clear"></div>' . "\n";
        echo '</div>' . "\n";
    }

    echo '<div class="dropdown-cart-wrap dropdown-cart-subtotal">' . "\n";
    echo '<div class="dropdown-cart-left">' . "\n";
    echo '<h6>Subtotal</h6>' . "\n";
    echo '</div>' . "\n";

    echo '<div class="dropdown-cart-right">' . "\n";
    echo '<h6>' . WC()->cart->get_cart_total() . '</h6>' . "\n";
    echo '</div>' . "\n";

    echo '<div class="clear"></div>' . "\n";
    echo '</div>' . "\n";

    $cart_url = $woocommerce->cart->get_cart_url();
    $checkout_url = $woocommerce->cart->get_checkout_url();

    echo '<div class="dropdown-cart-wrap dropdown-cart-links">' . "\n";
    echo '<div class="dropdown-cart-left dropdown-cart-link">' . "\n";
    echo '<a class="button viewcart cart-totals" href="' . $cart_url . '" title="View Cart"><i class="fa fa-shopping-cart fa-lg" style="color: #FFFFFF !important;" title="View Cart"></i><span class="cart-contents-count-button">' . esc_html( $item_count ) . '</span></a>' . "\n";
    echo '</div>' . "\n";

    echo '<div class="dropdown-cart-right dropdown-checkout-link">' . "\n";
    echo '<a href="' . $checkout_url . '">Checkout</a>' . "\n";
    echo '</div>' . "\n";

    echo '<div class="clear"></div>' . "\n";
    echo '</div>' . "\n";

    die();
}
add_action('wp_ajax_crispshop_add_cart_single', 'crispshop_add_cart_single_ajax');
add_action('wp_ajax_nopriv_crispshop_add_cart_single', 'crispshop_add_cart_single_ajax');
