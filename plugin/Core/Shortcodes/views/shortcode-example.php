<?php defined('ABSPATH') || exit('Forbidden!'); ?>
<?php

$atts = $this->prepare_atts();
?>

<h4 style="color: <?= $atts['color'] ?>;"><?= $atts['message'] ?></h4>