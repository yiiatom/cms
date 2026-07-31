<?php

declare(strict_types=1);

namespace Atom\Helper;

final class Translit
{
    public static function t(string $text): string
    {
        $text = mb_strtolower(trim($text));

        if (function_exists('transliterator_transliterate')) {
            $result = transliterator_transliterate('Any-Latin; Latin-ASCII', $text);
            if ($result !== false) {
                return self::clean($result);
            }
        }

        $matrix = [
            'а' => 'a',  'б' => 'b',  'в' => 'v',  'г' => 'g',  'д' => 'd',  'е' => 'e',  'ё' => 'yo',
            'ж' => 'zh', 'з' => 'z',  'и' => 'i',  'й' => 'y',  'к' => 'k',  'л' => 'l',  'м' => 'm',
            'н' => 'n',  'о' => 'o',  'п' => 'p',  'р' => 'r',  'с' => 's',  'т' => 't',  'у' => 'u',
            'ф' => 'f',  'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '',
            'ы' => 'y',  'ь' => '',   'э' => 'e',  'ю' => 'yu', 'я' => 'ya', 'і' => 'i',  'ї' => 'yi',
            'є' => 'ye', 'ђ' => 'dj', 'ћ' => 'ch', 'љ' => 'lj', 'њ' => 'nj', 'џ' => 'dz',
            
            'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
            
            'à' => 'a',  'á' => 'a',  'â' => 'a',  'ã' => 'a',  'å' => 'a',  'æ' => 'ae', 'ç' => 'c',
            'è' => 'e',  'é' => 'e',  'ê' => 'e',  'ë' => 'e',  'ì' => 'i',  'í' => 'i',  'î' => 'i',
            'ï' => 'i',  'ð' => 'd',  'ñ' => 'n',  'ò' => 'o',  'ó' => 'o',  'ô' => 'o',  'õ' => 'o',
            'ø' => 'o',  'ù' => 'u',  'ú' => 'u',  'û' => 'u',  'ý' => 'y',  'þ' => 'th', 'ÿ' => 'y',
            'ą' => 'a',  'ć' => 'c',  'ę' => 'e',  'ł' => 'l',  'ń' => 'n',  'ó' => 'o',  'ś' => 's',
            'ź' => 'z',  'ż' => 'z',  'č' => 'c',  'ď' => 'd',  'ě' => 'e',  'ň' => 'n',  'ř' => 'r',
            'š' => 's',  'ť' => 't',  'ů' => 'u',  'ž' => 'z',  'ā' => 'a',  'ē' => 'e',  'ģ' => 'g',
            'ī' => 'i',  'ķ' => 'k',  'ļ' => 'l',  'ņ' => 'n',  'ū' => 'u',  'ž' => 'z'
        ];

        $result = str_replace(array_keys($matrix), array_values($matrix), $text);

        return self::clean($result);
    }

    private static function clean(string $text): string
    {
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}