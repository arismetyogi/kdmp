<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use Crypt;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
#[Layout('components.layouts.app')]
class Upload extends Component
{
    use WithFileUploads;
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
        $this->updated_by = auth()->user()->id;
        $this->unitbisnis_code = $this->claimUpload->unitbisnis_code;
        $this->customer_id = $this->claimUpload->customer_name;
    }

    public function rules()
    {
        return [
            'upload_id' => 'required',
            'invoice_number' => 'required|string',
            'invoice_value' => 'required|numeric|min:0',
            'delivery_date' => 'required|date',
            'upload_invoice_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'receipt_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tax_invoice_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'invoice_date' => 'required|date',
            'po_customer_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'receipt_order_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'customer_tracking_number' => 'required|string',
            'updated_by' => 'required|string',
            'customer_id' => 'required|integer',
            'period' => 'required|string',
            'unitbisnis_code' => 'required|string',
        ];
    }
    public function save()
    {
        //
    }

    public function render(): View
    {
        $this->claimDetail = ClaimDetail::where('upload_id', '=', '$this->id')->get();
        return view('livewire.claim.document-upload.upload', ['claimDetails' => $this->claimDetail]);
    }

    public function resetForm()
    {
        $this->reset();
    }
}
