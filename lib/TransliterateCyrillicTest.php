<?php

namespace Acme;

require __DIR__ .  '/../transliterate-cyrillic-method.php';
use TransliterateCyrillicPlugin\TransliterateCyrillicMethod as TransliterateCyrillic;

class TransliterateCyrillicTest
{
    public const DATA = 'Лорем ипсум долор сит амет, еи импедит принципес яуи, нец цопиосае инвенире ут. Яуи оптион аудире ех, ад оптион воцибус дигниссим ест. Нам новум убияуе мелиоре ид. Меа цу ностер аудиам нусяуам, еа нам партем продессет. Ат мунере албуциус меа, дицам сцрипсерит сед ех, яуот омнесяуе вих ат. Сеа яуод нонумы еа, ан алияуам аппетере атоморум хас. Яуот велит цлита меа еа, ет мел ассум фиерент. Те хис меис детрацто ехплицари. Вертерем волуптатибус меи еа. Вим новум репудиаре еу. Дицит аперири цонсецтетуер ест еа. Унум ребум нец ех. Но фалли игнота меи. Албуциус молестиае хендрерит ат хис. Либрис перфецто еффициенди.';

    public function iterate()
    {
        TransliterateCyrillic::process(self::DATA);
    }
}