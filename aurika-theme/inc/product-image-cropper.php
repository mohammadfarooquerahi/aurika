<?php
/**
 * Aurika Product Image Cropper & Framing Tool (Admin Integration)
 *
 * Provides a dedicated, fully functional manual cropping and framing tool
 * inside the WooCommerce product edit screen (Add New and Edit Product).
 * Enables merchants to crop images from all sides (top, bottom, left, right,
 * corners), select 1:1 Square, 4:5 Portrait, or Freeform aspect ratios,
 * rotate, and zoom, ensuring all product images have an identical,
 * perfectly uniform luxury card display.
 *
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Enqueue Cropper Scripts and Styles on Product Edit Screens
 */
add_action( 'admin_enqueue_scripts', 'aurika_enqueue_product_cropper_admin_scripts' );
function aurika_enqueue_product_cropper_admin_scripts( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'product' !== $screen->post_type ) {
		return;
	}

	// Media library modal dependencies
	wp_enqueue_media();

	// Cropper.js CSS & Custom Admin Styles
	wp_enqueue_style(
		'aurika-cropperjs',
		get_template_directory_uri() . '/assets/css/cropper.min.css',
		array(),
		'1.6.1'
	);

	wp_enqueue_style(
		'aurika-admin-cropper',
		get_template_directory_uri() . '/assets/css/aurika-admin-cropper.css',
		array( 'aurika-cropperjs' ),
		'1.0.0'
	);

	// Cropper.js Script & Custom Admin JS
	wp_enqueue_script(
		'aurika-cropperjs',
		get_template_directory_uri() . '/assets/js/cropper.min.js',
		array( 'jquery' ),
		'1.6.1',
		true
	);

	wp_enqueue_script(
		'aurika-admin-cropper',
		get_template_directory_uri() . '/assets/js/aurika-admin-cropper.js',
		array( 'jquery', 'aurika-cropperjs' ),
		'1.0.0',
		true
	);

	global $post;
	$current_post_id = ( $post && isset( $post->ID ) ) ? $post->ID : 0;

	wp_localize_script( 'aurika-admin-cropper', 'aurika_cropper_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'aurika_crop_nonce' ),
		'post_id'  => $current_post_id,
	) );
}

/**
 * 2. Register Sidebar Metabox: Product Image Cropper & Framing Tool
 */
add_action( 'add_meta_boxes', 'aurika_register_product_cropper_metabox' );
function aurika_register_product_cropper_metabox() {
	add_meta_box(
		'aurika_product_crop_box',
		__( '✂️ Product Image Cropper & Framing', 'aurika' ),
		'aurika_render_product_crop_metabox',
		'product',
		'side',
		'default'
	);
}

/**
 * Render Sidebar Metabox Content
 */
function aurika_render_product_crop_metabox( $post ) {
	$thumb_id   = get_post_thumbnail_id( $post->ID );
	$has_image  = ! empty( $thumb_id );
	$image_url  = $has_image ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';
	?>
	<div class="aurika-metabox-content">
		<div class="aurika-metabox-tip">
			<strong>Uniform Catalog Framing:</strong> Crop and adjust from all 4 sides (top, bottom, left, right) to center your candle or art kit in Aurika's luxury frame.
		</div>

		<div class="aurika-metabox-preview-box" id="aurika-metabox-preview-box">
			<?php if ( $has_image && $image_url ) : ?>
				<img id="aurika-metabox-preview-img" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>">
			<?php else : ?>
				<span class="no-image-text">📷 No product image set yet.<br>Click below to select and crop.</span>
			<?php endif; ?>
		</div>

		<div id="aurika-metabox-has-image" style="<?php echo $has_image ? '' : 'display:none;'; ?>">
			<button type="button" class="button button-primary aurika-btn-crop" id="aurika-open-crop-metabox">
				<span class="dashicons dashicons-image-crop"></span> ✂️ Crop &amp; Frame Image (All Sides)
			</button>
		</div>

		<div id="aurika-metabox-no-image" style="<?php echo $has_image ? 'display:none;' : ''; ?>">
			<button type="button" class="button button-primary aurika-btn-crop" id="aurika-select-and-crop-metabox">
				<span class="dashicons dashicons-camera"></span> 📷 Select &amp; Crop Product Image
			</button>
		</div>
	</div>
	<?php
}

