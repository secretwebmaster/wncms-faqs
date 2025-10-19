<?php

namespace Secretwebmaster\WncmsFaqs\Models;

use Wncms\Models\BaseModel;
use Wncms\Translatable\Traits\HasTranslations;

class Faq extends BaseModel
{
    use HasTranslations;

    protected $table = 'faqs';

    protected $guarded = [];

    protected array $translatable = [
        'question',
        'answer',
        'label',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public const ICONS = [
        'fontawesome' => 'fa-solid fa-circle-question',
    ];

    public const ORDERS = [
        'id',
        'status',
        'order',
        'is_pinned',
        'created_at',
        'updated_at',
    ];

    public const ROUTES = [
        'index',
        'create',
    ];

    public const STATUSES = [
        'active',
        'inactive',
    ];
}
