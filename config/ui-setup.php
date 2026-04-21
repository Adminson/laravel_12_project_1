<?php
return [
    'date_formats' => [
        'Y-m-d'   => '2026-03-25',
        'd-m-Y'   => '25-03-2026',
        'm-d-Y'   => '03-25-2026',

        'd M Y'   => '25 Mar 2026',
        'd F Y'   => '25 March 2026',
        'M d, Y'  => 'Mar 25, 2026',
        'F d, Y'  => 'March 25, 2026',

        'D, d M Y' => 'Wed, 25 Mar 2026',
        'l, d F Y' => 'Wednesday, 25 March 2026',

        'j M Y'   => '25 Mar 2026',
        'j F Y'   => '25 March 2026',
        'j-n-Y'   => '25-3-2026',
        'n-j-Y'   => '3-25-2026',
    ],

    'time_formats' => [
        'H:i:s'   => '20:50:10',
        'H:i'     => '20:50',

        'h:i A'   => '08:50 PM',
        'h:i:s A' => '08:50:10 PM',

        'g:i A'   => '8:50 PM',
        'g:i:s A' => '8:50:10 PM',

        'h:i a'   => '08:50 pm',
        'g:i a'   => '8:50 pm',
    ],

    'date_separators' => [
        '-' => '-',
        '/' => '/',
        '.' => '.',
    ],

    'yes_no' => [
        1 => 'Yes',
        0 => 'No',
    ],

    'font_families' => [
        'Arial, sans-serif' => 'Arial',
        '"Times New Roman", serif' => 'Times New Roman',
        '"Courier New", monospace' => 'Courier New',
        '"Roboto", sans-serif' => 'Roboto',
        '"Open Sans", sans-serif' => 'Open Sans',
        '"Inter", sans-serif' => 'Inter',
    ],

    'font_weights' => [
        'normal' => 'Normal',
        '500' => 'Medium',
        '600' => 'Semi Bold',
        'bold' => 'Bold',
    ],

    'label_positions' => [
        'top' => 'TOP of input field',
        'left' => 'LEFT of input field',
        'floating' => 'Floating',
    ],

    'colors' => [
        '#000000' => 'Black 100%',
        '#404040' => 'Black 75%',
        '#808080' => 'Black 50%',
        '#bfbfbf' => 'Black 25%',
        '#d9d9d9' => 'Black 15%',
        '#0d6efd' => 'Primary',
    ],
];
