{{-- Admin form for editing the homepage texts (all optional; blank = default hardcoded copy). --}}
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

        <x-common.form.input
            label="Vision Eyebrow"
            name="vision_eyebrow"
            placeholder="What drives us"
            :value="$companyProfile?->vision_eyebrow"
        />

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-common.form.input
                label="Strength 1"
                name="strength_1"
                placeholder="Thoughtful execution"
                :value="$companyProfile?->strength_1"
            />

            <x-common.form.input
                label="Strength 2"
                name="strength_2"
                placeholder="Scalable by design"
                :value="$companyProfile?->strength_2"
            />

            <x-common.form.input
                label="Strength 3"
                name="strength_3"
                placeholder="Long-term partnership"
                :value="$companyProfile?->strength_3"
            />

            <x-common.form.input
                label="Strength 4"
                name="strength_4"
                placeholder="Clear communication"
                :value="$companyProfile?->strength_4"
            />
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

        <x-common.form.submit label="Save Homepage Settings" />

    </div>
</form>
