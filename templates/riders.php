<?php
/**
 * template for riders page
 *
 * It can be overriden
 *
 * @since 1.0.0
 */

get_header();

$riders = new WP_Query(
    array(
        'posts_per_page' => 15,
        'post_type' => 'riders',
        'paged' => get_query_var( 'paged' ),
        'orderby' => 'title',
        'order' => 'ASC',
    )
);
?>

<div class="container">

    <h1 class="page-title">Riders</h1>

    <div class="riders">
        <div class="row header">
            <div class="col-4 rider-name">Rider</div>
            <div class="col-1 rider-nat">Nat</div>
        </div>

        <?php if ( $riders->posts ) : ?>
            <?php
            while ( $riders->have_posts() ) :
                $riders->the_post();
                ?>
                <div class="row">
                    <div class="col-4 rider-name"><a href="<?php crm_rider_url( $post->post_name ); ?>"><?php the_title(); ?></a></div>
                    <div class="col-1 rider-nat"><?php echo crm_get_country_flag( $post->nat ); ?></div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <?php crm_pagination( $riders->max_num_pages ); ?>

</div>

<?php
get_footer();
