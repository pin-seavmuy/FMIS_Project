<div>
    <div class="space-y-6">
        {{-- Global Error Alert removed in favor of toast --}}

        {{-- Form Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Date --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Date</span>
                </label>
                <input type="date" wire:model="date" class="input input-bordered w-full @error('date') input-error @enderror" @if($isReadOnly) disabled @endif />
                @error('date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Reference --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Reference</span>
                </label>
                <input type="text" wire:model="reference" placeholder="(Auto-generated)" class="input input-bordered w-full" disabled />
            </div>

            {{-- Description --}}
            <div class="form-control md:col-span-3">
                <label class="label">
                    <span class="label-text font-medium">Description</span>
                </label>
                <textarea wire:model="description" class="textarea textarea-bordered h-20" placeholder="Enter journal entry description..." @if($isReadOnly) disabled @endif></textarea>
            </div>
        </div>

        {{-- Lines --}}
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200">
                            <tr>
                                <th class="w-1/3">Account</th>
                                <th class="w-1/3">Description</th>
                                <th class="w-1/6 text-right">Debit</th>
                                <th class="w-1/6 text-right">Credit</th>
                                <th class="w-12"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lines as $index => $line)
                                <tr wire:key="line-{{ $index }}">
                                    <td class="p-2">
                                        <select wire:model="lines.{{ $index }}.account_id" class="select select-bordered select-sm w-full" @if($isReadOnly) disabled @endif>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                        @error("lines.$index.account_id") <span class="text-error text-xs">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="p-2">
                                        <input type="text" wire:model="lines.{{ $index }}.description" class="input input-bordered input-sm w-full" placeholder="Line description..." @if($isReadOnly) disabled @endif />
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" wire:model="lines.{{ $index }}.debit" class="input input-bordered input-sm w-full text-right" @if($isReadOnly) disabled @endif />
                                        @error("lines.$index.debit") <span class="text-error text-xs">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" wire:model="lines.{{ $index }}.credit" class="input input-bordered input-sm w-full text-right" @if($isReadOnly) disabled @endif />
                                        @error("lines.$index.credit") <span class="text-error text-xs">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="p-2 text-center">
                                        @if(!$isReadOnly && count($lines) > 1)
                                            <button wire:click="removeLine({{ $index }})" class="btn btn-ghost btn-xs text-error">
                                                <span class="icon-[tabler--trash] w-4 h-4"></span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-base-200 font-bold">
                            <tr>
                                <td colspan="2" class="text-right">Totals:</td>
                                <td class="text-right {{ $this->totalDebit != $this->totalCredit ? 'text-error' : 'text-success' }}">
                                    {{ number_format($this->totalDebit, 2) }}
                                </td>
                                <td class="text-right {{ $this->totalDebit != $this->totalCredit ? 'text-error' : 'text-success' }}">
                                    {{ number_format($this->totalCredit, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="p-4 border-t border-base-200 flex justify-between items-center bg-base-100 rounded-b-xl">
                    @if(!$isReadOnly)
                        <button wire:click="addLine" class="btn btn-sm btn-outline">
                            <span class="icon-[tabler--plus] w-4 h-4"></span>
                            Add Line
                        </button>
                    @else
                        <div></div> {{-- Spacer if button is hidden --}}
                    @endif

                    <div class="flex items-center gap-4">
                        @if(abs($this->totalDebit - $this->totalCredit) > 0.0001)
                            <span class="text-error text-sm font-medium flex items-center gap-1">
                                <span class="icon-[tabler--alert-circle] w-4 h-4"></span>
                                Out of Balance: {{ number_format(abs($this->totalDebit - $this->totalCredit), 2) }}
                            </span>
                        @else
                            <span class="text-success text-sm font-medium flex items-center gap-1">
                                <span class="icon-[tabler--check] w-4 h-4"></span>
                                Balanced
                            </span>
                        @endif
                        
                        @if(!$isReadOnly)
                            <button wire:click="save" class="btn btn-primary btn-sm" wire:loading.attr="disabled" wire:target="save">
                                <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
                                Save Entry
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
