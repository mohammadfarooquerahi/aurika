<?php get_header(); ?>

<main class="single-post">
  <div class="container container--narrow">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>

        <header class="single-post-header text-center">
          <span class="post-date"><?php echo get_the_date(); ?></span>
          <h1 class="single-post-title"><?php the_title(); ?></h1>
        </header>

        <?php if ( has_post_thumbnail() ) : ?>
          <div class="single-post-thumb">
            <?php the_post_thumbnail( 'large' ); ?>
          </div>
        <?php endif; ?>

        <div class="single-post-content entry-content">
          <?php the_content(); ?>
        </div>

        <div class="post-navigation">
          <?php
          the_post_navigation( [
            'prev_text' => '&larr; %title',
            'next_text' => '%title &rarr;',
          ] );
          ?>
        </div>

      </article>
    <?php endwhile; endif; ?>
  </div>
</main>

<?php get_footer(); ?>
