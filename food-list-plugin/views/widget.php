<!-- This file is used to markup the public-facing widget. -->
<?php
	$cache = wp_cache_get('widget_recent_posts', 'widget');

	if ( !is_array($cache) )
		$cache = array();

	if ( ! isset( $args['widget_id'] ) )
		$args['widget_id'] = $this->id;

	if ( isset( $cache[ $args['widget_id'] ] ) ) {
		echo $cache[ $args['widget_id'] ];
		return;
	}

	ob_start();
	extract($args);

	$title = apply_filters('widget_title', empty($instance['title']) ? __('Food Wish List') : $instance['title'], $instance, $this->id_base);
	if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
		$number = 10;
//	$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
	$show_date = true;

	$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
	if ($r->have_posts()) :
?>
	<?php echo $before_widget; ?>
	<?php if ( $title ) echo $before_title . $title . $after_title; ?>
	<ul class="food-list">
	<?php while ( $r->have_posts() ) : $r->the_post(); ?>
		<li>
		<?php if ( $show_date ) : ?>
			<span class="post-date"><?php echo get_the_date(); ?></span>
			<?php if ( get_the_content() ) the_content(); else echo "No content."; ?>
		<?php endif; ?>
		</li>
	<?php endwhile; ?>
	</ul>
	<?php echo $after_widget; ?>
<?php
	// Reset the global $the_post as this query will have stomped on it
	wp_reset_postdata();

	endif;

	$cache[$args['widget_id']] = ob_get_flush();
	wp_cache_set('widget_recent_posts', $cache, 'widget');
?>
