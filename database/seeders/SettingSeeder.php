<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /** @var array */
    protected $settings = [
        [
            'key'   => 'company_name',
            'value' => 'E-Commerce',
        ],
        [
            'key'   => 'company_email',
            'value' => 'keochamraeun54@gmail.com',
        ],
        [
            'key'   => 'company_phone',
            'value' => '+855 886576689',
        ],
        [
            'key'   => 'company_address',
            'value' => 'Tanger, Maroc',
        ],
        [
            'key'   => 'company_tax',
            'value' => '0000000',
        ],
        [
            'key'   => 'currency_code',
            'value' => 'MAD',
        ],

        [
            'key'   => 'site_title',
            'value' => 'E-Commerce',
        ],
        [
            'key'   => 'site_logo',
            'value' => '',
        ],
        [
            'key'   => 'site_favicon',
            'value' => '',
        ],
        [
            'key'   => 'currency_symbol',
            'value' => 'DH',
        ],
        [
            'key'   => 'currency_position',
            'value' => 'right',
        ],
        [
            'key'   => 'footer_copyright_text',
            'value' => '',
        ],
        [
            'key'   => 'seo_meta_title',
            'value' => 'E-Commerce',
        ],
        [
            'key'   => 'seo_meta_description',
            'value' => 'E-Commerce',
        ],
        [
            'key'   => 'social_facebook',
            'value' => '#',
        ],
        [
            'key'   => 'social_twitter',
            'value' => '#',
        ],
        [
            'key'   => 'social_instagram',
            'value' => '#',
        ],
        [
            'key'   => 'social_linkedin',
            'value' => '#',
        ],
        [
            'key'   => 'social_whatsapp',
            'value' => '#',
        ],
        [
            'key'   => 'head_tags',
            'value' => '',
        ],
        [
            'key'   => 'body_tags',
            'value' => '',
        ],
        [
            'key'   => 'enableRegistrationTerms',
            'value' => '1',
        ],
        [
            'key'   => 'site_maintenance_message',
            'value' => 'Site is under maintenance',
        ],
        [
            'key'   => 'default_language',
            'value' => 'fr',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting) {
            $result = Settings::create($setting);

            if ( ! $result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }
        $this->command->info('Inserted '.count($this->settings).' records');
    }
}
