<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            [
                'country_id' => '2',
                'title' => 'لندن',
                'en_title' => 'London',
            ],
            [
                'country_id' => '2',
                'title' => 'بیرمنگام',
                'en_title' => 'Birmingham',
            ],
            [
                'country_id' => '2',
                'title' => 'برایتون',
                'en_title' => 'Brighton',
            ],
            [
                'country_id' => '2',
                'title' => 'منچستر',
                'en_title' => 'Manchester',
            ],
            [
                'country_id' => '2',
                'title' => 'لیورپول',
                'en_title' => 'Liverpool',
            ],
            [
                'country_id' => '2',
                'title' => 'ناتینگهام',
                'en_title' => 'Nottingham',
            ],
            [
                'country_id' => '2',
                'title' => 'لستر',
                'en_title' => 'Leicester',
            ],
            [
                'country_id' => '2',
                'title' => 'شفیلد',
                'en_title' => 'Sheffield',
            ],
            [
                'country_id' => '2',
                'title' => 'نیوکسل',
                'en_title' => 'Newcastle',
            ],
            [
                'country_id' => '2',
                'title' => 'لیدز',
                'en_title' => 'Leeds',
            ],
            [
                'country_id' => '2',
                'title' => 'ادینبورا',
                'en_title' => 'Edinburgh',
            ],
            [
                'country_id' => '2',
                'title' => 'گلاسکو',
                'en_title' => 'Glasgow',
            ],
            [
                'country_id' => '2',
                'title' => 'بریستول',
                'en_title' => 'Bristol',
            ],
            [
                'country_id' => '2',
                'title' => 'کاردیف',
                'en_title' => 'Cardiff',
            ],
            [
                'country_id' => '2',
                'title' => 'بلفاست',
                'en_title' => 'Belfast',
            ]
        ];
        foreach($cities as $city)
        {
            \Illuminate\Support\Facades\DB::table('cities')
                ->insert(array_merge($city, ['created_at' => \Carbon\Carbon::now()]));
        }
    }
}
