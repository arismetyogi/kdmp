<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use Crypt;
use Illuminate\View\View;
use Livewire\Component;
use function Pest\Laravel\get;

class Upload extends Component
{
    public $id;
    public ClaimUpload $claimUpload;
    public $claimDetail;

    // claim details
    public $upload_id, $invoice_number, $invoice_value, $delivery_date, $upload_invoice_file, $receipt_file, $tax_invoice_file, $invoice_date, $po_customer_file, $receipt_order_file, $customer_tracking_number, $updated_by;

    // claim - recap
    public $customer_id, $value, $period, $unitbisnis_code;
    public function mount($id): void
    {
        $this->id = Crypt::decryptString($id);
        $this->claimUpload = ClaimUpload::find($this->id);
    }

    public function save()
    {
        //
    }

    public function render(): View
    {
        $this->claimDetail = ClaimDetail::where('upload_id', '=', '$this->id')->get();
        return view('livewire.claim.document-upload.upload', ['$this->claimDetail' => $this->claimDetail]);
    }

    public function resetForm()
    {
        $this->reset();
    }
}
