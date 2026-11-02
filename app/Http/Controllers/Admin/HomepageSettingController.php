<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        'vision',
        'mission',
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
     * Per-section visibility toggles (boolean, default true = section shown).
     */
    public const TOGGLES = [
        'show_hero',
        'show_vision',
        'show_gallery',
        'show_products',
        'show_clients',
        'show_contact',
        'show_location',
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
            'hero_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_title'           => 'nullable|string|max:255',
            'hero_title_highlight' => 'nullable|string|max:255',
            'hero_description'     => 'nullable|string|max:1000',
            'hero_cta_primary'     => 'nullable|string|max:255',
            'hero_cta_secondary'   => 'nullable|string|max:255',
            'vision'               => 'nullable|string|max:1000',
            'mission'              => 'nullable|string|max:1000',
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
            'show_hero'            => 'nullable|boolean',
            'show_vision'          => 'nullable|boolean',
            'show_gallery'         => 'nullable|boolean',
            'show_products'        => 'nullable|boolean',
            'show_clients'         => 'nullable|boolean',
            'show_contact'         => 'nullable|boolean',
            'show_location'        => 'nullable|boolean',
        ]);

        /**
         * Handle hero image upload.
         * If a new image is uploaded:
         * - Remove the existing hero image file from storage
         * - Store the new image in public storage
         */
        if ($request->hasFile('hero_image')) {
            $companyProfile = CompanyProfile::first();
            if ($companyProfile?->hero_image) {
                Storage::disk('public')->delete($companyProfile->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('company', 'public');
        } else {
            unset($validated['hero_image']);
        }

        /**
         * Allowlist: persist homepage columns only.
         * Non-homepage columns (company_name, about, ...) are never touched here.
         */
        $homepageOnly = collect($validated)
            ->only(array_merge(self::FIELDS, self::TOGGLES, ['hero_image']))
            ->toArray();

        /**
         * Unchecked toggles are absent from the request — resolve every toggle
         * explicitly so unchecking a section actually hides it.
         */
        foreach (self::TOGGLES as $toggle) {
            $homepageOnly[$toggle] = $request->boolean($toggle);
        }

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
