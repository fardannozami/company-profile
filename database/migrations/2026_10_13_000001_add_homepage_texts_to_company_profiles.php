<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add editable homepage texts to company_profiles.
     * All nullable: null = keep current hardcoded fallback.
     */
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('hero_badge')->nullable()->after('email');
            $table->string('hero_title')->nullable()->after('hero_badge');
            $table->string('hero_title_highlight')->nullable()->after('hero_title');
            $table->string('hero_description', 1000)->nullable()->after('hero_title_highlight');
            $table->string('hero_cta_primary')->nullable()->after('hero_description');
            $table->string('hero_cta_secondary')->nullable()->after('hero_cta_primary');
            $table->string('hero_availability')->nullable()->after('hero_cta_secondary');
            $table->string('vision_eyebrow')->nullable()->after('hero_availability');
            $table->string('strength_1')->nullable()->after('vision_eyebrow');
            $table->string('strength_2')->nullable()->after('strength_1');
            $table->string('strength_3')->nullable()->after('strength_2');
            $table->string('strength_4')->nullable()->after('strength_3');
            $table->string('gallery_eyebrow')->nullable()->after('strength_4');
            $table->string('gallery_title')->nullable()->after('gallery_eyebrow');
            $table->string('gallery_subtitle')->nullable()->after('gallery_title');
            $table->string('products_eyebrow')->nullable()->after('gallery_subtitle');
            $table->string('products_title')->nullable()->after('products_eyebrow');
            $table->string('products_subtitle')->nullable()->after('products_title');
            $table->string('clients_eyebrow')->nullable()->after('products_subtitle');
            $table->string('clients_title')->nullable()->after('clients_eyebrow');
            $table->string('clients_subtitle')->nullable()->after('clients_title');
            $table->string('contact_eyebrow')->nullable()->after('clients_subtitle');
            $table->string('contact_title')->nullable()->after('contact_eyebrow');
            $table->string('contact_subtitle')->nullable()->after('contact_title');
            $table->string('location_eyebrow')->nullable()->after('contact_subtitle');
            $table->string('location_title')->nullable()->after('location_eyebrow');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'hero_badge',
                'hero_title',
                'hero_title_highlight',
                'hero_description',
                'hero_cta_primary',
                'hero_cta_secondary',
                'hero_availability',
                'vision_eyebrow',
                'strength_1',
                'strength_2',
                'strength_3',
                'strength_4',
                'gallery_eyebrow',
                'gallery_title',
                'gallery_subtitle',
                'products_eyebrow',
                'products_title',
                'products_subtitle',
                'clients_eyebrow',
                'clients_title',
                'clients_subtitle',
                'contact_eyebrow',
                'contact_title',
                'contact_subtitle',
                'location_eyebrow',
                'location_title',
            ]);
        });
    }
};
