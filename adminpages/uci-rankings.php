<div class="uci-results-admin uci-rankings">
    <h2>UCI Rankings</h2>
    
    <div id="uci-admin-message"></div>

    <form id="add-uci-rankings" name="add-uci-rankings" action="" method="post">
        <table class="form-table">
            <tbody>
                
                <tr>
                    <th scope="row">
                        <label form="add-rankings">Add New Rankings</label>
                    </th>
                    <td>
                        <input type="text" id="add-rider-rankings-input" name="file" value="" class="regular-text" /> <a class="button add-rider-rankings" href="">Add File</a>

                        <div class="custom-date">
                            <label form="custom-date">Custom Date</label>
                            <input type="text" name="date" id="custom-date" class="date uci-results-datepicker" value="<?php echo date( 'Y-m-d' ); ?>">
                            <p class="description">If empty, current date will be used. <i>Format: YYYY-MM-DD</i></p>
                        </div>
                        
                        <div class="discipline">
                            <label form="discipline">Discipline</label>
                            <?php
                            wp_dropdown_categories(
                                array(
                                    'hide_empty' => 0,
                                    'show_option_none'   => 'Select One',
                                    'option_none_value'  => '0',
                                    'orderby'            => 'name',
                                    'name'               => 'discipline',
                                    'id' => 'discipline',
                                    'taxonomy'           => 'discipline',
                                )
                            );
                            ?>
                        </div>
                        
                        <div class="clean-names">
                            <label form="clean-names">Clean Names</label>
                            <input type="checkbox" name="clean_names" id="clean-names" class="checkbox" value="1">
                            <p class="description">If names are not in correct order, this will use the UCI name sort where the last name is first.</p>
                        </div>
                        
                        <p><a class="button button-primary" id="insert-rider-rankings" href="">Insert into DB</a></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">View Rankings</th>
                    <td>
                        <?php $dates = cycling_results_management()->uci_rankings->get_rankings_dates(); ?>
                        <?php foreach ( $dates as $date ) : ?>
                            <a href="<?php crm_uci_rankings_url( $date->discipline, $date->date ); ?>"><?php echo $date->date; ?> (<?php echo $date->discipline; ?>)</a><br />
                        <?php endforeach; ?>
                    </td>
                </tr>
                
            </tbody>
        </table>

    </form>

</div>
