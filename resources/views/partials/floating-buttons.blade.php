@php use App\Helpers\SiteHelper; @endphp
<a href="{{ SiteHelper::whatsappHref() }}" target="_blank" rel="noopener noreferrer"
   class="fixed bottom-[5.25rem] left-4 sm:bottom-6 sm:left-6 z-50 bg-[#25D366] text-white w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center text-xl sm:text-2xl shadow-lg hover:scale-110 transition-transform"
   aria-label="WhatsApp">
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
</a>

<button type="button"
        class="fixed bottom-[5.25rem] right-4 sm:bottom-6 sm:right-6 z-50 btn-brand text-white px-4 py-2.5 sm:px-5 sm:py-3 rounded-full flex items-center gap-2 font-semibold text-xs sm:text-sm shadow-lg transition-all"
        aria-label="Ask AI">
    <i class="fas fa-robot" aria-hidden="true"></i>
    <span>Ask AI</span>
</button>
