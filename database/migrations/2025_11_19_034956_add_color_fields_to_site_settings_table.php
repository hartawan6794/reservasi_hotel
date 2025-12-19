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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('primary_color')->nullable()->default('#B56952');
            $table->string('secondary_color')->nullable()->default('#C890FF');
            $table->string('accent_color')->nullable()->default('#EE786C');
            $table->string('text_color')->nullable()->default('#292323');
            $table->string('link_color')->nullable()->default('#B56952');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'secondary_color', 'accent_color', 'text_color', 'link_color']);
        });
    }
};
