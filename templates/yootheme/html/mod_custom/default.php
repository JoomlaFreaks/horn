<?php

defined('_JEXEC') or die;

$image = $params->get('backgroundimage');

?>

<div class="custom <?= $moduleclass_sfx ?>" <?= $image ? " style=\"background-image:url({$image})\"" : '' ?>><?= $module->content ?></div>
