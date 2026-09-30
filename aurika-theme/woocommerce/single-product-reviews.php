<?php
/**
 * Display single product reviews (comments)
 *
 * @package Aurika
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
?>

<div id="reviews" class="aurika-product-reviews-wrapper" style="margin-top: 35px; padding-top: 25px; border-top: 1px solid #efeae3;">

	<!-- REVIEWS HEADER & SUMMARY STRIP -->
	<div class="aurika-reviews-header-strip" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; margin-bottom: 18px;">
		<div class="reviews-header-left" style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
			<h3 style="font-family: var(--font-sans); font-size: 20px; font-weight: 700; color: #111; margin: 0;">Customer Reviews</h3>
			<div style="display: flex; align-items: center; gap: 6px;">
				<div style="color: #b8860b; font-size: 16px; letter-spacing: 1px;">★★★★★</div>
				<span style="font-size: 13.5px; font-weight: 600; color: #444;"><?php echo $average ? esc_html( number_format( $average, 1 ) ) : '5.0'; ?> out of 5</span>
				<span style="font-size: 12.5px; color: #888;">(<?php echo esc_html( $review_count ? $review_count . ' reviews' : 'Verified Ratings' ); ?>)</span>
			</div>
		</div>

		<div class="reviews-header-right">
			<button type="button" id="btnToggleReviewForm" style="background: #111; color: #fff; border: none; padding: 10px 22px; border-radius: 50px; font-family: var(--font-sans); font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: all 0.25s ease;">
				✍️ Write a Review
			</button>
		</div>
	</div>

	<!-- REVIEWS LIST -->
	<div id="comments" class="aurika-comments-area">
		<?php if ( have_comments() ) : ?>
			<ol class="commentlist aurika-commentlist" style="list-style: none; padding: 0; margin: 0 0 20px;">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination aurika-reviews-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => '&larr;',
							'next_text' => '&rarr;',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
			<div style="background: #faf8f5; border: 1px solid #efeae3; border-radius: 10px; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
				<span style="font-size: 14px; color: #555;">⭐ No reviews yet. Be the first to share your experience with this handcrafted creation!</span>
			</div>
		<?php endif; ?>
	</div>

	<!-- WRITE REVIEW FORM (COLLAPSIBLE / COMPACT) -->
	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) || is_user_logged_in() || ! get_current_user_id() ) : ?>
		<div id="review_form_wrapper" class="aurika-review-form-card" style="display: none; background: #faf8f5; border: 1px solid #efeae3; border-radius: 12px; padding: 25px 30px; margin-top: 15px;">
			<div id="respond" class="comment-respond">

				<div class="form-header" style="margin-bottom: 16px;">
					<h4 id="reply-title" class="comment-reply-title" style="font-family: var(--font-sans); font-size: 17px; font-weight: 700; color: #111; margin: 0 0 4px;">
						Write a Customer Review
						<?php cancel_comment_reply_link( '<small>' . esc_html__( 'Cancel reply', 'woocommerce' ) . '</small>' ); ?>
					</h4>
					<p style="font-size: 12px; color: #777; margin: 0;">Required fields are marked *</p>
				</div>

				<form action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>" method="post" id="commentform" class="comment-form aurika-luxury-form">

					<!-- STAR RATING INPUT -->
					<?php if ( wc_review_ratings_enabled() ) : ?>
						<div style="margin-bottom: 14px;">
							<label style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #222; display: block; margin-bottom: 6px;">Your Overall Rating <span style="color:#d9534f;">*</span></label>
							<div class="star-rating-selector" id="star-rating-selector" style="display: inline-flex; gap: 4px; font-size: 22px; cursor: pointer; color: #b8860b;">
								<span class="star-pick active" data-value="1">★</span>
								<span class="star-pick active" data-value="2">★</span>
								<span class="star-pick active" data-value="3">★</span>
								<span class="star-pick active" data-value="4">★</span>
								<span class="star-pick active" data-value="5">★</span>
							</div>
							<select name="rating" id="rating" required style="display:none;">
								<option value="5" selected>5</option>
								<option value="4">4</option>
								<option value="3">3</option>
								<option value="2">2</option>
								<option value="1">1</option>
							</select>
						</div>
					<?php endif; ?>

					<!-- REVIEW COMMENT -->
					<div style="margin-bottom: 14px;">
						<label for="comment" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #222; display: block; margin-bottom: 6px;">Your Detailed Review <span style="color:#d9534f;">*</span></label>
						<textarea id="comment" name="comment" cols="45" rows="3" required placeholder="Write what you loved about the fragrance, burn time, craftsmanship, or packaging..." style="width: 100%; padding: 12px 16px; border: 1.5px solid #dfd8cb; border-radius: 8px; font-family: var(--font-sans); font-size: 13.5px; background: #fff;"></textarea>
					</div>

					<!-- AUTHOR FIELDS (FOR GUESTS) -->
					<?php if ( ! is_user_logged_in() ) : ?>
						<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
							<div>
								<label for="author" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #222; display: block; margin-bottom: 6px;">Your Name <span style="color:#d9534f;">*</span></label>
								<input id="author" name="author" type="text" value="" required placeholder="e.g. Ayesha Khan" style="width: 100%; height: 44px; padding: 0 14px; border: 1.5px solid #dfd8cb; border-radius: 8px; font-family: var(--font-sans); font-size: 13.5px; background: #fff;">
							</div>
							<div>
								<label for="email" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #222; display: block; margin-bottom: 6px;">Your Email <span style="color:#d9534f;">*</span></label>
								<input id="email" name="email" type="email" value="" required placeholder="e.g. ayesha@example.com" style="width: 100%; height: 44px; padding: 0 14px; border: 1.5px solid #dfd8cb; border-radius: 8px; font-family: var(--font-sans); font-size: 13.5px; background: #fff;">
							</div>
						</div>
					<?php else : ?>
						<p class="logged-in-as" style="font-size:13px; color:#666; margin-bottom:15px;">
							Logged in as <strong><?php echo esc_html( wp_get_current_user()->display_name ); ?></strong>.
						</p>
					<?php endif; ?>

					<!-- SUBMIT BUTTON -->
					<div>
						<button type="submit" name="submit" id="submit" style="background: #b8860b; color: #fff; border: none; padding: 12px 30px; border-radius: 50px; font-family: var(--font-sans); font-size: 12.5px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; transition: all 0.25s ease;">
							SUBMIT REVIEW &rarr;
						</button>
						<?php comment_id_fields(); ?>
					</div>

					<?php do_action( 'comment_form', $product->get_id() ); ?>
				</form>

			</div>
		</div>
	<?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var toggleBtn = document.getElementById('btnToggleReviewForm');
	var formWrapper = document.getElementById('review_form_wrapper');

	if (toggleBtn && formWrapper) {
		toggleBtn.addEventListener('click', function() {
			if (formWrapper.style.display === 'none' || formWrapper.style.display === '') {
				formWrapper.style.display = 'block';
				formWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
			} else {
				formWrapper.style.display = 'none';
			}
		});
	}

	var stars = document.querySelectorAll('#star-rating-selector .star-pick');
	var select = document.getElementById('rating');

	if (stars.length && select) {
		stars.forEach(function(star, index) {
			star.addEventListener('click', function() {
				var val = parseInt(this.getAttribute('data-value'));
				select.value = val;
				stars.forEach(function(s, i) {
					if (i < val) {
						s.classList.add('active');
						s.style.color = '#b8860b';
					} else {
						s.classList.remove('active');
						s.style.color = '#dfd8cc';
					}
				});
			});
		});
	}
});
</script>

