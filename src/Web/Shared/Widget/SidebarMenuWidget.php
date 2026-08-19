<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Widget;

use BackedEnum;
use InvalidArgumentException;
use Stringable;
use Atom\Sidebar\SidebarMenuItem;
use Yiisoft\Html\Html;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Widget\Widget;

final class SidebarMenuWidget extends Widget
{
    /** @var SidebarMenuItem[] */
    private array $items = [];

    private ?string $containerTag = null;
    private array $containerAttributes = [];

    private ?string $listTag = 'ul';
    private array $listAttributes = [];

    private ?string $itemTag = 'li';
    private array $itemAttributes = [];

    private array $linkAttributes = [];

    private array $iconAttributes = [];

    private string $activeClass = 'active';

    public function __construct(
        private CurrentRoute $currentRoute,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    /**
     * @param SidebarMenuItem[] $items
     */
    public function items(array $items): self
    {
        $new = clone $this;
        $new->items = $items;
        return $new;
    }

    public function containerTag(?string $tag): self
    {
        if ($tag === '') {
            throw new InvalidArgumentException('Tag name cannot be empty.');
        }

        $new = clone $this;
        $new->containerTag = $tag;
        return $new;
    }

    public function containerAttributes(array $attributes): self
    {
        $new = clone $this;
        $new->containerAttributes = $attributes;
        return $new;
    }

    public function addContainerClass(BackedEnum|string|null ...$class): self
    {
        $new = clone $this;
        Html::addCssClass($new->containerAttributes, $class);
        return $new;
    }

    public function listTag(?string $tag): self
    {
        if ($tag === '') {
            throw new InvalidArgumentException('Tag name cannot be empty.');
        }

        $new = clone $this;
        $new->listTag = $tag;
        return $new;
    }

    public function listAttributes(array $attributes): self
    {
        $new = clone $this;
        $new->listAttributes = $attributes;
        return $new;
    }

    public function addListClass(BackedEnum|string|null ...$class): self
    {
        $new = clone $this;
        Html::addCssClass($new->listAttributes, $class);
        return $new;
    }

    public function itemTag(?string $tag): self
    {
        if ($tag === '') {
            throw new InvalidArgumentException('Tag name cannot be empty.');
        }

        $new = clone $this;
        $new->itemTag = $tag;
        return $new;
    }

    public function itemAttributes(array $attributes): self
    {
        $new = clone $this;
        $new->itemAttributes = $attributes;
        return $new;
    }

    public function addItemClass(BackedEnum|string|null ...$class): self
    {
        $new = clone $this;
        Html::addCssClass($new->itemAttributes, $class);
        return $new;
    }

    public function linkAttributes(array $attributes): self
    {
        $new = clone $this;
        $new->linkAttributes = $attributes;
        return $new;
    }

    public function addLinkClass(BackedEnum|string|null ...$class): self
    {
        $new = clone $this;
        Html::addCssClass($new->linkAttributes, $class);
        return $new;
    }

    public function iconAttributes(array $attributes): self
    {
        $new = clone $this;
        $new->iconAttributes = $attributes;
        return $new;
    }

    public function addIconClass(BackedEnum|string|null ...$class): self
    {
        $new = clone $this;
        Html::addCssClass($new->iconAttributes, $class);
        return $new;
    }

    public function render(): string
    {
        $result = '';

        if ($this->containerTag !== null) {
            $result .= Html::openTag($this->containerTag, $this->containerAttributes) . "\n";
        }

        if ($this->listTag !== null) {
            $result .= Html::openTag($this->listTag, $this->listAttributes) . "\n";
        }

        $items = $this->renderItems();

        $result .= implode("\n", $items);

        if ($this->listTag !== null) {
            $result .= "\n" . Html::closeTag($this->listTag);
        }

        if ($this->containerTag !== null) {
            $result .= "\n" . Html::closeTag($this->containerTag);
        }

        return $result;
    }

    /**
     * @return array<Stringable>
     */
    private function renderItems(): array
    {
        $result = [];

        foreach ($this->items as $item) {
            $result[] = $this->renderItem($item);
        }

        return $result;
    }

    private function renderItem(SidebarMenuItem $item): Stringable
    {
        $content = Html::encode($item->getLabel());
        if ($icon = $item->getIcon()) {
            $attributes = $this->iconAttributes;
            Html::addCssClass($attributes, $icon);
            $content = Html::i('', $attributes) . $content;
        }

        $url = $this->urlGenerator->generate($item->getRouteName());

        $attributes = $this->linkAttributes;
        if ($item->isActive($this->currentRoute->getName())) {
            Html::addCssClass($attributes, $this->activeClass);
        }
        $link = Html::a($content, $url, $attributes)->encode(false);

        if ($this->itemTag === null) {
            return $link;
        }

        return Html::tag($this->itemTag, $link, $this->itemAttributes);
    }
}
