<?php

namespace App\Livewire\Claim;

use App\Models\ClaimUpload;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ExcelReaderXlsx;

#[Layout('components.layouts.app')]
class BulkUpload extends Component
{
    use WithPagination, WithFileUploads;

    public $claimFile = null;
    public $perPage = 10;

    public $batchId = null;

    #[On('delete-batch')]
    public function deleteUpload($batchId)
    {
        $this->batchId = $batchId;

        Flux::modal('delete-upload')->show();
    }

    public function delete()
    {
        $checkData = ClaimUpload::where('batch_id', $this->batchId)
            ->withCount('claimDetails')
            ->get()
            ->sum('claim_details_count');

        Flux::modals()->close();
        if ($checkData == 0) {
            $data = ClaimUpload::where('batch_id', $this->batchId);

            $delete = $data->delete(); //hapus data
            if (!$data) {
                session()->flash("error", "delete failed");
            } else {
                session()->flash("success", "delete success");
            }
            return back();
        } else {
            session()->flash("error", "Data Upload GAGAL di Hapus, karena sudah ada data Klaim!!");
            return back();
        }
    }

    public function uploadClaim(Request $request): RedirectResponse
    {
        $this->validate([
            'claimFile' => 'file|mimes:xlsx',
        ]);

        $fileUpload = $this->claimFile;
        $fileExtension = $fileUpload->getClientOriginalExtension();

        if ($fileExtension != 'xlsx') {
            return back()->with('error','Extensi Salah !!');
        }

        $filePath = $fileUpload->store('claim-uploads/' . date('Y/m'));

        if (!$filePath) {
            return back()->with('error','Upload file gagal !!');
        }

        $file = Storage::path($filePath);
        $reader = new ExcelReaderXlsx;

        $worksheet = $reader->listWorksheetInfo($file);
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getSheet(0);

        $totalRows = $worksheet[0]['totalRows'];
        $totalColumns = $worksheet[0]['totalColumns'];

        $results = [];

        $header = ['customer_name','sheet_value','recipe_value','commercial_value','tax_value','total','unitbisnis_code','period'];
        $batch_id = 'BATCH-' . now()->format('YmdHis') . '-' . Str::uuid();

        for($row=2; $row<=$totalRows; $row++) {
            for($col=1; $col<=$totalColumns; $col++) {
                $value = $sheet->getCell([$col, $row])->getFormattedValue();
                if ($header[$col - 1] === 'period') {
                    // Ubah format tanggal dari DD/MM/YYYY menjadi YYYY-MM-DD
                    try {
                        $value = Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Jika format tidak valid, bisa atur default atau abaikan
                        $value = null;
                    }
                }
                $results[$row][$header[$col-1]]= $value;
            }
        }
        // return $results;
        foreach($results as $result){

            ClaimUpload::create([
                'customer_name' => $result['customer_name'],
                'sheet_value' => $result['sheet_value'],
                'recipe_value' => $result['recipe_value'],
                'commercial_value' => $result['commercial_value'],
                'tax_value' => $result['tax_value'],
                'total' => $result['total'],
                'unitbisnis_code' => $result['unitbisnis_code'],
                'period' => $result['period'],
                'user_id' => auth()->user()->id,
                'batch_id' => $batch_id
            ]);
        }
        $isValid['is_valid'] = '1';

        ClaimUpload::where('batch_id', $batch_id)->update($isValid);

        $this->claimFile = null;

        return back()->with('success', 'Data Berhasil Diupload !!');
    }
    public function render(): View
    {
        $claimUploads = ClaimUpload::with(['user', 'unitBisnis'])->select('batch_id', 'user_id', 'unitbisnis_code', 'period', DB::raw('COUNT(*) as total_uploads, SUM(total) as total_claims'))
            ->groupBy('batch_id', 'user_id', 'unitbisnis_code', 'period')
            ->orderBy('id', 'DESC')
            ->paginate($this->perPage);
        return view('livewire.claim.bulk-upload', ['claimUploads' => $claimUploads]);
    }

}
