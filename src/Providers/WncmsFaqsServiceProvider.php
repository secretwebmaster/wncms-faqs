<?php

namespace Secretwebmaster\WncmsFaqs\Providers;

use Illuminate\Support\ServiceProvider;
use Secretwebmaster\WncmsFaqs\Models\Faq;
use Secretwebmaster\WncmsFaqs\Http\Controllers\FaqController;
use Secretwebmaster\WncmsFaqs\Services\Managers\FaqManager;

class WncmsFaqsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('wncms.faq', function () {
            return new FaqManager();
        });
    }

    public function boot(): void
    {
        // migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'wncms-faqs');

        // routes
        if (file_exists(__DIR__ . '/../../routes/web.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        }

        if (file_exists(__DIR__ . '/../../routes/api.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        }

        // seeders
        $this->publishes([
            __DIR__ . '/../../database/seeders/FaqSeeder.php' => database_path('seeders/FaqSeeder.php'),
        ], 'wncms-faqs-seeders');

        // translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'wncms-faqs');

        // Register WNCMS package
        wncms()->registerPackage('wncms-faqs', [
            'info' => [
                'name' => [
                    'en'    => 'FAQs',
                    'zh_TW' => '常見問題',
                    'zh_CN' => '常见问题',
                    'ja'    => 'よくある質問',
                ],
                'description' => [
                    'en'    => 'Frequently Asked Questions management',
                    'zh_TW' => '常見問題管理',
                    'zh_CN' => '常见问题管理',
                    'ja'    => 'よくある質問を管理します',
                ],
                'version' => '1.0.0',
                'author'  => 'Secretwebmaster',
                'icon'    => 'fa-solid fa-circle-question',
            ],

            'paths' => [
                'models' => [
                    'faq' => Faq::class,
                ],
                'managers' => [
                    'faq' => FaqManager::class,
                ],
                'controllers' => [
                    'faq' => FaqController::class,
                ],
                'base' => __DIR__ . '/../../',
            ],

            'menus' => [
                [
                    'title' => [
                        'en'    => 'FAQs',
                        'zh_TW' => '常見問題',
                        'zh_CN' => '常见问题',
                        'ja'    => 'FAQ',
                    ],
                    'icon'        => 'fa-solid fa-circle-question',
                    'permission'  => 'faq_index',
                    'items' => [
                        [
                            'name' => [
                                'en'    => 'FAQ List',
                                'zh_TW' => '問題列表',
                                'zh_CN' => '问题列表',
                                'ja'    => 'FAQ一覧',
                            ],
                            'route'       => 'faqs.index',
                            'permission'  => 'faq_index',
                        ],
                        [
                            'name' => [
                                'en'    => 'Create FAQ',
                                'zh_TW' => '新增問題',
                                'zh_CN' => '新增问题',
                                'ja'    => 'FAQを作成',
                            ],
                            'route'       => 'faqs.create',
                            'permission'  => 'faq_create',
                        ],
                    ],
                ],
            ],

            'permissions' => [
                'faq_index',
                'faq_show',
                'faq_create',
                'faq_edit',
                'faq_delete',
                'faq_list',
                'faq_clone',
                'faq_bulk_create',
                'faq_bulk_edit',
                'faq_bulk_delete',
            ],
        ]);
    }
}
