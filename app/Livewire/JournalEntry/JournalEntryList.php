<?php

namespace App\Livewire\JournalEntry;

use App\Models\JournalEntry;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Journal Entries')]
class JournalEntryList extends Component
{
    public $deleteId = null;
    public $postId = null;

    protected $listeners = [
        'trigger-delete-entry' => 'confirmDelete',
        'trigger-post-entry' => 'confirmPost',
        'refresh-journal-entries' => '$refresh',
        'journal-entry-saved' => 'handleSaved',
        'edit-entry' => 'edit', 
        'view-entry' => 'view',
    ];

    public function confirmPost($id)
    {
        $this->postId = $id;
        $this->dispatch('open-post-modal');
    }

    public function post()
    {
        if (!$this->postId) return;

        $entry = JournalEntry::find($this->postId);

        if ($entry && $entry->status === 'draft') {
            try {
                app(\App\Services\JournalEntryService::class)->postEntry($entry);
                $this->dispatch('journal-entry-posted'); 
                $this->postId = null;
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

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function delete()
    {
        if (!$this->deleteId) return;

        $entry = JournalEntry::find($this->deleteId);
        
        if ($entry && $entry->status === 'draft') {
            app(\App\Services\JournalEntryService::class)->deleteEntry($entry);
            $this->dispatch('journal-entry-deleted'); 
            $this->deleteId = null;
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
