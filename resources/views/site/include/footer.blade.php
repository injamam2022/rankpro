@php
    use App\Support\WebsiteSettings;

    $ws = WebsiteSettings::all();
    $wsPhone = WebsiteSettings::displayPhone('phone');
    $wsWhatsapp = WebsiteSettings::displayPhone('whatsapp');
    $wsTollFree = trim($ws['toll_free'] ?? '');
    $wsTel = WebsiteSettings::telHref('phone');
    $wsWa = WebsiteSettings::whatsappHref();
    $wsEmail = $ws['email'] ?? '';
    $wsAddress = $ws['address'] ?? '';
    $wsSocials = WebsiteSettings::socialLinks();
    $wsName = $ws['site_name'] ?? 'RankPro';

    $exam = \App\Models\Exam::where('status', 1)->where('type', 2)->where('is_in_footer', 1)->orderByDesc('id')->limit(8)->get();
    $locationsQuery = \App\Models\Location::where('status', 1)->orderBy('location_name');
    if (\Illuminate\Support\Facades\Schema::hasColumn('locations', 'is_deleted')) {
        $locationsQuery->where('is_deleted', 0);
    }
    $locations = $locationsQuery->limit(8)->get();
@endphp

<footer class="rp-ft">
    <div class="rp-ft__inner">
        <div class="rp-ft__grid">
            <div class="rp-ft__col">
                <h3 class="rp-ft__title">Menu</h3>
                <ul class="rp-ft__links rp-ft__links--split">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('course_packages') }}">Course Packages</a></li>
                    <li><a href="{{ route('scholarship') }}">Scholarship Test</a></li>
                    <li><a href="{{ route('testseries') }}">Upcoming Test</a></li>
                    <li><a href="{{ route('index') }}#pricing">Product pricing</a></li>
                    <li><a href="{{ Auth::check() ? route('custom_test') : route('signup') }}">Practice Test</a></li>
                    <li><a href="{{ route('blog') }}">Blog</a></li>
                    <li><a href="{{ route('careers') }}">Careers</a></li>
                    <li><a href="{{ route('ranker') }}">Ranker</a></li>
                    <li><a href="{{ route('help') }}">Help &amp; Support</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms and condition</a></li>
                </ul>
            </div>

            <div class="rp-ft__col">
                <h3 class="rp-ft__title">NEET Test Series</h3>
                <ul class="rp-ft__links">
                    @forelse($exam as $value)
                        <li><a href="{{ route('testseries.details', encrypt($value->id)) }}">{{ $value->name }}</a></li>
                    @empty
                        <li><a href="{{ route('testseries') }}">NEET Test Series</a></li>
                    @endforelse
                </ul>
                <h3 class="rp-ft__title rp-ft__title--spaced">Products</h3>
                <ul class="rp-ft__links">
                    <li><a href="{{ Auth::check() ? route('custom_test') : route('signup') }}">Practice Test</a></li>
                    <li><a href="{{ route('ranker') }}">Ranker mentorship</a></li>
                    <li><a href="{{ route('new_light') }}">NEET Pro Test Series</a></li>
                    <li><a href="{{ route('index') }}#pricing">Product pricing</a></li>
                </ul>
            </div>

            <div class="rp-ft__col">
                <h3 class="rp-ft__title">Contact Us</h3>
                <ul class="rp-ft__contact">
                    @if($wsWhatsapp)
                        <li>
                            <span>WhatsApp No</span>
                            <a href="{{ $wsWa }}" target="_blank" rel="noopener noreferrer">{{ $wsWhatsapp }}</a>
                        </li>
                    @endif
                    @if($wsPhone)
                        <li>
                            <span>Ph No</span>
                            <a href="{{ $wsTel }}">{{ $wsPhone }}</a>
                        </li>
                    @endif
                    @if($wsTollFree !== '')
                        <li>
                            <span>Toll Free</span>
                            {{ $wsTollFree }}
                        </li>
                    @endif
                    @if($wsEmail)
                        <li>
                            <span>Mail id</span>
                            <a href="mailto:{{ $wsEmail }}">{{ $wsEmail }}</a>
                        </li>
                    @endif
                </ul>

                <h3 class="rp-ft__title rp-ft__title--spaced">Location</h3>
                @if($wsAddress !== '')
                    <p class="rp-ft__address">{{ $wsAddress }}</p>
                @endif
                <ul class="rp-ft__links">
                    @foreach($locations as $location)
                        <li>{{ $location->location_name }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="rp-ft__col">
                <h3 class="rp-ft__title">Social Media</h3>
                <ul class="rp-ft__social-list">
                    @if($wsWa)
                        <li><a href="{{ $wsWa }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
                    @endif
                    @foreach($wsSocials as $social)
                        <li>
                            <a href="{{ $social['href'] }}" target="_blank" rel="noopener noreferrer">
                                <i class="{{ $social['icon'] }}"></i> {{ $social['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="rp-ft__bottom">
        <div class="rp-ft__bottom-inner">
            <div class="rp-ft__copy">
                <p>&copy; {{ date('Y') }} {{ $ws['copyright'] ?? ($wsName . '. All Rights Reserved.') }}</p>
                <p class="rp-ft__powered"><strong>{{ $wsName }}<br>Powered by Shikkha Educational Advisory Solutions LLP</strong></p>
            </div>
            <div class="rp-ft__icons">
                @if($wsWa)
                    <a href="{{ $wsWa }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                @endif
                @foreach($wsSocials as $social)
                    <a href="{{ $social['href'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}"><i class="{{ $social['icon'] }}"></i></a>
                @endforeach
            </div>
        </div>
    </div>
</footer>
