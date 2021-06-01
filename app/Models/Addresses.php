<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;
    protected $fillable = ['user_id',
'street_nomber','street_name','city','state','country','floor_nomber','apartment_nomber','post_code'
];
                 public function user()
    {
        return $this->belongsToMany(user::class);
    }

}
