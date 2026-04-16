<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExpenseAttachmentService
{
    public function upload(
        Expense $expense,
        UploadedFile $file,
        ?int $uploadedBy = null,
        bool $isPrimary = false
    ): ExpenseAttachment {
        $path = $file->store("expenses/{$expense->id}", 'public');

        if ($isPrimary) {
            $expense->attachments()->update(['is_primary' => false]);
        }

        return ExpenseAttachment::create([
            'expense_id' => $expense->id,
            'uploaded_by' => $uploadedBy,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize() ?: 0,
            'is_primary' => $isPrimary,
            'is_supporting_document' => true,
        ]);
    }

    public function delete(ExpenseAttachment $attachment): void
    {
        if (Storage::disk($attachment->disk)->exists($attachment->path)) {
            Storage::disk($attachment->disk)->delete($attachment->path);
        }

        $attachment->delete();
    }
}