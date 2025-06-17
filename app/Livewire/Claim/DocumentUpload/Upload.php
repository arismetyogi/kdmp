<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Models\Claim;
use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use Crypt;
use Illuminate\Http\Request;
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
    public Claim $claim;
    public ClaimDetail $claimDetail;

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
        $this->customer_id = $this->claimUpload->customer->id;
    }

    public function rules(): array
    {
        return [
            'upload_id' => 'required',
            'invoice_number' => 'required|string',
            'delivery_date' => 'required|date',
            'upload_invoice_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'receipt_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tax_invoice_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'invoice_date' => 'required|date',
            'po_customer_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'receipt_order_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'customer_tracking_number' => 'required|string',
            'updated_by' => 'required|string',
        ];
    }

    public function save(Request $request)
    {
        $invoiceValue = str_replace([',', '.'], '', $request->invoice_value);
        $detil = $this->validate();

        $rekap = $this->validate([
            'customer_id' => 'required|integer',
            'period' => 'required|string',
            'unitbisnis_code' => 'required|string',
            'invoice_value' => 'required|numeric|min:0',
        ]);

        foreach (['upload_invoice_file', 'receipt_file', 'tax_invoice_file', 'receipt_order_file', 'po_customer_file'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = $this->unitbisnis_code . '-' . $this->customer_id . '-' . $file->getClientOriginalName();
                $detil[$fileKey] = $file->storeAs(
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

        $claimExist = Claim::where('upload_id', $uploadId)->first();
        if ($claimExist) {
            $this->claim->update(['invoice_value' => $this->claim->invoice_value + $detil['invoice_value']]);
        } else {
            Claim::create($rekap);
            ClaimDetail::create($detil);
        }

        return back()->with('success', 'Upload Berhasil !!');
    }

    public function render(): View
    {
        $claimDetails = ClaimDetail::where('upload_id', '=', $this->id)->get();
        return view('livewire.claim.document-upload.upload', ['claimDetails' => $claimDetails]);
    }

    public function resetForm(): void
    {
        $this->reset();
    }
}
