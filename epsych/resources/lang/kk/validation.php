<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Сіз :attribute қабылдауыңыз керек.',
    'active_url' => ':attribute дұрыс URL емес.',
    'after' => ':attribute :date кейінгі күн болуы керек.',
    'after_or_equal' => ':attribute :date кейінгі немесе оған тең күн болуы керек.',
    'alpha' => ':attribute тек әріптерден тұруы керек.',
    'alpha_dash' => ':attribute тек әріптер, сандар, сызықша және асты сызылған белгілерден тұруы керек.',
    'alpha_num' => ':attribute тек әріптер мен сандардан тұруы керек.',
    'array' => ':attribute массив болуы керек.',
    'before' => ':attribute :date дейінгі күн болуы керек.',
    'before_or_equal' => ':attribute :date дейінгі немесе оған тең күн болуы керек.',
    'between' => [
        'numeric' => ':attribute мәні :min мен :max арасында болуы керек.',
        'file' => ':attribute өлшемі :min және :max килобайт арасында болуы керек.',
        'string' => ':attribute ұзындығы :min мен :max символдар арасында болуы керек.',
        'array' => ':attribute элементтері :min мен :max аралығында болуы керек.',
    ],
    'boolean' => ':attribute мәні шын немесе жалған болуы керек.',
    'confirmed' => ':attribute растауы сәйкес келмейді.',
    'date' => ':attribute дұрыс күн емес.',
    'date_format' => ':attribute :format форматымен сәйкес келмейді.',
    'different' => ':attribute мен :other әртүрлі болуы керек.',
    'digits' => ':attribute саны :digits цифр болуы керек.',
    'digits_between' => ':attribute саны :min мен :max цифр арасында болуы керек.',
    'dimensions' => ':attribute суреттің қате өлшемдері бар.',
    'distinct' => ':attribute өрісінде қайталанатын мән бар.',
    'email' => ':attribute жарамды электрондық пошта болуы керек.',
    'exists' => 'Таңдалған :attribute жарамсыз.',
    'file' => ':attribute файл болуы керек.',
    'filled' => ':attribute өрісі толтырылуы міндетті.',
    'image' => ':attribute сурет болуы керек.',
    'in' => 'Таңдалған :attribute жарамсыз.',
    'in_array' => ':attribute өрісі :other ішінде жоқ.',
    'integer' => ':attribute бүтін сан болуы керек.',
    'ip' => ':attribute жарамды IP мекенжайы болуы керек.',
    'ipv4' => ':attribute жарамды IPv4 мекенжайы болуы керек.',
    'ipv6' => ':attribute жарамды IPv6 мекенжайы болуы керек.',
    'json' => ':attribute жарамды JSON жолы болуы керек.',
    'max' => [
        'numeric' => ':attribute мәні :max артық болмауы керек.',
        'file' => ':attribute өлшемі :max килобайттан аспауы керек.',
        'string' => ':attribute ұзындығы :max символдан аспауы керек.',
        'array' => ':attribute :max элементтен аспауы керек.',
    ],
    'mimes' => ':attribute келесі типтегі файл болуы керек: :values.',
    'mimetypes' => ':attribute келесі типтегі файл болуы керек: :values.',
    'min' => [
        'numeric' => ':attribute кем дегенде :min болуы керек.',
        'file' => ':attribute кем дегенде :min килобайт болуы керек.',
        'string' => ':attribute кем дегенде :min символ болуы керек.',
        'array' => ':attribute кемінде :min элемент болуы керек.',
    ],
    'not_in' => 'Таңдалған :attribute жарамсыз.',
    'numeric' => ':attribute сан болуы керек.',
    'present' => ':attribute өрісі болуы керек.',
    'regex' => ':attribute форматы жарамсыз.',
    'required' => ':attribute өрісі міндетті.',
    'required_if' => ':other :value болғанда :attribute толтырылуы міндетті.',
    'required_unless' => ':other :values ішінде болмаса, :attribute толтырылуы міндетті.',
    'required_with' => ':values болғанда :attribute толтырылуы міндетті.',
    'required_with_all' => ':values болғанда :attribute толтырылуы міндетті.',
    'required_without' => ':values болмаған кезде :attribute толтырылуы міндетті.',
    'required_without_all' => ':values біреуі де болмаған кезде :attribute толтырылуы міндетті.',
    'same' => ':attribute мен :other сәйкес келуі керек.',
    'size' => [
        'numeric' => ':attribute өлшемі :size болуы керек.',
        'file' => ':attribute өлшемі :size килобайт болуы керек.',
        'string' => ':attribute ұзындығы :size символ болуы керек.',
        'array' => ':attribute :size элементтен тұруы керек.',
    ],
    'string' => ':attribute жол болуы керек.',
    'timezone' => ':attribute жарамды уақыт белдеуі болуы керек.',
    'unique' => ':attribute мәні бұрыннан бар.',
    'uploaded' => ':attribute жүктеу сәтсіз аяқталды.',
    'url' => ':attribute форматы жарамсыз.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'questionOptionSelectedId' => [
            'required' => 'Жауап міндетті түрде таңдалуы керек.',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'event_date' => 'Іс-шараның күні',
        'student_id' => 'Оқушы',
        'group_id' => 'Топ',
        'title' => 'Тақырып',
        'name' => 'Аты',
        'surname' => 'Тегі',
        'patronymic' => 'Әкесінің аты',
        'email' => 'Эл. пошта',
        'password' => 'Құпиясөз',
        'password_confirmation' => 'Құпиясөзді қайталаңыз',
        'terms' => 'Шарттар',
        'role_selected' => 'Рөл',
        'organization_selected_id' => 'Ұйымның БСН',
        'region_selected_id' => 'Өңір',
        'district_selected_id' => 'Аудан',
        'generalCharacteristics' => 'Жалпы сипаттама',
        'iin' => 'ЖСН',
        'birthday' => 'Туған күні',
        'phone' => 'Телефон',
        'classroom_id' => 'Сынып',
        'classRoomSelected' => 'Сынып',
        'classNumberSelected' => 'Сынып',
        'classLetterSelected' => 'Әріп',
        'languageSelected' => 'Оқу тілі',
        'specialMarks' => 'Ерекше белгілер',
        'incidents' => 'Полицияға шақыртулар, оқиғалар',
        'specialStatusSelected' => 'Ерекше статус',
        'form.tmpDocuments.1' => 'Оқиға құжаттары',
        'form.tmpDocuments.1.*' => 'Оқиға құжаттары',
        'form.tmpDocuments.2' => 'Үлгерім',
        'form.tmpDocuments.2.*' => 'Үлгерім құжаттары',
        'form.tmpDocuments.3' => 'Портфолио',
        'form.tmpDocuments.3.*' => 'Портфолио құжаттары',
        'form.tmpPhoto' => 'Фотосурет',
        'form.tmpPhoto.*' => 'Фотосурет',
        'form.questionOptionSelectedId' => 'Жауап нұсқасы міндетті түрде таңдалуы керек',
    ],

];
