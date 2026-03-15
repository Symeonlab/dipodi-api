<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (Arabic)
    |--------------------------------------------------------------------------
    */

    'accepted' => 'يجب قبول حقل :attribute.',
    'accepted_if' => 'يجب قبول حقل :attribute عندما يكون :other هو :value.',
    'active_url' => 'حقل :attribute يجب أن يكون عنوان URL صالحاً.',
    'after' => 'حقل :attribute يجب أن يكون تاريخاً بعد :date.',
    'after_or_equal' => 'حقل :attribute يجب أن يكون تاريخاً مساوياً أو بعد :date.',
    'alpha' => 'حقل :attribute يجب أن يحتوي على أحرف فقط.',
    'alpha_dash' => 'حقل :attribute يجب أن يحتوي على أحرف، أرقام، شرطات وشرطات سفلية فقط.',
    'alpha_num' => 'حقل :attribute يجب أن يحتوي على أحرف وأرقام فقط.',
    'array' => 'حقل :attribute يجب أن يكون مصفوفة.',
    'before' => 'حقل :attribute يجب أن يكون تاريخاً قبل :date.',
    'before_or_equal' => 'حقل :attribute يجب أن يكون تاريخاً مساوياً أو قبل :date.',
    'between' => [
        'array' => 'حقل :attribute يجب أن يحتوي بين :min و :max عنصر.',
        'file' => 'حقل :attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون بين :min و :max.',
        'string' => 'حقل :attribute يجب أن يكون بين :min و :max حرف.',
    ],
    'boolean' => 'حقل :attribute يجب أن يكون صحيحاً أو خاطئاً.',
    'confirmed' => 'تأكيد حقل :attribute غير متطابق.',
    'date' => 'حقل :attribute يجب أن يكون تاريخاً صالحاً.',
    'email' => 'حقل :attribute يجب أن يكون عنوان بريد إلكتروني صالح.',
    'exists' => 'حقل :attribute المحدد غير صالح.',
    'file' => 'حقل :attribute يجب أن يكون ملفاً.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'image' => 'حقل :attribute يجب أن يكون صورة.',
    'in' => 'حقل :attribute المحدد غير صالح.',
    'integer' => 'حقل :attribute يجب أن يكون عدداً صحيحاً.',
    'max' => [
        'array' => 'حقل :attribute يجب ألا يحتوي على أكثر من :max عنصر.',
        'file' => 'حقل :attribute يجب ألا يتجاوز :max كيلوبايت.',
        'numeric' => 'حقل :attribute يجب ألا يتجاوز :max.',
        'string' => 'حقل :attribute يجب ألا يتجاوز :max حرف.',
    ],
    'min' => [
        'array' => 'حقل :attribute يجب أن يحتوي على الأقل :min عنصر.',
        'file' => 'حقل :attribute يجب أن يكون على الأقل :min كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون على الأقل :min.',
        'string' => 'حقل :attribute يجب أن يحتوي على الأقل :min حرف.',
    ],
    'not_in' => 'حقل :attribute المحدد غير صالح.',
    'numeric' => 'حقل :attribute يجب أن يكون رقماً.',
    'required' => 'حقل :attribute مطلوب.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_with' => 'حقل :attribute مطلوب عند وجود :values.',
    'required_without' => 'حقل :attribute مطلوب عند عدم وجود :values.',
    'same' => 'حقل :attribute و :other يجب أن يتطابقا.',
    'size' => [
        'array' => 'حقل :attribute يجب أن يحتوي على :size عنصر.',
        'file' => 'حقل :attribute يجب أن يكون :size كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون :size.',
        'string' => 'حقل :attribute يجب أن يكون :size حرف.',
    ],
    'string' => 'حقل :attribute يجب أن يكون نصاً.',
    'unique' => 'حقل :attribute مُستخدم بالفعل.',
    'url' => 'حقل :attribute يجب أن يكون عنوان URL صالحاً.',

    'failed' => 'فشل التحقق',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'name' => [
            'required' => 'الاسم مطلوب.',
        ],
        'email' => [
            'required' => 'البريد الإلكتروني مطلوب.',
            'email' => 'يرجى إدخال عنوان بريد إلكتروني صالح.',
            'unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
        ],
        'password' => [
            'required' => 'كلمة المرور مطلوبة.',
            'min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
    ],

];
