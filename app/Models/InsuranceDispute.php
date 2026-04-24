<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceDispute extends Model
{
    protected $fillable = [
        'exchange_id', 'filed_by', 'description',
        'evidence_paths', 'status', 'admin_notes', 'resolved_by',
    ];

    protected $casts = [
        'evidence_paths' => 'array',
    ];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function filer()
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
