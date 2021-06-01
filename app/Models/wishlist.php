<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wishlist extends Model
{
    use HasFactory;
            
    protected $fillable = [
    
        'product_id',
        'customer_id',
        'moved_to_cart',
        'shared',
        'time_of_moving'
    ];

                public function customer(){
    	 return $this -> belongsTo(User::class);}


	public function product()
	{
		return $this->belongsTo(Product::class)->whereReviewed(true);
	}

public function create(array $data)
    {
        $wishlist = wishlist:: create($data);

        return $wishlist;
    }


public function update(array $data, $id)
    {
        $wishlist = wishlist::find($id);

        $wishlist->update($data);

        return $wishlist;
    }

	 public function getItemsWithProducts($id)
    {
        return $this-> wishlist::->find($id)->item_wishlist;
    }

    public function getCustomerWhishlist($user_id)
{
	   $product=Product::find($user_id);
            if(is_null($product))
                return 'Product is not found';
            $myPorduct=Product::where('status'=> 1)->get();
            if($myPorduct->count()==0)
                return 'your does not have wishlist ';
            else
                return $myPorduct;
        }
}
}
