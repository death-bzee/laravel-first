<?php

return [

    'column_toggle' => [

        'heading' => 'Бағандар',

    ],

    'columns' => [

        'text' => [

            'actions' => [
                'collapse_list' => ':count жасыру',
                'expand_list' => 'Тағы :count көрсету',
            ],

            'more_list_items' => 'және тағы :count',

        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => 'Жаппай әрекеттер үшін барлық элементтерді таңдау/таңдаудан бас тарту.',
        ],

        'bulk_select_record' => [
            'label' => 'Жаппай әрекеттер үшін :key таңдау/бас тарту.',
        ],

        'bulk_select_group' => [
            'label' => 'Жаппай әрекеттер үшін :title топтық таңдау/бас тарту.',
        ],

        'search' => [
            'label' => 'Іздеу',
            'placeholder' => 'Іздеу',
            'indicator' => 'Іздеу',
        ],

    ],

    'summary' => [

        'heading' => 'Жиынтық',

        'subheadings' => [
            'all' => 'Барлық :label',
            'group' => ':group жиынтығы',
            'page' => 'Осы бет',
        ],

        'summarizers' => [

            'average' => [
                'label' => 'Орташа',
            ],

            'count' => [
                'label' => 'Саны',
            ],

            'sum' => [
                'label' => 'Жиынтық',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => 'Реттілікті сақтау',
        ],

        'enable_reordering' => [
            'label' => 'Реттілікті өзгерту',
        ],

        'filter' => [
            'label' => 'Сүзгі',
        ],

        'group' => [
            'label' => 'Топтау',
        ],

        'open_bulk_actions' => [
            'label' => 'Әрекеттерді ашу',
        ],

        'toggle_columns' => [
            'label' => 'Бағандарды ауыстыру',
        ],

    ],

    'empty' => [

        'heading' => ':model табылмады',

        'description' => 'Бастау үшін :model жасаңыз.',

    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => 'Сүзгілерді қолдану',
            ],

            'remove' => [
                'label' => 'Сүзгіні жою',
            ],

            'remove_all' => [
                'label' => 'Сүзгілерді тазарту',
                'tooltip' => 'Сүзгілерді тазарту',
            ],

            'reset' => [
                'label' => 'Қалпына келтіру',
            ],

        ],

        'heading' => 'Сүзгілер',

        'indicator' => 'Белсенді сүзгілер',

        'multi_select' => [
            'placeholder' => 'Барлығы',
        ],

        'select' => [
            'placeholder' => 'Барлығы',
        ],

        'trashed' => [

            'label' => 'Жойылған жазбалар',

            'only_trashed' => 'Тек жойылған жазбалар',

            'with_trashed' => 'Жойылған жазбалармен бірге',

            'without_trashed' => 'Жойылған жазбаларсыз',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => 'Топтау',
                'placeholder' => 'Топтау бойынша',
            ],

            'direction' => [

                'label' => 'Бағыт',

                'options' => [
                    'asc' => 'Өсу ретімен',
                    'desc' => 'Кему ретімен',
                ],

            ],

        ],

    ],

    'reorder_indicator' => 'Реттілікті өзгерту үшін жазбаларды сүйреңіз.',

    'selection_indicator' => [

        'selected_count' => '1 жазба таңдалды|:count жазба таңдалды',

        'actions' => [

            'select_all' => [
                'label' => 'Барлығын таңдау :count',
            ],

            'deselect_all' => [
                'label' => 'Барлық таңдалғандарды алып тастау',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'Сұрыптау',
            ],

            'direction' => [

                'label' => 'Бағыт',

                'options' => [
                    'asc' => 'Өсу ретімен',
                    'desc' => 'Кему ретімен',
                ],

            ],

        ],

    ],

];
