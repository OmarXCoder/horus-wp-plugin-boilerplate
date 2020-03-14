<?php

defined('ABSPATH') || exit('Forbidden!');

global $post;

$input_val = get_post_meta($post->ID, 'ox_meta_example', true);

$input_val = $input_val ? $input_val : 'default value';

?>

<?php wp_nonce_field(OX_PLUGIN__FILE__, $this->get_nonce()) ?>

<div class="ox-wrapper ox-metabox">
    <div class="ox-container container-fluid">
        <div>
            <label for="example_input">Example Input</label>
            <input type="text" name="ox_meta_example" value="<?= $input_val ?>">
        </div>
    </div><!-- .ox-container -->
</div>