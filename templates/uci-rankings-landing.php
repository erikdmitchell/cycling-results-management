<?php
/**
 * template for uci rankings landing page
 *
 * It can be overriden
 *
 * @since 0.1.0
 */

get_header();

$disciplines = cycling_results_management()->uci_rankings->get_disciplines();

?>
<div class="container uci-rankings">
    <?php foreach($disciplines as $discipline) : ?>
        <?php $dates = cycling_results_management()->uci_rankings->get_rankings_dates($discipline->term_id); ?>
        
        <?php if (empty($dates)) { continue; } ?>
        <div class="row">
            <div class="col-12">
                <h3><?php echo $discipline->name; ?></h3>
        
                <div class="discipline-dates">
                    <?php foreach ( $dates as $date ) : ?>
                        <a href="<?php crm_uci_rankings_url( $discipline->slug, $date->date ); ?>"><?php echo date(get_option('date_format'), strtotime($date->date)); ?></a><br />
                    <?php endforeach; ?> 
                </div>       
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
get_footer();