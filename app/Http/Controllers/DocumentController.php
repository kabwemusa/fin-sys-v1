<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function preview(Document $document): BinaryFileResponse|StreamedResponse
    {
        $this->authorizeAccess($document);

        if (! $document->canPreviewInline()) {
            return $this->download($document);
        }

        $disk = Storage::disk('local');
        abort_unless($disk->exists($document->file_path), 404);

        return response()->file($disk->path($document->file_path), [
            'Content-Type' => $disk->mimeType($document->file_path) ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.addslashes($document->original_filename).'"',
        ]);
    }

    public function download(Document $document): StreamedResponse
    {
        $this->authorizeAccess($document);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($document->file_path), 404);

        return $disk->download($document->file_path, $document->original_filename);
    }

    private function authorizeAccess(Document $document): void
    {
        $user = auth()->user();

        abort_unless($user, 403);

        if ($user->isAdmin()) {
            return;
        }

        $application = $document->loanApplication()->with('customer')->first();

        abort_unless(
            $user->isCustomer()
                && $user->customer
                && $application
                && $application->customer_id === $user->customer->id,
            403
        );
    }
}
