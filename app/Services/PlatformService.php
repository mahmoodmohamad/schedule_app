<?php
namespace App\Services;

use App\Models\Platform;

class PlatformService
{
    public function index()
    {
        return Platform::all();
    }
    public function store(array $data)
{
    return Platform::create($data);
}

}
