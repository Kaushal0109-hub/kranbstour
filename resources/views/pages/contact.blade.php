@extends('layouts.app')

@php use App\Helpers\SiteHelper; @endphp

@section('title', 'Contact Us — ' . config('site.name'))
@section('meta_description', 'Contact ' . config('site.name') . ' for Agra, Delhi, Jaipur & Varanasi tour quotes. Call ' . SiteHelper::phoneDisplay() . ' or send us a message.')

@section('content')
    {{-- Hero --}}
    <section class="relative pt-28 pb-16 sm:pb-20 overflow-hidden min-h-[340px] sm:min-h-[400px] flex items-center">
        <div class="absolute inset-0">
            <x-site-image src="cities/hero-main.jpg" alt="Taj Mahal, Agra — {{ config('site.name') }}" width="1920" height="800" :eager="true"
                          class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-br from-ink/85 via-brand-900/70 to-ink/75"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 80% 20%, #3db976 0%, transparent 45%), radial-gradient(circle at 10% 80%, #f97316 0%, transparent 40%);"></div>
        </div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <nav class="text-xs text-brand-100/80 mb-6 flex justify-center flex-wrap items-center gap-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span aria-hidden="true">/</span>
                <span class="text-white">Contact</span>
            </nav>
            <span class="inline-block bg-white/10 text-white text-xs font-bold px-4 py-1.5 rounded-full border border-white/20 mb-4">
                We're here to help
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4">
                Plan your India trip with us
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto">
                Get a free custom quote for Agra, Delhi, Jaipur or Varanasi — we reply within 2 hours.
            </p>
        </div>
    </section>

    <section class="py-12 sm:py-16 bg-surface">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                {{-- Contact info --}}
                <div class="lg:col-span-5 space-y-5">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-6 sm:p-7">
                        <h2 class="text-xl font-extrabold text-ink mb-2">Get in touch</h2>
                        <p class="text-sm text-ink-muted mb-6">Reach us anytime — our team is available 24/7 for tour enquiries.</p>

                        <div class="space-y-4">
                            @if (SiteHelper::phoneDisplay())
                            <a href="{{ SiteHelper::telHref() }}"
                               class="flex items-center gap-4 p-4 rounded-xl bg-brand-50 border border-brand-100 hover:border-brand/30 transition-colors group">
                                <span class="w-12 h-12 rounded-xl bg-brand text-white flex items-center justify-center text-lg shrink-0">
                                    <i class="fas fa-phone-alt" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-brand">Call us</p>
                                    <p class="text-lg font-extrabold text-ink group-hover:text-brand transition-colors">{{ SiteHelper::phoneDisplay() }}</p>
                                </div>
                            </a>
                            @endif

                            @if (SiteHelper::whatsappDigits())
                            <a href="{{ SiteHelper::whatsappHref() }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-4 p-4 rounded-xl bg-[#25D366]/10 border border-[#25D366]/20 hover:border-[#25D366]/40 transition-colors group">
                                <span class="w-12 h-12 rounded-xl bg-[#25D366] text-white flex items-center justify-center text-xl shrink-0">
                                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#128C7E]">WhatsApp</p>
                                    <p class="text-sm font-bold text-ink group-hover:text-[#128C7E] transition-colors">Chat with us instantly</p>
                                </div>
                            </a>
                            @endif

                            @if (SiteHelper::email())
                            <a href="{{ SiteHelper::mailtoHref() }}"
                               class="flex items-center gap-4 p-4 rounded-xl bg-surface border border-slate-100 hover:border-brand/30 transition-colors group">
                                <span class="w-12 h-12 rounded-xl bg-slate-100 text-brand flex items-center justify-center text-lg shrink-0">
                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-ink-muted">Email</p>
                                    <p class="text-sm font-bold text-ink group-hover:text-brand transition-colors">{{ SiteHelper::email() }}</p>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Trust cards --}}
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ([
                            ['icon' => 'fa-clock', 'title' => '2hr Response', 'desc' => 'Quick quotes'],
                            ['icon' => 'fa-shield-alt', 'title' => 'Free Cancel', 'desc' => 'Most tours'],
                            ['icon' => 'fa-star', 'title' => '4.9 Rating', 'desc' => '2,260+ reviews'],
                            ['icon' => 'fa-user-tie', 'title' => 'Local Guides', 'desc' => 'City experts'],
                        ] as $item)
                            <div class="bg-white rounded-xl border border-slate-100 p-4 text-center shadow-soft">
                                <i class="fas {{ $item['icon'] }} text-brand text-lg mb-2" aria-hidden="true"></i>
                                <p class="text-xs font-extrabold text-ink">{{ $item['title'] }}</p>
                                <p class="text-[10px] text-ink-muted mt-0.5">{{ $item['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Form --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6 sm:p-8 lg:p-10">
                        <h2 class="text-2xl font-extrabold text-ink mb-1">Send us a message</h2>
                        <p class="text-sm text-ink-muted mb-6">Fill in the details and we'll get back to you with a custom itinerary & price.</p>

                        @if ($errors->any())
                            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm" role="alert">
                                <p class="font-bold mb-1">Please fix the following:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                            @csrf

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="block text-xs font-bold text-ink mb-1.5">Full name <span class="text-accent">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                           placeholder="Your name"
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors">
                                </div>
                                <div>
                                    <label for="email" class="block text-xs font-bold text-ink mb-1.5">Email <span class="text-accent">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           placeholder="you@email.com"
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors">
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="phone" class="block text-xs font-bold text-ink mb-1.5">Phone / WhatsApp</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                           placeholder="+91 98765 43210"
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors">
                                </div>
                                <div>
                                    <label for="city" class="block text-xs font-bold text-ink mb-1.5">Interested in <span class="text-accent">*</span></label>
                                    <select id="city" name="city" required
                                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors">
                                        <option value="" disabled {{ old('city') ? '' : 'selected' }}>Select destination</option>
                                        @foreach (['Agra / Taj Mahal', 'New Delhi', 'Jaipur', 'Varanasi', 'Golden Triangle', 'Multiple Cities', 'Not sure yet'] as $option)
                                            <option value="{{ $option }}" @selected(old('city') === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="travel_date" class="block text-xs font-bold text-ink mb-1.5">Travel date</label>
                                    <input type="date" id="travel_date" name="travel_date" value="{{ old('travel_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors">
                                </div>
                                <div>
                                    <label for="travelers" class="block text-xs font-bold text-ink mb-1.5">No. of travelers</label>
                                    <select id="travelers" name="travelers"
                                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors">
                                        @foreach (['1', '2', '3–5', '6–10', '10+'] as $n)
                                            <option value="{{ $n }}" @selected(old('travelers') === $n)>{{ $n }} {{ $n === '1' ? 'person' : 'people' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="message" class="block text-xs font-bold text-ink mb-1.5">Your message <span class="text-accent">*</span></label>
                                <textarea id="message" name="message" rows="5" required
                                          placeholder="Tell us about your trip — places you want to visit, group size, special requests..."
                                          class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors resize-y min-h-[120px]">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="btn-accent w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white font-bold text-sm px-10 py-4 rounded-xl">
                                <i class="fas fa-paper-plane text-xs" aria-hidden="true"></i>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Quick links --}}
    <section class="py-12 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-ink-muted mb-5">Or browse tours directly</p>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach ([
                    ['Agra', 'tours.taj-mahal'],
                    ['Delhi', 'tours.delhi'],
                    ['Jaipur', 'tours.jaipur'],
                    ['Varanasi', 'tours.varanasi'],
                    ['All Packages', 'tours.packages'],
                ] as [$label, $route])
                    <a href="{{ route($route) }}"
                       class="text-xs font-bold px-5 py-2.5 rounded-full border border-slate-200 text-ink-muted hover:border-brand hover:text-brand hover:bg-brand-50 transition-colors">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
