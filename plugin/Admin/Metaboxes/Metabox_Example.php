<?php

namespace Horus\Admin\Metaboxes;

use Horus\Admin\Contracts\Metabox;
use Horus\Logger;

/**
 * Metabox Example class
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Metabox_Example extends Metabox
{
    public function __construct($meta_key, $screens = [], $title = '', $context = 'normal', $priority = 'high')
    {
        $title = !empty($title) ? $title : __('Metabox', 'horus');
        parent::__construct($meta_key, $screens, $title, $context, $priority);
    }

    /**
     * Renders the metabox in the backend
     * 
     * @var WP_Post $post the post being created/updated
     */
    public function render($post)
    {
        require_once OX_ADMIN_PATH . 'views/metabox-example.php';
    }

    /**
     * Associate metabox data with the post meta
     * 
     * @var int $post_id the post id being created/updated
     */
    public function save_data($post_id)
    {
        if (!isset($_POST[$this->get_nonce()]) || !wp_verify_nonce($_POST[$this->get_nonce()], OX_PLUGIN__FILE__)) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['ox_meta_example'])) {
            update_post_meta($post_id, 'ox_meta_example', esc_sql($_POST['ox_meta_example']));
        }
    }
}
