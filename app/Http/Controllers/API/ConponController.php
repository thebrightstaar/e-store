<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConponController extends BaseController
{
    //

        public function ListOfCoupons()
    {
        $user= Auth::id();
        if($user->is_Admin == 1){
            $coupons = Coupon::orderBy('id', 'DESC')->get();
            if($coupons->count()==0){
                return $this->SendError('You do not have rights to access to coupons list');
            }
            else{
                return $this->SendResponse($coupons,'list of coupons');
            }
        }
        
    }


    public function storeNewCoupon(Request $request)
    {
        //['code','user_id','amount','active','percentage','usage_limit',
       // 'usage_per_customer','times_used', 'expired_at','is_primary',
        
            $validator = Validator::make($request->all(),[
          
        
            'code'=>'required'|conpon:: getRandomString(),
            'amount'=>'required',
            'active'=>'required',
            'percentage'=>'required',
            'usage_limit'=>'required',
            'is_primary'=>'required',
            'expired_at'=>'required|date',


        ]);
        if( $validator->fails())
                return $this->SendError('Validate Error',$validator->errors());
                    $user=User:: Auth::id());
               if($user->is_Admin == 1){
            
                        $Coupon=Coupon::create()
                       

                    $coupon->code = conpon:: getRandomString();
                    $coupon->amount = $request->amount;
                    $coupon->active = $request->active;
                    $coupon->percentage = $request->percentage;
                    $coupon->usage_limit = $request->usage_limit;
                    $coupon->is_primary = $request->is_primary;
					$coupon->expired_date = Carbon::parse($request->expired_date)->format('Y-m-d H:i:s');
                    $coupon->save();
                    return $this->SendResponse($coupon, 'Coupon added Successfully!');
                    
                }else{
                    return $this->SendError('You do not have rights to add coupon');
                }
    }
    
    public function editCoupon(Request $request, $id)
    {
        $user=User:: Auth::id());
               if($user->is_Admin == 1){
            $coupon = Coupon::find($id);
             $this->validate($request,['active'=>'required']);
            
                    $coupon->active = $request->active;
                  
                    $coupon->save();
                    return $this->SendResponse($coupon, 'Coupon Updated Successfully!');
                }  
                else{              

                 return $this->SendError('You do not have rights to update coupon');
                }

            }
        }

    
}
