<?php

namespace App\Http\Controllers\api\reservation;

use App\Http\Controllers\api\BaseController;
use App\Mail\ReservationMail;
use App\Models\Reservation;
use App\Models\RoomType;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReservationController extends BaseController
{
    public function getResData(Request $request){
        try{
            $getData = Reservation:: whereNull('deleted_at')->get();
            if(!empty($getData)){
                $success['list'] = $getData;
                return $this->sendResponse($success, 'Reservation data fetch succesfully');
            }
            return $this->sendError('Error.', ['error' => 'No Result Found']);
        }catch(\Exception $e){
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
    }
    
    public function addResData(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'checkin' => 'required',
                'checkout' => 'required',
                'rooms' => 'required',
                'guests' => 'required',
                'roomType' => 'required',
                'name' => 'required',
                'mobile' => 'required',
                'email' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error.', ['error' => $validator->errors()]);
            }
            $checkin1_date = new DateTime($request->checkin);
            $checkout1_date = new DateTime($request->checkout);
            $insert = Reservation::create([
                'checkin' => $checkin1_date->format("Y-m-d"),
                'checkout' => $checkout1_date->format("Y-m-d"),
                'rooms' => $request->rooms,
                'guests' => $request->guests,
                'roomType' => $request->roomType,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' =>$request->email,
            ]);
            

            if($insert){
                $checkin_date = new DateTime($request->checkin);
                $checkout_date = new DateTime($request->checkout);
                $insertMail =[
                    'checkin' => $checkin_date->format("m/d/Y"),
                    'checkout' => $checkout_date->format("m/d/Y"),
                    'rooms' => $request->rooms,
                    'guests' => $request->guests,
                    'roomType' => $request->roomType,
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                ];
                // if (is_array($request->room_type)) {
                //     foreach ($request->room_type as $room) {
                //         RoomType::create([
                //             'reservation_id' => $insert->id,
                //             'room_type'      => $room,
                //         ]);
                //     }
                // }
                
            Mail::to(env('MAIL_TO_ADDRESS'))->send(new ReservationMail($insertMail)); //mail goes in queue ind before data inserted in db after mail work
            
            $detail = Reservation::where('id', $insert->id)->get();
            $success['id'] = $detail[0]->id;
            return $this->sendResponse($success, 'Reservation Added successfully.');
                    }
            return $this->sendError('Error.', ['error' => 'Error while adding Reservation']);

        } catch (\Exception $e) {
        return response()->json(['error occured' => $e->getMessage()]);
        throw new HttpException(500, $e->getMessage());
        }
    }

}
