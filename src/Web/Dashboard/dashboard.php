<?php

declare(strict_types=1);

use Atom\Web\Dashboard\DashboardWidget;
use Yiisoft\Html\Html;

?>

<h1>Dashboard</h1>

<?= DashboardWidget::widget()->dataReader($dataReader) ?>
