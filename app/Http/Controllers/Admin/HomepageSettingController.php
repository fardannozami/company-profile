<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class HomepageSettingController extends Controller
{
    /**
     * Homepage text fields editable from this page.
     * Mirrors the nullable columns on company_profiles (null = hardcoded fallback).
     */
    public const FIELDS = [
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

    /**
     * Display the homepage settings management page.
     *
     * Responsibilities:
     * - Retrieve the existing company profile (single-record setup)
     * - Render the admin homepage settings page
     */
    public function index()
    {
        $companyProfile = CompanyProfile::first();

        return view('pages.admin.homepage-settings.index', [
            'companyProfile' => $companyProfile,
        ]);
    }

    /**
     * Update the homepage settings.
     *
     * Responsibilities:
     * - Validate incoming request data (all fields optional)
     * - Update ONLY the homepage columns, leaving every other
     *   company profile column untouched
     * - Ensure only one company profile record exists
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_badge'           => 'nullable|string|max:255',
            'hero_title'           => 'nullable|string|max:255',
            'hero_title_highlight' => 'nullable|string|max:255',
            'hero_description'     => 'nullable|string|max:1000',
            'hero_cta_primary'     => 'nullable|string|max:255',
            'hero_cta_secondary'   => 'nullable|string|max:255',
            'hero_availability'    => 'nullable|string|max:255',
            'vision_eyebrow'       => 'nullable|string|max:255',
            'strength_1'           => 'nullable|string|max:255',
            'strength_2'           => 'nullable|string|max:255',
            'strength_3'           => 'nullable|string|max:255',
            'strength_4'           => 'nullable|string|max:255',
            'gallery_eyebrow'      => 'nullable|string|max:255',
            'gallery_title'        => 'nullable|string|max:255',
            'gallery_subtitle'     => 'nullable|string|max:255',
            'products_eyebrow'     => 'nullable|string|max:255',
            'products_title'       => 'nullable|string|max:255',
            'products_subtitle'    => 'nullable|string|max:255',
            'clients_eyebrow'      => 'nullable|string|max:255',
            'clients_title'        => 'nullable|string|max:255',
            'clients_subtitle'     => 'nullable|string|max:255',
            'contact_eyebrow'      => 'nullable|string|max:255',
            'contact_title'        => 'nullable|string|max:255',
            'contact_subtitle'     => 'nullable|string|max:255',
            'location_eyebrow'     => 'nullable|string|max:255',
            'location_title'       => 'nullable|string|max:255',
        ]);

        /**
         * Allowlist: persist homepage columns only.
         * Non-homepage columns (company_name, about, ...) are never touched here.
         */
        $homepageOnly = collect($validated)
            ->only(self::FIELDS)
            ->toArray();

        /**
         * Update or create the company profile.
         * This ensures that only one record (ID = 1) exists.
         */
        CompanyProfile::updateOrCreate(
            ['id' => 1],
            $homepageOnly
        );

        return redirect()
            ->route('admin.homepage-settings.index')
            ->with('success', 'Homepage settings saved successfully.');
    }
}
