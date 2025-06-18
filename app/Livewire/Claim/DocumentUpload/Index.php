<?php

namespace App\Livewire\Claim\DocumentUpload;

use App\Models\BranchOffice;
use App\Models\ClaimUpload;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    #[Session]
    public $userUBCode;
    #[Session]
    public ?string $unitBisnisCode = null;
    public $periods;
    #[Session]
    public $period;

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
            'branchOffice' => $this->branchOffice
        ]);
    }
}
