<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
   protected $fillable  =['user_id','order_id','titel','Messages','status'];
    public function customer(){
    	 return $this -> belongsTo(User::class);}


	public function order()
	{
		return $this->belongsTo(order::class)->wherepaid(true);
}
