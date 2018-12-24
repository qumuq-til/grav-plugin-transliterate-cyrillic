<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Uri;
use Grav\Common\Utils;
use Grav\Common\Page\Page;
use Grav\Common\Twig\Twig;
use RocketTheme\Toolbox\Event\Event;

/**
 * Transliterates cyrillic text into latin
 *
 * Class TransliterateCyrillicPlugin
 *
 * @package Grav\Plugin
 * @return  void
 * @license MIT License by Ole Vik
 */
class TransliterateCyrillicPlugin extends Plugin
{
    /**
     * Register events with Grav
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     *
     * @return array
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            return;
        }
        
        $config = (array) $this->config->get('plugins');
        $config = $config['transliterate-cyrillic'];
        if (!isset($config['event'])) {
            if ($config['mode'] == 'markdown') {
                $event = 'onPageContentRaw';
            } elseif ($config['mode'] == 'html') {
                $event = 'onPageContentProcessed';
            }
        } else {
            $event = $config['event'];
        }
        if ($config['enabled']) {
            $this->enable(
                [
                    $event => ['output', 0],
                    'onTwigExtensions' => ['onTwigExtensions', 0]
                ]
            );
        } else {
            return;
        }
    }

    /**
     * Replaces content with transliterated Cyrillic content
     *
     * @param Event $event Instance of RocketTheme\Toolbox\Event\Event
     *
     * @return void
     */
    public function output(Event $event)
    {
        $page = $event['page'];
        $config = (array) $this->config->get('plugins');
        $config = $config['transliterate-cyrillic'];
        if ($config['mode'] == 'markdown') {
            $content = $page->getRawContent();
            $content = self::transliterateCyrillic($content);
        } elseif ($config['mode'] == 'html') {
            $content = $page->content();
            $content = self::transliterateCyrillic($content);
        }
        $page->setRawContent($content);
    }

