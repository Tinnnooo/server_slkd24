<?php

namespace App\Models;

use App\Enums\BannerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $guard = false;

    protected $casts = [
        'status' => BannerStatus::class,
    ];
}
