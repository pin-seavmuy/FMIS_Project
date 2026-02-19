@props(['active' => ''])

<aside
    class="fixed top-0 left-0 w-[260px] h-screen bg-base-100/80 backdrop-blur-xl border-r border-base-200 flex flex-col z-[90] transition-[width,transform] duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] overflow-hidden [.sidebar-collapsed_&]:w-[72px] -translate-x-full md:translate-x-0 [.sidebar-mobile-open_&]:translate-x-0 [.sidebar-mobile-open_&]:shadow-2xl print:hidden"
    id="sidebar">
    {{-- Logo / Brand --}}
    <div
        class="flex items-center gap-3.5 px-5 py-6 border-b border-base-200/50 [.sidebar-collapsed_&]:justify-center min-h-[88px]">
        <div
            class="w-10 h-10 min-w-[40px] rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2" />
                <polyline points="2 17 12 22 22 17" />
                <polyline points="2 12 12 17 22 12" />
            </svg>
        </div>
        <span
            class="text-xl font-bold bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent whitespace-nowrap [.sidebar-collapsed_&]:hidden transition-opacity duration-300">FMIS</span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto scrollbar-thin">
        <span
            class="block text-[11px] font-bold uppercase tracking-wider text-base-content/40 px-3 py-1.5 mt-2 mb-1 whitespace-nowrap [.sidebar-collapsed_&]:hidden">General</span>

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--layout-dashboard] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Dashboard') }}</span>
        </a>

        {{-- Users --}}
        <a href="{{ route('users') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('users') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--users] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Users') }}</span>
        </a>

        {{-- Divider --}}
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-bold text-base-content/40 uppercase tracking-wider [.sidebar-collapsed_&]:hidden">{{ __('Financials') }}</p>
        </div>

        {{-- COA --}}
        <a href="{{ route('coa') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('coa') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--list-tree] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Chart of Accounts') }}</span>
        </a>

        {{-- Journal Entries --}}
        <a href="{{ route('journal-entries.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('journal-entries.*') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--book] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Journal Entries') }}</span>
        </a>

        {{-- Accounting --}}
        <a href="{{ route('accounting') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('accounting') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--notebook] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Accounting') }}</span>
        </a>

        {{-- Banking --}}
        <a href="{{ route('banking') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('banking') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--building-bank] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Banking') }}</span>
        </a>

        {{-- Divider --}}
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-bold text-base-content/40 uppercase tracking-wider [.sidebar-collapsed_&]:hidden">{{ __('Sales & Purchases') }}</p>
        </div>

        {{-- Invoices --}}
        <a href="{{ route('invoices') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('invoices') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--file-invoice] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Invoices') }}</span>
        </a>

        {{-- Bills --}}
        <a href="{{ route('bills') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('bills') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--receipt] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Bills') }}</span>
        </a>

        {{-- Reports --}}
        <a href="{{ route('reports') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('reports') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--chart-pie] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Reports') }}</span>
        </a>

        {{-- Divider --}}
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-bold text-base-content/40 uppercase tracking-wider [.sidebar-collapsed_&]:hidden">{{ __('System') }}</p>
        </div>

        {{-- Settings --}}
        <a href="{{ route('settings') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group [.sidebar-collapsed_&]:justify-center {{ request()->routeIs('settings') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-sm ring-1 ring-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-500/20' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
            <span class="icon-[tabler--settings] w-5 h-5 transition-transform group-hover:scale-110"></span>
            <span class="text-sm font-medium tracking-wide [.sidebar-collapsed_&]:hidden">{{ __('Settings') }}</span>
        </a>

        <div class="flex-1"></div>


    </nav>

    {{-- Sidebar Footer --}}
    <div class="p-3 border-t border-base-200/50 space-y-1">
        <button
            class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl bg-transparent border-none text-xs font-medium cursor-pointer transition-all whitespace-nowrap hover:bg-error/10 hover:text-error text-base-content/70 [.sidebar-collapsed_&]:justify-center group"
            id="sidebar-signout-btn" aria-label="Sign out">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="flex-shrink-0 group-hover:text-error transition-colors">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
            <span class="[.sidebar-collapsed_&]:hidden">Sign Out</span>
        </button>
        <button
            class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl bg-transparent border-none text-xs font-medium cursor-pointer transition-all whitespace-nowrap hover:bg-base-200 hover:text-base-content text-base-content/70 [.sidebar-collapsed_&]:justify-center"
            id="sidebar-collapse-btn" aria-label="Collapse sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="flex-shrink-0 transition-transform duration-300 [.sidebar-collapsed_&]:rotate-180">
                <polyline points="11 17 6 12 11 7" />
                <polyline points="18 17 13 12 18 7" />
            </svg>
            <span class="[.sidebar-collapsed_&]:hidden">Collapse</span>
        </button>
    </div>
</aside>

{{-- Mobile overlay --}}
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden [.sidebar-overlay.active_&]:block transition-opacity"
    id="sidebar-overlay"></div>

{{-- Mobile hamburger --}}
<button
    class="fixed top-4 left-4 z-50 p-2 bg-base-100/80 backdrop-blur-md border border-base-200 rounded-lg text-base-content shadow-sm md:hidden flex items-center justify-center cursor-pointer"
    id="sidebar-hamburger" aria-label="Open menu">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" y1="12" x2="21" y2="12" />
        <line x1="3" y1="6" x2="21" y2="6" />
        <line x1="3" y1="18" x2="21" y2="18" />
    </svg>
</button>
