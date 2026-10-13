<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AboutSectionController extends Controller
{
    /**
     * Display a paginated list of about sections.
     *
     * Responsibilities:
     * - Retrieve about sections ordered by the custom `order` column
     * - Apply pagination
     * - Render the admin about sections index page
     */
    public function index()
    {
        return view('pages.admin.about-sections.index', [
            'aboutSections' => AboutSection::orderBy('order')->paginate(12),
        ]);
    }

    /**
     * Show the form for creating a new about section.
     *
     * Responsibilities:
     * - Render the about section creation form
     */
    public function create()
    {
        return view('pages.admin.about-sections.create');
    }

    /**
     * Store a newly created about section in storage.
     *
     * Responsibilities:
     * - Validate incoming request data
     * - Generate a unique slug from the title
     * - Assign display order automatically
     * - Cast active status to boolean
     * - Handle image upload
     * - Persist about section data to the database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'body'      => 'required|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'required|in:0,1',
        ]);

        /**
         * Generate a unique slug from the title for stable #profil-{slug} anchors.
         */
        $validated['slug'] = self::uniqueSlug(Str::slug($validated['title']) ?: 'bagian');

        /**
         * Assign the next available order value.
         * New about sections are placed at the bottom by default.
         */
        $validated['order'] = (AboutSection::max('order') ?? 0) + 1;

        /**
         * Ensure boolean casting for the active state.
         */
        $validated['is_active'] = (bool) $validated['is_active'];

        /**
         * Handle about section image upload.
         */
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('about-sections', 'public');
        }

        AboutSection::create($validated);

        return redirect()->route('admin.about-sections.index')
            ->with('success', 'About section added');
    }

    /**
     * Show the form for editing the specified about section.
     *
     * Responsibilities:
     * - Load the about section data
     * - Render the edit form
     */
    public function edit(AboutSection $aboutSection)
    {
        return view('pages.admin.about-sections.edit', compact('aboutSection'));
    }

    /**
     * Update the specified about section in storage.
     *
     * Responsibilities:
     * - Validate updated request data
     * - Handle about section reordering actions
     * - Replace image if a new one is uploaded
     * - Persist updated about section data
     *
     * Note: the slug stays stable on update so existing
     * #profil-{slug} anchors keep working after a title edit.
     */
    public function update(Request $request, AboutSection $aboutSection)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'is_active'    => 'required|in:0,1',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order_action' => 'nullable|in:top,up,down,bottom',
        ]);

        /**
         * Handle about section order movement if an order action is provided.
         */
        if ($request->order_action) {
            $this->reorder($aboutSection, $request->order_action);
        }

        /**
         * Handle image replacement.
         * If a new image is uploaded:
         * - Delete the existing image file
         * - Store the new image
         */
        if ($request->hasFile('image')) {
            if ($aboutSection->image) {
                Storage::disk('public')->delete($aboutSection->image);
            }
            $validated['image'] = $request->file('image')->store('about-sections', 'public');
        }

        /**
         * Remove order_action from the validated data
         * to prevent unintended database updates.
         */
        unset($validated['order_action']);

        $aboutSection->update($validated);

        return redirect()->route('admin.about-sections.index')
            ->with('success', 'About section updated');
    }

    /**
     * Handle about section order rearrangement.
     *
     * Supported actions:
     * - top:    Move about section to the first position
     * - bottom: Move about section to the last position
     * - up:     Move about section one position up
     * - down:   Move about section one position down
     *
     * This method adjusts surrounding records to maintain
     * a consistent ordering sequence.
     */
    private function reorder(AboutSection $aboutSection, string $action)
    {
        $max = AboutSection::max('order');

        /**
         * Shift surrounding about sections based on the selected action.
         */
        match ($action) {
            'top' => AboutSection::where('order', '<', $aboutSection->order)
                ->increment('order'),

            'bottom' => AboutSection::where('order', '>', $aboutSection->order)
                ->decrement('order'),

            'up' => AboutSection::where('order', $aboutSection->order - 1)
                ->increment('order'),

            'down' => AboutSection::where('order', $aboutSection->order + 1)
                ->decrement('order'),
        };

        /**
         * Assign the new order value to the current about section.
         */
        $aboutSection->order = match ($action) {
            'top'    => 1,
            'bottom' => $max,
            'up'     => max(1, $aboutSection->order - 1),
            'down'   => min($max, $aboutSection->order + 1),
        };

        $aboutSection->save();
    }

    /**
     * Remove the specified about section from storage.
     *
     * Responsibilities:
     * - Delete the about section image file
     * - Remove the about section record from the database
     */
    public function destroy(AboutSection $aboutSection)
    {
        if ($aboutSection->image) {
            Storage::disk('public')->delete($aboutSection->image);
        }
        $aboutSection->delete();

        return back()->with('success', 'About section deleted');
    }

    /**
     * Generate a slug not taken by another about section.
     */
    private static function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 2;

        while (AboutSection::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
