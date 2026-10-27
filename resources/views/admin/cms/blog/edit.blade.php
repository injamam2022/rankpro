@extends('layouts.backend')

@section('css_after')
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Blog Post</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{ route('admin.blog.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $details->id }}">
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                            <input class="form-control" id="title" type="text" name="title" value="{{ old('title', $details->title) }}" required />
                        </div>

                        <div class="col-sm-12 mb-3">
                            <label class="form-label" for="slug">URL / Slug</label>
                            <div class="input-group">
                                <span class="input-group-text">/blog/</span>
                                <input class="form-control" id="slug" type="text" name="slug" value="{{ old('slug', $details->slug) }}" />
                            </div>
                            <small class="text-muted">Public URL: {{ url('/blog') }}/{{ old('slug', $details->slug) }}</small>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <label class="form-label" for="excerpt">Description</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="4" maxlength="2000">{{ old('excerpt', $details->excerpt) }}</textarea>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <label class="form-label" for="editor">Full Content</label>
                            <textarea id="editor" name="content" class="form-control" rows="8">{{ old('content', $details->content) }}</textarea>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="image">Image (PNG, JPG, WEBP)</label>
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-control" id="image" type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp,image/gif" />
                                @if($details->image)
                                    <img src="{{ $details->thumbnail_url }}" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="keywords">Keywords</label>
                            <input class="form-control" id="keywords" type="text" name="keywords" value="{{ old('keywords', $details->keywords) }}" placeholder="NEET tips, revision, mock test" maxlength="1000" />
                            <small class="text-muted">Comma-separated keywords for SEO</small>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="published_at">Publish Date</label>
                            <input class="form-control" id="published_at" type="datetime-local" name="published_at"
                                value="{{ old('published_at', $details->published_at ? $details->published_at->format('Y-m-d\TH:i') : '') }}" />
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="meta_title">SEO Title (optional)</label>
                            <input class="form-control" id="meta_title" type="text" name="meta_title" value="{{ old('meta_title', $details->meta_title) }}" />
                        </div>

                        <div class="col-sm-12 mb-3">
                            <label class="form-label" for="meta_description">SEO Description (optional)</label>
                            <input class="form-control" id="meta_description" type="text" name="meta_description" value="{{ old('meta_description', $details->meta_description) }}" maxlength="500" />
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-control d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" value="1" @if((int) old('status', $details->status) === 1) checked @endif />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check ms-3">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" @if((int) old('status', $details->status) === 0) checked @endif />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-5 text-center">
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js_after')
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error('Error initializing CKEditor:', error);
        });
</script>
@endsection
