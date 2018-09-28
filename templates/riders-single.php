<?php
/**
 * The template for the single rider page
 *
 * It can be overriden
 *
 * @since 2.0.0
 */

get_header(); ?>

<pre>
    <?php print_r( $post ); ?>
</pre>

<div class="container uci-results uci-results-rider">

    <?php if ( $post ) : ?>
        <div id="rider-<?php echo $post->ID; ?>" class="row rider-stats">
            <div class="col-md-4 general">
                <?php the_title('<h1 class="page-title">', '</h1>'); ?>

                <div class="country"><span class="">Nationality:</span><?php echo uci_results_get_country_flag( $post->nat ); ?></div>
            </div>
            
            <?php foreach ( $rider->stats as $slug => $stats ) : ?>
                <?php echo uci_results_stats_info( $slug )->name; ?>
                <div class="rank"><span class="">Ranking:</span> <?php echo $rider->rank->rank; ?></div>
                <div class="col-md-4 championships">
                    <h4>Championships</h4>
    
                    <div class="world-titles"><span class="">World Titles:</span> <?php crm_display_total( $stats->world_champs ); ?></div>
                    <div class="world-cup-titles"><span class="">World Cup Titles:</span> <?php crm_display_total( $stats->world_cup_titles ); ?></div>
                    <div class="superprestige-titles"><span class="">Superprestige Titles:</span> <?php crm_display_total( $stats->superprestige_titles ); ?></div>
                    <div class="bpost-bank-titles"><span class="">Gva/BPost Bank Titles:</span> <?php crm_display_total( $stats->gva_bpost_bank_titles ); ?></div>
                </div>
                <div class="col-md-4 top-results">
                    <h4>Top Results</h4>
    
                    <div class="wins"><span class="">Wins:</span> <?php crm_display_total( $stats->wins ); ?></div>
                    <div class="podiums"><span class="">Podiums:</span> <?php crm_display_total( $stats->podiums ); ?></div>
                    <div class="world-cup-wins"><span class="">World Cup Wins:</span> <?php crm_display_total( $stats->world_cup_wins ); ?></div>
                    <div class="superprestige-wins"><span class="">Superprestige Wins:</span> <?php crm_display_total( $stats->superprestige_wins ); ?></div>
                    <div class="bpost-bank-wins"><span class="">GvA/BPost Bank Wins:</span> <?php crm_display_total( $stats->gva_bpost_bank_wins ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

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
