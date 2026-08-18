<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Atom\Dashboard\DashboardCardsProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private DashboardCardsProvider $dashboardCardsProvider,
        private TranslatorInterface $translator,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-cms');
        $dataReader = $this->dashboardCardsProvider->getCardsAsDataReader();

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/dashboard', [
                't' => $t,
                'dataReader' => $dataReader,
            ]);
    }
}
