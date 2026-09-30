<?php get_header(); ?>

<div class="page-hero">
  <div class="container text-center">
    <h1 class="page-hero-title"><?php the_archive_title(); ?></h1>
    <?php the_archive_description( '<p class="page-hero-sub">', '</p>' ); ?>
  </div>
</div>

<main class="blog-page">
  <div class="container">
    <div class="posts-grid">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article class="post-card">
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" class="post-card-img">
              <?php the_post_thumbnail( 'medium_large' ); ?>
            </a>
          <?php endif; ?>
          <div class="post-card-body">
            <span class="post-date"><?php echo get_the_date(); ?></span>
            <h2 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="post-card-excerpt"><?php the_excerpt(); ?></p>
            <a href="<?php the_permalink(); ?>" class="read-more-link">Read More &rarr;</a>
          </div>
        </article>
      <?php endwhile;
      else : ?>
        <p class="no-posts">No posts found.</p>
      <?php endif; ?>
    </div>
    <div class="pagination-wrap">
      <?php the_posts_pagination( [ 'prev_text' => '&larr; Prev', 'next_text' => 'Next &rarr;' ] ); ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>
