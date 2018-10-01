<?php
/**
 * The template for the single rider page
 *
 * It can be overriden
 *
 * @since 2.0.0
 */

get_header(); ?>

<div class="container uci-results uci-results-rider">

    <?php if ( $post ) : ?>
        <div id="rider-<?php echo $post->ID; ?>" class="row rider-stats">
            <div class="col-md-4 general">
                <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                <div class="country"><span class="">Nationality:</span><?php echo crm_get_country_flag( $post->nat ); ?></div>
            </div>
        </div>
        
        <div class="row header">
            <div class="col-md-5 race-season">Season</div>
            <div class="col-md-1 race-rank">Rank</div>
            <div class="col-md-1 rider-points">Points</div>
            <div class="col-md-1 rider-wins">Wins</div>
            <div class="col-md-1 race-podiums">Podiums</div>
        </div>
            
        <?php foreach ( $post->rankings as $ranking ) : ?>
            <div class="row">
                <div class="col-md-5 race-season"><?php echo $ranking->season; ?></div>
                <div class="col-md-1 race-rank"><?php echo $ranking->rank; ?></div>
                <div class="col-md-1 rider-points"><?php echo $ranking->points; ?></div>
                <div class="col-md-1 rider-wins"><?php echo $ranking->wins; ?></div>
                <div class="col-md-1 race-podiums"><?php echo $ranking->podiums; ?></div>                
            </div>
        <?php endforeach; ?>

        <?php if ( isset( $post->results ) && count( $post->results ) ) : ?>
            <div class="single-rider-results">
                <h3>Results</h3>

                <div class="row header">
                    <div class="col-md-2 race-date">Date</div>
                    <div class="col-md-5 race-name">Event</div>
                    <div class="col-md-1 rider-place">Place</div>
                    <div class="col-md-1 rider-points">Points</div>
                    <div class="col-md-1 race-class">Class</div>
                    <div class="col-md-2 race-season">Season</div>
                </div>

                <?php foreach ( $post->results as $result ) : ?>
                    <div class="row">
                        <div class="col-md-2 race-date"><?php echo date( get_option( 'date_format' ), strtotime( $result['race_date'] ) ); ?></div>
                        <div class="col-md-5 race-name"><a href="<?php uci_results_race_url( $result['race_id'] ); ?>"><?php echo $result['race_name']; ?></a></div>
                        <div class="col-md-1 rider-place"><?php echo $result['place']; ?></div>
                        <div class="col-md-1 rider-points"><?php echo $result['uci_points']; ?></div>
                        <div class="col-md-1 race-class"><?php echo $result['race_class']; ?></div>
                        <div class="col-md-2 race-season"><?php echo $result['race_season']; ?></div>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php else : ?>
            <div class="none-found">No results.</div>
        <?php endif; ?>

    <?php else : ?>
        <div class="none-found">Rider not found.</div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
