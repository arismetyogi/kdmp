<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Helpers\WithToast;
use App\Models\BranchOffice;
use App\Models\Claim;
use App\Models\ClaimUpload;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination, WithToast;

    public $perPage = 10;

    #[Session]
    public $userUBCode;

    #[Session]
    public ?string $unitBisnisCode = null;

    public $periods;

    #[Session]
    public $period;

    public $search;

    public function mount(): void
    {
        $this->userUBCode = auth()->user()->unitbisnis_code;
        $this->periods = ClaimUpload::select('period')->distinct()->pluck('period');
    }

    public function getClaimUploadsQueryProperty()
    {
        $period = Carbon::parse($this->period ?? now());

        $startDate = $period->copy()->startOfMonth();
        $endDate = $period->copy()->endOfMonth();

        $query = ClaimUpload::with('branch')
            ->whereBetween('period', [$startDate, $endDate])
            ->when($this->search, fn($query, $search) => $query
                ->whereAny(['customer_name'], 'like', "%{$search}%")
            )
            ->latest();

        if ($this->userUBCode != 3000) {
            $query->where('unitbisnis_code', $this->userUBCode);
        } else {
            $query->when($this->unitBisnisCode, fn($subquery, $filter) =>
            $subquery
                ->where('unitbisnis_code', $this->unitBisnisCode));
        }

        return $query;
    }

    public function getClaimUploadsProperty()
    {
        return $this->claimUploadsQuery->paginate($this->perPage);
    }

    public function getBranchOfficeProperty(): \Illuminate\Database\Eloquent\Collection|array|\LaravelIdea\Helper\App\Models\_IH_BranchOffice_C
    {
        if ($this->userUBCode == 3000) {
            return BranchOffice::all();
        } else {
            return BranchOffice::where('unitbisnis_code', $this->userUBCode)->get();
        }
    }

    public function render(): View
    {
        return view('livewire.claim.document-upload.index', [
            'claimUploads' => $this->claimUploads,
            'branchOffice' => $this->branchOffice,
        ]);
    }

    public function updatedPeriod(): void
    {
        $this->resetPage();
    }

    public function updatedUnitBisnisCode(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public ?Claim $claim = null;

    public function setReason(?Claim $claim = null): void
    {
        $this->claim = $claim;
        $this->dispatch('set-reason', $claim);
        Flux::modal('set-reason')->show();
    }

    public $reason;

    public $notes;

    public function updateReason(): void
    {
        $this->claim->update([
            'reason' => $this->reason,
            'notes' => $this->notes,
        ]);
        $this->toast('Alasan berhasil diperbaharui', 'success');
        Flux::modal('set-reason')->close();
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->validate([
            'period' => 'required',
        ]);
        $datas = $this->claimUploadsQuery
            ->withSum('claimDetails', 'invoice_value')
            ->get();
//        dd($datas);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Laporan Monitoring Alat Tagih');
        $sheet->setCellValue('A2', date('M Y', strtotime($this->period)));
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Unit Bisnis');
        $sheet->setCellValue('B3', 'Customer');
        $sheet->setCellValue('C3', 'Omset Kredit');
        $sheet->setCellValue('D3', 'Nilai Invoice');
        $sheet->setCellValue('E3', 'Nilai Belum Diinvoice');
        $sheet->setCellValue('F3', 'Persentase Pengiriman');
        $sheet->setCellValue('G3', 'Alasan Selisih');
        $sheet->setCellValue('H3', 'Keterangan');
        $rows = 4;
        foreach ($datas as $data) {
            $sheet->setCellValue('A' . $rows, $data->branch?->name);
            $sheet->setCellValue('B' . $rows, $data->customer_name);
            $sheet->setCellValue('C' . $rows, $data->total);
            $sheet->setCellValue('D' . $rows, $data->claim_details_sum_invoice_value);
            $sheet->setCellValue('E' . $rows, $data->claim_details_sum_invoice_value - $data->total);
            $sheet->setCellValue(
                'F' . $rows,
                $data->total != 0 ? ($data->claim_details_sum_invoice_value / $data->total) * 100 : 0
            );
            $sheet->setCellValue(
                'F' . $rows,
                $data->total != 0 ? ($data->claim_details_sum_invoice_value / $data->total) * 100 : 0
            );
            $sheet->setCellValue('G' . $rows, $data->claim?->reason);
            $sheet->setCellValue('H' . $rows, $data->claim?->notes);

            $rows++;
        }

//        dd($spreadsheet);

        $fileName = "Report_KAM_" . Carbon::parse($this->period)->format('m Y') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
//        $writer->save('php://output');
        $this->toast('Exporting . . .!', 'success');
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);

    }
}
