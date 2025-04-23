<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batalha extends Model
{
    use HasFactory;

    protected $fillable = ['startup_1_id', 'startup_2_id', 'pontos_startup_1', 'pontos_startup_2', 'vencedor_id'];

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }

    public function startup1()
    {
        return $this->belongsTo(Startup::class, 'startup_1_id');
    }

    public function startup2()
    {
        return $this->belongsTo(Startup::class, 'startup_2_id');
    }
}
