<?php

namespace App\Http\Controllers\Admin;

use App\Banners;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\ProductImage;
use App\ProductSubCategory;
use App\ProductVariable;
use App\ProductVariableOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannersController extends Controller{

    public function index()
    {
        abort_unless(\Gate::allows('view_banner'), 403);

        $banners = Banners::all();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('add_banner'), 403);

        $banner=new Banners;
        return view('admin.banner.create',compact('banner'));
    }

    public function store(Request $request)
    {

        abort_unless(\Gate::allows('add_banner'), 403);
        $validator = $request->validate([
            'bannerImage' => 'required|file|mimes:jpeg,png,jpg,svg',
            'is_active' => 'required',
        ]);
        $banner = new Banners;
        if($request->has('bannerImage')){
            $banner->imageUrl=$this->saveImage($request->bannerImage);
        }
        $banner->status=$request->is_active;
        $banner->save();

        return redirect()->route('admin.banners.index');
    }

    public function edit(Banners $banner)
    {
        abort_unless(\Gate::allows('add_banner'), 403);
        return view('admin.banner.edit', compact(['banner']));
    }

    public function update(Request $request, Banners $banner)
    {
        abort_unless(\Gate::allows('add_banner'), 403);
        $validator = $request->validate([
            'bannerImage' => 'nullable|file|mimes:jpeg,png,jpg,svg'
        ]);

        if($request->has('bannerImage')){
            $banner->imageUrl=$this->saveImage($request->bannerImage);
        }
        $banner->status=$request->is_active;
        $banner->update();


        return redirect()->route('admin.banners.index');
    }

    public function show(Banners $banner)
    {
        abort_unless(\Gate::allows('view_banner'), 403);
        return view('admin.products.show', compact(['banner']));
    }

    public function destroy(Banners $banner)
    {
        abort_unless(\Gate::allows('delete_banner'), 403);
        $banner->delete();
        return back();
    }

    function saveImage($image){
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('images/banners/');
        $image->move($destinationPath, $image_name);
        $imageURL=env('APP_URL').'/images/banners/'.$image_name;
        return $imageURL;
    }

}
