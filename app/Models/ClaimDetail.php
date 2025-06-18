<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ClaimDetail extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $guarded = ['id'];

    public function claimUpload(): BelongsTo
    {
        return $this->belongsTo(ClaimUpload::class, 'upload_id', 'id');
    }
}
