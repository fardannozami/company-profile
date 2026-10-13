<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-section visibility toggles for the homepage.
     * Default true so existing installs keep showing every section.
     */
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->boolean('show_hero')->default(true)->after('location_title');
            $table->boolean('show_vision')->default(true)->after('show_hero');
            $table->boolean('show_gallery')->default(true)->after('show_vision');
            $table->boolean('show_products')->default(true)->after('show_gallery');
            $table->boolean('show_clients')->default(true)->after('show_products');
            $table->boolean('show_contact')->default(true)->after('show_clients');
            $table->boolean('show_location')->default(true)->after('show_contact');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'show_hero',
                'show_vision',
                'show_gallery',
                'show_products',
                'show_clients',
                'show_contact',
                'show_location',
            ]);
        });
    }
};
