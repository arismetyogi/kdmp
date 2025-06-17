<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function claimUpload(): BelongsTo
    {
        return $this->belongsTo(ClaimUpload::class, 'upload_id', 'id');
    }
}
