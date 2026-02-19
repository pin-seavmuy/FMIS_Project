<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JournalEntryService
{
    public function createEntry(array $data)
    {
        $this->validateBalance($data['lines']);

        return DB::transaction(function () use ($data) {
            $entry = JournalEntry::create([
                'date' => $data['date'],
                'reference' => $this->generateReference(),
                'description' => $data['description'] ?? null,
                'currency_code' => $data['currency_code'] ?? 'USD',
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            foreach ($data['lines'] as $line) {
                $entry->lines()->create([
                    'account_id' => $line['account_id'],
                    'description' => $line['description'] ?? null,
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                ]);
            }

            return $entry;
        });
    }

    public function updateEntry(JournalEntry $entry, array $data)
    {
        if ($entry->status === 'posted') {
            throw ValidationException::withMessages(['status' => 'Cannot edit a posted journal entry.']);
        }

        $this->validateBalance($data['lines']);

        return DB::transaction(function () use ($entry, $data) {
            $entry->update([
                'date' => $data['date'],
                'description' => $data['description'] ?? null,
                'currency_code' => $data['currency_code'] ?? 'USD',
            ]);

            // Replace lines (simplest approach for now)
            $entry->lines()->delete();

            foreach ($data['lines'] as $line) {
                $entry->lines()->create([
                    'account_id' => $line['account_id'],
                    'description' => $line['description'] ?? null,
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                ]);
            }

            return $entry->fresh();
        });
    }

    public function deleteEntry(JournalEntry $entry)
    {
        if ($entry->status === 'posted') {
            throw ValidationException::withMessages(['status' => 'Cannot delete a posted journal entry.']);
        }

        return DB::transaction(function () use ($entry) {
            $entry->lines()->delete();
            return $entry->delete();
        });
    }

    public function postEntry(JournalEntry $entry)
    {
        if ($entry->status === 'posted') {
            return $entry;
        }

        // Re-validate balance just in case
        $this->validateBalance($entry->lines->toArray());

        $entry->update([
            'status' => 'posted',
            'posted_at' => now(),
        ]);

        return $entry;
    }

    private function validateBalance(array $lines)
    {
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $line) {
            $totalDebit += $line['debit'] ?? 0;
            $totalCredit += $line['credit'] ?? 0;
        }

        // Floating point comparison with epsilon
        if (abs($totalDebit - $totalCredit) > 0.0001) {
            throw ValidationException::withMessages(['balance' => "Journal Entry is not balanced. Debit: $totalDebit, Credit: $totalCredit"]);
        }
    }

    private function generateReference()
    {
        $prefix = 'JE-' . date('Ymd') . '-';
        $latest = JournalEntry::where('reference', 'like', $prefix . '%')
            ->orderBy('reference', 'desc')
            ->first();

        if (!$latest) {
            return $prefix . '0001';
        }

        $lastNumber = intval(substr($latest->reference, -4));
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
