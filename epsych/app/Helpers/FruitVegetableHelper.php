<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

class FruitVegetableHelper
{
    public static function getRandom(): string
    {
        $fruits = Lang::get('fruits_vegetables.fruits');
        $vegetables = Lang::get('fruits_vegetables.vegetables');
        $items = array_merge($fruits, $vegetables);

        $usedItems = Session::get('used_fruits_vegetables', []);

        $availableItems = array_diff($items, $usedItems);

        if (empty($availableItems)) {
            Session::forget('used_fruits_vegetables');
            $availableItems = $items;
        }

        $randomItem = Arr::random($availableItems);

        $usedItems[] = $randomItem;
        Session::put('used_fruits_vegetables', $usedItems);

        return $randomItem;
    }
}
