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
$uci_rankings = cycling_results_management()->uci_rankings->get_rankings();
$crm_rankings = cycling_results_management()->riders->get_riders();
?>
<pre>
    <?php
        //print_r($races);
        print_r($uci_rankings);
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
        
        <div class="col-md-6">
            
            <div class="uci-rankings">
                <h3>UCI Rankings</h3>
                
                <div class="row filters">
                    <div class="col-md-3">
                        
                        <select name="discipline" id="uci-rankings-discipline">
                            
                            <option value="0">Select Discipline</option>
                            
                            <?php foreach ( $uci_rankings->disciplines() as $discipline ) : ?>
                                <option value="<?php echo $discipline->id; ?>" <?php selected( $selected_discipline, $discipline->id, true ); ?>><?php echo $discipline->discipline; ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                    </div>
                    <div class="col-md-3">

                        <select name="date" id="uci-rankings-date">
                            
                            <option value="0">Select Date</option>
                            
                            <?php foreach ( $uci_rankings->get_rankings_dates( $selected_discipline ) as $date ) : ?>
                                <option value="<?php echo $date->date; ?>" <?php selected( $selected_date, $date->date, true ); ?>><?php echo $date->date; ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

                <div class="row header">
                    <div class="col-md-1 rider-rank">Rank</div>
                    <div class="col-md-5 rider-name">Rider</div>
                    <div class="col-md-1 rider-nat">Nat</div>
                    <div class="col-md-2 rider-points">Points</div>
                </div>
        
                <div class="riders-list-wrap">
                    <?php
                    if ( count( $riders ) ) :
                        foreach ( $riders as $rider ) :
                            ?>
                                                    <?php echo crm_get_template_part( 'uci-rankings-rider-row', $rider ); ?>
                                            <?php
                    endforeach;
endif;
                    ?>
                </div>
        
                <a class="view-all" href="#">View All Riders &raquo;</a>
            </div>
                
        </div>      
    </div>
    
</div>
