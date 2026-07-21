<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Yiisoft\Data\Reader\ReadableDataInterface;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class DashboardWidget extends Widget
{
    private ?ReadableDataInterface $dataReader = null;

    public function dataReader(ReadableDataInterface $dataReader): self
    {
        $new = clone $this;
        $new->dataReader = $dataReader;
        return $new;
    }

    public function render(): string
    {
        if ($this->dataReader === null) {
            return '';
        }

        $cardsHtml = '';
        /** @var DashboardCard $card */
        foreach ($this->dataReader->read() as $card) {
            $cardsHtml .= $this->renderCard($card);
        }

        return Html::div($cardsHtml, ['class' => 'row g-3 mb-4'])->encode(false)->render();
    }

    private function renderCard(DashboardCard $card): string
    {
        $title = Html::encode($card->title);
        $value = Html::encode($card->value);
        $icon = Html::encode($card->icon);
        $bgClass = Html::encode($card->bgClass);

        return <<<HTML
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 p-3 {$bgClass} bg-opacity-10 text-primary rounded" style="color: var(--bs-primary) !important;">
                        <i class="fa-solid {$icon} fa-xl"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-0">{$title}</h6>
                        <span class="h4 mb-0">{$value}</span>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}