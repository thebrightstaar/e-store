<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipments extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','payment_id','order_id','status','shipment_date'];

    public function customer(){
    	 return $this -> belongsTo(User::class, 'user_id');}
    public function order(){
    	 return $this -> belongsTo(order::class);}
    public function payment(){
    	 return $this -> hasOne(payment::class);}
}
