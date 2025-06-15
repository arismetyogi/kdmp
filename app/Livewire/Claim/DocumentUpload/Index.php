<?php

namespace App\Livewire\Claim\DocumentUpload;

use AllowDynamicProperties;
use App\Models\BranchOffice;
use App\Models\ClaimUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

#[AllowDynamicProperties] class Index extends Component
{
    public $userUBCode;
    public $unitBisnisCode;
    public $periods;
    public $period;

    public function mount(): void
    {
        $this->userUBCode = auth()->user()->unitbisnis_code;
        $this->periods = ClaimUpload::query()->distinct()->pluck('period');
    }

    public function getClaimUploadsQuery()
    {
        $period = Carbon::parse($this->period);
        $bulan = $period->month;
        $tahun = $period->year;

        $uploads = ClaimUpload::query()
            ->where('unitbisnis_code', $this->unitBisnisCode)
//            ->when(DB::getDriverName() === 'sqlite', function ($query) use ($bulan, $tahun) {
//                $query->whereRaw("strftime('%m', period) = ?", [str_pad($bulan, 2, '0', STR_PAD_LEFT)])
//                    ->whereRaw("strftime('%Y', period) = ?", [$tahun]);
//            })
//            ->when(DB::getDriverName() !== 'sqlite', function ($query) use ($bulan, $tahun) {
//                $query->whereRaw("MONTH(period) = ?", [$bulan])
//                    ->whereRaw("YEAR(period) = ?", [$tahun]);
//            })
            ->get();
        $this->claimUploads = $uploads;
        return $this->claimUploads;
    }

    public function filter(): void
    {
        $this->getClaimUploadsQuery();
    }

    public function getClaimUploadsProperty()
    {
        return ClaimUpload::when($this->userUBCode !== 3000, function ($query) {
            $query->where('unitbisnis_code', $this->userUBCode);
        })
            ->whereMonth('period', now()->month)
            ->whereYear('period', now()->year)
            ->latest()
            ->get();
    }

    public function getBranchOfficeProperty()
    {
        return BranchOffice::when($this->userUBCode !== 3000, function ($query) {
            $query->where('unitbisnis_code', $this->userUBCode);
        })->get();
    }

    public function render(): View
    {
        return view('livewire.claim.document-upload.index', [
            'claimUploads' => $this->claimUploads,
            'branchOffice' => $this->branchOffice
        ]);
    }
}
