<div class="sticky top-0 z-30 flex h-16 w-full justify-end bg-base-100/80 backdrop-blur-xl overflow-visible">
    <div class="flex items-center gap-5 px-6">

        <div class="flex items-center gap-4">

            {{-- ============================= --}}
            {{-- Language Switcher (Tailwind + Alpine) --}}
            {{-- ============================= --}}
            <div x-data="{ open: false }" class="relative">

                {{-- Trigger --}}
                <button
                    @click="open = !open"
                    @click.away="open = false"
                    class="flex items-center gap-1.5 bg-base-100 border border-base-200 hover:bg-base-200 rounded-full h-8 px-3">

                    {{-- Current Language Flag --}}
                    <span class="{{ app()->getLocale() == 'kh' ? 'icon-[circle-flags--kh]' : 'icon-[circle-flags--us]' }} w-5 h-5 rounded-full"></span>
                    
                    {{-- Language Name --}}
                    <span class="text-xs font-medium">{{ app()->getLocale() == 'kh' ? 'KH' : 'EN' }}</span>

                    {{-- Arrow --}}
                    <svg class="w-4 h-4 opacity-60 transition-transform duration-200"
                         :class="{ 'rotate-180': open }"
                         xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div x-show="open"
                     x-transition
                     class="absolute right-0 mt-2 w-full bg-base-100 border border-base-200 rounded-xl shadow-lg overflow-hidden z-50">

                    @if(app()->getLocale() !== 'en')
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-base-200 transition">
                        <span class="icon-[circle-flags--us] w-5 h-5 rounded-full"></span>
                        EN
                    </a>
                    @endif

                    @if(app()->getLocale() !== 'kh')
                    <a href="{{ route('lang.switch', 'kh') }}"
                       class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-base-200 transition">
                        <span class="icon-[circle-flags--kh] w-5 h-5 rounded-full"></span>
                        KH
                    </a>
                    @endif

                </div>
            </div>


            {{-- ============================= --}}
            {{-- User Info --}}
            {{-- ============================= --}}
            <div class="text-right hidden md:block">
                <p class="text-sm font-semibold text-base-content m-0"
                   x-data
                   x-text="$store.user?.name || 'User'"></p>
                <p class="text-xs text-base-content/60 m-0"
                   x-data
                   x-text="$store.user?.email || 'email@example.com'"></p>
            </div>

            {{-- User Avatar --}}
            <div
                class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-sm font-bold text-white shadow-md shadow-indigo-500/20">
                <span x-data
                      x-text="($store.user?.name || '?').charAt(0).toUpperCase()"></span>
            </div>
        </div>


        {{-- ============================= --}}
        {{-- Theme Toggle --}}
        {{-- ============================= --}}
        <button
            class="flex items-center justify-center bg-base-100 border border-base-200 rounded-lg cursor-pointer hover:bg-base-200 hover:border-base-300 w-9 h-9"
            id="theme-toggle-app"
            aria-label="Toggle theme">

            {{-- Sun Icon --}}
            <svg class="theme-icon-light-app w-4 h-4 text-base-content"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="5" />
                <line x1="12" y1="1" x2="12" y2="3" />
                <line x1="12" y1="21" x2="12" y2="23" />
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                <line x1="1" y1="12" x2="3" y2="12" />
                <line x1="21" y1="12" x2="23" y2="12" />
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
            </svg>

            {{-- Moon Icon --}}
            <svg class="theme-icon-dark-app w-4 h-4 text-base-content hidden"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
            </svg>
        </button>

    </div>
</div>


{{-- ============================= --}}
{{-- Alpine User Store --}}
{{-- ============================= --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('user', {
            name: localStorage.getItem('fmis-user-name') || '',
            email: localStorage.getItem('fmis-user-email') || '',

            init() {
                document.addEventListener('smis-session:ready', (event) => {
                    const profile = event.detail.profile || {};
                    this.name = profile.displayName || profile.username || 'User';
                    this.email = profile.email || '';

                    localStorage.setItem('fmis-user-name', this.name);
                    localStorage.setItem('fmis-user-email', this.email);
                });
            }
        });
    });
</script>
