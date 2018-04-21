<?php

defined('JPATH_BASE') or die;

$msgList = $displayData['msgList'];

$alert = [
    'message' => 'uk-alert-success',
    'warning' => 'uk-alert-warning',
    'error' => 'uk-alert-danger',
    'notice' => ''
];

?>
<div id="system-message-container">
<?php if (is_array($msgList) && !empty($msgList)) : ?>
    <?php foreach ($msgList as $type => $msgs) : ?>
    <div class="uk-alert <?= $alert[$type] ?>" uk-alert>

        <a href="#" class="uk-alert-close uk-close" uk-close></a>

        <?php if (!empty($msgs)) : ?>

            <h3><?= JText::_($type) ?></h3>

            <?php foreach ($msgs as $msg) : ?>
            <p><?= $msg ?></p>
            <?php endforeach ?>

        <?php endif ?>

    </div>
    <?php endforeach ?>
<?php endif ?>
</div>
