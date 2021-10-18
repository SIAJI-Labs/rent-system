<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Permission
    |--------------------------------------------------------------------------
    |
    | This value is the permission of your application. This value is used when the
    | framework needs to place the application's permission in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'permission' => [
        // Brand / Merek
        'brand' => [
            'name' => 'Merek',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],
        // Category / Kategori
        'category' => [
            'name' => 'Kategori',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],
        // Product / Produk
        'product' => [
            'name' => 'Produk',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],
        // Product Detail / Serial Number
        'product_detail' => [
            'name' => 'Produk Detail / Serial Number',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],

        // Transaction / Transaksi
        'transaction' => [
            'name' => 'Transaksi',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],
        // Accounting / Keuangan
        'accounting' => [
            'name' => 'Keuangan',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ],
            ]
        ],

        // Customer / Kostumer
        'customer' => [
            'name' => 'Kostumer',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],
        // Store / Toko
        'store' => [
            'name' => 'Toko',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],
        // Staff / Staff
        'staff' => [
            'name' => 'Staff',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Tambah Data',
                    'value' => 'create'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],

        // Website Configuration / Pengaturan Website
        'website_configuration' => [
            'name' => 'Pengaturan Website',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ], [
                    'name' => 'Edit Data',
                    'value' => 'edit'
                ],
            ]
        ],
        // Documentation / Dokumentasi / Buku Manual
        'documentation' => [
            'name' => 'Dokumentasi',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ],
            ]
        ],
        // Log Viewer / Log Sistem
        'log' => [
            'name' => 'Log Sistem',
            'permission' => [
                [
                    'name' => 'Lihat Data',
                    'value' => 'list'
                ],
            ]
        ],
    ]
];