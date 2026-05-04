<?php

declare(strict_types=1);

namespace Atom\Widget;

use Yiisoft\Html\Html;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Widget\Widget;

final class AlertWidget extends Widget
{
    public function __construct(
        private FlashInterface $flash,
    ) {}

    public function render(): string
    {
        $content = '';

        foreach($this->flash->getAll() as $type => $messages) {
            foreach ($messages as $message) {
                $content .= Html::div(Html::encode($message), [
                    'class' => 'alert alert-' . $type,
                    'role' => 'alert',
                ]);
            }
        }

        return $content;
    }
}
