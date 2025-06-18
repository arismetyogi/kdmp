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
    public ClaimDetail $claimDetail;

    public $upload_invoice_file, $receipt_file, $tax_invoice_file, $po_customer_file, $receipt_order_file;
    // claim details
    public $upload_id, $invoice_number, $invoice_value, $delivery_date, $invoice_date, $customer_tracking_number, $updated_by;
    // claim - recap
    public $customer_id, $value, $period, $unitbisnis_code, $user_id;

    public function mount($id): void
    {
        $this->id = Crypt::decryptString($id);
        $this->upload_id = $this->id;
        $this->claimUpload = ClaimUpload::with('customer')->find($this->id);
        $this->claim = Claim::where('upload_id', $this->id)->first();
        $this->updated_by = auth()->user()->id;
        $this->user_id = $this->updated_by;
        $this->unitbisnis_code = $this->claimUpload->unitbisnis_code;
        $this->customer_id = $this->claimUpload->customer?->id;
        $this->period = $this->claimUpload->period;
    }

    public function rules(): array
    {
        return [
            'upload_id' => 'required',
            'invoice_number' => 'required|string',
            'delivery_date' => 'required|date',
            'upload_invoice_file' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'receipt_file' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tax_invoice_file' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'invoice_date' => 'required|date',
            'po_customer_file' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'receipt_order_file' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'customer_tracking_number' => 'required|string',
            'updated_by' => 'required',
        ];
    }

    public function save(Request $request)
    {
        $invoiceValue = str_replace([',', '.'], '', $this->invoice_value);
        $detil = $this->validate();

        $rekap = $this->validate([
            'customer_id' => 'required|integer',
            'period' => 'required',
            'unitbisnis_code' => 'required',
            'invoice_value' => 'required',
        ]);

        foreach (['upload_invoice_file', 'receipt_file', 'tax_invoice_file', 'receipt_order_file', 'po_customer_file'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = $this->unitbisnis_code . '-' . $this->customer_id . '-' . $file->getClientOriginalName();
                $detil[$fileKey] = $file->storePubliclyAs(
                    'claims/docs/' . date('Y/m'),
                    $fileName
                );
            }
        }

        $uploadId = $this->claimUpload->id;
        $detil['upload_id'] = $uploadId;
        $rekap['upload_id'] = $uploadId;

        $detil['updated_by'] = $this->updated_by;
        $rekap['user_id'] = $this->updated_by;

        $detil['invoice_value'] = $invoiceValue;
        $rekap['invoice_value'] = $invoiceValue;
        //! check
        $rekap['value'] = $this->claimUpload->total;

        $claimExist = Claim::where('upload_id', $uploadId)->first();
        if ($claimExist) {
            $claimExist->update(['invoice_value' => $claimExist->invoice_value + $detil['invoice_value']]);
        } else {
            Claim::create($rekap);
        }

        ClaimDetail::create($detil);
        $this->resetForm();
        return back()->with('success', 'Upload Berhasil !!');
    }

    public function render(): View
    {
        $claimDetails = ClaimDetail::where('upload_id', '=', $this->id)->get();
        return view('livewire.claim.document-upload.upload', ['claimDetails' => $claimDetails]);
    }

    public function resetForm(): void
    {
    }
}
