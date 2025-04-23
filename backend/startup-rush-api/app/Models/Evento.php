<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = ['tipo', 'impacto', 'batalha_id'];

    public function startup()
{
    return $this->belongsTo(Startup::class);
}

}