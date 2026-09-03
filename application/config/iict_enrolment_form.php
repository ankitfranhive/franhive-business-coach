<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| IICT Enrolment and Payment Agreement - Static Form Definition
|--------------------------------------------------------------------------
| Single static form; fields match the IICT Enrolment and Payment Agreement.
| Edit this file to change labels or add/remove fields.
*/
$config['iict_enrolment_agreement'] = [

    'template' => [
        'id'               => 0,
        'form_type_id'     => 0,
        'slug'             => 'iict-enrolment-agreement',
        'title'            => 'IICT Enrolment and Payment Agreement',
        'heading'          => 'IICT Enrolment and Payment Agreement',
        'subheading'       => 'Please complete all fields and sign below.',
        'body_html'        => '<p>By completing this form you agree to the terms of enrolment and payment as set out in the IICT Enrolment and Payment Agreement.</p>',
        'overrides_json'   => '{}',
        'agree_text'       => 'I have read and agree to the IICT Enrolment and Payment Agreement terms and conditions.',
    ],

    // Fields: name, label, type, is_required, sort_order, options_json (optional)
    // Types: text, email, number, date, textarea, select, checkbox, radio, signature
    'fields' => [
        ['name' => 'full_name',           'label' => 'Full Name',              'type' => 'text',     'is_required' => 1, 'sort_order' => -10, 'options_json' => null],
        ['name' => 'email',               'label' => 'Email',                  'type' => 'email',   'is_required' => 1, 'sort_order' => -9,  'options_json' => null],
        ['name' => 'phone',               'label' => 'Phone',                  'type' => 'text',     'is_required' => 1, 'sort_order' => -8,  'options_json' => null],
        ['name' => 'address',             'label' => 'Address',                'type' => 'textarea', 'is_required' => 1, 'sort_order' => -7,  'options_json' => null],
        ['name' => 'city',                'label' => 'City / Suburb',          'type' => 'text',     'is_required' => 1, 'sort_order' => -6,  'options_json' => null],
        ['name' => 'state',               'label' => 'State / Territory',       'type' => 'text',     'is_required' => 1, 'sort_order' => -5,  'options_json' => null],
        ['name' => 'postcode',            'label' => 'Postcode',                'type' => 'text',     'is_required' => 1, 'sort_order' => -4,  'options_json' => null],
        ['name' => 'date_of_birth',       'label' => 'Date of Birth',           'type' => 'date',     'is_required' => 1, 'sort_order' => -3,  'options_json' => null],
        ['name' => 'course_program',       'label' => 'Course / Program Name',   'type' => 'text',     'is_required' => 1, 'sort_order' => -2,  'options_json' => null],
        ['name' => 'emergency_contact',    'label' => 'Emergency Contact Name',  'type' => 'text',     'is_required' => 0, 'sort_order' => 0,   'options_json' => null],
        ['name' => 'emergency_phone',     'label' => 'Emergency Contact Phone', 'type' => 'text',     'is_required' => 0, 'sort_order' => 1,   'options_json' => null],
    ],
];
