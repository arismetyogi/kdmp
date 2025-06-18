<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Models\Claim;
use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

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
//        $this->upload_id = $this->id;
        $this->claimUpload = ClaimUpload::with('customer')->find($this->id);
        $this->claim = Claim::where('upload_id', $this->id)->first();
//        $this->unitbisnis_code = $this->claimUpload->unitbisnis_code;
//        $this->customer_id = $this->claimUpload->customer?->id;
//        $this->period = $this->claimUpload->period;
    }
    public function render(): View
    {
        $claimDetails = ClaimDetail::where('upload_id', '=', $this->id)->get();
        return view('livewire.claim.document-upload.upload', compact(['claimDetails']));
    }
}
