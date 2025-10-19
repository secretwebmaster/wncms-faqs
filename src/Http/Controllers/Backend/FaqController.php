<?php

namespace Secretwebmaster\WncmsFaqs\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Wncms\Http\Controllers\Backend\BackendController;

class FaqController extends BackendController
{
    protected string $modelClass = \Secretwebmaster\WncmsFaqs\Models\Faq::class;
    protected array $cacheTags = ['faqs'];
    protected string $singular = 'faq';
    protected string $plural = 'faqs';

    public function index(Request $request)
    {
        $q = $this->modelClass::query();

        if (in_array($request->status, $this->modelClass::STATUSES)) {
            $q->where('status', $request->status);
        }

        if ($request->keyword) {
            $q->where(function ($subq) use ($request) {
                $subq->orWhere('id', $request->keyword)
                    ->orWhere('slug', 'like', "%" . $request->keyword . "%")
                    ->orWhere('question', 'like', "%" . $request->keyword . "%")
                    ->orWhere('answer', 'like', "%" . $request->keyword . "%")
                    ->orWhere('label', 'like', "%" . $request->keyword . "%")
                    ->orWhere('remark', 'like', "%" . $request->keyword . "%");
            });
        }

        if (in_array($request->order, $this->modelClass::ORDERS)) {
            $q->orderBy($request->order, in_array($request->sort, ['asc', 'desc']) ? $request->sort : 'desc');
        }

        $faqs = $q->paginate($request->page_size ?? 20);

        return $this->view('wncms-faqs::backend.faqs.index', [
            'page_title' => wncms_model_word('faq', 'management'),
            'faqs' => $faqs,
            'orders' => $this->modelClass::ORDERS,
            'statuses' => $this->modelClass::STATUSES,
        ]);
    }

    public function create($id = null)
    {
        if ($id) {
            $faq = $this->modelClass::find($id);
            if (!$faq) {
                return back()->withMessage(__('wncms::word.model_not_found', [
                    'model_name' => __('wncms::word.' . $this->singular),
                ]));
            }
        } else {
            $faq = new $this->modelClass;
        }

        return $this->view('wncms-faqs::backend.faqs.create', [
            'page_title' => wncms_model_word('faq', 'management'),
            'statuses'   => $this->modelClass::STATUSES,
            'faq'        => $faq,
            'faq_tags'   => wncms()->tag()->getArray(tagType: 'faq_tag', columnName: 'name'),
        ]);
    }

    public function store(Request $request)
    {
        $duplicated = $this->modelClass::where('slug', $request->slug)->first();
        if ($duplicated) {
            return back()->withInput()->withMessage(__('wncms-faqs::word.slug_already_in_use'));
        }

        $faq = $this->modelClass::create([
            'status'    => $request->status,
            'slug'      => $request->slug ?? wncms()->getUniqueSlug('faqs'),
            'question'  => $request->question,
            'answer'    => $request->answer,
            'label'     => $request->label,
            'remark'    => $request->remark,
            'order'     => $request->order,
            'is_pinned' => $request->is_pinned == 1,
        ]);

        $faq->syncTagsFromTagify($request->faq_tags, 'faq_tag');

        $this->flush();

        return redirect()->route('faqs.edit', ['id' => $faq])
            ->withMessage(__('wncms::word.successfully_created'));
    }

    public function edit($id)
    {
        $faq = $this->modelClass::find($id);
        if (!$faq) {
            return back()->withMessage(__('wncms::word.model_not_found', [
                'model_name' => __('wncms::word.' . $this->singular),
            ]));
        }

        return $this->view('wncms-faqs::backend.faqs.edit', [
            'page_title' => wncms_model_word('faq', 'management'),
            'faq'        => $faq,
            'statuses'   => $this->modelClass::STATUSES,
            'faq_tags'   => wncms()->tag()->getArray(tagType: 'faq_tag', columnName: 'name'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $faq = $this->modelClass::find($id);
        if (!$faq) {
            return back()->withMessage(__('wncms::word.model_not_found', [
                'model_name' => __('wncms::word.' . $this->singular),
            ]));
        }

        $faq->update([
            'status'    => $request->status,
            'slug'      => $request->slug ?? wncms()->getUniqueSlug('faqs'),
            'question'  => $request->question,
            'answer'    => $request->answer,
            'label'     => $request->label,
            'remark'    => $request->remark,
            'order'     => $request->order,
            'is_pinned' => $request->is_pinned == 1,
        ]);

        $faq->syncTagsFromTagify($request->faq_tags, 'faq_tag');

        $this->flush();

        return redirect()->route('faqs.edit', ['id' => $faq])
            ->withMessage(__('wncms::word.successfully_updated'));
    }
}
