<?php

namespace Secretwebmaster\WncmsFaqs\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Wncms\Http\Controllers\Frontend\FrontendController;
use Illuminate\View\View;

class FaqController extends FrontendController
{
    /**
     * Display a single FAQ by its slug.
     */
    public function single(string $slug)
    {
        $faq = wncms()->package('wncms-faqs')->faq()->get(['slug' => $slug]);

        if (!$faq) {
            abort(404, __('wncms::word.model_not_found', ['model_name' => __('wncms::word.faq')]));
        }

        return $this->view(
            "frontend.themes.{$this->theme}.faqs.single",
            compact('faq'),
            "wncms-faqs::frontend.faqs.single"
        );
    }

    /**
     * Search FAQs by keyword (GET request).
     */
    public function search_result(string $keyword)
    {
        $faqs = wncms()->package('wncms-faqs')->faq()->getList([
            'keywords' => $keyword,
            'page_size' => 20,
        ]);

        return $this->view(
            "frontend.themes.{$this->theme}.faqs.search_result",
            compact('keyword', 'faqs'),
            "wncms-faqs::frontend.faqs.search_result"
        );
    }

    /**
     * Handle FAQ search form (POST → redirect to GET).
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        return redirect()->route('frontend.faqs.search_result', ['keyword' => $keyword]);
    }

    /**
     * Display FAQs by tag (default tag type: faq_tag).
     */
    // public function tag(?string $tagName = null)
    // {
    //     $faqs = wncms()->package('wncms-faqs')->faq()->getList([
    //         'tags' => $tagName,
    //         'tag_type' => 'faq_tag',
    //         'page_size' => 20,
    //     ]);

    //     return $this->view(
    //         "frontend.themes.{$this->theme}.faqs.tag",
    //         compact('tagName', 'faqs'),
    //         "wncms-faqs::frontend.faqs.tag"
    //     );
    // }

        /**
     * Display faqs under any tag type (category, tag, brand, etc.)
     */
    public function tag(string $type, string $slug): View
    {
        $faqModel = wncms()->getModelClass('faq');
        $allowedTags  = (new $faqModel())->getAllowedTagTypes();

        abort_unless(in_array($type, $allowedTags), 404);

        $tag = wncms()->tag()->get(['slug' => $slug, 'type' => $type]);

        abort_unless($tag, 404);

        $manager = wncms()->package('wncms-faqs')->faq();

        $faqs = $manager->getList([
            'tags'     => [$tag->name],
            'tag_type' => 'faq_' . $type,
            'status'   => 'active',
            'cache'    => false,
            'page_size' => 10, 
        ]);

        return $this->view(
            "frontend.themes.{$this->theme}.faqs.tag",
            compact('tag', 'type', 'faqs'),
            'wncms-faqs::frontend.faqs.tag'
        );
    }

    /**
     * Display FAQs by a given tag type and tag name.
     */
    public function archive(string $tagType, ?string $tagName = null)
    {
        $faqs = wncms()->package('wncms-faqs')->faq()->getList([
            'tags' => $tagName,
            'tag_type' => $tagType,
            'page_size' => 20,
        ]);

        return $this->view(
            "frontend.themes.{$this->theme}.faqs.archive",
            compact('tagType', 'tagName', 'faqs'),
            "wncms-faqs::frontend.faqs.archive"
        );
    }
}
