<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizePorduct extends Model
{
    use HasFactory;
    protected $fillable = ['size','porduct_id'];
    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function coler()
    {
        return $this->belongsTo(Coler::class);
    }

    public function addSize(Request $request)
    {
        
            $input=$request->all();
            $validator = Validator::make($input,[
                'product_id'=>'required',
                'size'=>'required'
                ]);
            if( $validator->fails())
                return $this->SendError('Validate Error',$validator->errors());
            $user = Auth::user()->where('is_admin'=> 1);

            if($user->is_Admin == 1)
            {
                $product=Product::find($request->product_id);
                if(is_null($product))
                    return 'Product is not found';

                
              $sizePorduct=SizePorduct::create($input);
                return $product_size;
            }
            else
                return 'You do not have rights to add this size';
        
        }
    }

    public function getProductSizes($id)
    {
        
            $product=Product::find($id);
            if(is_null($product))
                return 'Product is not found';
            $sizePorduct=Product_size::where('product_id','=',$id)->get();
            if($sizePorduct->count()==0)
                return 'This product does not have sizes';
            else
                return $sizePorduct;
        }
    }

    public function deleteSize(Request $request)
    {
            $user = Auth::user()->where('is_admin'=> 1);
         if ( $user->is_admin != 1) {
        
                $deletedRows=Product_size::where('product_id',$request->product_id)->where('size',$request->size)->delete();
                
        }
    }

}
