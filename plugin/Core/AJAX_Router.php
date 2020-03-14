<?php

namespace Horus\Core;

defined('ABSPATH') || exit('Forbidden!');

class AJAX_Router
{

    public static function AddRoutes()
    {
        // Fetches a list of reviews for a product
        add_action('wp_ajax_fetch_reviews', function () {
            // Do some actions here
        });
        add_action('wp_ajax_nopriv_fetch_reviews', function () {
            // Do some actions here
        });
    }
}
