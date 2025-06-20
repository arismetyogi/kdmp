<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Helpers\WithToast;
use App\Models\Claim;
use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use Crypt;
use Flux\Flux;
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
    use WithFileUploads, WithToast;

    #[Session]
    public $id;
    #[Session]
    public ?ClaimUpload $claimUpload = null;
    public ?ClaimDetail $claimDetail = null;

    public ?Claim $claim = null;

    public function mount($id): void
    {
        $this->id = Crypt::decryptString($id);
        $this->claimUpload = ClaimUpload::with('customer')->find($this->id);
        $this->claim = Claim::where('upload_id', $this->id)->first();
    }

    public function render(): View
    {
        $claimDetails = ClaimDetail::where('upload_id', $this->id)->get();
        return view('livewire.claim.document-upload.upload', compact(['claimDetails']));
    }

    #[On('delete-detail')]
    public function deleteDetail($id): void
    {
        $this->claimDetail = ClaimDetail::find($id);
//        dd($this->claimDetail->getAttributes());
        Flux::modal('delete-upload')->show();
    }

    public function delete()
    {
        $this->claim = Claim::where('upload_id', $this->claimDetail->upload_id)->first();
        $this->claim->update(['invoice_value' => $this->claim->invoice_value - $this->claimDetail->invoice_value]);

        $this->claimDetail->delete();
        Flux::modal('delete-upload')->close();
        $this->toast('Data klaim berhasil dihapus', 'success');
    }
}
