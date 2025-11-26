<?php

namespace App\Http\Controllers\api\reservation;

use App\Http\Controllers\api\BaseController;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Http\Request;
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
                'no_of_room' => 'required',
                'guest' => 'required',
                'adult' => 'required',
                'children' => 'required',
                'name' => 'required',
                'mobile' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error.', ['error' => $validator->errors()]);
            }

            $insert = Reservation::create([
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'no_of_room' => $request->no_of_room,
                'guest' => $request->guest,
                'adult' => $request->adult,
                'children' => $request->children,
                'name' => $request->name,
                'mobile' => $request->mobile,
            ]);

            if($insert){
                if (is_array($request->room_type)) {
                    foreach ($request->room_type as $room) {
                        RoomType::create([
                            'reservation_id' => $insert->id,
                            'room_type'      => $room,
                        ]);
                    }
                }
            $detail = Reservation::where('id', $insert->id)->get();
            $success['id'] = $detail[0]->id;
            return $this->sendResponse($success, 'Reservation Added successfully.');
                    }
            return $this->sendError('Error.', ['error' => 'Error while adding Reservation']);

        } catch (\Exception $e) {
        throw new HttpException(500, $e->getMessage());
        }
    }

}
