<?php

namespace App\Livewire;

use App\Services\ChartOfAccountService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Coa extends Component
{
    public $isOpen = false;
    public $isEditMode = false;
    public $accountId = null;
    public $deleteId = null;

    // Form Fields
    public $code = '';
    public $name = '';
    public $type = 'asset';
    public $classification = '';
    public $is_active = true;
    public $description = '';

    public $classifications = [
        'Current Assets',
        'Non-Current Assets',
        'Current Liabilities',
        'Non-Current Liabilities',
        'Equity',
        'Revenue',
        'Cost of Sales',
        'Operating Expenses',
        'Other Income',
        'Other Expenses',
    ];

    protected $rules = [
        'code' => 'required|unique:chart_of_accounts,code',
        'name' => 'required',
        'type' => 'required',
        'classification' => 'nullable',
    ];

    protected $listeners = [
        'coa-updated' => '$refresh',
        'edit-account' => 'edit',
        'trigger-delete-coa' => 'confirmDelete',
    ];

    public function render(ChartOfAccountService $service)
    {
        $accounts = $service->all();

        $columnDefs = [
            ['headerName' => 'Code', 'field' => 'code', 'width' => 100],
            ['headerName' => 'Name', 'field' => 'name', 'flex' => 1], // Removed coaName renderer
            ['headerName' => 'Type', 'field' => 'type', 'width' => 120, 'cellStyle' => ['textTransform' => 'capitalize']],
            ['headerName' => 'Classification', 'field' => 'classification', 'width' => 180],
            ['headerName' => 'Status', 'field' => 'is_active', 'width' => 100, 'valueFormatter' => 'FmisFormatters.activeStatus'],
            ['headerName' => 'Actions', 'field' => 'id', 'width' => 100, 'cellRenderer' => 'FmisRenderers.coaActions', 'sortable' => false, 'filter' => false],
        ];

        return view('livewire.coa', compact('accounts', 'columnDefs'));
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['code', 'name', 'type', 'classification', 'is_active', 'description', 'accountId', 'isEditMode']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store(ChartOfAccountService $service)
    {
        $this->validate();

        $service->create([
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'classification' => $this->classification ?: null,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ]);

        $this->closeModal();
        $this->refreshGrid($service);
        $this->dispatch('coa-created');
    }

    public function edit($id, ChartOfAccountService $service)
    {
        $account = $service->find($id);
        $this->accountId = $id;
        $this->code = $account->code;
        $this->name = $account->name;
        $this->type = $account->type;
        $this->classification = $account->classification;
        $this->is_active = $account->is_active;
        $this->description = $account->description;
        
        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function update(ChartOfAccountService $service)
    {
        $this->validate([
            'code' => 'required|unique:chart_of_accounts,code,' . $this->accountId,
            'name' => 'required',
            'type' => 'required',
            'classification' => 'nullable',
        ]);

        $service->update($this->accountId, [
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'classification' => $this->classification ?: null,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ]);

        $this->closeModal();
        $this->refreshGrid($service);
        $this->dispatch('coa-updated-msg');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('open-delete-coa-modal');
    }

    public function delete(ChartOfAccountService $service)
    {
        if ($this->deleteId) {
            $service->delete($this->deleteId);
            $this->refreshGrid($service);
            $this->dispatch('coa-deleted');
            $this->deleteId = null;
        }
    }

    private function refreshGrid(ChartOfAccountService $service)
    {
        $this->dispatch('coa-updated', ['data' => $service->all()]);
    }
}
