<?php

declare(strict_types=1);

use Atom\Web\Dashboard\DashboardWidget;
use Yiisoft\Html\Html;

/** @var \Yiisoft\Translator\TranslatorInterface $t */

$title = $t->translate('Dashboard');

$this->setTitle($title);

?>

<h1><?= Html::encode($title) ?></h1>

<?= DashboardWidget::widget()->dataReader($dataReader) ?>
