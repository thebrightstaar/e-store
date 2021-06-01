<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColerPorduct extends Model
{
    use HasFactory;
    protected $fillable = ['coler','porduct_id','quantity','size_id'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function size()
    {
        return $this->belongsTo(size::class);
    }


    public function addColor(Request $request)
    {
        
            $input=$request->all();
            $validator = Validator::make($input,[
                'product_id'=>'required',
                'size_id'=>'required',
                'coler'=>'required',
                'quantity'=>'required'
                ]);
            if( $validator->fails())
                return $this->SendError('Validate Error',$validator->errors());

            $user = Auth::user()->where('is_admin'=> 1);

            if($user->is_Admin == 1)
            {
                $product=Product::find($request->product_id);
                if(is_null($product))
                    return 'Product is not found';

                $ColerPorduct=ColerPorduct::create($input);
                return $ColerPorduct;
            }
    }

    public function getColer(Request $request)
    {
        
            $input=$request->all();
            $validator = Validator::make($input,[
                'product_id'=>'required',
                'size_id'=>'required'
                ]);
            if( $validator->fails())
                return $this->SendError('Validate Error',$validator->errors());


            $colorQ=ColerPorduct::where('product_id','=',$request->product_id)->where('size_id','=',$request->size_id)->get();
            
                return $colorQ;
        
    }


    public function deleteColer(Request $request)
    {
        $user = Auth::user()->where('is_admin'=> 1);
         if ( $user->is_admin == 1) {
            
                $deletedRows=Product_size_color_quantity::where('product_id',$request->product_id)->where('size_id',$request->size_id)->where('color','=',$request->color)->delete();
         }
     }


    public function updateColor(Request $request)
    {
          $input=$request->all();
            $validator = Validator::make($input,[
                'product_id'=>'required',
                'size_id'=>'required',
                'coler_id'=>'required',
                'coler'=>'required',
                'quantity'=>'required'
            ]);
            if( $validator->fails())
                return $this->SendError('Validate Error',$validator->errors());
            $user = Auth::user()->where('is_admin'=> 1);
         if ( $user->is_admin == 1) {
        
                $updatedRows=Product_size_color_quantity::where('product_id',$request->product_id)->where('size_id',$request->size_id)->where('id',$request->color_id)->update(['color'=>$request->color, 'quantity'=>$request->quantity]);
                return $updatedRows;
            }
            
    }


}
