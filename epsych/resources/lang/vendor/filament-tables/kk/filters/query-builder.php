<?php

return [

    'label' => 'Сұраныс құрастырушы',

    'form' => [

        'operator' => [
            'label' => 'Оператор',
        ],

        'or_groups' => [

            'label' => 'Топтар',

            'block' => [
                'label' => 'Дизъюнкция (НЕМЕСЕ)',
                'or' => 'НЕМЕСЕ',
            ],

        ],

        'rules' => [

            'label' => 'Ережелер',

            'item' => [
                'and' => 'ЖӘНЕ',
            ],

        ],

    ],

    'no_rules' => '(Ережелер жоқ)',

    'item_separators' => [
        'and' => 'ЖӘНЕ',
        'or' => 'НЕМЕСЕ',
    ],

    'operators' => [

        'is_filled' => [

            'label' => [
                'direct' => 'Толтырылған',
                'inverse' => 'Бос',
            ],

            'summary' => [
                'direct' => ':attribute толтырылған',
                'inverse' => ':attribute бос',
            ],

        ],

        'boolean' => [

            'is_true' => [

                'label' => [
                    'direct' => 'Шын',
                    'inverse' => 'Жалған',
                ],

                'summary' => [
                    'direct' => ':attribute шын',
                    'inverse' => ':attribute жалған',
                ],

            ],

        ],

        'date' => [

            'is_after' => [

                'label' => [
                    'direct' => 'Кейін',
                    'inverse' => 'Кейін емес',
                ],

                'summary' => [
                    'direct' => ':attribute :date кейін',
                    'inverse' => ':attribute :date кейін емес',
                ],

            ],

            'is_before' => [

                'label' => [
                    'direct' => 'Бұрын',
                    'inverse' => 'Бұрын емес',
                ],

                'summary' => [
                    'direct' => ':attribute :date бұрын',
                    'inverse' => ':attribute :date бұрын емес',
                ],

            ],

            'form' => [

                'date' => [
                    'label' => 'Күні',
                ],

                'month' => [
                    'label' => 'Айы',
                ],

                'year' => [
                    'label' => 'Жылы',
                ],

            ],

        ],

        'number' => [

            'equals' => [

                'label' => [
                    'direct' => 'Тең',
                    'inverse' => 'Тең емес',
                ],

                'summary' => [
                    'direct' => ':attribute :number тең',
                    'inverse' => ':attribute :number тең емес',
                ],

            ],

            'is_max' => [

                'label' => [
                    'direct' => 'Максимум',
                    'inverse' => 'Көп',
                ],

                'summary' => [
                    'direct' => ':attribute максимум :number',
                    'inverse' => ':attribute :number көп',
                ],

            ],

            'is_min' => [

                'label' => [
                    'direct' => 'Минимум',
                    'inverse' => 'Аз',
                ],

                'summary' => [
                    'direct' => ':attribute минимум :number',
                    'inverse' => ':attribute :number аз',
                ],

            ],

        ],

        'select' => [

            'is' => [

                'label' => [
                    'direct' => 'Бұл',
                    'inverse' => 'Бұл емес',
                ],

                'summary' => [
                    'direct' => ':attribute - :values',
                    'inverse' => ':attribute - :values емес',
                ],

                'form' => [

                    'value' => [
                        'label' => 'Мәні',
                    ],

                    'values' => [
                        'label' => 'Мәндер',
                    ],

                ],

            ],

        ],

        'text' => [

            'contains' => [

                'label' => [
                    'direct' => 'Құрамында бар',
                    'inverse' => 'Құрамында жоқ',
                ],

                'summary' => [
                    'direct' => ':attribute :text құрамында бар',
                    'inverse' => ':attribute :text құрамында жоқ',
                ],

            ],

            'ends_with' => [

                'label' => [
                    'direct' => 'Осымен аяқталады',
                    'inverse' => 'Осымен аяқталмайды',
                ],

                'summary' => [
                    'direct' => ':attribute :text деп аяқталады',
                    'inverse' => ':attribute :text деп аяқталмайды',
                ],

            ],

            'equals' => [

                'label' => [
                    'direct' => 'Тең',
                    'inverse' => 'Тең емес',
                ],

                'summary' => [
                    'direct' => ':attribute :text тең',
                    'inverse' => ':attribute :text тең емес',
                ],

            ],

            'starts_with' => [

                'label' => [
                    'direct' => 'Осыдан басталады',
                    'inverse' => 'Осыдан басталмайды',
                ],

                'summary' => [
                    'direct' => ':attribute :text деп басталады',
                    'inverse' => ':attribute :text деп басталмайды',
                ],

            ],

            'form' => [

                'text' => [
                    'label' => 'Мәтін',
                ],

            ],

        ],

    ],

    'actions' => [

        'add_rule' => [
            'label' => 'Ереже қосу',
        ],

        'add_rule_group' => [
            'label' => 'Ережелер тобын қосу',
        ],

    ],

];
