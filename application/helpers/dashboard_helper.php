<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('dashboard_status'))
{
	function dashboard_status($role)
	{
        $info_boxes['pum'] = [
            [
                'count' => 0,
                'label' => 'Belum Diajukan',
                'icon' => 'fa-hourglass-start',
                'color' => 'bg-aqua',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Menunggu Persetujuan Koordinator Anggaran',
                'icon' => 'fa-user',
                'color' => 'bg-maroon',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Menunggu Konfirmasi Koordinator PUM',
                'icon' => 'fa-user-clock',
                'color' => 'bg-blue',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Menunggu Persetujuan Manajer Keuangan',
                'icon' => 'fa-user-check',
                'color' => 'bg-purple',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Disetujui Kasir',
                'icon' => 'fa-user',
                'color' => 'bg-teal',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pemeriksaan Verifikator',
                'icon' => 'fa-search',
                'color' => 'bg-green',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pencatatan Yunior Akuntan',
                'icon' => 'fa-book',
                'color' => 'bg-orange',
                'link' => '#'
            ]
        ];

        $info_boxes['anggaran'] = [
            [
                'count' => 0,
                'label' => 'Belum Diajukan',
                'icon' => 'fa-hourglass-start',
                'color' => 'bg-aqua',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Menunggu Persetujuan Koordinator Anggaran',
                'icon' => 'fa-user',
                'color' => 'bg-maroon',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Menunggu Konfirmasi Koordinator PUM',
                'icon' => 'fa-user-clock',
                'color' => 'bg-blue',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Menunggu Persetujuan Manajer Keuangan',
                'icon' => 'fa-user-check',
                'color' => 'bg-purple',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Disetujui Kasir',
                'icon' => 'fa-user',
                'color' => 'bg-teal',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pemeriksaan Verifikator',
                'icon' => 'fa-search',
                'color' => 'bg-green',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pencatatan Yunior Akuntan',
                'icon' => 'fa-book',
                'color' => 'bg-orange',
                'link' => '#'
            ]
        ];
        
        $info_boxes['korpum'] = [
            [
                'count' => 0,
                'label' => 'Menunggu Konfirmasi Koordinator PUM',
                'icon' => 'fa-user-clock',
                'color' => 'bg-blue',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Menunggu Persetujuan Manajer Keuangan',
                'icon' => 'fa-user-check',
                'color' => 'bg-purple',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Disetujui Kasir',
                'icon' => 'fa-user',
                'color' => 'bg-teal',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pemeriksaan Verifikator',
                'icon' => 'fa-search',
                'color' => 'bg-green',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pencatatan Yunior Akuntan',
                'icon' => 'fa-book',
                'color' => 'bg-orange',
                'link' => '#'
            ]
        ];

        $info_boxes['manajer'] = [
            [
                'count' => 0,
                'label' => 'Menunggu Persetujuan Manajer Keuangan',
                'icon' => 'fa-user-check',
                'color' => 'bg-purple',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Disetujui Kasir',
                'icon' => 'fa-user',
                'color' => 'bg-teal',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pemeriksaan Verifikator',
                'icon' => 'fa-search',
                'color' => 'bg-green',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pencatatan Yunior Akuntan',
                'icon' => 'fa-book',
                'color' => 'bg-orange',
                'link' => '#'
            ]
        ];

        $info_boxes['kasir'] = [
            [
                'count' => 0,
                'label' => 'Disetujui Kasir',
                'icon' => 'fa-user',
                'color' => 'bg-teal',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pemeriksaan Verifikator',
                'icon' => 'fa-search',
                'color' => 'bg-green',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pencatatan Yunior Akuntan',
                'icon' => 'fa-book',
                'color' => 'bg-orange',
                'link' => '#'
            ]
        ];

        $info_boxes['verifikator'] = [
            [
                'count' => 0,
                'label' => 'Disetujui Kasir',
                'icon' => 'fa-user',
                'color' => 'bg-teal',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pemeriksaan Verifikator',
                'icon' => 'fa-search',
                'color' => 'bg-green',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pencatatan Yunior Akuntan',
                'icon' => 'fa-book',
                'color' => 'bg-orange',
                'link' => '#'
            ]
        ];

        $info_boxes['yunior_akuntan'] = [
            [
                'count' => 0,
                'label' => 'Pemeriksaan Verifikator',
                'icon' => 'fa-search',
                'color' => 'bg-green',
                'link' => '#'
            ],
            [
                'count' => 0,
                'label' => 'Pencatatan Yunior Akuntan',
                'icon' => 'fa-book',
                'color' => 'bg-orange',
                'link' => '#'
            ]
        ]; 

        return $info_boxes[$role] ?? [
            [
                'count' => 0,
                'label' => 'Tidak ada data',
                'icon' => 'fa-exclamation-triangle',
                'color' => 'bg-red',
                'link' => '#'
            ]
        ];
    }    
}