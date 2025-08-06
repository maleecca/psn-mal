<?php

namespace App\Helpers;

class Menu
{
    static function getMenuLabel($route)
    {
        $navItems = config('nav');

        $findLabel = function ($items) use (&$findLabel, $route) {
            foreach ($items as $label => $item) {
                if (isset($item['route']) && $item['route'] === $route) {
                    return $label;
                }

                if (isset($item['child'])) {
                    $childLabel = $findLabel($item['child']);
                    if ($childLabel) {
                        return $childLabel;
                    }
                }
            }

            return null;
        };

        return $findLabel($navItems);
    }

    public static function setTabKategori($kategori)
    {
        session()->put('tab_kategori', $kategori);
    }

    public static function getTabKategori()
    {
        return session()->get('tab_kategori');
    }

    public static function setTabSubKategori($subKategori)
    {
        session()->put('tab_sub_kategori', $subKategori);
    }

    public static function getTabSubKategori()
    {
        return session()->get('tab_sub_kategori');
    }

    public static function setTabUmum($umum)
    {
        session()->put('tab_umum', $umum);
    }
    public static function getTabUmum()
    {
        return session()->get('tab_umum');
    }
    public static function setTabSubUmum($subUmum)
    {
        session()->put('tab_sub_umum', $subUmum);
    }
    public static function getTabSubUmum()
    {
        return session()->get('tab_sub_umum');
    }
    public static function setStatusFormulir($key)
    {

    }
}
