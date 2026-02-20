<?php

namespace App\Livewire;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Account Ledger')]
class AccountLedger extends Component
{
    public ChartOfAccount $account;
    public $startDate;
    public $endDate;

    public function mount($accountId)
    {
        $this->account = ChartOfAccount::findOrFail($accountId);
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $lines = JournalEntryLine::where('account_id', $this->account->id)
            ->whereHas('journalEntry', function ($query) {
                $query->where('status', 'posted')
                      ->whereBetween('date', [$this->startDate, $this->endDate]);
            })
            ->with('journalEntry')
            ->get()
            ->sortBy('journalEntry.date');

        // Calculate running balance? Let's do it in the view or here.
        // For simplicity, let's just pass the lines.
        // Or better, let's map them to include running balance.
        
        $runningBalance = 0;
        // Logic for opening balance would require a separate query for prior transactions.
        // For now, let's just show period transactions.

        $ledgerLines = $lines->map(function ($line) use (&$runningBalance) {
            // Adjust running balance based on account type (Debit vs Credit normal balance)
            // Asset/Expense: Dr increases, Cr decreases.
            // Liability/Equity/Revenue: Cr increases, Dr decreases.
            
            $isDebitNormal = in_array($this->account->type, ['asset', 'expense']);
            
            if ($isDebitNormal) {
                $change = $line->debit - $line->credit;
            } else {
                $change = $line->credit - $line->debit;
            }
            
            $runningBalance += $change;

            return [
                'id' => $line->id,
                'date' => $line->journalEntry->date->format('Y-m-d'),
                'reference' => $line->journalEntry->reference,
                'description' => $line->description ?: $line->journalEntry->description,
                'debit' => (float) $line->debit,
                'credit' => (float) $line->credit,
                'balance' => $runningBalance,
            ];
        });

        return view('livewire.account-ledger', [
            'ledgerLines' => $ledgerLines,
        ]);
    }
}
