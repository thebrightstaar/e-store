<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','stars','porduct_id','review','name'];
   
    public function customer(){
    	 return $this -> belongsTo(User::class);}
    public function porduct(){
    	 return $this -> belongsTo(Porduct::class);}

 function getCustomerReview()
    {
        $user = user::id()->id;

        $reviews = Review::where(['customer_id'=> $customerId])->with('product')->paginate(5);

        return $reviews;
    }


}
