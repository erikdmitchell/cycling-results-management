<div class="crm-settings">

    <form action="" method="post">
        <?php wp_nonce_field( 'update_settings', 'crm_admin_settings' ); ?>

        <section class="pages">
          <h2>Pages</h2>

          <p>Manage the WordPress pages assigned to each CRM page.</p>

        </section>

        <p class="submit">
            <input name="submit" type="submit" class="button button-primary" value="Save Settings">
        </p>

    </form>

</div>
