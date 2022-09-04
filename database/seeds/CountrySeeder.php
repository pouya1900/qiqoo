<?php

use Illuminate\Database\Seeder;
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                'title' => 'ایران',
                'en_title' => 'Iran, Islamic Republic of',
                'iso_code' => 'IR',
                'iso_code3' => 'IRN',
                'phone_code' => '98',
            ],
            [
                'title' => 'بریتانیا',
                'en_title' => 'United Kingdom',
                'iso_code' => 'GB',
                'iso_code3' => 'GBR',
                'phone_code' => '44',
            ]
        ];
        foreach($countries as $country)
        {
            \Illuminate\Support\Facades\DB::table('countries')
                ->insert([
                    'title' => $country['title'],
                    'en_title' => $country['en_title'],
                    'iso_code' => $country['iso_code'],
                    'iso_code3' => $country['iso_code3'],
                    'phone_code' => $country['phone_code'],
                    'created_at' => \Carbon\Carbon::now()
                ]
            );
        }
    }
}