/**
 * 3. Render Cropper Modal HTML in Admin Footer
 */
add_action( 'admin_footer', 'aurika_render_cropper_modal_html' );
function aurika_render_cropper_modal_html() {
	$screen = get_current_screen();
	if ( ! $screen || 'product' !== $screen->post_type ) {
		return;
	}
	?>
	<!-- Aurika Product Image Cropper Modal -->
	<div class="aurika-cropper-modal-overlay" id="aurika-cropper-modal">
		<div class="aurika-cropper-modal-container">
			
			<!-- Header -->
			<div class="aurika-cropper-modal-header">
				<h3>
					<span>✂️</span>
					Aurika Product Image Framing &amp; Crop Tool
					<span class="gold-tag">• Luxury Catalog Standard</span>
				</h3>
				<button type="button" class="aurika-cropper-modal-close" id="aurika-close-cropper-modal" title="Close Modal">&times;</button>
			</div>

			<!-- Body -->
			<div class="aurika-cropper-modal-body">
				
				<!-- Left Workspace -->
				<div class="aurika-cropper-workspace">
					<div class="aurika-cropper-canvas-wrap">
						<img id="aurika-cropper-image" src="" alt="Crop Workspace">
					</div>

					<!-- Toolbar Controls -->
					<div class="aurika-cropper-toolbar">
						
						<!-- Aspect Ratios -->
						<div class="aurika-ratio-group">
							<span class="aurika-ratio-label">Aspect Ratio:</span>
							<button type="button" class="aurika-tool-btn aurika-ratio-btn is-active" data-ratio="1" title="1:1 Square Catalog Standard">
								■ 1:1 Square (Catalog Standard)
							</button>
							<button type="button" class="aurika-tool-btn aurika-ratio-btn" data-ratio="portrait" title="4:5 Portrait Card Ratio">
								▯ 4:5 Portrait (Card Ratio)
							</button>
							<button type="button" class="aurika-tool-btn aurika-ratio-btn" data-ratio="free" title="Unlock: Crop Any Side Freely">
								⚏ Freeform (All Sides)
							</button>
						</div>

						<!-- Transform Tools -->
						<div class="aurika-tool-group">
							<button type="button" class="aurika-tool-btn" id="aurika-tool-rotate-left" title="Rotate Left 90°">
								↶ Rotate
							</button>
							<button type="button" class="aurika-tool-btn" id="aurika-tool-rotate-right" title="Rotate Right 90°">
								↷ Rotate
							</button>
							<button type="button" class="aurika-tool-btn" id="aurika-tool-zoom-in" title="Zoom In">
								🔍+
							</button>
							<button type="button" class="aurika-tool-btn" id="aurika-tool-zoom-out" title="Zoom Out">
								🔍-
							</button>
							<button type="button" class="aurika-tool-btn" id="aurika-tool-reset" title="Reset Crop Box">
								↺ Reset
							</button>
						</div>

					</div>
				</div>

				<!-- Right Sidebar (Live Preview & Guide) -->
				<div class="aurika-cropper-sidebar">
					<div class="aurika-sidebar-section-title">
						<span>👁️</span> Live Store Catalog Preview
					</div>
					
					<!-- Card Preview Box matching Aurika card ratio -->
					<div class="aurika-preview-frame">
						<div class="aurika-preview-container" id="aurika-cropper-preview-container"></div>
					</div>

					<!-- Crop Stats -->
					<div class="aurika-crop-stats">
						<div class="aurika-crop-stats-row">
							<span>Crop Area:</span>
							<strong id="aurika-crop-dimensions">—</strong>
						</div>
						<div class="aurika-crop-stats-row">
							<span>Catalog Box:</span>
							<strong style="color: #d4af37;">Uniform Size Guaranteed</strong>
						</div>
					</div>

					<!-- Tips -->
					<div class="aurika-crop-tips">
						<strong>Cropping Instructions:</strong>
						<ul>
							<li>Drag any of the <strong>4 edge handles</strong> (top, bottom, left, right) or corners.</li>
							<li>Center the candle or kit inside the frame with balanced margins.</li>
							<li>Click <strong>Save &amp; Apply Crop</strong> to update your product image automatically.</li>
						</ul>
					</div>
				</div>

			</div>

			<!-- Footer -->
			<div class="aurika-cropper-modal-footer">
				<button type="button" class="button aurika-btn-cancel" id="aurika-cancel-crop-btn">
					Cancel
				</button>
				<button type="button" class="button button-primary aurika-btn-save-crop" id="aurika-save-crop-btn">
					<span class="aurika-spinner"></span>
					<span>💾 Save &amp; Apply Crop to Product</span>
				</button>
			</div>

		</div>
	</div>
	<?php
}

