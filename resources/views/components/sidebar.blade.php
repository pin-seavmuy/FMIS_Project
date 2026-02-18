@props(['active' => ''])

<aside class="sidebar" id="sidebar">
    {{-- Logo / Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2" />
                <polyline points="2 17 12 22 22 17" />
                <polyline points="2 12 12 17 22 12" />
            </svg>
        </div>
        <span class="sidebar-brand-text">FMIS</span>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <span class="sidebar-section-title">General</span>

        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="icon-[tabler--layout-dashboard]" style="width:18px;height:18px"></span>
            <span>Dashboard</span>
        </a>

        <span class="sidebar-section-title">Finance</span>

        <a href="{{ route('accounting') }}" class="sidebar-link {{ request()->routeIs('accounting') ? 'active' : '' }}">
            <span class="icon-[tabler--book-2]" style="width:18px;height:18px"></span>
            <span>Accounting</span>
        </a>
        <a href="{{ route('coa') }}" class="sidebar-link {{ request()->routeIs('coa') ? 'active' : '' }}">
            <span class="icon-[tabler--list-tree]" style="width:18px;height:18px"></span>
            <span>Chart of Accounts</span>
        </a>
        <a href="{{ route('banking') }}" class="sidebar-link {{ request()->routeIs('banking') ? 'active' : '' }}">
            <span class="icon-[tabler--building-bank]" style="width:18px;height:18px"></span>
            <span>Banking</span>
        </a>
        <a href="{{ route('invoices') }}" class="sidebar-link {{ request()->routeIs('invoices') ? 'active' : '' }}">
            <span class="icon-[tabler--file-invoice]" style="width:18px;height:18px"></span>
            <span>Invoices</span>
        </a>
        <a href="{{ route('bills') }}" class="sidebar-link {{ request()->routeIs('bills') ? 'active' : '' }}">
            <span class="icon-[tabler--receipt]" style="width:18px;height:18px"></span>
            <span>Bills</span>
        </a>

        <span class="sidebar-section-title">Reports</span>

        <a href="{{ route('reports') }}" class="sidebar-link {{ request()->routeIs('reports') ? 'active' : '' }}">
            <span class="icon-[tabler--report-analytics]" style="width:18px;height:18px"></span>
            <span>Reports</span>
        </a>

        <span class="sidebar-section-title">System</span>

        <a href="{{ route('users') }}" class="sidebar-link {{ request()->routeIs('users') ? 'active' : '' }}">
            <span class="icon-[tabler--users]" style="width:18px;height:18px"></span>
            <span>Users</span>
        </a>
        <a href="{{ route('settings') }}" class="sidebar-link {{ request()->routeIs('settings') ? 'active' : '' }}">
            <span class="icon-[tabler--settings]" style="width:18px;height:18px"></span>
            <span>Settings</span>
        </a>
    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <button class="sidebar-signout-btn" id="sidebar-signout-btn" aria-label="Sign out">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
            <span>Sign Out</span>
        </button>
        <button class="sidebar-collapse-btn" id="sidebar-collapse-btn" aria-label="Collapse sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="11 17 6 12 11 7" />
                <polyline points="18 17 13 12 18 7" />
            </svg>
            <span>Collapse</span>
        </button>
    </div>
</aside>

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>

{{-- Mobile hamburger --}}
<button class="sidebar-hamburger" id="sidebar-hamburger" aria-label="Open menu">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" y1="12" x2="21" y2="12" />
        <line x1="3" y1="6" x2="21" y2="6" />
        <line x1="3" y1="18" x2="21" y2="18" />
    </svg>
</button>
