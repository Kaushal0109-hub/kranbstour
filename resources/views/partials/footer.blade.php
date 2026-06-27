@php
    $companyLinks = [
        ['Blog', 'blog'],
        ['About Us', 'about'],
        ['Contact Us', 'contact'],
        ['Our Awards', 'awards'],
    ];

    $serviceLinks = [
        ['Taj Mahal Tours', 'tours.taj-mahal'],
        ['Jaipur Tours', 'tours.jaipur'],
        ['New Delhi Tours', 'tours.delhi'],
        ['Golden Triangle Tours', 'tours.golden-triangle'],
        ['Varanasi Tours', 'tours.varanasi'],
        ['Tour Packages', 'tours.packages'],
    ];

    $legalLinks = [
        ['Terms of Service', 'terms'],
        ['Privacy Policy', 'privacy'],
    ];

    $socialLinks = [
        ['fab fa-youtube', 'YouTube'],
        ['fab fa-facebook-f', 'Facebook'],
        ['fab fa-twitter', 'Twitter'],
        ['fab fa-instagram', 'Instagram'],
        ['fab fa-pinterest-p', 'Pinterest'],
    ];

    $paymentMethods = [
        ['PayPal', 'bg-[#003087] text-white', 'PayPal'],
        ['Mastercard', 'bg-white', 'mastercard'],
        ['Visa', 'bg-white text-[#1A1F71]', 'VISA'],
        ['Maestro', 'bg-white text-[#0099DF]', 'Maestro'],
        ['Amex', 'bg-[#006FCF] text-white', 'AMEX'],
        ['JCB', 'bg-white text-[#0B4EA2]', 'JCB'],
        ['Discover', 'bg-white', 'DISCOVER'],
        ['Klarna', 'bg-[#FFB3C7] text-[#0A0B09]', 'Klarna.'],
        ['G Pay', 'bg-white', 'G Pay'],
        ['Apple Pay', 'bg-white text-black', ' Pay'],
        ['UPI', 'bg-white text-[#097939]', 'UPI'],
        ['RuPay', 'bg-white text-[#0072BC]', 'RuPay'],
    ];
@endphp

