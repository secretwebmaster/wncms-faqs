<?php

namespace Secretwebmaster\WncmsFaqs\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;

class ImportDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     * php artisan wncms-faqs:import-demo {count=5}
     */
    protected $signature = 'wncms-faqs:import-demo {count=5}';

    /**
     * The console command description.
     */
    protected $description = 'Generate demo FAQs using Faker in English';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = (int) $this->argument('count');
        $this->generateFAQs($count);
    }

    /**
     * Generate demo FAQs.
     */
    protected function generateFAQs(int $count): void
    {
        $faker = Faker::create('en_US');
        $faqModel = wncms()->package('wncms-faqs')->model('faq');

        for ($i = 1; $i <= $count; $i++) {
            $faqModel::create([
                'status'   => 'active',
                'slug'    => wncms()->getUniqueSlug('faqs'),
                'question' => $faker->sentence(),
                'answer'   => $faker->paragraph(),
            ]);
        }
    }
}
