<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchOffice extends Model
{
    protected $guarded = ['id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unitbisnis_code', 'unitbisnis_code');
    }

    public function claimUploads(): HasMany
    {
        return $this->hasMany(ClaimUpload::class, 'unitbisnis_code', 'unitbisnis_code');
    }
}
