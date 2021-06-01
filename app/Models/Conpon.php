<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conpon extends Model
{
    use HasFactory;

    	protected $fillable = ['code','user_id','amount','active','percentage','usage_limit',
        'usage_per_customer','times_used', 'expired_at','is_primary',
    ];
	public $timestamps = true;

	public function owner()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_coupon', 'coupon_id', 'user_id')->withPivot('purchased');
	}




	protected $charsets = [
        'alphanumeric' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        'alphabetical' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'numeric'      => '0123456789',
    ];

public function getRandomString($format, $length)
    {
        $couponCode = '';

        for ($i = 0; $i < $length; $i++) {
            $couponCode .= $this->charsets[$format][rand(0, strlen($this->charsets[$format]) - 1)];
        }

        return $couponCode;
    }
}

}
