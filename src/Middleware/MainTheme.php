<?php

declare(strict_types=1);

namespace Atom\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Form\Theme\ThemeContainer;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Yii\DataView\GridView\GridView;
use Yiisoft\Yii\DataView\Pagination\OffsetPagination;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class MainTheme implements MiddlewareInterface
{
    public function __construct(
        private Aliases $aliases,
        private ContainerInterface $container,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        ThemeContainer::initialize([
            'horizontal' => require $this->aliases->get('@atom/config/theme/main-horizontal.php'),
            'inline' => require $this->aliases->get('@atom/config/theme/main-inline.php'),
        ], 'horizontal');

        WidgetFactory::initialize($this->container, [
            GridView::class => [
                'containerClass()' => ['table-responsive'],
                'tableClass()' => ['table table-bordered table-sm table-hover'],
                'headerRowAttributes()' => [['class' => 'table-dark']],
            ],
            OffsetPagination::class => [
                'listTag()' => ['ul'],
                'listAttributes()' => [['class' => 'pagination']],
                'itemTag()' => ['li'],
                'itemAttributes()' => [['class' => 'page-item']],
                'addLinkClass()' => ['page-link'],
                'currentItemClass()' => ['active'],
                'disabledItemClass()' => ['disabled'],
                'labelPrevious()' => ['«'],
                'labelNext()' => ['»'],
                'labelFirst()' => [null],
                'labelLast()' => [null],
            ],
        ]);

        $renderer = $this->viewRenderer->withLayout('@atom/src/Web/Shared/Layout/Main/main');
        $request = $request->withAttribute(WebViewRenderer::class, $renderer);

        return $handler->handle($request);
    }
}
