@php
    $isEdit = isset($details);
    $features = $isEdit ? $details->features->pluck('text')->all() : [''];
    $modes = $isEdit ? $details->modes : collect([(object)[
        'mode_code' => 'online', 'label' => 'Online', 'price' => 0,
        'requires_location' => 0, 'requires_address' => 0,
        'includes_online' => 1, 'includes_omr' => 0, 'help_text' => '',
    ]]);
@endphp

<div class="row">
    <div class="col-sm-4">
        <label class="form-label">Code *</label>
        <input class="form-control" name="code" value="{{ old('code', $details->code ?? '') }}" required>
    </div>
    <div class="col-sm-4">
        <label class="form-label">Name *</label>
        <input class="form-control" name="name" value="{{ old('name', $details->name ?? '') }}" required>
    </div>
    <div class="col-sm-4">
        <label class="form-label">Slug</label>
        <input class="form-control" name="slug" value="{{ old('slug', $details->slug ?? '') }}">
    </div>
    <div class="col-sm-6 mt-3">
        <label class="form-label">Tagline</label>
        <input class="form-control" name="tagline" value="{{ old('tagline', $details->tagline ?? '') }}">
    </div>
    <div class="col-sm-3 mt-3">
        <label class="form-label">Display price</label>
        <input class="form-control" type="number" step="0.01" name="display_price" value="{{ old('display_price', $details->display_price ?? 0) }}">
    </div>
    <div class="col-sm-3 mt-3">
        <label class="form-label">Tax</label>
        <input class="form-control" type="number" step="0.01" name="tax" value="{{ old('tax', $details->tax ?? 0) }}">
    </div>
    <div class="col-sm-12 mt-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="3">{{ old('description', $details->description ?? '') }}</textarea>
    </div>
    <div class="col-sm-3 mt-3">
        <label class="form-label">Theme</label>
        <select class="form-control" name="theme">
            @foreach(['sky','pink','blue','violet','orange','green'] as $t)
                <option value="{{ $t }}" @selected(old('theme', $details->theme ?? 'blue') === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label class="form-label">Mode selection</label>
        <select class="form-control" name="mode_selection">
            <option value="fixed" @selected(old('mode_selection', $details->mode_selection ?? '') === 'fixed')">Fixed (auto)</option>
            <option value="choose_one" @selected(old('mode_selection', $details->mode_selection ?? '') === 'choose_one')">Student chooses one</option>
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label class="form-label">Schedule scope</label>
        <select class="form-control" name="schedule_scope">
            @foreach(['none','chapter','part','full','both'] as $s)
                <option value="{{ $s }}" @selected(old('schedule_scope', $details->schedule_scope ?? 'none') === $s)>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label class="form-label">Sort order</label>
        <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $details->sort_order ?? 0) }}">
    </div>
    <div class="col-sm-12 mt-3 d-flex gap-4">
        <label><input type="checkbox" name="is_free" value="1" @checked(old('is_free', $details->is_free ?? false))> Free package</label>
        <label><input type="checkbox" name="includes_online" value="1" @checked(old('includes_online', $details->includes_online ?? false))> Includes Online</label>
        <label><input type="checkbox" name="includes_omr" value="1" @checked(old('includes_omr', $details->includes_omr ?? false))> Includes OMR</label>
    </div>
    <div class="col-sm-6 mt-3">
        <label class="form-label">Status</label>
        <div class="form-control d-flex">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" value="1" @checked(old('status', $details->status ?? 1) == 1)>
                <label class="form-check-label">Active</label>
            </div>
            <div class="form-check ms-3">
                <input class="form-check-input" type="radio" name="status" value="0" @checked(old('status', $details->status ?? 1) == 0)>
                <label class="form-check-label">Inactive</label>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <h5>Features</h5>
        <div id="featuresWrap">
            @foreach($features as $f)
                <input class="form-control mb-2" name="features[]" value="{{ $f }}" placeholder="Feature line">
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFeature()">+ Feature</button>
    </div>

    <div class="col-12 mt-4">
        <h5>Fulfilment modes &amp; prices</h5>
        <p class="text-muted small">Use <code>bundle</code> for Online+OMR included. Use <code>choose_one</code> + centre/delivery for OMR Master style pricing.</p>
        <div id="modesWrap">
            @foreach($modes as $idx => $m)
                <div class="border rounded p-3 mb-3 mode-row">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label">Code</label>
                            <select class="form-control" name="modes[{{ $idx }}][mode_code]">
                                @foreach(['online','bundle','centre','delivery'] as $c)
                                    <option value="{{ $c }}" @selected(($m->mode_code ?? '') === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Label</label>
                            <input class="form-control" name="modes[{{ $idx }}][label]" value="{{ $m->label ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price</label>
                            <input class="form-control" type="number" step="0.01" name="modes[{{ $idx }}][price]" value="{{ $m->price ?? 0 }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Help text</label>
                            <input class="form-control" name="modes[{{ $idx }}][help_text]" value="{{ $m->help_text ?? '' }}">
                        </div>
                        <div class="col-12 d-flex gap-3 mt-2">
                            <label><input type="checkbox" name="modes[{{ $idx }}][requires_location]" value="1" @checked(!empty($m->requires_location))> Needs centre</label>
                            <label><input type="checkbox" name="modes[{{ $idx }}][requires_address]" value="1" @checked(!empty($m->requires_address))> Needs address</label>
                            <label><input type="checkbox" name="modes[{{ $idx }}][includes_online]" value="1" @checked(!empty($m->includes_online))> Online</label>
                            <label><input type="checkbox" name="modes[{{ $idx }}][includes_omr]" value="1" @checked(!empty($m->includes_omr))> OMR</label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addMode()">+ Mode</button>
    </div>
</div>

@push('js_after')
<script>
let modeIndex = {{ count($modes) }};
function addFeature() {
    const wrap = document.getElementById('featuresWrap');
    const input = document.createElement('input');
    input.className = 'form-control mb-2';
    input.name = 'features[]';
    input.placeholder = 'Feature line';
    wrap.appendChild(input);
}
function addMode() {
    const wrap = document.getElementById('modesWrap');
    const i = modeIndex++;
    wrap.insertAdjacentHTML('beforeend', `
        <div class="border rounded p-3 mb-3 mode-row">
            <div class="row g-2">
                <div class="col-md-2">
                    <label class="form-label">Code</label>
                    <select class="form-control" name="modes[${i}][mode_code]">
                        <option value="online">online</option>
                        <option value="bundle">bundle</option>
                        <option value="centre">centre</option>
                        <option value="delivery">delivery</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Label</label>
                    <input class="form-control" name="modes[${i}][label]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Price</label>
                    <input class="form-control" type="number" step="0.01" name="modes[${i}][price]" value="0">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Help text</label>
                    <input class="form-control" name="modes[${i}][help_text]">
                </div>
                <div class="col-12 d-flex gap-3 mt-2">
                    <label><input type="checkbox" name="modes[${i}][requires_location]" value="1"> Needs centre</label>
                    <label><input type="checkbox" name="modes[${i}][requires_address]" value="1"> Needs address</label>
                    <label><input type="checkbox" name="modes[${i}][includes_online]" value="1"> Online</label>
                    <label><input type="checkbox" name="modes[${i}][includes_omr]" value="1"> OMR</label>
                </div>
            </div>
        </div>`);
}
</script>
@endpush
