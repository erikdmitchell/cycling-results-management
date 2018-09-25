<?php $rider_rankings=crm_get_rider_rankings(array('posts_per_page' => -1)); ?>

<div class="crm-rider-rankings">
    <h2>Rider Rankings</h2>

    <table class="wp-list-table widefat fixed striped riders">
        <thead>
            <tr>
                <th scope="col" class="rider-rank">Rank</th>
                <th scope="col" class="rider-name">Name</th>
                <th scope="col" class="rider-points">Points</th>
                <th scope="col" class="rider-nat">Nat.</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rider_rankings as $rider) : ?>
                <tr>
                    <td class="rider-rank"><?php echo $rider->rank; ?></td>
                    <td class="rider-name"><a href="<?php echo admin_url( 'admin.php?page=uci-results&tab=riders&rider=' . urlencode( $rider->name ) ); ?>"><?php echo $rider->name; ?></a></td>
                    <td class="rider-points"><?php echo $rider->points; ?></td>
                    <td class="rider-nat"><?php echo $rider->nat; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
