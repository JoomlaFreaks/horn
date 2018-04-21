<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$app = JFactory::getApplication();
$params = $app->getTemplate(true)->params->get('config');

$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

$error = $this->error->getCode();
$message = $this->error->getMessage();

?>

<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>" vocab="http://schema.org/">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (isset($params['favicon'])) : ?>
        <link rel="shortcut icon" href="<?= $this->baseurl.'/'.$params['favicon'] ?>">
    <?php endif; ?>
    <?php if (isset($params['touchicon'])) : ?>
        <link rel="apple-touch-icon-precomposed" href="<?= $this->baseurl.'/'.$params['touchicon'] ?>">
    <?php endif; ?>
    <title><?= $error; ?> - <?= $message; ?></title>
    <?php if ($this->direction == 'ltr') : ?>
        <link rel="stylesheet" href="<?= $this->baseurl; ?>/templates/yootheme/css/theme.css" type="text/css" />
    <?php else : ?>
        <link rel="stylesheet" href="<?= $this->baseurl; ?>/templates/system/css/theme.rtl.css" type="text/css" />
    <?php endif; ?>
    <script src="<?= $this->baseurl; ?>/templates/yootheme/vendor/assets/uikit/dist/js/uikit.min.js"></script>
</head>
<body class="">

<div class="uk-section uk-section-default uk-flex uk-flex-center uk-flex-middle uk-text-center" uk-height-viewport>
    <div>
        <h1 class="uk-heading-hero"><?= $error; ?></h1>
        <p class="uk-h3"><?= $message; ?></p>
        <a class="uk-button uk-button-primary" href="<?= $this->baseurl; ?>/index.php"><?= JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>
    </div>
</div>

</body>
</html>