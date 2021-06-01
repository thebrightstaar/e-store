<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Pmochine\Report\Traits\HasReports;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasReports;
        use HasFactory;

}
