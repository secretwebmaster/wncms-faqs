<?php

namespace Secretwebmaster\WncmsFaqs\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Secretwebmaster\WncmsFaqs\Models\Faq;
use Secretwebmaster\WncmsFaqs\Services\Managers\FaqManager;
use Wncms\Http\Controllers\Api\V1\ApiController;

class FaqController extends ApiController
{
    protected FaqManager $faqs;

    public function __construct(FaqManager $faqs)
    {
        parent::__construct();
        $this->faqs = $faqs;
    }

    /**
     * List FAQs (with optional filters).
     */
    public function index(Request $request)
    {
        $faqs = $this->faqs->getList(
            tags: $request->input('tags', []),
            keywords: $request->input('keywords', []),
            count: $request->input('count', 0),
            pageSize: $request->input('pageSize', 10),
            order: $request->input('order', 'id'),
            sequence: $request->input('sequence', 'desc'),
            status: $request->input('status', 'active'),
        );

        return response()->json([
            'status' => 200,
            'data' => $faqs,
        ]);
    }

    /**
     * Show single FAQ.
     */
    public function show(int $id)
    {
        $faq = $this->faqs->get($id);

        if (!$faq) {
            return response()->json([
                'status' => 404,
                'message' => 'FAQ not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $faq,
        ]);
    }

    /**
     * Store a new FAQ (basic version, for extensions).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'answer'   => 'nullable|string',
            'slug'     => 'nullable|string|unique:faqs,slug',
            'status'   => 'nullable|string|in:active,inactive',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = str()->slug($data['question']);
        }

        $faq = Faq::create($data);

        return response()->json([
            'status' => 201,
            'message' => 'FAQ created successfully',
            'data' => $faq,
        ]);
    }
}
