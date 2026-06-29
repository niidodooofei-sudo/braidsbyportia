<?php
// If CMS has saved a services override, use it; otherwise return built-in defaults
$_svc_override = dirname(__DIR__) . '/data/services.json';
if (is_file($_svc_override)) {
    $_ = json_decode(file_get_contents($_svc_override), true);
    if (is_array($_)) { unset($_svc_override); return $_; }
}
unset($_svc_override);

return [
    'quick' => [
        'name' => 'Quick & Specialty Styles',
        'desc' => 'Protective basics and versatile styles for everyday wear.',
        'type' => 'list',
        'services' => [
            ['id'=>'wig-braid-down',      'name'=>'Wig Braid Down',           'price'=>40,  'duration'=>60],
            ['id'=>'boy-twist',           'name'=>'Boy Twist',                'price'=>80,  'duration'=>90],
            ['id'=>'re-twist',            'name'=>'Re-Twist',                 'price'=>80,  'duration'=>90],
            ['id'=>'crochet',             'name'=>'Crochet',                  'price'=>120, 'duration'=>120],
            ['id'=>'half-cornrow-crochet','name'=>'Half Cornrow Half Crochet','price'=>180, 'duration'=>180],
        ],
    ],
    'stitch' => [
        'name' => 'Stitch Braids',
        'desc' => 'Precise, clean cornrow stitch patterns from 2 to 8 braids.',
        'type' => 'list',
        'services' => [
            ['id'=>'stitch-2','name'=>'2 Stitch Braids','price'=>50, 'duration'=>45],
            ['id'=>'stitch-4','name'=>'4 Stitch Braids','price'=>80, 'duration'=>60],
            ['id'=>'stitch-6','name'=>'6 Stitch Braids','price'=>100,'duration'=>80],
            ['id'=>'stitch-8','name'=>'8 Stitch Braids','price'=>120,'duration'=>90],
        ],
    ],
    'tribal' => [
        'name' => 'Tribal Braids',
        'desc' => 'Bold cornrow patterns inspired by African heritage. Medium size.',
        'type' => 'list',
        'services' => [
            ['id'=>'tribal-midback','name'=>'Tribal Braids – Midback','price'=>220,'duration'=>200],
            ['id'=>'tribal-waist',  'name'=>'Tribal Braids – Waist',  'price'=>240,'duration'=>240],
        ],
    ],
    'locs' => [
        'name' => 'Locs',
        'desc' => 'Lightweight faux loc styles gentle on the scalp.',
        'type' => 'list',
        'services' => [
            ['id'=>'faux-locs',      'name'=>'Faux Locs',      'price'=>220,'duration'=>280],
            ['id'=>'butterfly-locs', 'name'=>'Butterfly Locs', 'price'=>240,'duration'=>280],
        ],
    ],
    'box-braids' => [
        'name' => 'Box Braids',
        'desc' => 'Classic individual braids with a defined square parting. ~5 hrs.',
        'type' => 'matrix',
        'sizes'  => ['Large','Medium','Smedium','Small'],
        'lengths'=> ['Shoulder','Midback','Waist','Butt'],
        'prices' => [
            'Large'  =>['Shoulder'=>140,'Midback'=>180,'Waist'=>240,'Butt'=>260],
            'Medium' =>['Shoulder'=>180,'Midback'=>200,'Waist'=>260,'Butt'=>280],
            'Smedium'=>['Shoulder'=>200,'Midback'=>240,'Waist'=>280,'Butt'=>320],
            'Small'  =>['Shoulder'=>220,'Midback'=>240,'Waist'=>280,'Butt'=>300],
        ],
        'durations' => [
            'Large'  =>['Shoulder'=>280,'Midback'=>300,'Waist'=>300,'Butt'=>300],
            'Medium' =>['Shoulder'=>300,'Midback'=>300,'Waist'=>300,'Butt'=>300],
            'Smedium'=>['Shoulder'=>300,'Midback'=>300,'Waist'=>320,'Butt'=>300],
            'Small'  =>['Shoulder'=>300,'Midback'=>320,'Waist'=>340,'Butt'=>340],
        ],
    ],
    'knotless' => [
        'name' => 'Knotless Braids',
        'desc' => 'Feed-in braids with no knot — maximum comfort and hair health. ~5 hrs.',
        'type' => 'matrix',
        'sizes'  => ['Large','Medium','Smedium','Small'],
        'lengths'=> ['Shoulder','Midback','Waist','Butt'],
        'prices' => [
            'Large'  =>['Shoulder'=>160,'Midback'=>180,'Waist'=>200,'Butt'=>220],
            'Medium' =>['Shoulder'=>200,'Midback'=>240,'Waist'=>260,'Butt'=>280],
            'Smedium'=>['Shoulder'=>220,'Midback'=>260,'Waist'=>300,'Butt'=>320],
            'Small'  =>['Shoulder'=>240,'Midback'=>280,'Waist'=>320,'Butt'=>340],
        ],
        'durations' => [
            'Large'  =>['Shoulder'=>300,'Midback'=>300,'Waist'=>300,'Butt'=>300],
            'Medium' =>['Shoulder'=>300,'Midback'=>300,'Waist'=>320,'Butt'=>320],
            'Smedium'=>['Shoulder'=>300,'Midback'=>300,'Waist'=>300,'Butt'=>300],
            'Small'  =>['Shoulder'=>300,'Midback'=>320,'Waist'=>340,'Butt'=>320],
        ],
    ],
    'french-curls' => [
        'name' => 'French Curls',
        'desc' => 'Knotless braids finished with gorgeous curly ends. Boho options available. ~5 hrs.',
        'type' => 'matrix',
        'sizes'  => ['Medium','Smedium','Small'],
        'lengths'=> ['Shoulder','Midback','Waist','Butt'],
        'prices' => [
            'Medium' =>['Shoulder'=>180,'Midback'=>220,'Waist'=>240,'Butt'=>260],
            'Smedium'=>['Shoulder'=>200,'Midback'=>240,'Waist'=>260,'Butt'=>280],
            'Small'  =>['Shoulder'=>220,'Midback'=>260,'Waist'=>280,'Butt'=>300],
        ],
        'durations' => [
            'Medium' =>['Shoulder'=>300,'Midback'=>300,'Waist'=>320,'Butt'=>320],
            'Smedium'=>['Shoulder'=>300,'Midback'=>320,'Waist'=>320,'Butt'=>320],
            'Small'  =>['Shoulder'=>300,'Midback'=>300,'Waist'=>320,'Butt'=>320],
        ],
        'extras' => [
            ['id'=>'fc-boho-small-shoulder', 'name'=>'French Curls Small Shoulder Boho', 'price'=>280,'duration'=>320],
            ['id'=>'fc-boho-smed-midback',   'name'=>'French Curls Smedium Midback Boho','price'=>280,'duration'=>300],
        ],
    ],
    'twists' => [
        'name' => 'Twists',
        'desc' => 'Smooth, rope-like protective twists in four sizes and lengths. ~5 hrs.',
        'type' => 'matrix',
        'sizes'  => ['Large','Medium','Smedium','Small'],
        'lengths'=> ['Shoulder','Midback','Waist','Butt'],
        'prices' => [
            'Large'  =>['Shoulder'=>160,'Midback'=>180,'Waist'=>200,'Butt'=>240],
            'Medium' =>['Shoulder'=>180,'Midback'=>220,'Waist'=>240,'Butt'=>280],
            'Smedium'=>['Shoulder'=>200,'Midback'=>240,'Waist'=>260,'Butt'=>300],
            'Small'  =>['Shoulder'=>220,'Midback'=>260,'Waist'=>280,'Butt'=>320],
        ],
        'durations' => [
            'Large'  =>['Shoulder'=>240,'Midback'=>300,'Waist'=>300,'Butt'=>300],
            'Medium' =>['Shoulder'=>300,'Midback'=>300,'Waist'=>300,'Butt'=>300],
            'Smedium'=>['Shoulder'=>300,'Midback'=>300,'Waist'=>320,'Butt'=>320],
            'Small'  =>['Shoulder'=>300,'Midback'=>300,'Waist'=>320,'Butt'=>320],
        ],
    ],
];
