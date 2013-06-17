<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$alt = 1;
$attributes = $product->get_attributes();

if ( empty( $attributes ) && ( ! $product->enable_dimensions_display() || ( ! $product->has_dimensions() && ! $product->has_weight() ) ) ) return;
?>
<table class="shop_attributes">
    <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
        <th><?php _e( 'SKU', 'woocommerce' ) ?></th>
        <td class="sku"><?php echo $product->get_sku();?></td>
    </tr>

    <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
        <th><?php _e( 'Short Description', 'woocommerce' ) ?></th>
        <td class="short_description"><?php echo get_post_meta( get_the_ID(), 'Short Description', true ) ?></td>
    </tr>
    <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
        <th><?php _e( 'Carton Package', 'woocommerce' ) ?></th>
        <td class="carton_package_per_unit"><?php echo get_post_meta( get_the_ID(), 'Carton Package per Unit', true ) ?></td>
    </tr>
<!--     <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
        <th><?php _e( 'Unit of Measure', 'woocommerce' ) ?></th>
        <td class="unit_of_measure"><?php echo get_post_meta( get_the_ID(), 'Unit of Measure', true ) ?></td>
    </tr>

 -->
    <?php if ( $product->enable_dimensions_display() ) : ?>

        <?php if ( $product->has_weight() ) : $alt = $alt * -1; ?>

            <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
                <th><?php _e( 'Weight', 'woocommerce' ) ?></th>
                <td class="product_weight"><?php echo $product->get_weight() . ' ' . esc_attr( get_option('woocommerce_weight_unit') ); ?></td>
            </tr>

        <?php endif; ?>

        <?php if ($product->has_dimensions()) : $alt = $alt * -1; ?>

            <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
                <th><?php _e( 'Dimensions', 'woocommerce' ) ?></th>
                <td class="product_dimensions"><?php echo $product->get_dimensions(); ?></td>
            </tr>

        <?php endif; ?>

    <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
        <th><?php _e( 'Manufacturer', 'woocommerce' ) ?></th>
        <td class="manufacturer"><?php echo get_post_meta( get_the_ID(), 'Manufacturer', true ) ?></td>
    </tr>

    <?php endif; ?>

    <?php foreach ($attributes as $attribute) :

        if ( ! isset( $attribute['is_visible'] ) || ! $attribute['is_visible'] ) continue;
        if ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) continue;

        $alt = $alt * -1;
        ?>

        <tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
            <th><?php echo $woocommerce->attribute_label( $attribute['name'] ); ?></th>
            <td><?php
                if ( $attribute['is_taxonomy'] ) {

                    $values = woocommerce_get_product_terms( $product->id, $attribute['name'], 'names' );
                    echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

                } else {

                    // Convert pipes to commas and display values
                    $values = array_map( 'trim', explode( '|', $attribute['value'] ) );
                    echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

                }
            ?></td>
        </tr>

    <?php endforeach; ?>

</table>