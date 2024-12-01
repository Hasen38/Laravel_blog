<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class blogs extends Model
{
    /** @use HasFactory<\Database\Factories\BlogsFactory> */
    use HasFactory;

    protected $fillable = [         
        'title',
        'body',
        'user_id',
    ];
 public function user(){
    return $this->belongsTo(User::class);
 }
 
}