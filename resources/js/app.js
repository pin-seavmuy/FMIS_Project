import { createGrid, ModuleRegistry, AllCommunityModule } from 'ag-grid-community';

// Register all community modules
ModuleRegistry.registerModules([AllCommunityModule]);

// Make AG Grid available globally
window.AgGrid = { createGrid };

// Global Formatters for AG Grid
window.FmisFormatters = {
    date: (params) => {
        if (!params.value) return '—';
        return new Date(params.value).toLocaleDateString();
    },
    currency: (params) => {
        if (!params.value && params.value !== 0) return '—';
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(params.value);
    }
};

// Global Renderers for AG Grid
window.FmisRenderers = {
    actions: (params) => {
        if (!params.data) return '';
        const id = params.data.id;
        // Simple icon buttons without circle background
        const btnStyle = "padding: 6px; border: none; background: none; cursor: pointer; border-radius: 4px; transition: background 0.2s;";
        const hover = "this.style.background='rgba(0,0,0,0.05)'";
        const out = "this.style.background='none'";

        return `
            <div style="display: flex; gap: 8px; align-items: center; justify-content: center; height: 100%;">
                <button 
                    onclick="Livewire.dispatch('view-user', { id: ${id} })" 
                    title="View" 
                    style="${btnStyle}"
                    onmouseover="${hover}" 
                    onmouseout="${out}">
                    <span class="icon-[tabler--eye]" style="width: 20px; height: 20px; color: #3b82f6; display: block;"></span>
                </button>
                <button 
                    onclick="Livewire.dispatch('edit-user', { id: ${id} })" 
                    title="Edit" 
                    style="${btnStyle}"
                    onmouseover="${hover}" 
                    onmouseout="${out}">
                    <span class="icon-[tabler--pencil]" style="width: 20px; height: 20px; color: #eab308; display: block;"></span>
                </button>
                <button 
                    onclick="Livewire.dispatch('confirm-delete', { id: ${id} })" 
                    title="Delete" 
                    style="${btnStyle}"
                    onmouseover="${hover}" 
                    onmouseout="${out}">
                    <span class="icon-[tabler--trash]" style="width: 20px; height: 20px; color: #ef4444; display: block;"></span>
                </button>
            </div>
        `;
    }
};
