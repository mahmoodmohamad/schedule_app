<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('platforms', function (Blueprint $t) {
        $t->string('driver')->nullable()->after('type');
        $t->string('external_id')->nullable();
        $t->text('access_token')->nullable();
    });

    Schema::table('post_platforms', function (Blueprint $t) {
        $t->string('publish_status')->default('pending');
        $t->string('external_post_id')->nullable();
        $t->timestamp('published_at')->nullable();
        $t->text('error')->nullable();
    });
}

public function down(): void
{
    Schema::table('platforms', fn (Blueprint $t) => $t->dropColumn(['driver', 'external_id', 'access_token']));
    Schema::table('post_platforms', fn (Blueprint $t) => $t->dropColumn(['publish_status', 'external_post_id', 'published_at', 'error']));
}
};
