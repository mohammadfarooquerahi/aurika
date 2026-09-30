import os
import zipfile
import shutil

docx_path = r"C:\Users\DC\BIC data\Aurika\AURIKA_products_prices -updated.docx"
output_dir = r"C:\Users\DC\BIC data\Aurika"

auto_plugin_dir = os.path.join(output_dir, "_auto_plugin_build_temp")
if os.path.exists(auto_plugin_dir):
    shutil.rmtree(auto_plugin_dir)

plugin_folder = os.path.join(auto_plugin_dir, "aurika-auto-products-importer")
assets_folder = os.path.join(plugin_folder, "client_images")
os.makedirs(assets_folder, exist_ok=True)

# 1. Extract all images from docx into plugin's client_images folder
with zipfile.ZipFile(docx_path, 'r') as docx:
    for f in docx.namelist():
        if f.startswith('word/media/'):
            filename = os.path.basename(f)
            with open(os.path.join(assets_folder, filename), 'wb') as out_f:
                out_f.write(docx.read(f))
            print(f"Packed image: {filename}")

# 2. Write the comprehensive Auto-Importer PHP Plugin
plugin_php = """<?php
/**
 * Plugin Name: Aurika One-Click Product & Real Images Auto-Populator
 * Plugin URI: https://aurika.online
 * Description: 1-Click tool that creates all 13+ official Aurika client products (exact titles, prices, descriptions, variations, and automatically uploads & attaches the 17 real client photos).
 * Version: 2.0.0
 * Author: Aurika Team
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Auto-Import Client Products',
        '⚡ Import Client Products',
        'manage_options',
        'aurika-auto-products',
        'aurika_render_auto_products_page'
    );
} );

function aurika_render_auto_products_page() {
    if ( isset( $_POST['aurika_do_import'] ) && check_admin_referer( 'aurika_import_nonce' ) ) {
        $result = aurika_execute_full_product_creation();
        echo '<div class="notice notice-success is-dismissible" style="padding:15px; font-size:15px;"><p><strong>🎉 ' . esc_html( $result ) . '</strong></p></div>';
    }
    ?>
    <div class="wrap" style="max-width:850px; background:#fff; padding:35px; border-radius:12px; margin-top:20px; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
        <h1 style="font-family:sans-serif; font-size:26px; font-weight:800; color:#111; margin-bottom:12px;">⚡ Aurika 1-Click Product &amp; Real Images Importer</h1>
        <p style="font-size:15px; color:#555; line-height:1.7;">
            This tool automatically reads the client document (<strong>AURIKA_products_prices -updated.docx</strong>) and creates/updates all products with:
        </p>
        <ul style="font-size:14px; color:#444; line-height:1.8; margin-left:20px; list-style:disc;">
            <li><strong>All 17 Real Client Product Images</strong> automatically uploaded into WordPress Media Library and attached to each product!</li>
            <li><strong>Exact Client Prices:</strong> Rs. 80, Rs. 150, Rs. 199, Rs. 250, Rs. 299, Rs. 399, Rs. 499, Rs. 599, Rs. 699, Rs. 999, Rs. 1,100, Rs. 1,199, Rs. 1,299, Rs. 1,499, Rs. 1,799.</li>
            <li><strong>Full Variations:</strong> 1 Piece / 2 Pieces / 3 Pieces &amp; DIY Small (4 Toys) / Large (8 Toys).</li>
            <li><strong>Full Specifications:</strong> Burning times (5h to 24h), Fragrances (Lavender, Ocean, Rose, Lily, Sunflower), Wax types, and Child safety notes.</li>
        </ul>
        <hr style="margin:25px 0; border:0; border-top:1px solid #eee;">
        <form method="post">
            <?php wp_nonce_field( 'aurika_import_nonce' ); ?>
            <button type="submit" name="aurika_do_import" class="button button-primary button-hero" style="background:#b8860b; border-color:#b8860b; font-weight:700; font-size:15px; padding:12px 32px; height:auto; text-transform:uppercase; letter-spacing:1px; cursor:pointer;">
                🚀 Create / Update All Products with Real Photos Now
            </button>
        </form>
    </div>
    <?php
}

function aurika_attach_image( $filename, $title ) {
    $img_path = plugin_dir_path( __FILE__ ) . 'client_images/' . $filename;
    if ( ! file_exists( $img_path ) ) return 0;

    $upload_dir = wp_upload_dir();
    $target_filename = 'aurika-' . sanitize_file_name( $filename );
    $target_path = $upload_dir['path'] . '/' . $target_filename;

    if ( ! file_exists( $target_path ) ) {
        copy( $img_path, $target_path );
    }

    $file_type = wp_check_filetype( $target_filename, null );
    $attachment = array(
        'guid'           => $upload_dir['url'] . '/' . $target_filename,
        'post_mime_type' => $file_type['type'],
        'post_title'     => sanitize_text_field( $title ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    $attachment_id = wp_insert_attachment( $attachment, $target_path );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attachment_id, $target_path );
    wp_update_attachment_metadata( $attachment_id, $attach_data );

    return $attachment_id;
}

function aurika_execute_full_product_creation() {
    if ( ! class_exists( 'WC_Product_Simple' ) ) {
        return 'WooCommerce is required.';
    }

    // Categories
    $cat_cnd = wp_create_term( 'Scented Candles', 'product_cat' );
    $cat_diy = wp_create_term( 'DIY Art Kits', 'product_cat' );
    $cat_cnd_id = is_array($cat_cnd) ? (int)$cat_cnd['term_id'] : (int) get_term_by('name', 'Scented Candles', 'product_cat')->term_id;
    $cat_diy_id = is_array($cat_diy) ? (int)$cat_diy['term_id'] : (int) get_term_by('name', 'DIY Art Kits', 'product_cat')->term_id;

    // Map Images
    $img_map = array(
        'cnd_lavender'   => aurika_attach_image('image1.jpeg', 'Lavender Scented Candle'),
        'cnd_lavender_2' => aurika_attach_image('image2.jpeg', 'Lavender Scented Candle Rose Variant'),
        'cnd_ocean_art'  => aurika_attach_image('image3.jpeg', 'Sea Shell Ocean Candle Artistic'),
        'cnd_ocean_pack' => aurika_attach_image('image4.jpg', 'Sea Shell Ocean Candle 1/2 Piece'),
        'cnd_ocean_1799' => aurika_attach_image('image5.jpg', 'Sea Shell Ocean Candle Luxury Artistic'),
        'cnd_ocean_tray' => aurika_attach_image('image6.jpeg', 'Sea Shell Candle 2 Pieces with Tray'),
        'cnd_ocean_pair' => aurika_attach_image('image7.png', 'Sea Shell Ocean Candle Set of 2'),
        'cnd_peony'      => aurika_attach_image('image8.jpg', 'Peony Flower Scented Candle'),
        'cnd_daisy'      => aurika_attach_image('image9.jpeg', 'Daisy Flower Candle'),
        'cnd_mini_heart' => aurika_attach_image('image10.jpg', 'Mini Heart Hand Candles'),
        'cnd_rose_heart' => aurika_attach_image('image11.jpg', 'Aurika Rose Heart Candle'),
        'cnd_rose_flw'   => aurika_attach_image('image12.jpeg', 'Rose Flower Scented Candle'),
        'cnd_starfish'   => aurika_attach_image('image13.jpg', 'Star Fish Scented Candle'),
        'diy_8toys'      => aurika_attach_image('image14.jpg', 'DIY Art Kit 8 Toys Set'),
        'diy_8toys_g1'   => aurika_attach_image('image15.jpg', 'DIY Art Kit Figurines and Colors'),
        'diy_8toys_g2'   => aurika_attach_image('image16.jpg', 'DIY Art Kit Packaging & Brush'),
        'diy_4toys'      => aurika_attach_image('image17.jpeg', 'DIY Art Kit 4 Toys Compact Set')
    );

    $count = 0;

    // 1. Lavender Scented Candle (Rs. 999)
    $p1 = aurika_create_or_get_simple('AUR-CND-01', 'Lavender Scented Candle', '999', array($cat_cnd_id),
        'Fragrance: Lavender | Burning Time: 24 Hours | Wax: Natural Paraffin Blend',
        "A beautifully crafted scented candle with a soothing lavender fragrance, designed to add warmth and elegance to your space.\n\n• Fragrance: French Lavender (Rose & Sunflower variants available)\n• Burn Time: 24 Hours\n• Wax: Paraffin Blend\n• Handcrafted with love in Pakistan",
        $img_map['cnd_lavender'], array($img_map['cnd_lavender_2'])
    );
    $count++;

    // 2. Sea Shell Ocean Candle (Artistic 699)
    aurika_create_or_get_simple('AUR-CND-02', 'Sea Shell Ocean Candle (Artistic)', '699', array($cat_cnd_id),
        'Fragrance: Ocean | Burning Time: 10 Hours | Hand-carved Sea Shell Design',
        "A sea-shell inspired candle with a refreshing ocean fragrance, bringing a coastal touch to your space.\n\n• Fragrance: Ocean\n• Burn Time: 10 Hours\n• Artistic coastal shape",
        $img_map['cnd_ocean_art']
    );
    $count++;

    // 3. Sea Shell Ocean Candle (1 pc Rs. 599 / 2 pcs Rs. 1100) - Variable
    aurika_create_variable_product('AUR-CND-03', 'Sea Shell Ocean Candle', array($cat_cnd_id),
        'Fragrance: Ocean | Burning Time: 10 Hours | Available in 1 Piece or 2 Pieces Pack',
        "A charming sea-shell candle with a refreshing ocean fragrance, perfect for adding a decorative touch to your space.\n\n• Fragrance: Ocean\n• Burn Time: 10 Hours (individual)\n• 1 Piece: Rs. 599\n• 2 Pieces Pack: Rs. 1,100 (Save Rs. 98)",
        $img_map['cnd_ocean_pack'], 'Pack Size', array(
            array('val' => '1 Piece', 'price' => '599'),
            array('val' => '2 Pieces Pack', 'price' => '1100')
        )
    );
    $count++;

    // 4. Sea Shell Ocean Candle (Luxury Artistic Rs. 1799)
    aurika_create_or_get_simple('AUR-CND-04-LUX', 'Sea Shell Ocean Candle (Luxury Grand)', '1799', array($cat_cnd_id),
        'Fragrance: Ocean | Burning Time: 10 Hours (individual) | Large Artistic Display',
        "An artistic sea-shell candle with a refreshing ocean fragrance, designed to combine decorative appeal with a fragrant experience.\n\n• Fragrance: Ocean\n• Burn Time: 10 Hours\n• Premium luxury mold",
        $img_map['cnd_ocean_1799']
    );
    $count++;

    // 5. Sea Shell Ocean Candle Set (2 Pieces with Tray Rs. 1499)
    aurika_create_or_get_simple('AUR-CND-04', 'Sea Shell Ocean Candle (2 Pieces with Tray)', '1499', array($cat_cnd_id),
        'Includes 2 Sea Shell Candles + Luxury Decorative Tray | Fragrance: Ocean',
        "A two-piece sea-shell candle set with a tray and refreshing ocean fragrance, perfect for gifting or elegant home décor.\n\n• 2 Candles + 1 Matching Tray\n• Fragrance: Ocean\n• Burn Time: 10 Hours (individual)",
        $img_map['cnd_ocean_tray']
    );
    $count++;

    // 6. Sea Shell Ocean Candle (Set of 2 Pieces Rs. 1299)
    aurika_create_or_get_simple('AUR-CND-05', 'Sea Shell Ocean Candle (Set of 2 Pieces)', '1299', array($cat_cnd_id),
        'Set of 2 Artistic Sea Shell Candles | Fragrance: Ocean | Burn Time: 10 Hours Each',
        "A set of two artistic sea-shell candles with a refreshing ocean fragrance, adding charm and warmth to your space.\n\n• Quantity: 2 Pieces\n• Fragrance: Ocean\n• Burn Time: 10 Hours (each)",
        $img_map['cnd_ocean_pair']
    );
    $count++;

    // 7. Peony Flower Scented Candle (1 pc Rs. 199 / 3 pcs Rs. 499)
    aurika_create_variable_product('AUR-CND-06', 'Peony Flower Scented Candle', array($cat_cnd_id),
        'Delicate flower-shaped candle. Fragrance: Lavender, Rose, Lily | 1 Piece (Rs. 199) or 3 Pieces (Rs. 499)',
        "A delicate peony flower-shaped candle available in lavender, rose, and lily fragrances, perfect for adding a floral touch to your space.\n\n• Fragrances: Lavender, Rose, Lily\n• Burn Time: 6 Hours (individual)\n• 1 Piece: Rs. 199\n• 3 Pieces Pack: Rs. 499",
        $img_map['cnd_peony'], 'Pack Size', array(
            array('val' => '1 Piece', 'price' => '199'),
            array('val' => '3 Pieces Pack', 'price' => '499')
        )
    );
    $count++;

    // 8. Daisy Flower Candle (1 pc Rs. 150 / 3 pcs Rs. 399)
    aurika_create_variable_product('AUR-CND-07', 'Daisy Flower Candle', array($cat_cnd_id),
        'Charming daisy candle in Rose, Lily, Lavender, Sunflower scents. 1 pc (Rs. 150) / 3 pcs (Rs. 399)',
        "A charming daisy-shaped candle available in rose, lily, lavender, and sunflower fragrances.\n\n• Scents: Rose, Lily, Lavender, Sunflower\n• Burn Time: 5 Hours (individual)\n• 1 Piece: Rs. 150\n• 3 Pieces Pack: Rs. 399",
        $img_map['cnd_daisy'], 'Pack Size', array(
            array('val' => '1 Piece', 'price' => '150'),
            array('val' => '3 Pieces Pack', 'price' => '399')
        )
    );
    $count++;

    // 9. Mini Heart Hand Candles (1 pc Rs. 80 / 2 pcs Rs. 150 / 3 pcs Rs. 250)
    aurika_create_variable_product('AUR-CND-08', 'Mini Heart Hand Candles', array($cat_cnd_id),
        'Small heart candles in Rose, Ocean, Lavender. 1 pc (Rs. 80) / 2 pcs (Rs. 150) / 3 pcs (Rs. 250)',
        "Small heart-shaped candles available in rose, ocean, and lavender fragrances — perfect for gifting and home decor.\n\n• Burn Time: 5 Hours (individual)\n• 1 Piece: Rs. 80\n• 2 Pieces: Rs. 150\n• 3 Pieces: Rs. 250",
        $img_map['cnd_mini_heart'], 'Pack Size', array(
            array('val' => '1 Piece', 'price' => '80'),
            array('val' => '2 Pieces', 'price' => '150'),
            array('val' => '3 Pieces', 'price' => '250')
        )
    );
    $count++;

    // 10. Aurika Rose Heart Candle (Rs. 999)
    aurika_create_or_get_simple('AUR-CND-09', 'Aurika Rose Heart Candle', '999', array($cat_cnd_id),
        'Elegant heart-shaped candle with rich English Rose fragrance. Burn time: 15 hours.',
        "An elegant heart-shaped candle with a beautiful rose fragrance, designed to add warmth, charm, and elegance to your space.\n\n• Fragrance: Romantic Rose\n• Burn Time: 15 Hours\n• Shape: Sculpted Rose Heart",
        $img_map['cnd_rose_heart']
    );
    $count++;

    // 11. Rose Flower Scented Candle (Rs. 299)
    aurika_create_or_get_simple('AUR-CND-10', 'Rose Flower Scented Candle', '299', array($cat_cnd_id),
        'Decorative rose-shaped candle with delicate rose aroma. Burn time: 5 hours.',
        "A decorative rose-shaped candle with a delicate rose fragrance, bringing a floral and elegant touch to your space.\n\n• Fragrance: Rose\n• Burn Time: 5 Hours\n• Shape: 3D Rose Flower",
        $img_map['cnd_rose_flw']
    );
    $count++;

    // 12. Star Fish Scented Candle (Rs. 1199)
    aurika_create_or_get_simple('AUR-CND-11', 'Star Fish Scented Candle', '1199', array($cat_cnd_id),
        'Artistic starfish candle with refreshing ocean fragrance. Burn time: 8 hours.',
        "An artistic starfish-shaped candle with a refreshing ocean fragrance, inspired by the beauty of the sea.\n\n• Fragrance: Ocean Breeze\n• Burn Time: 8 Hours\n• Shape: Coastal Starfish",
        $img_map['cnd_starfish']
    );
    $count++;

    // 13. DIY Art Kit (8 Concrete Toys Set Rs. 599)
    aurika_create_or_get_simple('AUR-DIY-08', 'Aurika DIY Art Kit (8 Concrete Toys Set)', '599', array($cat_diy_id),
        '8 Concrete Toys + Acrylic Paints + Detail Brush | Ages 3–12 | Screen-Free Creative Play',
        "A creative DIY painting kit with 8 concrete toys, paints, and a brush, designed to encourage hands-on creativity and imaginative play for children ages 3–12.\n\n• Recommended Age: 3 to 12 Years\n• Items Included: Painting brush, 8 concrete toys, painting colors\n• Child-Safe, Non-Toxic & Washable",
        $img_map['diy_8toys'], array($img_map['diy_8toys_g1'], $img_map['diy_8toys_g2'])
    );
    $count++;

    // 14. DIY Art Kit (4 Concrete Toys Set Rs. 299)
    aurika_create_or_get_simple('AUR-DIY-04', 'Aurika DIY Art Kit (4 Concrete Toys Set)', '299', array($cat_diy_id),
        '4 Concrete Toys + Acrylic Paints + Detail Brush | Ages 3–12 | Compact Play Kit',
        "A compact DIY painting kit with 4 concrete toys, paints, and a brush, designed for creative, hands-on fun for children ages 3–12.\n\n• Recommended Age: 3 to 12 Years\n• Items Included: Painting brush, 4 concrete toys, painting colors\n• Child-Safe & Non-Toxic",
        $img_map['diy_4toys']
    );
    $count++;

    return "Successfully populated all {$count} products with real client photos, exact prices, burning times, and variations!";
}

function aurika_create_or_get_simple( $sku, $name, $price, $categories, $short_desc, $full_desc, $image_id = 0, $gallery_ids = array() ) {
    $existing_id = wc_get_product_id_by_sku( $sku );
    $product = $existing_id ? wc_get_product( $existing_id ) : new WC_Product_Simple();
    $product->set_name( $name );
    $product->set_sku( $sku );
    $product->set_regular_price( $price );
    $product->set_short_description( $short_desc );
    $product->set_description( $full_desc );
    $product->set_category_ids( $categories );
    $product->set_status( 'publish' );
    $product->set_manage_stock( false );
    $product->set_stock_status( 'instock' );
    if ( $image_id ) {
        $product->set_image_id( $image_id );
    }
    if ( ! empty( $gallery_ids ) ) {
        $product->set_gallery_image_ids( array_filter( $gallery_ids ) );
    }
    $product->save();
    return $product;
}

function aurika_create_variable_product( $sku, $name, $categories, $short_desc, $full_desc, $image_id, $attr_name, $variations ) {
    $existing_id = wc_get_product_id_by_sku( $sku );
    $product = $existing_id ? wc_get_product( $existing_id ) : new WC_Product_Variable();
    $product->set_name( $name );
    $product->set_sku( $sku );
    $product->set_short_description( $short_desc );
    $product->set_description( $full_desc );
    $product->set_category_ids( $categories );
    $product->set_status( 'publish' );
    if ( $image_id ) {
        $product->set_image_id( $image_id );
    }

    $attr_values = array_column( $variations, 'val' );
    $attr = new WC_Product_Attribute();
    $attr->set_name( $attr_name );
    $attr->set_options( $attr_values );
    $attr->set_position( 0 );
    $attr->set_visible( true );
    $attr->set_variation( true );
    $product->set_attributes( array( $attr ) );
    $parent_id = $product->save();

    foreach ( $variations as $var ) {
        $var_slug = sanitize_title( $var['val'] );
        $var_sku = $sku . '-' . $var_slug;
        $var_id = wc_get_product_id_by_sku( $var_sku );
        $variation = $var_id ? new WC_Product_Variation( $var_id ) : new WC_Product_Variation();
        $variation->set_parent_id( $parent_id );
        $variation->set_sku( $var_sku );
        $variation->set_attributes( array( sanitize_title( $attr_name ) => $var['val'] ) );
        $variation->set_regular_price( $var['price'] );
        $variation->set_status( 'publish' );
        $variation->set_stock_status( 'instock' );
        $variation->save();
    }

    return $product;
}
"""

with open(os.path.join(plugin_folder, "aurika-auto-products-importer.php"), "w", encoding="utf-8") as f:
    f.write(plugin_php)

plugin_zip = os.path.join(output_dir, "aurika-auto-products-importer-plugin.zip")
if os.path.exists(plugin_zip):
    os.remove(plugin_zip)

with zipfile.ZipFile(plugin_zip, 'w', zipfile.ZIP_DEFLATED) as zf:
    for root, dirs, files in os.walk(auto_plugin_dir):
        for file in files:
            full_path = os.path.join(root, file)
            rel_path = os.path.relpath(full_path, auto_plugin_dir).replace('\\', '/')
            zf.write(full_path, rel_path)

shutil.rmtree(auto_plugin_dir)
print(f"Updated Auto-Importer Plugin with 17 photos: {plugin_zip} ({os.path.getsize(plugin_zip)/1024/1024:.2f} MB)")
