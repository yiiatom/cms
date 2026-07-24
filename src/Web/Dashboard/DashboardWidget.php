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
        $icon = Html::encode($card->icon);

        $itemsHtml = '';
        if (!empty($card->items)) {
            $itemsHtml .= '<div class="mt-2 pt-2 border-top">';
            foreach ($card->items as $label => $val) {
                $encodedLabel = Html::encode($label);
                $encodedVal = Html::encode($val);
                $itemsHtml .= <<<HTML
                <div class="d-flex justify-content-between align-items-center text-muted">
                    <span>{$encodedLabel}</span>
                    <span class="fw-semibold text-dark">{$encodedVal}</span>
                </div>
                HTML;
            }
            $itemsHtml .= '</div>';
        }

        $footerHtml = '';
        if ($card->linkUrl && $card->linkText) {
            $encodedUrl = Html::encode($card->linkUrl);
            $encodedText = Html::encode($card->linkText);
            $footerHtml .= <<<HTML
            <div class="mt-2 pt-2 border-top border-light-subtle d-flex justify-content-end">
                <a href="{$encodedUrl}" class="small text-primary text-decoration-none fw-semibold icon-link icon-link-hover">
                    {$encodedText} <i class="fa-solid fa-arrow-right small ms-1"></i>
                </a>
            </div>
            HTML;
        }

        return <<<HTML
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="card-title text-muted fs-4 mb-0">{$title}</div>
                            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                <i class="{$icon} fa-l"></i>
                            </div>
                        </div>
                        {$itemsHtml}
                    </div>
                    {$footerHtml}
                </div>
            </div>
        </div>
        HTML;
    }
}