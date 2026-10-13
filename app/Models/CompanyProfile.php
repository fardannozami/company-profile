<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * These fields represent the company's public profile information
     * and can be safely filled using create() or update().
     *
     * Typical use cases:
     * - Displaying company information on public pages
     * - Managing company profile settings from the admin panel
     */
    protected $fillable = [
        'company_name',
        'logo',
        'about',
        'vision',
        'mission',
        'address',
        'latitude',
        'longitude',
        'phone',
        'whatsapp',
        'instagram',
        'fax',
        'email',
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
    ];
}
