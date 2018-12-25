# [Grav](http://getgrav.org/) Transliterate Cyrillic Plugin

Transliterates cyrillic text for Kumyk (Qumuq) language into latin. From this Markdown content:

		Кёп арив, ону Абдулкеримге айтыгъыз, барып гёрсюн, ол мени булан турмажакъ, Абдулкерим булан оьмюрюн йибережек. Оьзюню оьмюрлюк ёлдашы болажакъ гишини гьалын-къылыгъын билмей алгъасап этме яхшы тюгюл, – деп жавап берди.

Into this transliteration:

		Köp ariw, onu Abdulkerimge aytığız, barıp görsün, ol meni bulan turmajaq, Abdulkerim bulan ömürün yiberejek. Özünü ömürlük yoldaşı bolajaq gişini halın-qılığın bilmey alğasap etme yaxşı tügül, – dep cawap berdi.	

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

A :christmas_tree: Christmas project responding to a request from marziyeh, using [his script](https://raw.githubusercontent.com/qumuq-til/cyrillic-to-latin/master/transliteration.php) largely untouched (in [transliterate-cyrillic-method.php](transliterate-cyrillic-method.php)). Written as a gentle introduction to plugins in Grav and OOP in PHP by :santa: [OleVik](https://github.com/OleVik).

MIT License 2018 by [Ole Vik](http://github.com/olevik).
