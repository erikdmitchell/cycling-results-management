<?php
/**
 * template for crm rankings page
 *
 * It can be overriden
 *
 * @since 1.0.0
 */

get_header(); ?>

<?php
$rankings = cycling_results_management()->riders->get_riders_rankings(
    array(
        'discipline' => get_query_var( 'crm_rankings_discipline' ),        
        'date' => get_query_var( 'crm_rankings_season' ),        
    )
);
?>
<div class="container crm-rankings">
    <h1>CRM Rankings <span class="date"><?php echo get_query_var( 'crm_rankings_season' ); ?></span></h1>
    
    <div class="row header">
        <div class="col-sm-2">Rank</div>
        <div class="col-sm-7">Name</div>
        <div class="col-sm-3">Points</div>       
    </div>
        
    <?php foreach ( $rankings['riders'] as $rank ) : ?>
        <div class="row">
            <div class="col-sm-2"><?php echo $rank->rank; ?></div>
            <div class="col-sm-7"><a href="<?php echo crm_rider_url( $rank->rider_id ); ?>"><?php echo $rank->name; ?></a></div>
            <div class="col-sm-3"><?php echo $rank->points; ?></div>         
        </div>
    <?php endforeach; ?>
</div>

<?php get_footer();
