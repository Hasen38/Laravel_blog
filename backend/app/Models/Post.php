<?php

namespace App\Models;
use App\Models\user;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;
    protected $fillable = [
        "title"
        ,"body",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}