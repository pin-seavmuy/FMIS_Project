<div class="sticky top-0 z-30 flex h-16 w-full justify-end bg-base-100/80 backdrop-blur-xl transition-all duration-300">
    <div class="flex items-center gap-5 px-6">
        <div class="flex items-center gap-3">
            <div class="text-right hidden md:block">
                <p class="text-sm font-semibold text-base-content m-0" x-data x-text="$store.user?.name || 'User'"></p>
                <p class="text-xs text-base-content/60 m-0" x-data x-text="$store.user?.email || 'email@example.com'"></p>
            </div>
            <div
                class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-sm font-bold text-white shadow-md shadow-indigo-500/20">
                <span x-data x-text="($store.user?.name || '?').charAt(0).toUpperCase()"></span>
            </div>
        </div>

        <button
            class="flex items-center gap-2 px-3 py-1.5 bg-base-100 border border-base-200 rounded-lg text-sm font-medium cursor-pointer transition-all hover:bg-base-200 hover:border-base-300"
            id="theme-toggle-app" aria-label="Toggle theme">
            {{-- Sun icon --}}
            <svg class="theme-icon-light-app theme-icon w-4 h-4 text-base-content"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            {{-- Moon icon --}}
            <svg class="theme-icon-dark-app theme-icon w-4 h-4 text-base-content hidden"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
            </svg>
        </button>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('user', {
            name: localStorage.getItem('fmis-user-name') || '',
            email: localStorage.getItem('fmis-user-email') || '',
            init() {
                // Listen for SSO session ready event to populate user data
                document.addEventListener('smis-session:ready', (event) => {
                    const profile = event.detail.profile || {};
                    this.name = profile.displayName || profile.username || 'User';
                    this.email = profile.email || '';
                    
                    // Persist to localStorage to prevent FOUC on refresh
                    localStorage.setItem('fmis-user-name', this.name);
                    localStorage.setItem('fmis-user-email', this.email);
                });
            }
        });
    });
</script>
