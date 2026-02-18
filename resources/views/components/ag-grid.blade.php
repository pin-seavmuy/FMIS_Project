@props(['id', 'rowData' => [], 'columnDefs' => [], 'height' => '500px', 'updateEvent' => null])

<div wire:ignore x-data="{
    gridApi: null,
    theme: 'light',
    initGrid() {
        const gridDiv = document.getElementById('{{ $id }}');
        if (!gridDiv || this.gridApi) return;

        // Initialize theme based on document attribute
        this.updateTheme();

        // Observer for theme changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                    this.updateTheme();
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-theme']
        });

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
            theme: 'legacy',
        };

        this.gridApi = AgGrid.createGrid(gridDiv, gridOptions);

        @if($updateEvent)
        Livewire.on('{{ $updateEvent }}', (args) => {
            if (this.gridApi) {
                let newData = args;

                // Scenario 1: Event is an array containing the payload object [ { data: [...] } ]
                if (Array.isArray(args) && args.length === 1 && args[0].data) {
                    newData = args[0].data;
                }
                // Scenario 2: Event is the payload object { data: [...] }
                else if (args.data) {
                    newData = args.data;
                }
                // Scenario 3: Event is the data array itself [...] (legacy or plain dispatch)
                else if (Array.isArray(args)) {
                    // Check if it's wrapped in an array by Livewire (e.g. [[...]])
                    if (args.length === 1 && Array.isArray(args[0])) {
                        newData = args[0];
                    } else {
                        newData = args;
                    }
                }

                this.gridApi.setGridOption('rowData', newData);
            }
        });
        @endif
    },
    updateTheme() {
        const docTheme = document.documentElement.getAttribute('data-theme');
        this.theme = docTheme === 'dark' ? 'dark' : 'light';
    }
}" x-init="initGrid()" x-on:livewire:navigated.window="initGrid()">

    <div id="{{ $id }}"
        :class="theme === 'dark' ? 'ag-theme-quartz-dark' : 'ag-theme-quartz'"
        style="width: 100%; height: {{ $height }}; --ag-font-family: 'Battambang', 'Inter', sans-serif;"></div>
</div>
