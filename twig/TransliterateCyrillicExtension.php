<?php
namespace Grav\Plugin;

use Grav\Plugin\TransliterateCyrillicPlugin;

/**
 * Add Twig-extensions for transliterating cyrillic
 */
class TransliterateCyrillicExtension extends \Twig_Extension
{
    /**
     * Declare extension-name
     *
     * @return void
     */
    public function getName()
    {
        return 'TransliterateCyrillic';
    }

    /**
     * Add filter
     *
     * @return void
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('transliterate_cyrillic', [$this, 'TransliterateCyrillic']),
        ];
    }

    /**
     * Add function
     *
     * @return void
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('transliterate_cyrillic', [$this, 'TransliterateCyrillic'])
        ];
    }

    /**
     * Call plugin static function
     *
     * @return void
     */
    public function TransliterateCyrillic($content)
    {
        return TransliterateCyrillicPlugin::transliterateCyrillic($content);
    }
}