<?php

namespace Secretwebmaster\WncmsFaqs\Services\Managers;

use Illuminate\Database\Eloquent\Builder;
use Wncms\Services\Managers\ModelManager;

class FaqManager extends ModelManager
{
    protected string $cacheKeyPrefix = 'wncms_faq';
    protected string $defaultTagType = 'faq_tag';
    protected bool $shouldAuth = false;
    protected string|array $cacheTags = ['faqs'];

    /**
     * Define which model this manager uses.
     */
    public function getModelClass(): string
    {
        return wncms()->getModelClass('faq');
    }

    /**
     * Get a single FAQ.
     */
    public function get(array $options = []): ?\Illuminate\Database\Eloquent\Model
    {
        return parent::get($options);
    }

    /**
     * Get a list of FAQs (paginated, filtered, cached).
     */
    public function getList(array $options = []): mixed
    {
        return parent::getList($options);
    }

    /**
     * Build the query for FAQ listing.
     *
     * Supported $options keys:
     * - ids, excluded_ids, excluded_tag_ids, tags, tag_type, keywords, wheres, status
     * - withs, order, sequence, select, offset, count
     * - website_id
     */
    protected function buildListQuery(array $options): mixed
    {
        $q = $this->query();

        // Apply all standard filters (helpers come from ModelManager)
        $this->applyIds($q, 'faqs.id', $options['ids'] ?? []);
        $this->applyExcludeIds($q, 'faqs.id', $options['excluded_ids'] ?? []);
        $this->applyExcludedTags($q, $options['excluded_tag_ids'] ?? []);
        $this->applyTagFilter($q, $options['tags'] ?? [], $options['tag_type'] ?? $this->defaultTagType);
        $this->applyKeywordFilter($q, $options['keywords'] ?? [], ['title', 'content']);
        $this->applyWhereConditions($q, $options['wheres'] ?? []);
        $this->applyStatus($q, 'status', $options['status'] ?? 'active');
        $this->applyWiths($q, $options['withs'] ?? []);
        $this->applyOrdering($q, $options['order'] ?? 'id', $options['sequence'] ?? 'desc', ($options['order'] ?? '') === 'random');
        $this->applyWebsiteId($q, $options['website_id'] ?? null);

        // Columns
        $select = $options['select'] ?? ['faqs.*'];
        $select = $this->autoAddOrderByColumnsToSelect($q, $select);
        $this->applySelect($q, $select);

        // Pagination
        $this->applyOffset($q, $options['offset'] ?? 0);
        $this->applyLimit($q, $options['count'] ?? 0);

        $q->distinct();

        return $q;
    }

    /**
     * Get FAQ by slug.
     */
    public function getBySlug(string $slug, ?int $websiteId = null)
    {
        return $this->getList([
            'wheres' => [['slug', $slug]],
            'website_id' => $websiteId,
        ])?->first();
    }

    /**
     * Apply ordering logic.
     */
    protected function applyOrdering(Builder $q, string $order, string $sequence = 'desc', bool $isRandom = false)
    {
        if ($isRandom) {
            $q->inRandomOrder();
            return;
        }

        // Always prioritize pinned FAQs first
        $q->orderBy('faqs.is_pinned', 'desc');

        if (!in_array($order, ['is_pinned', 'random'])) {
            $q->orderBy("faqs.{$order}", in_array($sequence, ['asc', 'desc']) ? $sequence : 'desc');
        }

        $q->orderBy('faqs.id', 'desc');
    }
}
