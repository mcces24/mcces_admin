<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewUsers extends Model
{
    use HasFactory;

    protected $table = 'new_user'; 
    protected $fillable = ['delete_flg'];
    
}