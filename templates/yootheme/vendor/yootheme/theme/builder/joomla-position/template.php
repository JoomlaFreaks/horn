<?php

$id    = $element['id'];
$class = $element['class'];
$attrs = $element['attrs'];

$name = (string) $element;
$layout = $element['layout'] == 'stack' ? 'grid-stack' : 'grid';

?>

<div<?= $this->attrs(compact('id', 'class'), $attrs) ?>>

    <?= $renderer->render($name, ['name' => $name, 'style' => $layout]) ?>

</div>
