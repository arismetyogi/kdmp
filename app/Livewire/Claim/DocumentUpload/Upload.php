<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Models\Claim;
use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use Crypt;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

#[On('refresh-details')]
#[Layout('components.layouts.app')]
class Upload extends Component
{
    use WithFileUploads;

    #[Session]
    public $id;
    #[Session]
    public ClaimUpload $claimUpload;

    public ?Claim $claim = null;

    public function mount($id): void
    {
        $this->id = Crypt::decryptString($id);
        $this->claimUpload = ClaimUpload::with('customer')->find($this->id);
        $this->claim = Claim::where('upload_id', $this->id)->first();
    }
    public function render(): View
    {
        $claimDetails = ClaimDetail::where('upload_id', '=', $this->id)->get();
        return view('livewire.claim.document-upload.upload', compact(['claimDetails']));
    }
}
