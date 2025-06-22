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
            ->when($this->search, fn ($query, $search) => $query
                ->whereAny(['customer_name'], 'like', "%{$search}%")
            )
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
}
