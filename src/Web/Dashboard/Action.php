<?php

declare(strict_types=1);

namespace Atom\Web\Dashboard;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $dataReader = $this->dashboardService->getCardsDataReader();

        return $request
            ->getAttribute(WebViewRenderer::class)
            ->render(__DIR__ . '/dashboard', [
                'dataReader' => $dataReader,
            ]);
    }
}
