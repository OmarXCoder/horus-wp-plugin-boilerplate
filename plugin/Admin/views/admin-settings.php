<?php

/**
 * Admin settings View
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
defined('ABSPATH') || exit;

if ('' === $current_tab) {
    $current_tab = key($tabs);
}

$current_tab_label = isset($tabs[$current_tab]) ? $tabs[$current_tab] : '';

?>
<div class="wrap horus">
    <div id="ox-wrapper">
        <div class="ox-container container-fluid">
            <form method="<?php echo esc_attr(apply_filters('ox_settings_form_method_tab_' . $current_tab, 'post')); ?>" id="mainform" action="" enctype="multipart/form-data">
                <nav class="nav-tab-wrapper mb-4">
                    <?php
                    foreach ($tabs as $slug => $label) {
                        echo '<a href="' . esc_html(admin_url('admin.php?page=ox-settings&tab=' . esc_attr($slug))) . '" class="nav-tab ' . ($current_tab === $slug ? 'nav-tab-active' : '') . '">' . esc_html($label) . '</a>';
                    }

                    do_action('ox_settings_tabs');

                    ?>
                </nav>
                <h1 class="screen-reader-text"><?php echo esc_html($current_tab_label); ?></h1>
                <?php
                do_action('ox_sections_' . $current_tab);

                self::show_messages();

                do_action('ox_settings_' . $current_tab);

                ?>
                <p class="submit">
                    <?php if (empty($GLOBALS['hide_save_button'])) : ?>
                        <button name="save" class="button-primary" type="submit" value="<?php esc_attr_e('Save changes', 'horus'); ?>"><?php esc_html_e('Save changes', 'horus'); ?></button>
                    <?php endif; ?>
                    <?php wp_nonce_field('horus-settings'); ?>
                </p>
            </form>
        </div><!-- .ox-container -->
    </div><!-- #ox-wrapper -->
</div>