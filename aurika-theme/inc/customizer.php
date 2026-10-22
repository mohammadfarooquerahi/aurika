<?php
/**
 * Aurika Theme Customizer Options
 * Allows client to edit all website text, images, banners, social links, contact info, and footer.
 *
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'aurika_customize_register' );

function aurika_customize_register( $wp_customize ) {

	// ─────────────────────────────────────────────────────────
	// MAIN PANEL: Aurika Theme Settings
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_panel( 'aurika_theme_panel', array(
		'title'       => __( 'Aurika Theme Settings', 'aurika' ),
		'description' => __( 'Manage all website text, images, hero slider, trust badges, offer banners, contact info, and footer.', 'aurika' ),
		'priority'    => 25,
	) );

	// ─────────────────────────────────────────────────────────
	// 1. ANNOUNCEMENT & MARQUEE BAR
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_marquee_section', array(
		'title'    => __( '📢 Announcement & Marquee Bar', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'aurika_show_marquee', array(
		'default'           => true,
		'sanitize_callback' => 'aurika_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'aurika_show_marquee', array(
		'label'    => __( 'Show Announcement Bar', 'aurika' ),
		'section'  => 'aurika_marquee_section',
		'type'     => 'checkbox',
	) );

	$wp_customize->add_setting( 'aurika_marquee_text', array(
		'default'           => 'HAND-POURED SCENTED CANDLES & DIY ART KITS • FREE SHIPPING ON ORDERS ABOVE RS. 5,000 • AURIKA — WHERE ART MEETS ELEGANCE • CASH ON DELIVERY AVAILABLE',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'aurika_marquee_text', array(
		'label'       => __( 'Marquee / Ticker Text', 'aurika' ),
		'description' => __( 'Separate items with bullet (•) or dashes.', 'aurika' ),
		'section'     => 'aurika_marquee_section',
		'type'        => 'textarea',
	) );

	// ─────────────────────────────────────────────────────────
	// 2. HERO SLIDER & CALL TO ACTION
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_hero_section', array(
		'title'    => __( '🖼️ Hero Slider & Banners', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 20,
	) );

	$wp_customize->add_setting( 'aurika_hero_slide1_img', array(
		'default'           => get_template_directory_uri() . '/assets/images/hero-aurika.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aurika_hero_slide1_img', array(
		'label'    => __( 'Slide 1 Background Image', 'aurika' ),
		'section'  => 'aurika_hero_section',
	) ) );

	$wp_customize->add_setting( 'aurika_hero_slide2_img', array(
		'default'           => get_template_directory_uri() . '/assets/images/hero-aurika.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aurika_hero_slide2_img', array(
		'label'    => __( 'Slide 2 Background Image', 'aurika' ),
		'section'  => 'aurika_hero_section',
	) ) );

	$wp_customize->add_setting( 'aurika_hero_btn_text', array(
		'default'           => 'SHOP NOW',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'aurika_hero_btn_text', array(
		'label'    => __( 'Hero Button Text', 'aurika' ),
		'section'  => 'aurika_hero_section',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'aurika_hero_btn_url', array(
		'default'           => '/shop',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'aurika_hero_btn_url', array(
		'label'    => __( 'Hero Button Link', 'aurika' ),
		'section'  => 'aurika_hero_section',
		'type'     => 'url',
	) );

	// ─────────────────────────────────────────────────────────
	// 3. TRUST & VALUE STRIP (4 Badges)
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_trust_section', array(
		'title'    => __( '🛡️ Trust & Guarantee Strip', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 30,
	) );

	// Item 1
	$wp_customize->add_setting( 'aurika_trust1_title', array( 'default' => 'Free Shipping > Rs. 5k', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust1_title', array( 'label' => __( 'Badge 1: Title', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_trust1_desc', array( 'default' => 'Rs. 350 flat rate under Rs. 5,000', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust1_desc', array( 'label' => __( 'Badge 1: Subtitle', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );

	// Item 2
	$wp_customize->add_setting( 'aurika_trust2_title', array( 'default' => 'Cash on Delivery', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust2_title', array( 'label' => __( 'Badge 2: Title', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_trust2_desc', array( 'default' => 'Available across Pakistan', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust2_desc', array( 'label' => __( 'Badge 2: Subtitle', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );

	// Item 3
	$wp_customize->add_setting( 'aurika_trust3_title', array( 'default' => '100% Handcrafted', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust3_title', array( 'label' => __( 'Badge 3: Title', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_trust3_desc', array( 'default' => 'Natural wax & lead-free wicks', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust3_desc', array( 'label' => __( 'Badge 3: Subtitle', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );

	// Item 4
	$wp_customize->add_setting( 'aurika_trust4_title', array( 'default' => 'Safe & Verified', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust4_title', array( 'label' => __( 'Badge 4: Title', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_trust4_desc', array( 'default' => 'Damage replacement guarantee', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trust4_desc', array( 'label' => __( 'Badge 4: Subtitle', 'aurika' ), 'section' => 'aurika_trust_section', 'type' => 'text' ) );

	// ─────────────────────────────────────────────────────────
	// 4. DIY ART KIT SHOWCASE SECTION
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_diy_section', array(
		'title'    => __( '🎨 DIY Art Kit Showcase', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 40,
	) );

	$wp_customize->add_setting( 'aurika_diy_eyebrow', array( 'default' => 'FOR THE LITTLE CREATORS', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_eyebrow', array( 'label' => __( 'Eyebrow', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_title', array( 'default' => 'Aurika DIY Art Kit', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_title', array( 'label' => __( 'Main Title', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_subtitle', array( 'default' => 'Paint & Create for Kids', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_subtitle', array( 'label' => __( 'Subtitle', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_desc', array(
		'default'           => 'Less screen time, more creativity! A hands-on paint-your-own kit with fun concrete shapes (animals, planes, rainbows & more) — perfect for screen-free playtime, birthdays, or gifting.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'aurika_diy_desc', array( 'label' => __( 'Description Paragraph', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'aurika_diy_point1', array( 'default' => 'Suitable for ages 3–12 (and creative adults!)', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_point1', array( 'label' => __( 'Checklist Item 1', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_point2', array( 'default' => 'Complete set: concrete toys, paints & brushes', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_point2', array( 'label' => __( 'Checklist Item 2', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_point3', array( 'default' => '100% child-safe, non-toxic washable paints', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_point3', array( 'label' => __( 'Checklist Item 3', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_point4', array( 'default' => 'Screen-free activity — perfect for gifts & birthdays', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_point4', array( 'label' => __( 'Checklist Item 4', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_img', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aurika_diy_img', array(
		'label'       => __( 'Custom DIY Section Image', 'aurika' ),
		'description' => __( 'Leave empty to use default photo.', 'aurika' ),
		'section'     => 'aurika_diy_section',
	) ) );

	$wp_customize->add_setting( 'aurika_diy_btn_text', array( 'default' => 'EXPLORE DIY KITS →', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_diy_btn_text', array( 'label' => __( 'Button Text', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_diy_btn_url', array( 'default' => '/product-category/diy-art-kits', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'aurika_diy_btn_url', array( 'label' => __( 'Button Link', 'aurika' ), 'section' => 'aurika_diy_section', 'type' => 'url' ) );

	// ─────────────────────────────────────────────────────────
	// 5. LAUNCH OFFER & PROMOTION BANNER
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_offer_section', array(
		'title'    => __( '🎉 Promotional / Offer Banner', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'aurika_offer_tag', array( 'default' => 'LAUNCH PROMOTION', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_offer_tag', array( 'label' => __( 'Banner Tag Badge', 'aurika' ), 'section' => 'aurika_offer_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_offer_title', array( 'default' => 'Free Shipping on Orders Above Rs. 5,000', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_offer_title', array( 'label' => __( 'Banner Heading', 'aurika' ), 'section' => 'aurika_offer_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_offer_desc', array(
		'default'           => 'Rs. 350 flat rate delivery on orders under Rs. 5,000 across Pakistan • Cash on Delivery Available.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'aurika_offer_desc', array( 'label' => __( 'Banner Subtitle', 'aurika' ), 'section' => 'aurika_offer_section', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'aurika_offer_btn_text', array( 'default' => 'SHOP NOW →', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_offer_btn_text', array( 'label' => __( 'Button Text', 'aurika' ), 'section' => 'aurika_offer_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_offer_btn_url', array( 'default' => '/shop', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'aurika_offer_btn_url', array( 'label' => __( 'Button Link', 'aurika' ), 'section' => 'aurika_offer_section', 'type' => 'url' ) );

	// ─────────────────────────────────────────────────────────
	// 6. TESTIMONIALS ("HAPPY CUSTOMERS")
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_reviews_section', array(
		'title'    => __( '💬 Testimonials (Happy Customers)', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 60,
	) );

	$wp_customize->add_setting( 'aurika_reviews_eyebrow', array( 'default' => 'TESTIMONIALS', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_reviews_eyebrow', array( 'label' => __( 'Section Eyebrow', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_reviews_title', array( 'default' => 'HAPPY CUSTOMERS', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_reviews_title', array( 'label' => __( 'Section Title', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	// Review 1
	$wp_customize->add_setting( 'aurika_rev1_quote', array( 'default' => 'The Lavender candle fills my entire living room within minutes. Burns clean with zero black smoke. The packaging was so aesthetic — will definitely order more as gifts!', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aurika_rev1_quote', array( 'label' => __( 'Review 1 Quote', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'aurika_rev1_author', array( 'default' => 'Ayesha Khan', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev1_author', array( 'label' => __( 'Review 1 Author', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_rev1_product', array( 'default' => 'Lavender Scented Candle', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev1_product', array( 'label' => __( 'Review 1 Product', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	// Review 2
	$wp_customize->add_setting( 'aurika_rev2_quote', array( 'default' => 'Ordered the Sea Shell candle set with tray. The ocean breeze notes are refreshing and long-lasting. Looked exactly like the photos and delivered safely in 2 days.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aurika_rev2_quote', array( 'label' => __( 'Review 2 Quote', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'aurika_rev2_author', array( 'default' => 'Sara Malik', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev2_author', array( 'label' => __( 'Review 2 Author', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_rev2_product', array( 'default' => 'Sea Shell Ocean Candle with Tray', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev2_product', array( 'label' => __( 'Review 2 Product', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	// Review 3
	$wp_customize->add_setting( 'aurika_rev3_quote', array( 'default' => 'Got the 8-toy DIY kit for my daughter. She was completely screen-free for the entire weekend painting each figure. Safe materials and vibrant paints. 10/10 recommend!', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aurika_rev3_quote', array( 'label' => __( 'Review 3 Quote', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'aurika_rev3_author', array( 'default' => 'Fatima Riaz', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev3_author', array( 'label' => __( 'Review 3 Author', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_rev3_product', array( 'default' => 'DIY Art Kit (8 Concrete Toys)', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev3_product', array( 'label' => __( 'Review 3 Product', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	// ─────────────────────────────────────────────────────────
	// 7. CONTACT & SOCIAL MEDIA
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_contact_section', array(
		'title'    => __( '📞 Contact Info & Social Links', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 70,
	) );

	$wp_customize->add_setting( 'aurika_whatsapp', array( 'default' => '+92 321 1632101', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_whatsapp', array( 'label' => __( 'WhatsApp Number', 'aurika' ), 'description' => __( 'Displayed in header, contact page, and footer.', 'aurika' ), 'section' => 'aurika_contact_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_email', array( 'default' => 'aurikaofficial25@gmail.com', 'sanitize_callback' => 'sanitize_email' ) );
	$wp_customize->add_control( 'aurika_email', array( 'label' => __( 'Official Contact Email', 'aurika' ), 'section' => 'aurika_contact_section', 'type' => 'email' ) );

	$wp_customize->add_setting( 'aurika_phone', array( 'default' => '+92 321 1632101', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_phone', array( 'label' => __( 'Phone Number', 'aurika' ), 'section' => 'aurika_contact_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_address', array( 'default' => 'Jamshoro / Karachi, Pakistan', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_address', array( 'label' => __( 'Office / Studio Location', 'aurika' ), 'section' => 'aurika_contact_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_instagram', array( 'default' => 'https://instagram.com/aurikaofficial', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'aurika_instagram', array( 'label' => __( 'Instagram Profile URL', 'aurika' ), 'section' => 'aurika_contact_section', 'type' => 'url' ) );

	$wp_customize->add_setting( 'aurika_facebook', array( 'default' => 'https://facebook.com/aurikaofficial', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'aurika_facebook', array( 'label' => __( 'Facebook Page URL', 'aurika' ), 'section' => 'aurika_contact_section', 'type' => 'url' ) );

	$wp_customize->add_setting( 'aurika_tiktok', array( 'default' => 'https://tiktok.com/@aurikaofficial', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'aurika_tiktok', array( 'label' => __( 'TikTok Profile URL', 'aurika' ), 'section' => 'aurika_contact_section', 'type' => 'url' ) );

	// ─────────────────────────────────────────────────────────
	// 8. FOOTER SETTINGS
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_footer_section', array(
		'title'    => __( '📄 Footer Settings', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 80,
	) );

	$wp_customize->add_setting( 'aurika_footer_brand_desc', array(
		'default'           => 'We create candles that bring warmth, calm, and a touch of elegance to every home — with pure craftsmanship and meaningful care.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'aurika_footer_brand_desc', array( 'label' => __( 'Footer Brand Description', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'aurika_footer_shipping_cod', array( 'default' => '💳 Cash on Delivery Nationwide', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_shipping_cod', array( 'label' => __( 'Shipping Note 1', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_footer_shipping_free', array( 'default' => '🚚 Free delivery on orders above Rs. 5,000', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_shipping_free', array( 'label' => __( 'Shipping Note 2', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_footer_shipping_flat', array( 'default' => '📦 Rs. 350 flat shipping under Rs. 5,000', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_shipping_flat', array( 'label' => __( 'Shipping Note 3', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_footer_copyright', array(
		'default'           => 'Copyright © ' . date('Y') . ' Aurika — Where Art Meets Elegance. All Rights Reserved.',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'aurika_footer_copyright', array( 'label' => __( 'Copyright Text', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_footer_bic_text', array( 'default' => 'Incubated at BIC MUET Jamshoro', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_bic_text', array( 'label' => __( 'Incubation Badge Text', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );

	// ─────────────────────────────────────────────────────────
	// 9. SEO & SOCIAL SHARING (OPEN GRAPH)
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_seo_section', array(
		'title'    => __( '🔍 SEO & Social Media Previews', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 90,
	) );

	$wp_customize->add_setting( 'aurika_seo_meta_desc', array(
		'default'           => 'Discover Aurika — handcrafted luxury scented candles, aromatherapy candles, and screen-free DIY art kits for children in Pakistan. Cash on Delivery nationwide.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'aurika_seo_meta_desc', array(
		'label'       => __( 'Homepage Meta Description (Google)', 'aurika' ),
		'description' => __( 'Displayed in Google search results (recommended 140–160 characters).', 'aurika' ),
		'section'     => 'aurika_seo_section',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'aurika_seo_meta_keywords', array(
		'default'           => 'scented candles pakistan, hand poured candles, aromatherapy candles, DIY art kits for kids, luxury home decor, scented candle online shopping, cash on delivery candles pakistan, Aurika',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'aurika_seo_meta_keywords', array(
		'label'       => __( 'Meta Keywords (Comma separated)', 'aurika' ),
		'section'     => 'aurika_seo_section',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'aurika_seo_og_image', array(
		'default'           => get_template_directory_uri() . '/assets/images/hero-aurika.jpg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aurika_seo_og_image', array(
		'label'       => __( 'Default Social Share Image (WhatsApp / Facebook)', 'aurika' ),
		'description' => __( 'Preview image shown when someone shares your link on WhatsApp, Facebook, or Twitter. Minimum 1200x630px recommended.', 'aurika' ),
		'section'     => 'aurika_seo_section',
	) ) );

	$wp_customize->add_setting( 'aurika_seo_schema_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'aurika_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'aurika_seo_schema_enabled', array(
		'label'    => __( 'Enable Google Rich Snippets (Schema.org JSON-LD)', 'aurika' ),
		'section'  => 'aurika_seo_section',
		'type'     => 'checkbox',
	) );

	// ─────────────────────────────────────────────────────────
	// 10. HOMEPAGE SECTIONS
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_homepage_section', array(
		'title'    => __( '🏠 Homepage Sections', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 35,
	) );
	
	$wp_customize->add_setting( 'aurika_trending_title', array( 'default' => 'TRENDING NOW', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trending_title', array( 'label' => __( 'Trending Title', 'aurika' ), 'section' => 'aurika_homepage_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_trending_subtitle', array( 'default' => 'Our Most-Loved Creations', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trending_subtitle', array( 'label' => __( 'Trending Subtitle', 'aurika' ), 'section' => 'aurika_homepage_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_trending_count', array( 'default' => 8, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'aurika_trending_count', array( 'label' => __( 'Trending Count', 'aurika' ), 'description' => __( 'Number of trending products to show', 'aurika' ), 'section' => 'aurika_homepage_section', 'type' => 'number' ) );
	
	$wp_customize->add_setting( 'aurika_trending_btn_text', array( 'default' => 'View All Products →', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_trending_btn_text', array( 'label' => __( 'Trending Button Text', 'aurika' ), 'section' => 'aurika_homepage_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_category_title', array( 'default' => 'SHOP BY CATEGORY', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_category_title', array( 'label' => __( 'Category Title', 'aurika' ), 'section' => 'aurika_homepage_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_category_subtitle', array( 'default' => 'Explore Our Collections', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_category_subtitle', array( 'label' => __( 'Category Subtitle', 'aurika' ), 'section' => 'aurika_homepage_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_show_category_section', array( 'default' => true, 'sanitize_callback' => 'aurika_sanitize_checkbox' ) );
	$wp_customize->add_control( 'aurika_show_category_section', array( 'label' => __( 'Show Category Section', 'aurika' ), 'section' => 'aurika_homepage_section', 'type' => 'checkbox' ) );

	// ─────────────────────────────────────────────────────────
	// 11. REVIEWS 4, 5, 6
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'aurika_rev4_quote', array( 'default' => 'The peony shape is a work of art. The subtle rose and lily scent is calming without giving a headache. Fast delivery and lovely unboxing experience.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aurika_rev4_quote', array( 'label' => __( 'Review 4 Quote', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'aurika_rev4_author', array( 'default' => 'Zainab Ali', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev4_author', array( 'label' => __( 'Review 4 Author', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_rev4_product', array( 'default' => 'Peony Flower Scented Candle', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev4_product', array( 'label' => __( 'Review 4 Product', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_rev5_quote', array( 'default' => 'Ordered 3 candles with the bundle discount. Arrived in protective packaging with zero damage. Premium wax quality and long burn hours. Very happy!', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aurika_rev5_quote', array( 'label' => __( 'Review 5 Quote', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'aurika_rev5_author', array( 'default' => 'Bilal Sheikh', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev5_author', array( 'label' => __( 'Review 5 Author', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_rev5_product', array( 'default' => 'Rose Heart Candle (Pack of 3)', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev5_product', array( 'label' => __( 'Review 5 Product', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_rev6_quote', array( 'default' => 'From the craftsmanship to the fragrance intensity, Aurika matches top international brands. Proud to support a local Pakistani brand doing luxury right.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aurika_rev6_quote', array( 'label' => __( 'Review 6 Quote', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'aurika_rev6_author', array( 'default' => 'Hina Tariq', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev6_author', array( 'label' => __( 'Review 6 Author', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'aurika_rev6_product', array( 'default' => 'Daisy Flower Scented Candle', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_rev6_product', array( 'label' => __( 'Review 6 Product', 'aurika' ), 'section' => 'aurika_reviews_section', 'type' => 'text' ) );

	// ─────────────────────────────────────────────────────────
	// 12. PRODUCT PAGE DEFAULTS
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_product_defaults_section', array(
		'title'    => __( '📦 Product Page Defaults', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 45,
	) );
	
	$wp_customize->add_setting( 'aurika_product_tagline_fallback', array( 'default' => 'Hand-poured Luxury Home Fragrance', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_product_tagline_fallback', array( 'label' => __( 'Tagline Fallback', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_product_stock_text', array( 'default' => 'In Stock — Handcrafted in Pakistan', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_product_stock_text', array( 'label' => __( 'Stock Text', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'text' ) );
	
	// ── Product Image Fit & Cropping Control ──
	$wp_customize->add_setting( 'aurika_product_image_fit', array(
		'default'           => 'contain',
		'sanitize_callback' => 'sanitize_key',
	) );
	$wp_customize->add_control( 'aurika_product_image_fit', array(
		'label'       => __( '🖼️ Main Product Image Fit', 'aurika' ),
		'description' => __( 'Choose "Full Image / No Crop" so the entire candle is visible inside the frame without edges being cut off.', 'aurika' ),
		'section'     => 'aurika_product_defaults_section',
		'type'        => 'select',
		'choices'     => array(
			'contain' => __( 'Full Image / No Crop (contain) – Recommended', 'aurika' ),
			'cover'   => __( 'Fill Entire Frame (cover – may crop edges)', 'aurika' ),
		),
	) );
	
	// ── Candle Accordion Defaults ──
	$wp_customize->add_setting( 'aurika_accordion_details', array( 'default' => '<ul><li><strong>Craftsmanship:</strong> Hand-Poured in Pakistan</li><li><strong>Burn Time:</strong> Approx. 28–40 Hours</li><li><strong>Wax:</strong> Premium Natural Wax Blend</li><li><strong>Wick:</strong> 100% Natural Cotton Wick (Lead-Free)</li><li><strong>Safety:</strong> Non-Toxic, Clean Burning</li></ul>', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_accordion_details', array( 'label' => __( '🕯️ Candle – Product Details (HTML)', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_accordion_disclaimer', array( 'default' => 'Every candle is individually hand-poured. Minor variations in appearance, texture, and color are authentic signs of handmade craftsmanship. Keep burning candles away from drafts, flammable materials, children, and pets.', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_accordion_disclaimer', array( 'label' => __( '🕯️ Candle – Disclaimer', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_accordion_howto', array( 'default' => '<ul><li>Trim wick to 1/4 inch (5mm) before lighting every time.</li><li>Allow the entire top layer of wax to melt to the edges on first burn (2–3 hours).</li><li>Do not burn for longer than 4 hours in a single session.</li><li>Place on a heat-resistant, flat surface.</li></ul>', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_accordion_howto', array( 'label' => __( '🕯️ Candle – How to Use (HTML)', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_accordion_shipping', array( 'default' => '<ul><li><strong>Free Delivery:</strong> On all orders above Rs. 5,000.</li><li><strong>Flat Shipping:</strong> Rs. 350 on orders under Rs. 5,000 across Pakistan.</li><li><strong>Payment:</strong> Cash on Delivery (COD) available nationwide.</li><li><strong>Exchange Policy:</strong> Exchange or return within 7 days of delivery. Product must be unused and in original packaging. Share unboxing video on WhatsApp for damage claims.</li></ul>', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_accordion_shipping', array( 'label' => __( '🕯️ Candle – Shipping & Exchange (HTML)', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	// ── DIY Art Kit Accordion Defaults ──
	$wp_customize->add_setting( 'aurika_diy_accordion_details', array( 'default' => '<ul class="product-details-list"><li><strong>Includes:</strong> Concrete Toys + Acrylic Paints + Detail Brush</li><li><strong>Ages:</strong> 3–12 Years</li><li><strong>Material:</strong> Non-Toxic Concrete & Acrylic Paint</li><li><strong>Finish:</strong> Hand-Painted by Your Little Artist</li><li><strong>Safety:</strong> Child-Safe, Non-Toxic Materials</li></ul>', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_diy_accordion_details', array( 'label' => __( '🎨 DIY Kit – Product Details (HTML)', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_diy_accordion_disclaimer', array( 'default' => 'Every DIY Art Kit is individually handcrafted. Minor variations in shape, texture, and size are natural characteristics of handmade concrete toys. Acrylic paints included are non-toxic and child-safe. Adult supervision recommended for children under 5.', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_diy_accordion_disclaimer', array( 'label' => __( '🎨 DIY Kit – Disclaimer', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_diy_accordion_howto', array( 'default' => '<ul class="product-details-list"><li>Lay newspaper or plastic sheet on work surface to protect it.</li><li>Paint one toy at a time using the included detail brush.</li><li>Allow 15–20 minutes drying time between coats.</li><li>For best results, apply 2 thin coats of paint.</li><li>Wash brush with water immediately after use.</li></ul>', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_diy_accordion_howto', array( 'label' => __( '🎨 DIY Kit – How to Use (HTML)', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_diy_accordion_shipping', array( 'default' => '<ul class="product-details-list"><li><strong>Free Delivery:</strong> On all orders above Rs. 5,000.</li><li><strong>Flat Shipping:</strong> Rs. 350 on orders under Rs. 5,000 across Pakistan.</li><li><strong>Payment:</strong> Cash on Delivery (COD) available nationwide.</li><li><strong>Exchange Policy:</strong> Exchange or return within 7 days of delivery. Product must be unused and in original packaging. Share unboxing video on WhatsApp for damage claims.</li></ul>', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'aurika_diy_accordion_shipping', array( 'label' => __( '🎨 DIY Kit – Shipping & Exchange (HTML)', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_related_title', array( 'default' => 'You May Also Like', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_related_title', array( 'label' => __( 'Related Title', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_related_subtitle', array( 'default' => 'Handpicked pairings for your space', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_related_subtitle', array( 'label' => __( 'Related Subtitle', 'aurika' ), 'section' => 'aurika_product_defaults_section', 'type' => 'text' ) );

	// ─────────────────────────────────────────────────────────
	// 13. SHOP & ARCHIVE PAGE
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_shop_section', array(
		'title'    => __( '🛒 Shop & Archive Page', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 55,
	) );
	
	$wp_customize->add_setting( 'aurika_shop_title', array( 'default' => 'The Aurika Collection', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_shop_title', array( 'label' => __( 'Shop Title', 'aurika' ), 'section' => 'aurika_shop_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_shop_subtitle', array( 'default' => 'Hand-poured Scented Candles & DIY Art Kits', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_shop_subtitle', array( 'label' => __( 'Shop Subtitle', 'aurika' ), 'section' => 'aurika_shop_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_shop_hero_eyebrow', array( 'default' => 'WHERE ART MEETS ELEGANCE', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_shop_hero_eyebrow', array( 'label' => __( 'Hero Eyebrow', 'aurika' ), 'section' => 'aurika_shop_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_card_image_fit', array(
		'default'           => 'cover',
		'sanitize_callback' => 'sanitize_key',
	) );
	$wp_customize->add_control( 'aurika_card_image_fit', array(
		'label'       => __( '🖼️ Product Card Image Fit (Shop Grid)', 'aurika' ),
		'description' => __( 'Choose how product images fit in the shop catalog cards.', 'aurika' ),
		'section'     => 'aurika_shop_section',
		'type'        => 'select',
		'choices'     => array(
			'cover'   => __( 'Fill Frame (cover – uniform grid)', 'aurika' ),
			'contain' => __( 'Full Image (contain – never crop candle)', 'aurika' ),
		),
	) );

	// ─────────────────────────────────────────────────────────
	// 14. PAGE DEFAULTS
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_page_defaults_section', array(
		'title'    => __( '📄 Page Hero & Defaults', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 65,
	) );
	
	$wp_customize->add_setting( 'aurika_page_hero_eyebrow', array( 'default' => 'AURIKA OFFICIAL', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_page_hero_eyebrow', array( 'label' => __( 'Page Hero Eyebrow', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_page_hero_tagline', array( 'default' => 'Where Art Meets Elegance.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_page_hero_tagline', array( 'label' => __( 'Page Hero Tagline', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_about_cta_title', array( 'default' => 'Experience Aurika', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_about_cta_title', array( 'label' => __( 'About CTA Title', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_about_cta_desc', array( 'default' => 'Discover our hand-poured candles and creative DIY art kits.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_about_cta_desc', array( 'label' => __( 'About CTA Desc', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_about_cta_btn', array( 'default' => 'EXPLORE OUR COLLECTION →', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_about_cta_btn', array( 'label' => __( 'About CTA Button', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_404_title', array( 'default' => 'Page Not Found', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_404_title', array( 'label' => __( '404 Title', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_404_message', array( 'default' => 'The page you\'re looking for doesn\'t exist. Let\'s get you back home.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aurika_404_message', array( 'label' => __( '404 Message', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'textarea' ) );
	
	$wp_customize->add_setting( 'aurika_404_btn_text', array( 'default' => 'Back to Home', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_404_btn_text', array( 'label' => __( '404 Button Text', 'aurika' ), 'section' => 'aurika_page_defaults_section', 'type' => 'text' ) );

	// ─────────────────────────────────────────────────────────
	// 15. FOOTER COLUMN HEADINGS
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'aurika_footer_col1_heading', array( 'default' => 'Get in Touch', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_col1_heading', array( 'label' => __( 'Column 1 Heading', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_footer_col2_heading', array( 'default' => 'Quick Links', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_col2_heading', array( 'label' => __( 'Column 2 Heading', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_footer_col3_heading', array( 'default' => 'Information', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_col3_heading', array( 'label' => __( 'Column 3 Heading', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_footer_col4_heading', array( 'default' => 'Customer Service', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_col4_heading', array( 'label' => __( 'Column 4 Heading', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_footer_col5_heading', array( 'default' => 'Newsletter', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_col5_heading', array( 'label' => __( 'Column 5 Heading', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_footer_newsletter_desc', array( 'default' => 'Receive our latest updates, offers, and new product launches.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_newsletter_desc', array( 'label' => __( 'Newsletter Desc', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'aurika_footer_newsletter_btn', array( 'default' => 'Subscribe', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_footer_newsletter_btn', array( 'label' => __( 'Newsletter Button', 'aurika' ), 'section' => 'aurika_footer_section', 'type' => 'text' ) );

	// ─────────────────────────────────────────────────────────
	// 16. PRICING & SHIPPING RULES
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_pricing_section', array(
		'title'    => __( '💰 Pricing & Shipping Rules', 'aurika' ),
		'panel'    => 'aurika_theme_panel',
		'priority' => 75,
	) );
	
	$wp_customize->add_setting( 'aurika_pack2_discount', array( 'default' => 10, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'aurika_pack2_discount', array( 'label' => __( 'Pack of 2 Discount %', 'aurika' ), 'section' => 'aurika_pricing_section', 'type' => 'number' ) );
	
	$wp_customize->add_setting( 'aurika_pack3_discount', array( 'default' => 15, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'aurika_pack3_discount', array( 'label' => __( 'Pack of 3 Discount %', 'aurika' ), 'section' => 'aurika_pricing_section', 'type' => 'number' ) );
	
	$wp_customize->add_setting( 'aurika_diy_small_price', array( 'default' => 299, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'aurika_diy_small_price', array( 'label' => __( 'DIY Small Kit Price (Rs.)', 'aurika' ), 'section' => 'aurika_pricing_section', 'type' => 'number' ) );
	
	$wp_customize->add_setting( 'aurika_diy_big_price', array( 'default' => 599, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'aurika_diy_big_price', array( 'label' => __( 'DIY Big Kit Price (Rs.)', 'aurika' ), 'section' => 'aurika_pricing_section', 'type' => 'number' ) );
	
	$wp_customize->add_setting( 'aurika_shipping_free_threshold', array( 'default' => 5000, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'aurika_shipping_free_threshold', array( 'label' => __( 'Free Shipping Threshold (Rs.)', 'aurika' ), 'section' => 'aurika_pricing_section', 'type' => 'number' ) );
	
	$wp_customize->add_setting( 'aurika_shipping_flat_rate', array( 'default' => 350, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'aurika_shipping_flat_rate', array( 'label' => __( 'Flat Rate Shipping Cost (Rs.)', 'aurika' ), 'section' => 'aurika_pricing_section', 'type' => 'number' ) );

	// ─────────────────────────────────────────────────────────
	// 17. SEARCH & MENU SETTINGS
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'aurika_search_placeholder', array( 'default' => 'Search candles, art kits...', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aurika_search_placeholder', array( 'label' => __( 'Search Placeholder', 'aurika' ), 'section' => 'aurika_marquee_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'aurika_use_custom_menu', array( 'default' => false, 'sanitize_callback' => 'aurika_sanitize_checkbox' ) );
	$wp_customize->add_control( 'aurika_use_custom_menu', array(
		'label'       => __( 'Enable Custom Menu from WP Menus', 'aurika' ),
		'description' => __( 'When unchecked, displays the official Aurika luxury 6-link navigation (Home, Shop, Candles, DIY Kits, About, Contact). Check this ONLY if you created and assigned a custom menu in Appearance → Menus.', 'aurika' ),
		'section'     => 'aurika_marquee_section',
		'type'        => 'checkbox',
	) );

	// ─────────────────────────────────────────────────────────
	// 18. GOOGLE ANALYTICS & PERFORMANCE TRACKING
	// ─────────────────────────────────────────────────────────
	$wp_customize->add_section( 'aurika_analytics_section', array(
		'title'       => __( '📈 Google Analytics & Tracking', 'aurika' ),
		'panel'       => 'aurika_theme_panel',
		'priority'    => 92,
		'description' => __( 'Connect Google Analytics (GA4) to track real-time visitors, user acquisition channels, and WooCommerce sales performance.', 'aurika' ),
	) );

	$wp_customize->add_setting( 'aurika_ga4_measurement_id', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'aurika_ga4_measurement_id', array(
		'label'       => __( 'Google Analytics 4 Measurement ID', 'aurika' ),
		'description' => __( 'Enter your GA4 Measurement ID (format: G-XXXXXXXXXX). Found in Google Analytics → Admin → Data Streams.', 'aurika' ),
		'section'     => 'aurika_analytics_section',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'aurika_ga4_track_ecommerce', array(
		'default'           => true,
		'sanitize_callback' => 'aurika_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'aurika_ga4_track_ecommerce', array(
		'label'       => __( 'Enable Enhanced eCommerce Tracking', 'aurika' ),
		'description' => __( 'Automatically sends WooCommerce events (view_item, add_to_cart, begin_checkout, and purchase with revenue) to Google Analytics.', 'aurika' ),
		'section'     => 'aurika_analytics_section',
		'type'        => 'checkbox',
	) );

	$wp_customize->add_setting( 'aurika_ga4_exclude_admin', array(
		'default'           => true,
		'sanitize_callback' => 'aurika_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'aurika_ga4_exclude_admin', array(
		'label'       => __( 'Exclude Admin & Store Manager Visits', 'aurika' ),
		'description' => __( 'Recommended. Prevents store owners and admins from inflating live visitor counts while managing the website.', 'aurika' ),
		'section'     => 'aurika_analytics_section',
		'type'        => 'checkbox',
	) );

	$wp_customize->add_setting( 'aurika_gtm_id', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'aurika_gtm_id', array(
		'label'       => __( 'Google Tag Manager ID (Optional)', 'aurika' ),
		'description' => __( 'Optional. If you use Google Tag Manager, enter your container ID (format: GTM-XXXXXXX).', 'aurika' ),
		'section'     => 'aurika_analytics_section',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'aurika_custom_head_tracking', array(
		'default'           => '',
		'sanitize_callback' => 'aurika_sanitize_tracking_scripts',
	) );
	$wp_customize->add_control( 'aurika_custom_head_tracking', array(
		'label'       => __( 'Custom Header Scripts (<head>)', 'aurika' ),
		'description' => __( 'Paste any additional tracking codes or pixels (e.g. Meta / Facebook Pixel, TikTok Pixel, Pinterest tag).', 'aurika' ),
		'section'     => 'aurika_analytics_section',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'aurika_custom_body_tracking', array(
		'default'           => '',
		'sanitize_callback' => 'aurika_sanitize_tracking_scripts',
	) );
	$wp_customize->add_control( 'aurika_custom_body_tracking', array(
		'label'       => __( 'Custom Footer Scripts (before </body>)', 'aurika' ),
		'description' => __( 'Paste any conversion scripts or chat widgets that should load at the end of the page.', 'aurika' ),
		'section'     => 'aurika_analytics_section',
		'type'        => 'textarea',
	) );
}

function aurika_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

function aurika_sanitize_tracking_scripts( $code ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		return $code;
	}
	return wp_kses_post( $code );
}
