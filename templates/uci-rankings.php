<?php
/**
 * template for uci rankings page
 *
 * It can be overriden
 *
 * @since 2.0.0
 */

get_header(); ?>

<?php
$rankings = cycling_results_management()->uci_rankings->get_rankings(
    array(
        'order_by' => 'rank',
        'date' => get_query_var( 'rankings_date' ),
        'discipline' => get_query_var( 'rankings_discipline' ),
    )
);
?>
<div class="container uci-results uci-rankings">
    <h1>UCI Rankings <span class="date"><?php echo get_query_var( 'rankings_date' ); ?></span></h1>
    
    <div class="row header">
        <div class="col-sm-2">Rank</div>
        <div class="col-sm-7">Name</div>
        <div class="col-sm-3">Points</div>       
    </div>
        
    <?php foreach ( $rankings as $rank ) : ?>
        <div class="row">
            <div class="col-sm-2"><?php echo $rank->rank; ?></div>
            <div class="col-sm-7"><a href="<?php echo crm_rider_url( $rank->rider_id ); ?>"><?php echo $rank->name; ?></a></div>
            <div class="col-sm-3"><?php echo $rank->points; ?></div>         
        </div>
    <?php endforeach; ?>
</div>

<?php get_footer();
