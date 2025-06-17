<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimUpload extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class, 'unitbisnis_code', 'unitbisnis_code');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function claimDetails(): HasMany
    {
        return $this->hasMany(ClaimDetail::class, 'upload_id', 'id');
    }

    public function claim(): HasOne
    {
        return $this->hasOne(Claim::class, 'upload_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_name', 'customer_name');
    }
}
