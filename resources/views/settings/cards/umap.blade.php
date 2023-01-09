<div class="card mt-3">
    <div class="card-header">
        {{ __('settings.umap') }}
    </div>
    <div class="card-body">
        <form action="{{ route('settings.umap') }}" method="post">
            @csrf

            <div class="form-group row">
                <label for="umap_url" class="col-md-4 cold-form-label text-md-end">
                    uMap-URL
                </label>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="umap_url" id="umap_url">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
