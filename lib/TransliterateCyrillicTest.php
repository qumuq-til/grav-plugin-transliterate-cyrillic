<?php

namespace Acme;

require __DIR__ .  '/../transliterate-cyrillic-method.php';
use TransliterateCyrillicPlugin\TransliterateCyrillicMethod as TransliterateCyrillic;

class TransliterateCyrillicTest
{
    public const DATA = 'Кёп арив, ону Абдулкеримге айтыгъыз, барып гёрсюн, ол мени булан турмажакъ, Абдулкерим булан оьмюрюн йибережек. Оьзюню оьмюрлюк ёлдашы болажакъ гишини гьалын-къылыгъын билмей алгъасап этме яхшы тюгюл, – деп жавап берди.';

    public function iterate()
    {
        TransliterateCyrillic::process(self::DATA);
    }
}
