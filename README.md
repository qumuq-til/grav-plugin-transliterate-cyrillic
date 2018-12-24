# [Grav](http://getgrav.org/) Transliterate Cyrillic Plugin

Transliterates cyrillic text into latin. From this Markdown content:

		Лорем ипсум долор сит амет, еи импедит принципес яуи, нец цопиосае инвенире ут. Яуи оптион аудире ех, ад оптион воцибус дигниссим ест. Нам новум убияуе мелиоре ид. Меа цу ностер аудиам нусяуам, еа нам партем продессет. Ат мунере албуциус меа, дицам сцрипсерит сед ех, яуот омнесяуе вих ат. Сеа яуод нонумы еа, ан алияуам аппетере атоморум хас. Яуот велит цлита меа еа, ет мел ассум фиерент. Те хис меис детрацто ехплицари. Вертерем волуптатибус меи еа. Вим новум репудиаре еу. Дицит аперири цонсецтетуер ест еа. Унум ребум нец ех. Но фалли игнота меи. Албуциус молестиае хендрерит ат хис. Либрис перфецто еффициенди.

Into this transliteration:

		Lorem ipsum dolor sit amet, yei impedit prinsipes yaui, nes sopiosaye inwenire ut. Yaui option audire yex, ad option wosibus dignissim yest. Nam nowum ubyauye meliore id. Mea su noster audiam nusäuam, yea nam partem prodesset. At munere albusius mea, disam ssripserit sed yex, yauot omnesäuye wix at. Sea yauod nonumı yea, an alyauam appetere atomorum xas. Yauot welit slita mea yea, yet mel assum fiyerent. Te xis meis detrasto yexplisari. Werterem woluptatibus mei yea. Wim nowum repudiare yeu. Disit aperiri sonsestetuyer yest yea. Unum rebum nes yex. No falli ignota mei. Albusius molestiaye xendrerit at xis. Libris perfesto yeffisiyendi.

# Installation and Configuration

1. Download the zip version of [this repository](https://github.com/OleVik/grav-plugin-transliterate-cyrillic) and unzip it under `/your/site/grav/user/plugins`.
2. Rename the folder to `transliterate-cyrillic`.

You should now have all the plugin files under

    /your/site/grav/user/plugins/transliterate-cyrillic

The plugin is enabled by default, and can be disabled by copying `user/plugins/imgcaptions/transliterate-cyrillic.yaml` into `user/config/plugins/transliterate-cyrillic.yaml` and setting `enabled: false`.

## Usage and configuration

Change the `mode`-setting from `markdown` to `html` to target processed content. Change `event` to whatever [Event Hook](https://learn.getgrav.org/plugins/event-hooks) you find more appropriate than the default "onPageContentRaw" (`markdown`) or "onPageContentProcessed" (`html`).

A Twig-function, `transliterate_cyrillic()`, can be used to apply the functionality directly in Twig. Just pass content into it like `transliterate_cyrillic(page.content)`, or use it as a filter like `page.content|transliterate_cyrillic`.

# Running tests

Run `composer update` to install the testing dependencies. Then run `composer test` in the root folder. Finally, run `composer update --no-dev` to uninstall the testing dependencies.

A :christmas_tree: Christmas project responding to a request from marziyeh, using [his script](https://raw.githubusercontent.com/qumuq-til/cyrillic-to-latin/master/transliteration.php) largely untouched (in `[transliterate-cyrillic-method.php](transliterate-cyrillic-method.php)`). Written as a gentle introduction to plugins in Grav and OOP in PHP by :santa: [OleVik](https://github.com/OleVik).

MIT License 2018 by [Ole Vik](http://github.com/olevik).