<footer class="site-footer bg-black text-slate-400">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 pt-10 sm:pt-14 pb-36 sm:pb-10">

        {{--
            Mobile/tablet: 2-col grid — brand full width, Company|Services side-by-side, Legal + Newsletter full width
            Desktop (xl): 5 equal columns
        --}}
        <div class="grid grid-cols-2 xl:grid-cols-5 gap-x-6 sm:gap-x-8 gap-y-8 xl:gap-x-6 pb-10 border-b border-white/10">

            {{-- 1. Brand & Contact — full width on mobile --}}
            <div class="col-span-2 xl:col-span-1 space-y-5">
                @include('partials.logo', ['variant' => 'dark'])

                <p class="text-sm leading-relaxed text-slate-300">
                    {{ config('site.name') }} is a dynamic and experienced tour operator company,
                    dedicated to providing our clients with unforgettable travel experiences.
                </p>

                <a href="tel:{{ config('site.phone') }}" class="inline-flex items-start gap-3 group">
                    <span class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white shrink-0 mt-0.5 group-hover:bg-brand/20 transition-colors">
                        <i class="fas fa-phone-alt text-xs" aria-hidden="true"></i>
                    </span>
                    <span>
                        <span class="block text-sm text-slate-300 mb-1">Need help? Call us</span>
                        <span class="block text-[1.35rem] sm:text-2xl font-extrabold text-accent tracking-tight group-hover:text-accent-light transition-colors">
                            {{ config('site.phone_display') }}
                        </span>
                    </span>
                </a>
            </div>

            {{-- 2. Company — left column on mobile --}}
            <div>
                <h4 class="text-base font-bold text-white mb-4 sm:mb-5">Company</h4>
                <ul class="space-y-3 sm:space-y-3.5">
                    @foreach ($companyLinks as [$label, $route])
                        <li>
                            <a href="{{ route($route) }}" class="text-sm text-slate-400 hover:text-white transition-colors leading-snug">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 3. Services — right column on mobile --}}
            <div>
                <h4 class="text-base font-bold text-white mb-4 sm:mb-5">Services</h4>
                <ul class="space-y-3 sm:space-y-3.5">
                    @foreach ($serviceLinks as [$label, $route])
                        <li>
                            <a href="{{ route($route) }}" class="text-sm text-slate-400 hover:text-white transition-colors leading-snug">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 4. Legal — full width below Company|Services on mobile --}}
            <div class="col-span-2 xl:col-span-1">
                <h4 class="text-base font-bold text-white mb-4 sm:mb-5">Legal</h4>
                <ul class="space-y-3 sm:space-y-3.5">
                    @foreach ($legalLinks as [$label, $route])
                        <li>
                            <a href="{{ route($route) }}" class="text-sm text-slate-400 hover:text-white transition-colors">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 5. Newsletter + Payments — full width on mobile, last column on desktop --}}
            <div class="col-span-2 xl:col-span-1 space-y-7">
                <div>
                    <h4 class="text-base font-bold text-white mb-4">Newsletter</h4>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3 max-w-md xl:max-w-none">
                        @csrf
                        <label for="newsletter-email" class="sr-only">Email address</label>
                        <input type="email" id="newsletter-email" name="email" required placeholder="Enter your email"
                               class="footer-newsletter-input w-full px-4 py-3 rounded-lg bg-[#141414] border border-white/10 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-accent/40 focus:ring-1 focus:ring-accent/30">
                        <button type="submit" class="footer-subscribe w-full py-3 rounded-lg bg-accent hover:bg-accent-dark text-white font-bold text-sm transition-colors">
                            Subscribe
                        </button>
                    </form>
                </div>

                <div>
                    <h4 class="text-base font-bold text-white mb-3">We accept payments</h4>
                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2 max-w-md xl:max-w-none">
                        @foreach ($paymentMethods as [$name, $classes, $label])
                            <div class="footer-pay-badge aspect-[5/3] rounded {{ $classes }} flex items-center justify-center overflow-hidden px-1" title="{{ $name }}">
                                @if ($label === 'mastercard')
                                    <span class="flex items-center gap-0.5">
                                        <span class="w-3 h-3 rounded-full bg-[#EB001B]"></span>
                                        <span class="w-3 h-3 rounded-full bg-[#F79E1B] -ml-1.5"></span>
                                    </span>
                                @elseif ($label === 'DISCOVER')
                                    <span class="text-[7px] sm:text-[8px] font-extrabold text-[#FF6000] leading-none">DISCOVER</span>
                                @elseif ($label === ' Pay')
                                    <span class="text-[8px] sm:text-[9px] font-semibold text-black leading-none"><i class="fab fa-apple mr-0.5"></i>Pay</span>
                                @elseif ($label === 'G Pay')
                                    <span class="text-[8px] sm:text-[9px] font-bold leading-none"><span class="text-[#4285F4]">G</span><span class="text-[#EA4335]">&nbsp;Pay</span></span>
                                @else
                                    <span class="text-[7px] sm:text-[8px] font-extrabold leading-none text-center">{{ $label }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="pt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <p class="text-xs text-slate-500 order-2 sm:order-1">
                © {{ date('Y') }} {{ config('site.name') }} Inc. All rights reserved.
            </p>
            <div class="order-1 sm:order-2">
                <p class="text-sm font-bold text-white mb-3">Follow us</p>
                <div class="flex items-center gap-2">
                    @foreach ($socialLinks as [$icon, $label])
                        <a href="#" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-brand hover:border-brand transition-all" aria-label="{{ $label }}">
                            <i class="{{ $icon }} text-xs" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>

<button type="button" id="back-to-top"
        class="fixed bottom-4 right-4 z-40 w-9 h-9 text-white flex items-center justify-center opacity-0 pointer-events-none transition-all hover:text-accent"
        aria-label="Back to top">
    <i class="fas fa-chevron-up text-base" aria-hidden="true"></i>
</button>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('back-to-top');
        if (!btn) return;
        var toggle = function () {
            var show = window.scrollY > 400;
            btn.classList.toggle('opacity-0', !show);
            btn.classList.toggle('pointer-events-none', !show);
        };
        window.addEventListener('scroll', toggle, { passive: true });
        toggle();
        btn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    });
</script>
@endpush
