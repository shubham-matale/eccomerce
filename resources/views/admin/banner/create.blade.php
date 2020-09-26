@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Banner
    </div>

    <div class="card-body">
        <form action="{{ route("admin.banners.store") }}" method="POST" enctype="multipart/form-data">
            @csrf


            <div class="form-group {{ $errors->has('bannerImage') ? 'has-error' : '' }}">
                <label for="bannerImage">Banner Image</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" accept=".jpeg,.bmp,.png,.jpg,.svg" class="custom-file-input" id="bannerImage" name="bannerImage">
                        <label class="custom-file-label" for="bannerImage">Choose file</label>
                    </div>
                </div>
                @if($errors->has('bannerImage'))
                    <p class="help-block">
                        {{ $errors->first('bannerImage') }}
                    </p>
                @endif
            </div>
            <div class="form-group">
                <label>Is Active</label>
                <select class="form-control" name="is_active" id="is_active" value="{{ old('is_active', isset($bannner) ? $bannner->status : 0) }}">
                    <option  value="1">Yes </option>
                    <option value="0">No</option>

                </select>
            </div>

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection
