<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Helpers\WithToast;
use App\Models\Claim;
use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\Mechanisms\HandleComponents\HandleComponents;
use Livewire\WithFileUploads;

class UploadForm extends Component
{
    use WithToast, WithFileUploads;

    public $id;
    public ClaimDetail $claimDetail;
    #[Session]
    public ClaimUpload $claimUpload;

    public $upload_invoice_file, $receipt_file, $tax_invoice_file, $po_customer_file, $receipt_order_file;
    // claim details
    public $upload_id, $invoice_number, $invoice_value, $delivery_date, $invoice_date, $customer_tracking_number, $updated_by;
    // claim - recap
    public $customer_id, $value, $period, $unitbisnis_code, $user_id;

    // access parent component
    private function getParentComponentInstance()
    {
        return app(HandleComponents::class)::$componentStack[0];
    }

    public function mount(): void
    {
        $parent = $this->getParentComponentInstance();
        $this->id = $parent->id;
        $this->upload_id = $parent->id;
        $this->claimUpload = ClaimUpload::find($this->id);
        $this->updated_by = auth()->user()->id;
        $this->user_id = $this->updated_by;
        $this->unitbisnis_code = $this->claimUpload->unitbisnis_code;
        $this->customer_id = $this->claimUpload->customer?->id;
        $this->period = $this->claimUpload->period;
    }

    public function setClaimDetail(?ClaimDetail $claimDetail = null): void
    {
        $this->claimDetail = $claimDetail;
        $this->invoice_number = $claimDetail->invoice_number;
        $this->invoice_value = $claimDetail->invoice_value;
        $this->delivery_date = $claimDetail->delivery_date;
    }

    public function edit(?ClaimDetail $claimDetail = null): void
    {
        $this->setClaimDetail($claimDetail);
    }

    public function rules(): array
    {
        return [
            'upload_id' => 'required',
            'invoice_number' => 'required|string',
            'invoice_date' => 'required|date',
            'upload_invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tax_invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'delivery_date' => 'nullable|date',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'po_customer_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'receipt_order_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'customer_tracking_number' => 'nullable|string',
        ];
    }

    public function save(): \Illuminate\Http\RedirectResponse
    {
        $invoiceValue = str_replace([',', '.'], '', $this->invoice_value);
        $detil = $this->validate();

        $rekap = $this->validate([
            'customer_id' => 'required|integer',
            'period' => 'required',
            'unitbisnis_code' => 'required',
            'invoice_value' => 'required',
        ]);

        $uploadId = $this->upload_id;
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
        $claim = ClaimDetail::create($detil);

        foreach (['upload_invoice_file', 'receipt_file', 'tax_invoice_file', 'receipt_order_file', 'po_customer_file'] as $fileKey) {
            if (!isset($this->{$fileKey}) || !$this->{$fileKey}) {
                continue; // skip if the file is not uploaded
            }
            $fileName = $this->unitbisnis_code . '_' . $this->invoice_number . '_' . $fileKey . '_' . now()->timestamp . '.' . $this->{$fileKey}->getClientOriginalExtension();
            $claim->addMedia($this->{$fileKey})
                ->usingName($this->customer_id . now()->timestamp)
                ->usingFileName($fileName)
                ->toMediaCollection($fileKey);
        }

        $this->resetForm();

        $this->toast('Detil klaim berhasil ditambahkan', 'success');
        $this->dispatch('refresh-details');
        return back();
    }

    public function render(): View
    {
        return view('livewire.claim.document-upload.upload-form');
    }

    public function resetForm(): void
    {
        $this->resetExcept('claimUpload');
    }
}
