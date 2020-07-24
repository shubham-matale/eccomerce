<?php
namespace App\Http\Controllers\Api\V1\User;


use App\Http\Controllers\Controller;
use App\Customer;
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerApiController extends Controller{

    public function isNewUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|numeric'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $customer=Customer::where('mobile_no','=',$request->mobile_no)->first();

                if ($customer==null) {
                    return response()->json(['success' => true,
                        'data' => ['userExist'=>false]], 200);
                } else {
                    return response()->json(['success' => true,
                        'data'=>['userExist'=>true,'userDetails'=>$customer]], 200);
                }
            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }

    }

    public function createNewUser(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|numeric|unique:customers',
                'name'=>'required',
                'email'=>'required|email'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $customer=new Customer;
                $customer->name=$request->name;
                $customer->mobile_no=$request->mobile_no;
                $customer->email=$request->email;
                $customerSaveStatus=$customer->save();
                if ($customerSaveStatus) {
                    return response()->json(['success' => true,
                        'data' => ['customerDetails'=>$customer]], 200);
                } else {
                    return response()->json(['success' => false,
                        'msg'=>'Customer Not Created'], 200);
                }
            }
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function getAddress(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|numeric'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $customer=Customer::where('mobile_no','=',$request->mobile_no)->first();

                if ($customer==null) {
                    return response()->json(['success' => false,
                        'msg'=>'No User Found'], 200);
                } else {
                    $customerAddress = Address::where('customer_id','=',$customer->id)->get();
                    if($customerAddress->isEmpty()){
                        return response()->json(['success' => false,
                            'msg'=>'No Address Found',
                            'data'=>[]], 200);
                    }
                    return response()->json(['success' => true,
                        'data'=>$customerAddress], 200);
                }
            }
//
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }

    public function createAddress(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|numeric',
                'address_line_1'=>'required',
                'address_line_2'=>'required',
                'pincode'=>'required',
                'latitude'=>'required|numeric',
                'longitude'=>'required|numeric'
            ]);
            if($validator->fails()){
                return response()->json(['success' => false,
                    'msg'=>$validator->errors()], 200);
            }
            else {
                $customer=Customer::where('mobile_no','=',$request->mobile_no)->first();
                if ($customer==null) {
                    return response()->json(['success' => false,
                        'msg'=>'No User Found'], 200);
                } else {

                    $address = new Address;
                    $address->address_line_1=$request->address_line_1;
                    $address->address_line_2=$request->address_line_2;
                    $address->pincode=$request->pincode;
                    $address->latitude=$request->latitude;
                    $address->longitude=$request->longitude;
                    $addressSaveStatus = $address->save();
                    if (!$addressSaveStatus) {
                        return response()->json(['success' => false,
                            'msg' => 'Error in storing Address'], 200);
                    }
                    $customerAddress = Address::where('customer_id','=',$customer->id)->get();
                    if($customerAddress->isEmpty()){
                        return response()->json(['success' => false,
                            'msg'=>'No Address Found',
                            'data'=>[]], 200);
                    }
                    return response()->json(['success' => true,
                        'data'=>$customerAddress], 200);
                }
            }
        }catch (Exception $e) {
            return response()->json(['success' => false,
                'data'=>$e], 200);
        }
    }
}
