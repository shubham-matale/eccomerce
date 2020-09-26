@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Banner
    </div>

    <div class="card-body">
        <form action="{{ route("admin.banners.update", [$banner->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('bannerImage') ? 'has-error' : '' }}">
                <label for="bannerImage">Banner Image</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" accept=".jpeg,.bmp,.png,.jpg" class="custom-file-input" id="bannerImage" name="bannerImage">
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
                    <option  value="1" {{isset($banner) && $banner->status==1?'selected':''}}>Yes </option>
                    <option value="0" {{isset($banner) && $banner->status==0?'selected':''}}>No</option>

                </select>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection
