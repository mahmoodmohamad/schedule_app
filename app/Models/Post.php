<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable=['title', 'content','image_url','schedule_time', 'status','user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function platforms() {
        return $this->belongsToMany(Platform::class, 'post_platforms')->withPivot('platform_status');;
    }
}
