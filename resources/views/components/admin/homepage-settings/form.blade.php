{{-- Admin form for editing the homepage texts, grouped per section (all optional; blank = default hardcoded copy). --}}
@props([
    'companyProfile' => null
])

<form
    method="POST"
    action="{{ route('admin.homepage-settings.update') }}"
>
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 gap-5">

        {{-- ============ HERO ============ --}}
        <fieldset class="form-group">
            <legend>Hero</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-app-text">
                        <input
                            type="checkbox"
                            name="show_hero"
                            value="1"
                            @checked(old('show_hero', $companyProfile?->show_hero ?? true))
                            class="h-4 w-4"
                        >
                        Tampilkan section ini di homepage
                    </label>

                    @error('show_hero')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <x-common.form.input
                    label="Hero Badge"
                    name="hero_badge"
                    placeholder="See what we're building"
                    :value="$companyProfile?->hero_badge"
                />

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <x-common.form.input
                        label="Hero Title"
                        name="hero_title"
                        placeholder="We craft digital products that"
                        :value="$companyProfile?->hero_title"
                    />

                    <x-common.form.input
                        label="Hero Title Highlight"
                        name="hero_title_highlight"
                        placeholder="move brands forward"
                        :value="$companyProfile?->hero_title_highlight"
                    />
                </div>

                <x-common.form.textarea
                    label="Hero Description"
                    name="hero_description"
                    rows="3"
                    :value="$companyProfile?->hero_description"
                />

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <x-common.form.input
                        label="Hero CTA Primary"
                        name="hero_cta_primary"
                        placeholder="Start a project"
                        :value="$companyProfile?->hero_cta_primary"
                    />

                    <x-common.form.input
                        label="Hero CTA Secondary"
                        name="hero_cta_secondary"
                        placeholder="Explore our work"
                        :value="$companyProfile?->hero_cta_secondary"
                    />
                </div>

                <x-common.form.input
                    label="Hero Availability"
                    name="hero_availability"
                    placeholder="Available for new projects"
                    :value="$companyProfile?->hero_availability"
                />
            </div>
        </fieldset>

        {{-- ============ VISION & MISSION ============ --}}
        <fieldset class="form-group">
            <legend>Vision &amp; Mission</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-app-text">
                        <input
                            type="checkbox"
                            name="show_vision"
                            value="1"
                            @checked(old('show_vision', $companyProfile?->show_vision ?? true))
                            class="h-4 w-4"
                        >
                        Tampilkan section ini di homepage
                    </label>

                    @error('show_vision')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <x-common.form.textarea
                    label="Vision"
                    name="vision"
                    rows="3"
                    placeholder="To become a trusted digital partner for growing brands worldwide."
                    :value="$companyProfile?->vision"
                />

                <x-common.form.textarea
                    label="Mission"
                    name="mission"
                    rows="3"
                    placeholder="To design and build digital products that solve real problems and deliver lasting value."
                    :value="$companyProfile?->mission"
                />
            </div>
        </fieldset>

        {{-- ============ GALLERY ============ --}}
        <fieldset class="form-group">
            <legend>Gallery</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-app-text">
                        <input
                            type="checkbox"
                            name="show_gallery"
                            value="1"
                            @checked(old('show_gallery', $companyProfile?->show_gallery ?? true))
                            class="h-4 w-4"
                        >
                        Tampilkan section ini di homepage
                    </label>

                    @error('show_gallery')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <x-common.form.input
                        label="Gallery Eyebrow"
                        name="gallery_eyebrow"
                        placeholder="Selected work"
                        :value="$companyProfile?->gallery_eyebrow"
                    />

                    <x-common.form.input
                        label="Gallery Title"
                        name="gallery_title"
                        placeholder="A look at what we've made"
                        :value="$companyProfile?->gallery_title"
                    />

                    <x-common.form.input
                        label="Gallery Subtitle"
                        name="gallery_subtitle"
                        placeholder="Recent projects we're proud of."
                        :value="$companyProfile?->gallery_subtitle"
                    />
                </div>
            </div>
        </fieldset>

        {{-- ============ PRODUCTS ============ --}}
        <fieldset class="form-group">
            <legend>Products</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-app-text">
                        <input
                            type="checkbox"
                            name="show_products"
                            value="1"
                            @checked(old('show_products', $companyProfile?->show_products ?? true))
                            class="h-4 w-4"
                        >
                        Tampilkan section ini di homepage
                    </label>

                    @error('show_products')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <x-common.form.input
                        label="Products Eyebrow"
                        name="products_eyebrow"
                        placeholder="What we offer"
                        :value="$companyProfile?->products_eyebrow"
                    />

                    <x-common.form.input
                        label="Products Title"
                        name="products_title"
                        placeholder="Products & services"
                        :value="$companyProfile?->products_title"
                    />

                    <x-common.form.input
                        label="Products Subtitle"
                        name="products_subtitle"
                        placeholder="Digital solutions built to support growth, performance, and long-term scalability."
                        :value="$companyProfile?->products_subtitle"
                    />
                </div>
            </div>
        </fieldset>

        {{-- ============ CLIENTS ============ --}}
        <fieldset class="form-group">
            <legend>Clients</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-app-text">
                        <input
                            type="checkbox"
                            name="show_clients"
                            value="1"
                            @checked(old('show_clients', $companyProfile?->show_clients ?? true))
                            class="h-4 w-4"
                        >
                        Tampilkan section ini di homepage
                    </label>

                    @error('show_clients')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <x-common.form.input
                        label="Clients Eyebrow"
                        name="clients_eyebrow"
                        placeholder="Partnerships"
                        :value="$companyProfile?->clients_eyebrow"
                    />

                    <x-common.form.input
                        label="Clients Title"
                        name="clients_title"
                        placeholder="Companies we've worked with"
                        :value="$companyProfile?->clients_title"
                    />

                    <x-common.form.input
                        label="Clients Subtitle"
                        name="clients_subtitle"
                        placeholder="From early-stage startups to established organizations, teams trust us to deliver."
                        :value="$companyProfile?->clients_subtitle"
                    />
                </div>
            </div>
        </fieldset>

        {{-- ============ CONTACT ============ --}}
        <fieldset class="form-group">
            <legend>Contact</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-app-text">
                        <input
                            type="checkbox"
                            name="show_contact"
                            value="1"
                            @checked(old('show_contact', $companyProfile?->show_contact ?? true))
                            class="h-4 w-4"
                        >
                        Tampilkan section ini di homepage
                    </label>

                    @error('show_contact')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <x-common.form.input
                        label="Contact Eyebrow"
                        name="contact_eyebrow"
                        placeholder="Let's talk"
                        :value="$companyProfile?->contact_eyebrow"
                    />

                    <x-common.form.input
                        label="Contact Title"
                        name="contact_title"
                        placeholder="Have a project in mind?"
                        :value="$companyProfile?->contact_title"
                    />

                    <x-common.form.input
                        label="Contact Subtitle"
                        name="contact_subtitle"
                        placeholder="Tell us what you're building. We'll get back to you within one business day."
                        :value="$companyProfile?->contact_subtitle"
                    />
                </div>
            </div>
        </fieldset>

        {{-- ============ LOCATION ============ --}}
        <fieldset class="form-group">
            <legend>Location</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-app-text">
                        <input
                            type="checkbox"
                            name="show_location"
                            value="1"
                            @checked(old('show_location', $companyProfile?->show_location ?? true))
                            class="h-4 w-4"
                        >
                        Tampilkan section ini di homepage
                    </label>

                    @error('show_location')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <x-common.form.input
                        label="Location Eyebrow"
                        name="location_eyebrow"
                        placeholder="Visit us"
                        :value="$companyProfile?->location_eyebrow"
                    />

                    <x-common.form.input
                        label="Location Title"
                        name="location_title"
                        placeholder="Where to find us"
                        :value="$companyProfile?->location_title"
                    />
                </div>
            </div>
        </fieldset>

        <x-common.form.submit label="Simpan Pengaturan Homepage" />

    </div>
</form>
