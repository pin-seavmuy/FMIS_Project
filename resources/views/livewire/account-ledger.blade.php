<div>
    <div class="p-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2 text-sm text-base-content/70 mb-1">
                    <a href="{{ route('coa') }}" class="hover:text-primary transition-colors">Chart of Accounts</a>
                    <span class="icon-[tabler--chevron-right] w-3 h-3"></span>
                    <span>Ledger</span>
                </div>
                <h1 class="text-3xl font-bold text-base-content mb-1">
                    {{ $this->account->code }} - {{ $this->account->name }}
                </h1>
                <p class="text-sm text-base-content/70">
                    Type: <span class="capitalize">{{ $this->account->type }}</span> • 
                    Currency: USD
                </p>
            </div>

            {{-- Date Filter --}}
            <div class="bg-base-100 p-2 rounded-lg border border-base-200 shadow-sm">
                <x-date-range-picker start="startDate" end="endDate" />
            </div>
        </div>

        {{-- Ledger Grid --}}
        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-0">
                <x-ag-grid 
                    id="ledgerGrid"
                    :column-defs="[
                        ['field' => 'date', 'headerName' => 'Date', 'sortable' => true, 'filter' => true],
                        ['field' => 'reference', 'headerName' => 'Reference', 'sortable' => true, 'filter' => true],
                        ['field' => 'description', 'headerName' => 'Description', 'flex' => 1, 'sortable' => true, 'filter' => true],
                        ['field' => 'debit', 'headerName' => 'Debit', 'type' => 'rightAligned', 'valueFormatter' => 'value ? value.toLocaleString(\'en-US\', {style: \'currency\', currency: \'USD\'}) : \'-\''],
                        ['field' => 'credit', 'headerName' => 'Credit', 'type' => 'rightAligned', 'valueFormatter' => 'value ? value.toLocaleString(\'en-US\', {style: \'currency\', currency: \'USD\'}) : \'-\''],
                        ['field' => 'balance', 'headerName' => 'Balance', 'type' => 'rightAligned', 'valueFormatter' => 'value.toLocaleString(\'en-US\', {style: \'currency\', currency: \'USD\'})', 'cellClass' => 'font-bold'],
                    ]"
                    :row-data="$ledgerLines"
                    class="h-[600px]"
                />
            </div>
        </div>
    </div>
</div>
