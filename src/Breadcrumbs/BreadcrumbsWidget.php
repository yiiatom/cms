<?php

declare(strict_types=1);

namespace Atom\Breadcrumbs;

use BackedEnum;
use InvalidArgumentException;
use Stringable;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class BreadcrumbsWidget extends Widget
{
    /**
     * @var Breadcrumb[]
     */
    private array $breadcrumbs = [];

    private ?string $separator = null;

    private ?Breadcrumb $home = null;

    private bool $showOnEmpty = false;

    private ?string $containerTag = 'nav';
    private array $containerAttributes = [
        'aria-label' => 'breadcrumb',
    ];

    private ?string $listTag = 'ol';
    private array $listAttributes = [];

    private ?string $itemTag = 'li';
    private array $itemAttributes = [];

    private array $linkAttributes = [];

    private ?string $activeItemClass = null;

    /**
     * @param Breadcrumb[] $breadcrumbs
     * 
     * @psalm-param Breadcrumb[] $breadcrumbs
     */
    public function breadcrumbs(array $breadcrumbs): self
    {
        $new = clone $this;
        $new->breadcrumbs = $breadcrumbs;
        return $new;
    }

    public function separator(string $separator): self
    {
        $new = clone $this;
        $new->separator = $separator;
        return $new;
    }

    public function home(Breadcrumb $home): self
    {
        $new = clone $this;
        $new->home = $home;
        return $new;
    }

    public function showOnEmpty(bool $show): self
    {
        $new = clone $this;
        $new->showOnEmpty = $show;
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

    public function activeItemClass(?string $class): self
    {
        $new = clone $this;
        $new->activeItemClass = $class;
        return $new;
    }

    public function render(): string
    {
        if (empty($this->breadcrumbs) && !$this->showOnEmpty) {
            return '';
        }

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
        $items = [];

        if ($this->home !== null) {
            $items[] = $this->renderItem($this->home);
        }

        $last = end($this->breadcrumbs);

        foreach ($this->breadcrumbs as $breadcrumb) {
            if (!empty($items) && $this->separator !== null) {
                $items[] = $this->separator;
            }
            $items[] = $this->renderItem($breadcrumb, $breadcrumb === $last);
        }

        return $items;
    }
    
    private function renderItem(Breadcrumb $breadcrumb, bool $active = false): Stringable
    {
        $content = $breadcrumb->getLabel();
        if ($url = $breadcrumb->getUrl()) {
            $content = Html::a($content, $url, $this->linkAttributes)
                ->encode($breadcrumb->getEncode());
        } elseif ($breadcrumb->getEncode()) {
            $content = Html::encode($content);
        }

        if ($this->itemTag === null) {
            return $content;
        }

        $attributes = $this->itemAttributes;
        if ($active) {
            $attributes['aria-current'] = 'page';
            if ($this->activeItemClass !== null) {
                Html::addCssClass($attributes, $this->activeItemClass);
            }
        }

        return Html::tag($this->itemTag, $content, $attributes);
    }
}
