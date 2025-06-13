<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function claimDetails(): HasMany
    {
        return $this->hasMany(ClaimDetail::class, 'upload_id', 'upload_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function claimUpload(): BelongsTo
    {
        return $this->belongsTo(ClaimUpload::class, 'upload_id', 'id');
    }

    public function unitBisnis(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class, 'unitbisnis_code', 'unitbisnis_code');
    }
}
