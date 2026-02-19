<?php

namespace App\Livewire\JournalEntry;

use App\Models\JournalEntry;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Journal Entries')]
class JournalEntryList extends Component
{
    public $editingEntryId = null;

    protected $listeners = [
        'confirm-delete-entry' => 'delete',
        'confirm-post-entry' => 'post', 
        'refresh-journal-entries' => '$refresh',
        'journal-entry-saved' => 'handleSaved',
        'edit-entry' => 'edit', 
        'view-entry' => 'view',
        'create-entry' => 'create',
    ];

    public function post($id)
    {
        $entry = JournalEntry::find($id);

        if ($entry && $entry->status === 'draft') {
            try {
                app(\App\Services\JournalEntryService::class)->postEntry($entry);
                $this->dispatch('journal-entry-posted'); 
            } catch (\Exception $e) {
                $this->dispatch('error', $e->getMessage());
            }
        }
    }

    public function create()
    {
        $this->editingEntryId = null;
        $this->dispatch('start-create-entry'); 
    }

    public function edit($id)
    {
        $this->editingEntryId = $id;
        $this->dispatch('load-journal-entry', id: $id, readonly: false); 
    }

    public function view($id)
    {
        $this->editingEntryId = $id;
        $this->dispatch('load-journal-entry', id: $id, readonly: true); 
    }

    public function handleSaved()
    {
        $this->dispatch('close-journal-form-modal');
        $this->dispatch('journal-entry-saved-msg'); 
        $this->dispatch('refresh-journal-entries');
    }

    public function delete($id)
    {
        $entry = JournalEntry::find($id);
        
        if ($entry && $entry->status === 'draft') {
            app(\App\Services\JournalEntryService::class)->deleteEntry($entry);
            $this->dispatch('journal-entry-deleted'); 
        } else {
             $this->dispatch('error', 'Cannot delete posted entry.');
        }
    }

    public function render()
    {
        $entries = JournalEntry::with(['lines', 'creator'])
            ->latest()
            ->get()
            ->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'date' => $entry->date->format('Y-m-d'),
                    'reference' => $entry->reference,
                    'description' => $entry->description,
                    'status' => $entry->status,
                    'total_amount' => $entry->lines->sum('debit'),
                    'created_by' => $entry->creator->name ?? 'System',
                ];
            });

        return view('livewire.journal-entry.journal-entry-list', [
            'entries' => $entries,
        ]);
    }
}
