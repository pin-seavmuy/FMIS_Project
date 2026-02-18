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
                let newData = event;

                // Scenario 1: Event is an array containing the payload object [ { data: [...] } ]
                if (Array.isArray(event) && event.length === 1 && event[0].data) {
                    newData = event[0].data;
                } 
                // Scenario 2: Event is the payload object { data: [...] }
                else if (event.data) {
                    newData = event.data;
                }
                // Scenario 3: Event is the data array itself [...] (legacy or plain dispatch)
                else if (Array.isArray(event)) {
                     // Check if it's wrapped in an array by Livewire (e.g. [[...]])
                     if (event.length === 1 && Array.isArray(event[0])) {
                         newData = event[0];
                     } else {
                         newData = event;
                     }
                }

                this.gridApi.setGridOption('rowData', newData);
            }
        });
        @endif
    }
}" x-init="initGrid()"
    x-on:livewire:navigated.window="initGrid()">

    <div id="{{ $id }}" style="width: 100%; height: {{ $height }};"></div>
</div>
