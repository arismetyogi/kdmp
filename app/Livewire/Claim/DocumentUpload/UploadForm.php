<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Helpers\WithToast;
use App\Models\Claim;
use App\Models\ClaimDetail;
use App\Models\ClaimUpload;
use DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\Mechanisms\HandleComponents\HandleComponents;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UploadForm extends Component
{
    use WithFileUploads, WithToast;

    public $id;

    public ?ClaimDetail $claimDetail = null;

    #[Session]
    public ClaimUpload $claimUpload;

    public $upload_invoice_file;

    public $receipt_file;

    public $tax_invoice_file;

    public $po_customer_file;

    public $receipt_order_file;

    // claim details
    public $upload_id;

    public $invoice_number;

    public $invoice_value;

    public $delivery_date;

    public $invoice_date;

    public $customer_tracking_number;

    public $updated_by;

    // claim - recap
    public $customer_id;

    public $value;

    public $period;

    public $unitbisnis_code;

    public $user_id;

    // access parent component
    private function getParentComponentInstance()
    {
        return app(HandleComponents::class)::$componentStack[0];
    }

    public function mount(): void
    {
        //        dd($this->getparentcomponentinstance());
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
        $this->invoice_date = $claimDetail->invoice_date;
        $this->invoice_number = $claimDetail->invoice_number;
        $this->invoice_value = $claimDetail->invoice_value;
        $this->delivery_date = $claimDetail->delivery_date;
        $this->customer_tracking_number = $claimDetail->customer_tracking_number;

        $this->upload_invoice_file = $claimDetail->upload_invoice_file;
        $this->tax_invoice_file = $claimDetail->tax_invoice_file;
        $this->receipt_file = $claimDetail->receipt_file;
        $this->po_customer_file = $claimDetail->po_customer_file;
        $this->receipt_order_file = $claimDetail->receipt_order_file;
    }

    #[On('edit-detail')]
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
            'upload_invoice_file' => function () {
                return $this->upload_invoice_file instanceof \Illuminate\Http\UploadedFile
                    ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
                    : 'nullable';
            },
            'tax_invoice_file' => function () {
                return $this->upload_invoice_file instanceof \Illuminate\Http\UploadedFile
                    ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
                    : 'nullable';
            },
            'delivery_date' => 'nullable|date',
            'receipt_file' => function () {
                return $this->upload_invoice_file instanceof \Illuminate\Http\UploadedFile
                    ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
                    : 'nullable';
            },
            'po_customer_file' => function () {
                return $this->upload_invoice_file instanceof \Illuminate\Http\UploadedFile
                    ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
                    : 'nullable';
            },
            'receipt_order_file' => function () {
                return $this->upload_invoice_file instanceof \Illuminate\Http\UploadedFile
                    ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
                    : 'nullable';
            },
            'customer_tracking_number' => 'nullable|string',
        ];
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     * @throws \Throwable
     */
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
        // ! check
        $rekap['value'] = $this->claimUpload->total;

        DB::beginTransaction();
        try {

            if (!$this->claimDetail) {
                $claimDetail = ClaimDetail::create($detil);
                $this->toast('Detil klaim berhasil ditambahkan!', 'success');
            } else {
                $this->claimDetail->update($detil); //returns bool
                $claimDetail = $this->claimDetail->refresh(); // retrieve claimDetail model instance
                $this->toast('Detil klaim berhasil diperbaharui!', 'success');
            }

            foreach (['upload_invoice_file', 'receipt_file', 'tax_invoice_file', 'receipt_order_file', 'po_customer_file'] as $fileKey) {
                if (!isset($this->{$fileKey}) || !$this->{$fileKey}) {
                    continue; // skip if the file is not uploaded
                }
                if ($this->{$fileKey} instanceof \Illuminate\Http\UploadedFile) {
                    $fileName = $this->unitbisnis_code . '_' . $this->invoice_number . '_' . $fileKey . '_' . now()->timestamp . '.' . $this->{$fileKey}->getClientOriginalExtension();
                    $claimDetail->addMedia($this->{$fileKey})
                        ->usingName($this->customer_id . '-' . now()->timestamp)
                        ->usingFileName($fileName)
                        ->toMediaCollection($fileKey);
                }
            }

            $claimExist = Claim::where('upload_id', $uploadId)->first();
            if ($claimExist) {
                $claimExist->update(['invoice_value' => $claimExist->invoice_value + $detil['invoice_value']]);
            } else {
                Claim::create($rekap);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->toast('Terjadi kesalahan saat memproses data: ' . $e->getMessage(), 'danger');
            return back();
        }


        $this->resetForm();

        $this->dispatch('refresh-details');

        return back();
    }

    public function render(): View
    {
        return view('livewire.claim.document-upload.upload-form');
    }

    public function resetForm(): void
    {
        $this->resetExcept(['claimUpload', 'upload_id', 'unitbisnis_code', 'customer_id', 'period']);
    }
}
