<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use App\LanguageTranslation;
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
        $msg = array
        (
            "body"=>"New Order Received On Shree Kakaji Masale App",
        "title"=> "New Order Received"

        );
        $fields = array
        (
            'to'      => "/topics/NewOrderNotification",
            'notification'                  => $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); //For firebase, use https://fcm.googleapis.com/fcm/send

        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        print_r($result);

        $languageData = LanguageTranslation::all();
        $english=[];
        $hindi=[];
        $marathi=[];
        $english_2=[];
        $hindi_2=[];
        $marathi_2=[];
        foreach($languageData as $key=>$eachLanguage){
            $english_2[$eachLanguage['originalText']]=$eachLanguage['englishText'];
            $hindi_2[$eachLanguage['originalText']]=$eachLanguage['hindiText'];
            $marathi_2[$eachLanguage['originalText']]=$eachLanguage['marathiText'];
            $english[$eachLanguage['englishText']]=$eachLanguage['englishText'];
            $hindi[$eachLanguage['englishText']]=$eachLanguage['hindiText'];
            $marathi[$eachLanguage['englishText']]=$eachLanguage['marathiText'];
        }

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


    }
}
