<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Models\BranchOffice;
use App\Models\ClaimUpload;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
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
