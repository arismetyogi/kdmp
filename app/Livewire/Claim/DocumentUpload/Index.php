<?php

namespace App\Livewire\Claim\DocumentUpload;

use AllowDynamicProperties;
use App\Models\BranchOffice;
use App\Models\ClaimUpload;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

#[AllowDynamicProperties] class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $userUBCode;
    public ?string $unitBisnisCode = null;
    public $periods;
    public $period;

    public function mount(): void
    {
        $this->userUBCode = auth()->user()->unitbisnis_code;
        $this->periods = ClaimUpload::query()->distinct()->pluck('period');
    }

    public function getClaimUploadsQueryProperty()
    {
        $period = Carbon::parse($this->period ?? now());

        $startDate = $period->copy()->startOfMonth();
        $endDate = $period->copy()->endOfMonth();

        $query = ClaimUpload::with('branch')
            ->whereBetween('period', [$startDate, $endDate])
            ->latest();

        if ($this->userUBCode != 3000) {
            $query->where('unitbisnis_code', $this->userUBCode);
        } else {
            $query->where('unitbisnis_code', $this->unitBisnisCode);
        }
        return $query;
    }

    public function getClaimUploadsProperty()
    {
        return $this->claimUploadsQuery->paginate($this->perPage);
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
