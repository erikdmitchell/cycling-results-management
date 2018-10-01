<?php
/**
 * The template for races page
 *
 * It can be overriden
 *
 * @since 1.0.0
 */

get_header();

$races = new WP_Query(
    array(
        'posts_per_page' => 15,
        'post_type' => 'races',
        'paged' => get_query_var( 'paged' ),
    )
);
?>

<div class="container">

    <h1 class="page-title">Races</h1>

    <div class="crm-results-races">
        <div class="row header">
            <div class="col-sm-6 race-name">Name</div>
            <div class="col-sm-2 race-date">Date</div>
            <div class="col-sm-1 race-nat">Nat</div>
            <div class="col-sm-1 race-class">Class</div>
        </div>

        <?php if ( $races->posts ) : ?>
            <?php
            while ( $races->have_posts() ) :
                $races->the_post();
                ?>
                <div class="row">
                    <div class="col-sm-6 race-name"><a href="<?php crm_race_url( $post->post_name ); ?>"><?php the_title(); ?></a></div>
                    <div class="col-sm-2 race-date"><?php echo $post->race_date; ?></div>
                    <div class="col-sm-1 race-nat"><?php echo crm_get_country_flag( $post->nat ); ?></div>
                    <div class="col-sm-1 race-class"><?php echo $post->class; ?></div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>

    <?php crm_pagination( $races->max_num_pages ); ?>
</div>

<?php
get_footer();
