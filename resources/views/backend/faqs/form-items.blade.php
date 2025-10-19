<div class="card-body border-top p-3 p-md-9">
    {{-- status --}}
    @if(!empty($statuses))
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label required fw-bold fs-6" for="status">@lang('wncms::word.status')</label>
        <div class="col-lg-9 fv-row">
            <select id="status" name="status" class="form-select form-select-sm" required>
                <option value="">@lang('wncms::word.please_select')</option>
                @foreach($statuses ?? [] as $status)
                <option value="{{ $status }}" {{ $status===old('status', $faq->status ?? 'active') ? 'selected' :'' }}>@lang('wncms::word.' . $status)</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    {{-- slug --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label required fw-bold fs-6" for="slug">@lang('wncms::word.slug')</label>
        <div class="col-lg-9 fv-row">
            <input id="slug" type="text" name="slug" class="form-control form-control-sm" value="{{ old('slug', $faq->slug ?? null) }}" required />
        </div>
    </div>

    {{-- question --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label required fw-bold fs-6" for="question">@lang('wncms::word.question')</label>
        <div class="col-lg-9 fv-row">
            <input id="question" type="text" name="question" class="form-control form-control-sm" value="{{ old('question', $faq->question ?? null) }}" required />
        </div>
    </div>

    {{-- answer --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6" for="answer">@lang('wncms::word.answer')</label>
        <div class="col-lg-9 fv-row">
            <textarea id="kt_docs_tinymce_basic" name="answer" class="tox-target">{{ old('answer', $faq->answer ?? null) }}</textarea>
        </div>
    </div>

    {{-- label --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6" for="label">@lang('wncms::word.label')</label>
        <div class="col-lg-9 fv-row">
            <input id="label" type="text" name="label" class="form-control form-control-sm" value="{{ old('label', $faq->label ?? null) }}" />
        </div>
    </div>

    {{-- remark --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6" for="remark">@lang('wncms::word.remark')</label>
        <div class="col-lg-9 fv-row">
            <textarea id="remark" name="remark" class="form-control" rows="5">{{ old('remark', $faq->remark ?? null) }}</textarea>
        </div>
    </div>

    {{-- order --}}
    <div class="row mb-3">
        <label class="col-lg-3 col-form-label fw-bold fs-6" for="order">@lang('wncms::word.order')</label>
        <div class="col-lg-9 fv-row">
            <input id="order" type="number" name="order" class="form-control form-control-sm" value="{{ old('order', $faq->order ?? null) }}" />
        </div>
    </div>

    {{-- is_pinned --}}
    <div class="row mb-3">
        <label class="col-auto col-md-3 col-form-label fw-bold fs-6" for="is_pinned">@lang('wncms::word.is_pinned')</label>
        <div class="col-auto col-md-9 d-flex align-items-center">
            <div class="form-check form-check-solid form-check-custom form-switch fv-row">
                <input id="is_pinned" type="hidden" name="is_pinned" value="0">
                <input class="form-check-input w-35px h-20px" type="checkbox" id="is_pinned" name="is_pinned" value="1" {{ old('is_pinned', $faq->is_pinned ?? false) ? 'checked' : '' }}/>
                <label class="form-check-label" for="is_pinned"></label>
            </div>
        </div>
    </div>

</div>

@include('wncms::backend.common.developer-hints')
