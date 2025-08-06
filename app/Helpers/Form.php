<?php

namespace App\Helpers;

use App\Models\Klasifikasi;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use function Laravel\Prompts\select;

class Form
{
    public static function showklasifikasi($klasifikasiOptions, $selected = null)
    {
        $view = Blade::render('components.default-input', [
            'klasifikasiOptions' => $klasifikasiOptions,
            'options' => $selected,
        ]);
        $subKlasifikasi = collect($klasifikasiOptions)->filter(function ($item) use ($selected) {
            return $item->parent_id == $selected;
        })->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->nama,
            ];
        });
        foreach ($subKlasifikasi as $item) {
            $item->subKlasifikasi = self::showklasifikasi($klasifikasiOptions, $item->id);
        }
    }

    public static function generateSelectOptions($items, $categories, $parentId = null, $level = 0)
    {
        // Filter categories that match the parent_id
        $filteredCategories = $categories->filter(function ($item) use ($parentId) {
            return $item->parent_id == $parentId;
        });
        // If no categories are found, return empty string
        if (empty($filteredCategories)) {
            return '';
        }

        // Initialize the select options string
        $view = '';
        if (count($filteredCategories) > 0) {
            $view = view('default-input', [
                'items' => $items,
                'options' => $filteredCategories->mapWithKeys(function ($item) {
                    return [
                        $item->id => $item->nama,
                    ];
                }),
                'label' => 'Klasifikasi ' . ($level + 1),
                'name' => 'items.klasifikasi.' . $level,
                'type' => 'select',
            ])->render();
        }
        if (isset($items['klasifikasi'][$level]) && $items['klasifikasi'][$level]) {
            $view .= self::generateSelectOptions($items, $categories, isset($items['klasifikasi'][$level]) ? $items['klasifikasi'][$level] : null, $level + 1);
        }
        return $view;
    }

}
