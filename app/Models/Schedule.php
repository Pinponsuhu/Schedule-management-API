<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'description', 'added_by', 'status', 'from', 'to'];

    protected $hidden = [
        'added_by'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
