<?php

namespace App\Livewire\Claim;

use App\Models\ClaimUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class BulkUpload extends Component
{
    use WithPagination;

    public function render():View
    {
        $claimUploads = ClaimUpload::with(['user','unitBisnis'])->select('batch_id','user_id','unitbisnis_code','period', DB::raw('COUNT(*) as total_uploads'))
            ->groupBy('batch_id','user_id','unitbisnis_code','period')->paginate(10);
        return view('livewire.claim.bulk-upload', ['claimUploads' => $claimUploads]);
    }
}