/**
 * 4. AJAX Handler: Save Cropped Image & Attach to Product
 */
add_action( 'wp_ajax_aurika_save_cropped_product_image', 'aurika_ajax_save_cropped_product_image' );
function aurika_ajax_save_cropped_product_image() {
	check_ajax_referer( 'aurika_crop_nonce', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( __( 'Permission denied.', 'aurika' ) );
	}

	$post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	$data_url  = isset( $_POST['image_data'] ) ? sanitize_text_field( wp_unslash( $_POST['image_data'] ) ) : '';

	if ( empty( $data_url ) ) {
		wp_send_json_error( __( 'No image data provided.', 'aurika' ) );
	}

	// Decode Base64 data
	if ( ! preg_match( '/^data:image\/(\w+);base64,/', $data_url, $type ) ) {
		wp_send_json_error( __( 'Invalid image format.', 'aurika' ) );
	}

	$data_raw = substr( $data_url, strpos( $data_url, ',' ) + 1 );
	$decoded  = base64_decode( $data_raw );

	if ( false === $decoded ) {
		wp_send_json_error( __( 'Base64 decoding failed.', 'aurika' ) );
	}

	// Generate clean filename
	$post_title = $post_id ? get_the_title( $post_id ) : 'aurika-product';
	$slug       = sanitize_title( $post_title );
	if ( empty( $slug ) ) {
		$slug = 'aurika-product';
	}
	$filename = $slug . '-cropped-' . time() . '.jpg';

	// Upload file into WordPress uploads directory
	$upload = wp_upload_bits( $filename, null, $decoded );

	if ( ! empty( $upload['error'] ) ) {
		wp_send_json_error( $upload['error'] );
	}

	// Prepare attachment data
	$file_path = $upload['file'];
	$file_url  = $upload['url'];
	$file_type = wp_check_filetype( $filename, null );

	$attachment = array(
		'guid'           => $file_url,
		'post_mime_type' => $file_type['type'] ?: 'image/jpeg',
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $file_path, $post_id );

	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		wp_send_json_error( __( 'Could not save attachment to media library.', 'aurika' ) );
	}

	// Generate intermediate image sizes (WooCommerce thumbnail, single, etc.)
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	// Assign as featured thumbnail if post exists
	if ( $post_id > 0 ) {
		set_post_thumbnail( $post_id, $attach_id );
	}

	// Prepare HTML for #postimagediv
	require_once ABSPATH . 'wp-admin/includes/post.php';
	$full_html = _wp_post_thumbnail_html( $attach_id, $post_id );

	wp_send_json_success( array(
		'attachment_id' => $attach_id,
		'image_url'     => $file_url,
		'thumb_html'    => wp_get_attachment_image( $attach_id, 'medium' ),
		'full_html'     => $full_html,
		'message'       => __( 'Product image cropped and set successfully!', 'aurika' ),
	) );
}
