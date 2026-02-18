import {
    createGrid,
    ModuleRegistry,
    AllCommunityModule,
} from "ag-grid-community";

// Register all community modules
ModuleRegistry.registerModules([AllCommunityModule]);

// Make AG Grid available globally
window.AgGrid = { createGrid };

// Global Formatters for AG Grid
window.FmisFormatters = {
    date: (params) => {
        if (!params.value) return "—";
        return new Date(params.value).toLocaleDateString();
    },
    currency: (params) => {
        if (!params.value && params.value !== 0) return "—";
        return new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
        }).format(params.value);
    },
    activeStatus: (params) => {
        return params.value ? "Active" : "Inactive";
    },
};

// Helper: generate a single action button HTML string
// type can be: 'view', 'edit', 'delete', 'download' (or any custom)
window.makeActionBtn = (type, onclick, titleOverride) => {
    const presets = {
        view: { icon: "tabler--eye", cls: "action-view", title: "View" },
        edit: { icon: "tabler--pencil", cls: "action-edit", title: "Edit" },
        delete: {
            icon: "tabler--trash",
            cls: "action-delete",
            title: "Delete",
        },
        download: {
            icon: "tabler--download",
            cls: "action-download",
            title: "Download",
        },
    };
    const p = presets[type] || { icon: "tabler--dots", cls: "", title: type };
    const title = titleOverride || p.title;
    return `<button class="btn-action ${p.cls}" title="${title}" onclick="${onclick}">
                <span class="icon-[${p.icon}]" style="width:18px;height:18px;display:block"></span>
            </button>`;
};

// Global Renderers for AG Grid
window.FmisRenderers = {
    actions: (params) => {
        if (!params.data) return "";
        const id = params.data.id;
        return `<div class="btn-action-group">
            ${makeActionBtn("view", `Livewire.dispatch('view-user', { id: ${id} })`)}
            ${makeActionBtn("edit", `Livewire.dispatch('edit-user', { id: ${id} })`)}
            ${makeActionBtn("delete", `Livewire.dispatch('confirm-delete', { id: ${id} })`)}
        </div>`;
    },
    coaName: (params) => {
        if (!params.data) return "";
        const depth = params.data.depth || 0;
        const padding = depth * 20;
        const icon = params.data.hasChildren
            ? '<span class="icon-[tabler--folder]" style="width:16px;height:16px;margin-right:5px;opacity:0.7"></span>'
            : '<span class="icon-[tabler--file]" style="width:16px;height:16px;margin-right:5px;opacity:0.4"></span>';
        return `<div style="padding-left: ${padding}px; display: flex; align-items: center;">${icon} ${params.value}</div>`;
    },
    coaActions: (params) => {
        if (!params.data) return "";
        const id = params.data.id;
        return `<div class="btn-action-group">
            ${makeActionBtn("edit", `Livewire.dispatch('edit-account', { id: ${id} })`)}
            ${makeActionBtn("delete", `Livewire.dispatch('trigger-delete-coa', { id: ${id} })`)}
        </div>`;
    },
};
