<?php

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logUserActivity')) {
    function logUserActivity(string $action, ?string $description = null, $model = null): void
    {
        UserActivity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
        ]);
    }
}
