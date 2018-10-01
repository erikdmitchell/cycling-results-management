<?php
/**
 * template for main page (via shortcode)
 *
 * It can be overriden
 *
 * @since 1.0.0
 */

$races = crm_get_races(
    array(
        'per_page' => 10,
    )
);
$uci_rankings = cycling_results_management()->uci_rankings->get_rankings(
    array(
        'order_by' => 'rank',
        'discipline' => 'cyclocross',
        'limit' => 10,
    )
);

$crm_rankings = cycling_results_management()->riders->get_riders_rankings(
    array(
        'limit' => 10,
    )
);
?>
<pre>
    <?php
        print_r($crm_rankings);  
    ?>
</pre>
<div class="crm-results-main container">
    
    <div class="row">
        <div class="col-12">
        
            <div class="crm-recent-race-results">
                <h3>Recent Race Results</h3>
        
                <?php if ( count( $races ) ) : ?>
                    <?php foreach ( $races as $race ) : ?>
                        <div class="row">
                            <div class="col-md-6 race-name"><a href="<?php crm_race_url( $race->post_name ); ?>"><?php echo $race->post_title; ?></a></div>
                            <div class="col-md-2 race-date"><?php echo $race->race_date; ?></div>
                            <div class="col-md-2 race-nat"><?php echo crm_get_country_flag( $race->nat ); ?></div>
                            <div class="col-md-2 race-class"><?php echo $race->class; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
        
                <a class="view-all" href="<?php crm_races_url(); ?>">View All Races &raquo;</a>
            </div>
    
        </div>
        
    </div>
    
    <div class="row crm-rankings-wrap">
        
        <div class="col-sm-12 col-md-6">
            
            <h3>UCI Rankings</h3>
            
            <div class="row header">
                <div class="col-sm-2">Rank</div>
                <div class="col-sm-7">Name</div>
                <div class="col-sm-3">Points</div>       
            </div>
        
            <div class="riders-list-wrap">
                <?php if ( count( $uci_rankings ) ) : ?>
                    <?php foreach ( $uci_rankings as $rider ) : ?>
                        <div class="row">
                            <div class="col-sm-2"><?php echo $rider->rank; ?></div>
                            <div class="col-sm-7"><a href="<?php echo crm_rider_url( $rider->rider_id ); ?>"><?php echo $rider->name; ?></a></div>
                            <div class="col-sm-3"><?php echo $rider->points; ?></div> 
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        
            <a class="view-all" href="<?php crm_riders_url(); ?>">View More &raquo;</a>
        </div>

        <div class="col-sm-12 col-md-6">
            
            <h3>Rankings</h3>
            
            <div class="row header">
                <div class="col-sm-2">Rank</div>
                <div class="col-sm-7">Name</div>
                <div class="col-sm-3">Points</div>       
            </div>
        
            <div class="riders-list-wrap">
                <?php if ( count( $crm_rankings ) ) : ?>
                    <?php foreach ( $crm_rankings as $rider ) : ?>
                        <div class="row">
                            <div class="col-sm-2"><?php echo $rider->rank; ?></div>
                            <div class="col-sm-7"><a href="<?php echo crm_rider_url( $rider->rider_id ); ?>"><?php echo $rider->name; ?></a></div>
                            <div class="col-sm-3"><?php echo $rider->points; ?></div> 
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        
            <a class="view-all" href="#">View More &raquo;</a>
        </div>
                
    </div>
    
</div>
