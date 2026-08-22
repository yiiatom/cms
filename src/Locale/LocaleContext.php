<?php

declare(strict_types=1);

namespace Atom\Locale;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Cookies\Cookie;

final class LocaleContext
{
    public const REQUEST_ATTRIBUTE = 'atom-cms.locale';

    private const COOKIE_NAME = '_atom_locale';
    private const DEFAULT_LOCALE = 'en';

    private const LOCALES = [
        'en' => 'English',
        'ru' => 'Русский',
    ];

    private ?string $locale = null;

    public function read(ServerRequestInterface $request): string
    {
        $cookies = $request->getCookieParams();
        $locale = $request->getCookieParams()[self::COOKIE_NAME] ?? self::DEFAULT_LOCALE;

        if (!array_key_exists($locale, self::LOCALES)) {
            $locale = self::DEFAULT_LOCALE;
        }

        return $this->locale = $locale;
    }

    public function write(ResponseInterface $response, string $locale): ResponseInterface
    {
        return (new Cookie(self::COOKIE_NAME, $locale))
            ->withSecure(false)
            ->addToResponse($response);
    }

    /**
     * @return LocaleItem[]
     */
    public function getLocales(): array
    {
        $items = [];

        foreach (self::LOCALES as $code => $label) {
            $items[] = new LocaleItem($code, $label, $code === $this->locale);
        }

        return $items;
    }

    public function getCurrentLocale(): LocaleItem
    {
        $locale = $this->locale ?? self::DEFAULT_LOCALE;
        return new LocaleItem($locale, self::LOCALES[$locale], true);
    }
}
