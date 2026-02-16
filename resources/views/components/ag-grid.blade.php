@props([
    'id',
    'rowData' => [],
    'columnDefs' => [],
    'height' => '500px',
    'updateEvent' => null,
])

<div wire:ignore x-data="{
    gridApi: null,
    initGrid() {
        const gridDiv = document.getElementById('{{ $id }}');
        if (!gridDiv || this.gridApi) return;

        // Process columnDefs to map string formatters and renderers to functions
        const processedColDefs = @js($columnDefs).map(col => {
            if (col.valueFormatter && typeof col.valueFormatter === 'string' && col.valueFormatter.startsWith('FmisFormatters.')) {
                const formatterName = col.valueFormatter.split('.')[1];
                if (window.FmisFormatters && window.FmisFormatters[formatterName]) {
                    col.valueFormatter = window.FmisFormatters[formatterName];
                }
            }
            if (col.cellRenderer && typeof col.cellRenderer === 'string' && col.cellRenderer.startsWith('FmisRenderers.')) {
                const rendererName = col.cellRenderer.split('.')[1];
                if (window.FmisRenderers && window.FmisRenderers[rendererName]) {
                    col.cellRenderer = window.FmisRenderers[rendererName];
                }
            }
            return col;
        });

        const gridOptions = {
            columnDefs: processedColDefs,
            rowData: @js($rowData),
            pagination: true,
            paginationPageSize: 20,
            paginationPageSizeSelector: [10, 20, 50, 100],
            defaultColDef: {
                sortable: true,
                resizable: true,
                filter: true,
            },
            animateRows: true,
            rowSelection: { mode: 'singleRow' },
        };

        this.gridApi = AgGrid.createGrid(gridDiv, gridOptions);

        @if($updateEvent)
        Livewire.on('{{ $updateEvent }}', (event) => {
            if (this.gridApi) {
                // Handle both direct data array or event.data structure
                const newData = event.data || event;
                this.gridApi.setGridOption('rowData', newData);
            }
        });
        @endif
    }
}" x-init="initGrid()"
    x-on:livewire:navigated.window="initGrid()">

    <div id="{{ $id }}" style="width: 100%; height: {{ $height }};"></div>
</div>
