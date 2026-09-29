<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'driver', 'external_id', 'access_token'];

protected $hidden = ['access_token']; // never returned by the API

protected function casts(): array
{
    return ['access_token' => 'encrypted'];
}
    public function posts() {
        return $this->belongsToMany(Post::class)->withPivot('platform_status');
    }
}
