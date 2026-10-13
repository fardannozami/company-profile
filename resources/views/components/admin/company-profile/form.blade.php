{{-- Admin form for editing the site's single company profile record (details, contact info, location). --}}
@props([
    'companyProfile' => null
])

<form
    method="POST"
    action="{{ route('admin.company-profile.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    <div class="grid grid-cols-1 gap-5">

        {{-- ============ IDENTITAS ============ --}}
        <fieldset class="form-group">
            <legend>Identitas</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <x-common.form.image-upload
                        label="Company Logo"
                        name="logo"
                        :current="$companyProfile?->logo"
                    />
                    <p class="mt-1.5 text-xs text-app-muted">
                        Logo tampil di header, footer, dan ikon tab browser.
                    </p>
                </div>

                <div>
                    <x-common.form.input
                        label="Company Name"
                        name="company_name"
                        :value="$companyProfile?->company_name"
                    />
                    <p class="mt-1.5 text-xs text-app-muted">
                        Nama tampil di header, footer, dan judul besar halaman About.
                    </p>
                </div>
            </div>
        </fieldset>

        {{-- ============ PROFIL ============ --}}
        <fieldset class="form-group">
            <legend>Profil</legend>

            <div>
                <x-common.form.richtext
                    label="About Company"
                    name="about"
                    :value="$companyProfile?->about"
                />
                <p class="mt-1.5 text-xs text-app-muted">
                    Ringkasan/intro (tampil di hero About); isi seksi dikelola di menu About Sections.
                </p>
            </div>
        </fieldset>

        {{-- ============ VISI MISI ============ --}}
        <fieldset class="form-group">
            <legend>Visi Misi</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <x-common.form.richtext
                        label="Vision"
                        name="vision"
                        :value="$companyProfile?->vision"
                    />
                    <p class="mt-1.5 text-xs text-app-muted">
                        Visi tampil di panel Visi Misi homepage dan pita navy halaman About.
                    </p>
                </div>

                <div>
                    <x-common.form.richtext
                        label="Mission"
                        name="mission"
                        :value="$companyProfile?->mission"
                    />
                    <p class="mt-1.5 text-xs text-app-muted">
                        Misi tampil berdampingan dengan visi di kedua tempat tersebut.
                    </p>
                </div>
            </div>
        </fieldset>

        {{-- ============ KONTAK ============ --}}
        <fieldset class="form-group">
            <legend>Kontak</legend>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <x-common.form.input
                    label="Phone"
                    name="phone"
                    :value="$companyProfile?->phone"
                />

                <x-common.form.input
                    label="WhatsApp"
                    name="whatsapp"
                    placeholder="6281234567890"
                    :value="$companyProfile?->whatsapp"
                />

                <x-common.form.input
                    label="Instagram"
                    name="instagram"
                    placeholder="yourcompany"
                    :value="$companyProfile?->instagram"
                />

                <x-common.form.input
                    label="Fax"
                    name="fax"
                    :value="$companyProfile?->fax"
                />

                <x-common.form.input
                    label="Email"
                    name="email"
                    type="email"
                    :value="$companyProfile?->email"
                />
            </div>
            <p class="mt-3 text-xs text-app-muted">
                Kontak tampil di footer, halaman Kontak, dan tombol WhatsApp/Instagram.
            </p>
        </fieldset>

        {{-- ============ LOKASI ============ --}}
        <fieldset class="form-group">
            <legend>Lokasi</legend>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <x-common.form.textarea
                        label="Address"
                        name="address"
                        rows="3"
                        :value="$companyProfile?->address"
                    />
                    <p class="mt-1.5 text-xs text-app-muted">
                        Alamat tampil di halaman Kontak, seksi Lokasi homepage, dan halaman About.
                    </p>
                </div>

                {{-- Map coordinates for the location map on the contact page --}}
                <div>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <x-common.form.input label="Latitude" name="latitude" type="number" step="any" placeholder="-6.2088" :value="$companyProfile?->latitude" />
                        <x-common.form.input label="Longitude" name="longitude" type="number" step="any" placeholder="106.8456" :value="$companyProfile?->longitude" />
                    </div>
                    <p class="mt-1.5 text-xs text-app-muted">
                        Optional. In Google Maps, right-click the location > click the latitude, longitude numbers to copy them.
                    </p>
                </div>
            </div>
        </fieldset>

        <x-common.form.submit label="Save Company Profile" />

    </div>
</form>
