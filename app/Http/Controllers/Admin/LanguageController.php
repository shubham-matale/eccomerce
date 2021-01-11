<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use App\LanguageTranslation;
use App\Http\Requests\StoreLanguageRequest;
use App\ProductSubCategory;
use App\ProductCategory;
class LanguageController extends Controller
{
    public function downloadJSONFile(){
        $data = json_encode(['Text 1','Text 2','Text 3','Text 4','Text 5']);
        $file = time() . '_file.json';
        $destinationPath=public_path()."/upload/json/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$data);
        return response()->download($destinationPath.$file);
    }

    public function CreateLanguagrTranslationFile(){

        define( 'API_ACCESS_KEY', 'AAAAn5pEcwU:APA91bHu-WVF70SFm9I7JRi4nSY-1IAwhHnxEfSLXxc-NJANFscBcfhxbfQwcu1n-6FZjnL7zXNu-1079KakSnXtySJAbcyZ9RtskNA3Hp2B_SUlnSsSciQKKdx8UKYb_TmFsOLC-xRj' ); // get API access key from Google/Firebase API's Console

//        $registrationIds = array( 'cyMSGTKBzwU:APA91...xMKgjgN32WfoJY6mI' ); //Replace this with your device token


// Modify custom payload here
// $languageData = LanguageTranslation::where('id','>=',186)->whereNull('englishText')->get();

        // foreach($languageData as $key=>$eachLanguage){
        //     $tempText=str_replace('_', ' ', $eachLanguage['originalText']);
        //     $Category = ProductCategory::where('product_category_name','like', '%' . $tempText . '%')->first();
        //     if($Category){
        //         print_r($Category->product_category_name);
        //         $eachLanguage->englishText=$Category->product_category_name;
        //         $eachLanguage->save();
                
        //     }else{
        //         $subcategory = ProductSubCategory::where('product_subcategory_name','like', '%' . $tempText . '%')->first();
        //     if($subcategory){
        //         print_r($subcategory->product_subcategory_name);
        //         $eachLanguage->englishText=$subcategory->product_subcategory_name;
        //         $eachLanguage->save();
        //     }   
        //     }
            

        // }


        $languageData = LanguageTranslation::all();
        $english=[];
        $hindi=[];
        $marathi=[];
        $english_2=[];
        $hindi_2=[];
        $marathi_2=[];
        foreach($languageData as $key=>$eachLanguage){
            $english_2[$eachLanguage['originalText']]=ucwords($eachLanguage['englishText']);
            $hindi_2[$eachLanguage['originalText']]=$eachLanguage['hindiText'];
            $marathi_2[$eachLanguage['originalText']]=$eachLanguage['marathiText'];
            $english[$eachLanguage['englishText']]=$eachLanguage['englishText'];
            $hindi[$eachLanguage['englishText']]=$eachLanguage['hindiText'];
            $marathi[$eachLanguage['englishText']]=$eachLanguage['marathiText'];
        }

        // dd($english_2);

        $data = json_encode($marathi,JSON_UNESCAPED_UNICODE);
        $file = 'marathi_file.json';
        $destinationPath=public_path()."/language/json/";
        File::put($destinationPath.$file,$data);


        $data = json_encode($english,JSON_UNESCAPED_UNICODE);
        $file = 'english_file.json';
        $destinationPath=public_path()."/language/json/";
        File::put($destinationPath.$file,$data);


        $data = json_encode($hindi,JSON_UNESCAPED_UNICODE);
        $file = 'hindi_file.json';
        $destinationPath=public_path()."/language/json/";
        File::put($destinationPath.$file,$data);

        $data = json_encode($marathi_2,JSON_UNESCAPED_UNICODE);
        $file = 'marathi_file_2.json';
        $destinationPath=public_path()."/language/json/";
        File::put($destinationPath.$file,$data);


        $data = json_encode($english_2,JSON_UNESCAPED_UNICODE);
        $file = 'english_file_2.json';
        $destinationPath=public_path()."/language/json/";
        File::put($destinationPath.$file,$data);


        $data = json_encode($hindi_2,JSON_UNESCAPED_UNICODE);
        $file = 'hindi_file_2.json';
        $destinationPath=public_path()."/language/json/";
        File::put($destinationPath.$file,$data);

        return redirect()->route('admin.language.index');
    }

    public function index()
    {
        $languageData=LanguageTranslation::all();

        return view('admin.language.index',compact(['languageData']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languageData = new LanguageTranslation;
        return view('admin.language.create', compact(['languageData']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLanguageRequest $request)
    {
        $languageData = new LanguageTranslation;
        $languageData->originalText=strtolower(str_replace(' ', '_', $request->englishText));
        $languageData->englishText=$request->englishText;
        $languageData->hindiText=$request->hindiText;
        $languageData->marathiText=$request->marathiText;
        $languageData->save();

        return redirect()->route('admin.language.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LanguageTranslation  $languageTranslation
     * @return \Illuminate\Http\Response
     */
    public function show(LanguageTranslation $languageTranslation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LanguageTranslation  $languageTranslation
     * @return \Illuminate\Http\Response
     */
    public function edit($languageTranslation)
    {

        $languageData = LanguageTranslation::find($languageTranslation);
        return view('admin.language.edit', compact(['languageData']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LanguageTranslation  $languageTranslation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $languageTranslation)
    {

        $languageData = LanguageTranslation::find($languageTranslation);
        $languageData->englishText=$request->englishText;
        $languageData->hindiText=$request->hindiText;
        $languageData->marathiText=$request->marathiText;
        $languageData->save();

        return redirect()->route('admin.language.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LanguageTranslation  $languageTranslation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $languageData = LanguageTranslation::find($id);
        $languageData->delete();

        return redirect()->route('admin.language.index');
    }
}
