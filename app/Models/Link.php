<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function shortLink()
    {
        return url("/go/$this->shortened_url");
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
