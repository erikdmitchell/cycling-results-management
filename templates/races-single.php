<?php
/**
 * The template for the single race page
 *
 * It can be overriden
 *
 * @since 1.0.0
 */

get_header(); ?>

<div class="container crm-template crm-single-race">

    <?php if ( ! $post->results || empty( $post->results ) ) : ?>
        <div class="race-results-not-found">Race results not found.</div>
    <?php else : ?>
        <h1 class="page-title"><?php echo $post->post_title; ?><span class="flag"><?php echo uci_results_get_country_flag( $post->nat ); ?></span></h1>

        <div class="row race-details">
            <div class="col-md-2 race-date"><?php echo date( get_option( 'date_format' ), strtotime( $post->race_date ) ); ?></div>
            <div class="col-md-1 race-class">(<?php echo $post->class; ?>)</div>
        </div>

        <div class="single-race">
            <div class="row header">
                    <div class="col-md-1 rider-place">Place</div>
                    <div class="col-md-4 rider-name">Rider</div>
                    <div class="col-md-1 rider-points">Points</div>
                    <div class="col-md-1 rider-nat">Nat</div>
                    <div class="col-md-1 rider-age">Age</div>
                    <div class="col-md-2 rider-time">Time</div>
            </div>

            <?php foreach ( $post->results as $result ) : ?>
                <div class="row rider-results">
                    <div class="col-md-1 rider-place"><?php echo $result['result_place']; ?></div>
                    <div class="col-md-4 rider-name"><a href="<?php echo crm_rider_url( $result['slug'] ); ?>"><?php echo $result['name']; ?></a></div>
                    <div class="col-md-1 rider-points"><?php echo $result['result_uci_points']; ?></div>
                    <div class="col-md-1 rider-nat"><?php echo uci_results_get_country_flag( $result['nat'] ); ?></div>
                    <div class="col-md-1 rider-age"><?php echo $result['result_age']; ?></div>
                    <div class="col-md-2 rider-time"><?php echo $result['result_result']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
