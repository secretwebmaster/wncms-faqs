<?php

namespace Secretwebmaster\WncmsFaqs\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Wncms\Http\Controllers\Frontend\FrontendController;

class FaqController extends FrontendController
{
    /**
     * Display a single FAQ by its slug.
     */
    public function single(string $slug)
    {
        $faq = wncms()->faq()->getBySlug($slug);

        if (!$faq) {
            abort(404, __('wncms::word.model_not_found', ['model_name' => __('wncms::word.faq')]));
        }

        return wncms()->view(
            name: "frontend.theme.{$this->theme}.faqs.single",
            params: compact('faq'),
            fallback: "wncms-faqs::frontend.faqs.single"
        );
    }

    /**
     * Search for FAQs by a keyword (GET request).
     */
    public function search_result(string $keyword)
    {
        $faqs = wncms()->faq()->getList(
            keywords: $keyword,
            pageSize: 20
        );

        return wncms()->view(
            name: "frontend.theme.{$this->theme}.faqs.search_result",
            params: compact('keyword', 'faqs'),
            fallback: "wncms-faqs::frontend.faqs.search_result"
        );
    }

    /**
     * Perform a search for FAQs (POST request).
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        return redirect()->route('frontend.faqs.search_result', ['keyword' => $keyword]);
    }

    /**
     * Display FAQs by a specific tag.
     */
    public function tag(?string $tagName = null)
    {
        $faqs = wncms()->faq()->getList(
            tags: $tagName,
            tagType: 'faq_tag',
            pageSize: 20
        );

        return wncms()->view(
            name: "frontend.theme.{$this->theme}.faqs.tag",
            params: compact('tagName', 'faqs'),
            fallback: "wncms-faqs::frontend.faqs.tag"
        );
    }

    /**
     * Display FAQs by a tag type and tag name.
     */
    public function archive(string $tagType, ?string $tagName = null)
    {
        $faqs = wncms()->faq()->getList(
            tags: $tagName,
            tagType: $tagType,
            pageSize: 20
        );

        return wncms()->view(
            name: "frontend.theme.{$this->theme}.faqs.archive",
            params: compact('tagType', 'tagName', 'faqs'),
            fallback: "wncms-faqs::frontend.faqs.archive"
        );
    }
}
