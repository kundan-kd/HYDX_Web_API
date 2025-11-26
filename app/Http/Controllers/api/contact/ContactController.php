<?php

namespace App\Http\Controllers\api\contact;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ContactController extends BaseController
{
    public function getContact(){
        try{
            $getData = ContactUs::whereNull('deleted_at')->get();
            if(!empty($getData)){
                $success['list'] = $getData;
                return $this->sendResponse($success, 'Contact data fetched successfully');
            }
            return $this->sendError('Error', 'No data found');
        }catch(\Exception $e){
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
    }
    public function addContact(Request $request){
    try{
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Error',['error' => $validator->errors()]);
        };
        $insert = ContactUs::create([
            'name'=> $request->name,
            'email' =>$request->email,
            'mobile' => $request->mobile,
            'subject' => $request->subject,
            'message' => $request->message,
            ]);
        if($insert){
         Mail::to(env('MAIL_TO_ADDRESS'))->queue(new ContactMail($insert)); //mail goes in queue ind before data inserted in db after mail work
            $success['id'] = $insert->id;
            return $this->sendResponse($success, 'Query submitted successfully');
        }

        return $this->sendError('Error.',['error' => 'Error while query submit']);
    }catch(\Exception $e){
        throw new HttpException(500,$e->getMessage());
    }
    }
    
}
