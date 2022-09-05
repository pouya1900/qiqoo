<?php

use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reportTypes = [
            [
                'title' => 'دارای محتوای مجرمانه',
                'description' => 'در صورتی که داده مورد نظر از محتوای مجرمانه جهت تحریک، ترغیب یا دعوت به ارتکاب جرم کند.',
                'importance_level' => 5
            ],
            [
                'title' => 'استفاده از محتوای غیر اخلاقی',
                'description' => 'در صورتی که داده مورد نظر از محتوای غیر اخلاقی، مستهجن و مبتذل در تصاویر، محتوا و... استفاده کرده باشد.',
                'importance_level' => 4
            ],

            [
                'title' => 'استفاده از اطلاعات نادرست',
                'description' => 'در صورتی که داده مورد نظر از اطلاعات نادرست و اشتباه استفاده کرده باشد.',
                'importance_level' => 3
            ],
            [
                'title' => 'عدم رعایت مالکیت معنوی و حق کپی رایت',
                'description' => 'در صورتی که داده مورد نظر مالکیت معنوی اثر را رعایت نکرده و با سواستفاده آن را منتشر کند.',
                'importance_level' => 2
            ],
            [
                'title' => 'عدم رعایت فضای رقابتی و اخلاق حرفه ای',
                'description' => 'در صورتی که داده موردنظر از اطلاعات نادرست جهت تمسخر و توهین رقبا استفاده کرده و اخلاق حرفه ای را رعایت نکرده باشد',
                'importance_level' => 1
            ],
        ];
        foreach($reportTypes as $reportType)
        {
            \Illuminate\Support\Facades\DB::table('report_types')
                ->insert(array_merge($reportType, ['created_at' => \Carbon\Carbon::now()]));
        }
    }
}
