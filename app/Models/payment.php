<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;
    protected $fillable = ['amount','user_id','order_id','payment_method','paid_on','payment_reference',];
    public function customer(){
    	 return $this -> belongsTo(User::class, 'user_id');}


}
