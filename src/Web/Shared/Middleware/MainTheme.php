<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Form\Theme\ThemeContainer;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Yii\DataView\GridView\GridView;

final readonly class MainTheme implements MiddlewareInterface
{
    public function __construct(
        private Aliases $aliases,
        private ContainerInterface $container,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        ThemeContainer::initialize([
            'horizontal' => require $this->aliases->get('@atom/config/theme/main-horizontal.php'),
        ], 'horizontal');

        WidgetFactory::initialize($this->container, [
            GridView::class => [
                'tableClass()' => ['table table-bordered table-sm'],
                'headerRowAttributes()' => [['class' => 'table-dark']],
            ],
        ]);

        return $handler->handle($request);
    }
}