    /**
     * Transliterate text from cyrillic to latin
     *
     * @param string $text Text to transliterate
     *
     * @return void
     */
    public static function transliterateCyrillic($text)
    {
        //Исключения
        $exceptions["cyrillic"] = [
            "\bкъангючев",
            "\bавангард",
            "\bконтингент",
            "\bсинген",
            "\bжурнал",
            "\bжилет",
            "\bжакет",
            "\bжанр",
            "\bжаргон",
            "\bжандарм",
            "\bжумамежит",
            "\bмесжит",
            "\bгьажи",
            "\bзулгьижжа",
            "\bилаж",
            "\bхалмаж",
            "\bбалжибин",
            "\bкъаражибин",
            "\bкъаражигер",
            "\bкъолжувмакъ",
            "\bкъолжувмагъ",
            "\bнажжас",
            "\bанжи",
            "\bинжи",
            "\bсарижымчыкъ",
            "\bсарижымчыгъ",
            "\bсолтанжая",
            "\bтажжал",
            "\bдажжал",
            "\bтенгиржая",
            "\bэнемжая",
            "\bтележибин",
            "\bуьйжанлы",
            "\bхатиржан",
            "\bтажик",
            "\bтаржума",
            "\bсужда",
            "\bража",
            "\bражап",
            "\bижара",
            "\bмажлис",
            "\bмежлис",
            "\bмажмуа",
            "\bмежит",
            "\bмугьажир",
            "\bмужаллат",
            "\bмуъжиза",
            "\bтаж",
            "\bбурж",
            "\bажам",
            "\bажар",
            "\bажжал",
            "\bажиз",
            "\bжанжал",
            "\bжанжепил",
            "\bёл",
            "\bюк",
            "\bюн",
            "\bёрт",
            "\bёюв",
            "\bюз",
            "\bачыкъюрек",
            "\bкъурсакъетек",
            "\bаламат",
            "\bалапа",
            "\bалат",
            "\bбаркаман",
            "\bжумла",
            "\bзакат",
            "\bилагьи",
            "\bимкан",
            "\bинкар",
            "\bихтилат",
            "\bкабап",
            "\bкабаб",
            "\bкагъыз",
            "\bкагъыт",
            "\bкагьраба",
            "\bкалам",
            "\bкалима",
            "\bкама",
            "\bкамал",
            "\bкамил",
            "\bкампет",
            "\bкартоп",
            "\bкакич",
            "\bкъапиля",
            "\bкъафиля",
            "\bкарахюлю",
            "\bкажин",
            "\bкасбу",
            "\bканзи",
            "\bканав",
            "\bкант",
            "\bкапарат",
            "\bкафарат",
            "\bкапир",
            "\bкафир",
            "\bкатип",
            "\bкатиб",
            "\bкъаламтар",
            "\bлабизи",
            "\bлагьим",
            "\bналат",
            "\bникагь",
            "\bсалам-калам",
            "\bтаала",
            "\bтаъла",
            "\bталакъ",
            "\bталагъ",
            "\bхала",
            "\bмалайик",
            "\bмалайик",
            "\bмалакулмавут",
            "\bмаслак",
            "\bмаслагъ",
            "\bмагьсулат",
            "\bмакан",
            "\bлайла",
            "\bгамиш",
            "\bкасип",
            "\bкантил",
            "\bкатил",
            "\bкагьраба",
            "\bгагь",
            "\bнагагь",
            "\bдескагь",
            "\bкап",
            "\bмуракаплы",
            "\bмакъала",
            "\bмасала",
            "\bмасъала",
            "\bмахлукъ",
            "\bмаълумат",
            "\bаукцион",
            "\bакционер",
            "\bаббрев",
            "\bавангард",
            "\bаванс",
            "\bавиа",
            "\bавтовокзал",
            "\bагглютинатив",
            "\bагрессив",
            "\bадвокат",
            "\bадминистратив",
            "\bаккредитив",
            "\bарxив",
            "\bаэровокзал",
            "\bболшев",
            "\bбуквар",
            "\bбулевар",
            "\bвагон",
            "\bвазелин",
            "\bваксин",
            "\bвалерьян",
            "\bвалет",
            "\bвальс",
            "\bвалют",
            "\bванна",
            "\bварьянт",
            "\bвассал",
            "\bваучер",
            "\bвексель",
            "\bвектор",
            "\bвелосипед",
            "\bверст",
            "\bветеран",
            "\bвиза",
            "\bвинт",
            "\bвиолончель",
            "\bвирус",
            "\bвискоз",
            "\bвитамин",
            "\bвитрин",
            "\bвокзал",
            "\bволейбол",
            "\bвулкан",
            "\bвьетнам",
            "\bгидрав",
            "\bдиверс",
            "\bдивиз",
            "\bдиректив",
            "\bинвалид",
            "\bинвентарь",
            "\bинверсья",
            "\bинтервьюw",
            "\bинфинитив",
            "\bкавалер",
            "\bколлектив",
            "\bконв",
            "\bконсерв",
            "\bконтррев",
            "\bкооператив",
            "\bкультив",
            "\bкурсив",
            "\bлокомотив",
            "\bневро",
            "\bнегатив",
            "\bнерв",
            "\bобсерв",
            "\bобъектив",
            "\bоператив",
            "\bпавильон",
            "\bпортвейн",
            "\bприват",
            "\bпрогрессив",
            "\bрадиоактив",
            "\bревиз",
            "\bрезерв",
            "\bресидив",
            "\bсервант",
            "\bсервис",
            "\bсовет",
            "\bстолов",
            "\bсубъектив",
            "\bсуверен",
            "\bтелевизор",
            "\bфакультатив",
            "\bфевраль",
            "\bфедератив",
            "\bфестиваль",
            "\bцивил",
            "\bшовин",
            "\bштатив",
            "\bэвакуа",
            "\bэволюс",
            "\bэкwивал",
            "\bэлеват",
            "\bюгослав",
            "\bактив",
            "\bпоситив",
            "\bпияла",
            "\bраият",
            "\bсакътиян",
            "\bсарияв",
            "\bсиягь",
            "\bсиясат",
            "\bтелиянгур",
            "\bхалияр",
            "\bхасият",
            "\bсияла",
            "\bсиян",
            "\bкъансияла",
            "\bихтияр",
            "\bинсаният",
            "\bзияра",
            "\bзиян",
            "\bвасият",
            "\bбиябур",
            "\bмалиян",
            "\bханц",
            "\bкюц",
            "\bгьанцыл",
            "\bгьанцукъал",
            "\bанцукъал",
            "\bзылцын",
            "\bзыц",
            "\bлицил",
            "\bхуцири",
            "\bхырц",
            "\bгюнгюрт",
            "\bтюрт",
            "\bбюртюк",
            "\bуюртю",
            "\bтюкюрт",
            "\bсюрт",
            "\bоькюрт",
            "\bкюрт",
            "\bкюкюрт",
            "\bкюсдюрт",
            "\bсёндюрт",
            "\bёткюрт",
            "\bгьюрт",
            "\bгюйдюрт",
            "\bгёчюрт",
            "\bгёпюрт",
            "\bгёпдюрт",
            "\bшюшгюрт",
            "\bтарбия",
            "\bсания",
            "\bмия",
            "\bмадания",
            "\bадабия",
            "\bагьамия",
            "\bкъалия",
            "\bкисия",
            "\bирия",
            "\bилия",
            "\bжамия",
            "\bзабания",
            "\bдерия",
            "\bгьурия",
            "\bгьюрия",
            "\bгьакимия",
            "\bзуррия",
            "\bкъапия",
            "\bтазия",
            "\bён",
            "\bбарият",
            "юрт",
            "\bсингир\b",
            "\bсюнгюн\b",
            "\bгёнгюн\b",
            "\bхангиши"
        ];
        $exceptions["latin"] = [
            "qangüçew",
            "avangard",
            "kontingent",
            "siñen",
            "jurnal",
            "jilet",
            "jaket",
            "janr",
            "jargon",
            "jandarm",
            "cumamecit",
            "mescit",
            "haci",
            "zulhicca",
            "iläc",
            "xalmac",
            "balcibin",
            "qaracibin",
            "qaraciger",
            "qolcuwmaq",
            "qolcuwmağ",
            "naccas",
            "anci",
            "inci",
            "saricımçıq",
            "saricımçığ",
            "soltancaya",
            "taccal",
            "daccal",
            "teñircaya",
            "enemcaya",
            "telecibin",
            "üycanlı",
            "xatircan",
            "tacik",
            "tarcuma",
            "sucda",
            "raca",
            "racap",
            "icara",
            "maclis",
            "meclis",
            "macmua",
            "mecit",
            "muhacir",
            "mucallät",
            "muʼciza",
            "tac",
            "burc",
            "acam",
            "acar",
            "accal",
            "aciz",
            "cancal",
            "cancepil",
            "yol",
            "yük",
            "yün",
            "yort",
            "yoyuw",
            "yüz",
            "açıqyürek",
            "qursaqetek",
            "alämat",
            "aläpa",
            "alät",
            "barkäman",
            "cumlä",
            "zakät",
            "ilähi",
            "imkän",
            "inkär",
            "ixtilät",
            "käbap",
            "käbab",
            "käğız",
            "käğıt",
            "kähraba",
            "käläm",
            "kälima",
            "käma",
            "kämal",
            "kämil",
            "kämpet",
            "kärtop",
            "käkiç",
            "qapilä",
            "qafilä",
            "käraxülü",
            "käjin",
            "käsbu",
            "känzi",
            "känaw",
            "känt",
            "käparat",
            "käfarat",
            "käpir",
            "käfir",
            "kätip",
            "kätib",
            "qalämtar",
            "läbizi",
            "lähim",
            "nalät",
            "nikäh",
            "salam-käläm",
            "taalä",
            "taʼlä",
            "taläq",
            "taläğ",
            "xalä",
            "maläyik",
            "maläyig",
            "maläkulmawut",
            "masläk",
            "masläğ",
            "mahsulät",
            "makän",
            "läyla",
            "gämiş",
            "käsip",
            "käntil",
            "kätip",
            "kähraba",
            "gäh",
            "nagäh",
            "deskäh",
            "käp",
            "murakkäplı",
            "maqalä",
            "masala",
            "masʼalä",
            "maxlüq",
            "maʼlümat",
            "auksyon",
            "aksyoner",
            "abbrev",
            "avangard",
            "avans",
            "avia",
            "awtovokzal",
            "agglütinativ",
            "agressiv",
            "advokat",
            "administrativ",
            "akkreditiv",
            "arxiv",
            "aerovokzal",
            "bolşev",
            "bukvar",
            "bulevar",
            "vagon",
            "vazelin",
            "vaksin",
            "valeryan",
            "valet",
            "vals",
            "valüt",
            "vanna",
            "varyant",
            "vassal",
            "vauçer",
            "veksel",
            "vektor",
            "velosiped",
            "verst",
            "veteran",
            "viza",
            "vint",
            "violonçel",
            "virus",
            "viskoz",
            "vitamin",
            "vitrin",
            "vokzal",
            "voleybol",
            "vulkan",
            "vyetnam",
            "gidrav",
            "divers",
            "diviz",
            "direktiv",
            "invalid",
            "inventar",
            "inversya",
            "intervyüw",
            "infinitiv",
            "kavaler",
            "kollektiv",
            "konv",
            "konserv",
            "kontrrev",
            "kooperativ",
            "kultiv",
            "kursiv",
            "lokomotiv",
            "nevro",
            "negativ",
            "nerv",
            "observ",
            "obyektiv",
            "operativ",
            "pavilyon",
            "portveyn",
            "privat",
            "progressiv",
            "radioaktiv",
            "reviz",
            "rezerv",
            "residiv",
            "servant",
            "servis",
            "sovet",
            "stolov",
            "subyektiv",
            "suveren",
            "televizor",
            "fakultativ",
            "fevral",
            "federativ",
            "festival",
            "sivil",
            "şovin",
            "ştativ",
            "evakua",
            "evolüs",
            "ekwival",
            "elevat",
            "yugoslav",
            "aktiv",
            "positiv",
            "piyala",
            "raiyat",
            "saqtiyan",
            "sariyaw",
            "siyah",
            "siyasat",
            "teliyañur",
            "xaliyar",
            "xasiyat",
            "siyala",
            "siyan",
            "qansiyala",
            "ixtiyar",
            "insani",
            "ziyara",
            "ziyan",
            "wasiyat",
            "biyabur",
            "maliyan",
            "xants",
            "küts",
            "hantsıl",
            "hantsuqal",
            "antsuqal",
            "zıltsın",
            "zıts",
            "litsil",
            "xutsiri",
            "xırts",
            "güñürt",
            "türt",
            "bürtük",
            "üyürt",
            "tükürt",
            "sürt",
            "ökürt",
            "kürt",
            "kükürt",
            "küsdürt",
            "söndürt",
            "yötkürt",
            "hürt",
            "güydürt",
            "göçürt",
            "göpürt",
            "göpdürt",
            "şüşgürt",
            "tarbiya",
            "saniya",
            "miya",
            "madaniya",
            "adabiya",
            "ahamiya",
            "qaliya",
            "kisiya",
            "iriya",
            "iliya",
            "camiya",
            "zabaniya",
            "deriya",
            "huriya",
            "hüriya",
            "hakimiya",
            "zurriya",
            "qapiya",
            "taziya",
            "yon",
            "bariyat",
            "yurt",
            "siñir",
            "süñün",
            "göñün",
            "xangişi"
        ];
    
        array_walk($exceptions["cyrillic"], function (&$currentword) {
            $currentword = preg_replace("/^/", "/", $currentword);
            $currentword = preg_replace("/$/", "/iu", $currentword);
        });
    
        $text = preg_replace_callback($exceptions["cyrillic"], function ($matches) use ($exceptions) {
            $check = mb_substr($matches[0], 0, 1);
            if (mb_strtoupper($check) == $check) {
                $replacement = preg_replace($exceptions["cyrillic"], $exceptions["latin"], $matches[0]);
                return ucfirst($replacement);
            } else {
                $replacement = preg_replace($exceptions["cyrillic"], $exceptions["latin"], $matches[0]);
                return $replacement;
            }
        }, $text);
    
        //Замены букв
        $text = str_replace('къ', 'q', $text); // къ
        $text = str_replace('КЪ', 'Q', $text); // КЪ
        $text = str_replace('Къ', 'Q', $text); // Къ
        $text = str_replace('гь', 'h', $text); // гь
        $text = str_replace('ГЬ', 'H', $text); // ГЬ
        $text = str_replace('Гь', 'H', $text); // Гь
        $text = str_replace('гъ', 'ğ', $text); // гъ
        $text = str_replace('ГЪ', 'Ğ', $text); // ГЪ
        $text = str_replace('Гъ', 'Ğ', $text); // Гъ
        $text = str_replace('уь', 'ü', $text); // уь
        $text = str_replace('УЬ', 'Ü', $text); // УЬ
        $text = str_replace('Уь', 'Ü', $text); // Уь
        $text = str_replace('оь', 'ö', $text); // оь
        $text = str_replace('ОЬ', 'Ö', $text); // ОЬ
        $text = str_replace('Оь', 'Ö', $text); // Оь
        
        $text = preg_replace('/\bЕ(?=\p{Lu})/u', 'YE', $text); // ^Е+заглавные
        $text = preg_replace('/\bЕ(?=\p{Ll})/u', 'Ye', $text); // ^Е+прописные
        $text = preg_replace('/\bе/u', 'ye', $text); // е+прописные
        $text = preg_replace('/(?<=[аоуэыяёюеиьъАОУЭЫЯЁЮЕИЬЪ])е/u', 'ye', $text); // гласная(любой регистр)+е
        $text = preg_replace('/(?<=[аоуэыяёюеиьъАОУЭЫЯЁЮЕИЬЪ])Е/u', 'YE', $text); // гласная(любой регистр)+Е
    
        $text = preg_replace('/(?<=[бвгджзклмнпрстфхцчшщйБВГДЖЗКЛМНПРСТФХЦЧШЩЙ])Я/u', 'Ä', $text); // согласная(любой регистр)+Я
        $text = preg_replace('/(?<=[бвгджзклмнпрстфхцчшщйБВГДЖЗКЛМНПРСТФХЦЧШЩЙ])я/u', 'ä', $text); // согласная(любой регистр)+я
        $text = preg_replace('/Я(?=\p{Ll})/u', 'Ya', $text); // Я + прописные
        $text = preg_replace('/\bЯ\b/u', 'Ya', $text); // Я отдельно стоящаяe
    
        $text = preg_replace('/(\bЁ|(?:(?<=Э)|(?<=E)|(?<=УЬ)|(?<=ОЬ)|(?<=Ю)|(?<=Ё|(?<=Ъ)))Ё)(?=([БВГДЖЗКЛМНПРСТФХЦЧШЩЙЬЪЁЮ]*(Е|И|УЬ|ОЬ|YE|Ye|\b))|К\b|\b)/u', 'YÖ', $text);
        $text = preg_replace('/(\bЁ|(?:(?<=э)|(?<=e)|(?<=уь)|(?<=оь)|(?<=ю)|(?<=Э)|(?<=E)|(?<=УЬ)|(?<=ОЬ)|(?<=Уь)|(?<=Оь)|(?<=ё)|(?<=Ё)|(?<=ъ)|(?<=Ъ))Ё)(?=([бвгджзклмнпрстфхцчшщйьъёю]*(Е|И|УЬ|ОЬ|е|и|уь|оь|YE|Ye|ye|\b))|К\b|к\b|\b)/u', 'Yö', $text);
        $text = preg_replace('/(\bЁ|(?<=[АОУЫЮЯЁЪ])Ё)(?=([БВГДЖЗКЛМНПРСТФХЦЧШЩЙЬЪĞQ]*(А|Ы|У|О|Ю|Ё|\b))|Q\b)/u', 'YO', $text);
        $text = preg_replace('/(\bЁ|(?<=[аоуыюяёъАОУЫЮЯЁЪ])Ё)(?=([бвгджзклмнпрстфхцчшщйьъğq]*(А|Ы|У|О|Ю|Ё|а|ы|у|о|ю|ё|\b))|Q\b|q\b)/u', 'Yo', $text);
        $text = preg_replace('/(\bЮ|(?:(?<=Э)|(?<=E)|(?<=УЬ)|(?<=ОЬ)|(?<=Ё)|(?<=Ö)|(?<=Ъ))Ю)(?=([БВГДЖЗКЛМНПРСТФХЦЧШЩЙЬЪЮЁÖ]*(Е|И|УЬ|ОЬ|YE|Ye|\b))|К\b)/u', 'YÜ', $text);
        $text = preg_replace('/(\bЮ|(?:(?<=э)|(?<=e)|(?<=уь)|(?<=оь)|(?<=ю)|(?<=Э)|(?<=E)|(?<=УЬ)|(?<=ОЬ)|(?<=Уь)|(?<=Оь)|(?<=ё)|(?<=Ё)|(?<=ö)|(?<=ъ)|(?<=Ъ))Ю)(?=([бвгджзклмнпрстфхцчшщйьъюёö]*(Е|И|УЬ|ОЬ|е|и|уь|оь|YE|Ye|ye|\b))|К\b|к\b)/u', 'Yü', $text);
        $text = preg_replace('/(\bЮ|(?<=[АОУЫЯЁЮЪO])Ю)(?=([БВГДЖЗКЛМНПРСТФХЦЧШЩЙЬЪĞQ]*(А|Ы|У|О|Ё|Ю|YO|\b))|КЪ\b)/u', 'YU', $text);
        $text = preg_replace('/(\bЮ|(?<=[аоуыяёюъАОУЫЯЁЮЪOo])Ю)(?=([бвгджзклмнпрстфхцчшщйьъğq]*(А|Ы|У|О|Ё|Ю|YO|Yo|а|ы|у|о|ё|ю|yo|\b))|КЪ\b|къ\b)/u', 'Yu', $text);
    
        $text = preg_replace('/(\bё|(?:(?<=э)|(?<=e)|(?<=ü)|(?<=ö)|(?<=ю)|(?<=Э)|(?<=E)|(?<=Ü)|(?<=Ö)|(?<=ё)|(?<=Ё)|(?<=ъ)|(?<=Ъ))ё)(?=([бвгджзклмнпрстфхцчшщйьъёю]*(Е|И|Ü|ОЬ|е|и|ü|ö|YE|Ye|ye|\b))|К\b|к\b|\b)/u', 'yö', $text);
        $text = preg_replace('/(\bё|(?<=[аоуыюяёъАОУЫЮЯЁЪ])ё)(?=([бвгджзклмнпрстфхцчшщйьъğq]*(А|Ы|У|О|Ю|Ё|а|ы|у|о|ю|ё|\b))|Q\b|q\b)/u', 'yo', $text);
    
        $text = preg_replace('/(\bю|(?:(?<=э)|(?<=e)|(?<=ü)|(?<=ö)|(?<=ю)|(?<=Э)|(?<=E)|(?<=Ü)|(?<=Ö)|(?<=ё)|(?<=Ё)|(?<=ö)|(?<=ъ)|(?<=Ъ))ю)(?=([бвгджзклмнпрстфхцчшщйьъюёö]*(Е|И|Ü|Ö|е|и|ü|ö|YE|Ye|ye|\b))|К\b|к\b)/u', 'yü', $text);
        $text = preg_replace('/(\bю|(?<=[аоуыяёюъАОУЫЯЁЮЪOo])ю)(?=([бвгджзклмнпрстфхцчшщйьъğq]*(А|Ы|У|О|Ё|Ю|а|ы|у|о|ё|ю|yo|\b))|КЪ\b|къ\b)/u', 'yu', $text);
    
        $text = str_replace('ДЖ', 'C', $text); // ДЖ
        $text = str_replace('дж', 'c', $text); // дж
        $text = preg_replace('/\bЖ/u', 'C', $text); // ^Ж
        $text = preg_replace('/\bж/u', 'c', $text); // ^ж
    
        $text = preg_replace('/ЗИЯ\b/u', 'SYA', $text); // ЗИЯ^
        $text = preg_replace('/зия\b/iu', 'sya', $text); // зия^ без учёта регистра
        $text = preg_replace('/ЛЬОН\b/u', 'LYON', $text); // ЛЬОН^
        $text = preg_replace('/льон\b/iu', 'lyon', $text); // льон^ без учёта регистра
        $text = preg_replace('/СЬОН\b/u', 'SYON', $text); // СЬОН^
        $text = preg_replace('/сьон\b/iu', 'syon', $text); // сьон^ без учёта регистра
        $text = preg_replace('/ЛЬЕ\b/u', 'LYE', $text); // ЛЬЕ^
        $text = preg_replace('/лье\b/iu', 'lye', $text); // лье^ без учёта регистра
        $text = preg_replace('/(?<![БВГДЖЗКЛМНПРСТФХЦЧШЩЙ]{2}|[АЕИОУЫЭЮЯOÖUÜ])ИЯ/u', 'YA', $text); // not согласная*2 | гласная + ИЯ(раньше было дополнительно + конец слова)
        $text = preg_replace('/(?<![бвгджзклмнпрстфхцчшщй]{2}|[аеиоуыэюяoöuü])ия/iu', 'ya', $text); // not согласная*2 | гласная + ия без учёта регистра(раньше было дополнительно + конец слова)
    
        $text = str_replace('НГЕН', 'NGEN', $text); // НГЕН^
        $text = str_ireplace('нген', 'ngen', $text); // нген^ без учёта регистра
        $text = str_replace('ГЕНГ', 'GENG', $text); // ГЕНГ^
        $text = str_ireplace('генг', 'geng', $text); // генг^ без учёта регистра
        $text = str_replace('НГИН', 'NGİN', $text); // НГИН^
        $text = str_ireplace('нгин', 'ngin', $text); // нгин^ без учёта регистра
        $text = str_replace('НГЪЫН', 'NĞİN', $text); // НГЪЫН^
        $text = str_ireplace('нгъын', 'nğın', $text); // нгъын^ без учёта регистра
        $text = str_replace('НГЪУН', 'NĞUN', $text); // НГЪУН^
        $text = str_ireplace('нгъун', 'nğun', $text); // нгъун^ без учёта регистра
        $text = str_replace('НГЮН', 'NGÜN;', $text); // НГЮН^
        $text = str_ireplace('нгюн', 'ngün;', $text); // нгюн^ без учёта регистра
        $text = str_replace('НГЪЫР', 'NĞİR', $text); // НГЪЫР^
        $text = str_ireplace('нгъыр', 'nğır', $text); // нгъыр^ без учёта регистра
        $text = str_replace('НГИР', 'NGİR', $text); // НГИР^
        $text = str_ireplace('нгир', 'ngir', $text); // нгир^ без учёта регистра
        $text = str_replace('НГЪУР', 'NGUR', $text); // НГЪУР^
        $text = str_ireplace('нгъур', 'ngur', $text); // нгъур^ без учёта регистра
        $text = str_replace('НГЮР', 'NGÜR', $text); // НГЮР^
        $text = str_ireplace('нгюр', 'ngür', $text); // нгюр^ без учёта регистра
    
        $text = str_replace('НГ', 'Ñ', $text); // НГ
        $text = str_replace('Нг', 'Ñ', $text); // Нг
        $text = str_replace('нг', 'ñ', $text); // нг
        $text = str_replace('Ж', 'J', $text); // Ж
        $text = str_replace('ж', 'j', $text); // ж
        $text = str_replace('ё', 'ö', $text); // ё
        $text = str_replace('Ё', 'Ö', $text); // Ё
        $text = str_replace('ю', 'ü', $text); // ю
        $text = str_replace('Ю', 'Ü', $text); // Ю
        $text = str_replace('е', 'e', $text); // е
        $text = str_replace('Е', 'E', $text); // Е
        $text = str_replace('а', 'a', $text); // а
        $text = str_replace('А', 'A', $text); // А
        $text = str_replace('б', 'b', $text); // б
        $text = str_replace('Б', 'B', $text); // Б
        $text = str_replace('з', 'z', $text); // з
        $text = str_replace('З', 'Z', $text); // З
        $text = str_replace('й', 'y', $text); // й
        $text = str_replace('Й', 'Y', $text); // Й
        $text = str_replace('ч', 'ç', $text); // ч
        $text = str_replace('Ч', 'Ç', $text); // Ч
        $text = str_replace('ц', 's', $text); // ц
        $text = str_replace('Ц', 'S', $text); // Ц
        $text = str_replace('д', 'd', $text); // д
        $text = str_replace('Д', 'D', $text); // Д
        $text = str_replace('э', 'e', $text); // э
        $text = str_replace('Э', 'E', $text); // Э
        $text = str_replace('ф', 'f', $text); // ф
        $text = str_replace('Ф', 'F', $text); // Ф
        $text = str_replace('г', 'g', $text); // г
        $text = str_replace('Г', 'G', $text); // Г
        $text = str_replace('ы', 'ı', $text); // ы
        $text = str_replace('Ы', 'I', $text); // Ы
        $text = str_replace('и', 'i', $text); // и
        $text = str_replace('И', 'İ', $text); // И
        $text = str_replace('к', 'k', $text); // к
        $text = str_replace('К', 'K', $text); // К
        $text = str_replace('л', 'l', $text); // л
        $text = str_replace('Л', 'L', $text); // Л
        $text = str_replace('м', 'm', $text); // м
        $text = str_replace('М', 'M', $text); // М
        $text = str_replace('н', 'n', $text); // н
        $text = str_replace('Н', 'N', $text); // Н
        $text = str_replace('о', 'o', $text); // о
        $text = str_replace('О', 'O', $text); // О
        $text = str_replace('п', 'p', $text); // п
        $text = str_replace('П', 'P', $text); // П
        $text = str_replace('р', 'r', $text); // р
        $text = str_replace('Р', 'R', $text); // Р
        $text = str_replace('с', 's', $text); // с
        $text = str_replace('С', 'S', $text); // С
        $text = str_replace('ш', 'ş', $text); // ш
        $text = str_replace('Ш', 'Ş', $text); // Ш
        $text = str_replace('щ', 'ş', $text); // щ
        $text = str_replace('Щ', 'Ş', $text); // Щ
        $text = str_replace('т', 't', $text); // т
        $text = str_replace('Т', 'T', $text); // Т
        $text = str_replace('у', 'u', $text); // у
        $text = str_replace('У', 'U', $text); // У
        $text = str_replace('х', 'x', $text); // х
        $text = str_replace('Х', 'X', $text); // Х
        $text = str_replace('в', 'w', $text); // в
        $text = str_replace('В', 'W', $text); // В
        $text = preg_replace('/ъ(я|ya|yu|yü|ye|yo|yö|ü|ö)/iu', '$1', $text); // ъя, ъю, ъё, …
        $text = str_ireplace('ъ', 'ʼ', $text); // ъ
        $text = str_replace('ь', '', $text); // ь
        $text = str_replace('я', 'ya', $text); // я
        $text = str_replace('Я', 'YA', $text); // Я
    
        return $text;
    }

    /**
     * Add Twig Extension
     *
     * @return void
     */
    public function onTwigExtensions()
    {
        include_once __DIR__ . '/twig/TransliterateCyrillicExtension.php';
        $this->grav['twig']->twig->addExtension(new TransliterateCyrillicExtension());
    }
}
