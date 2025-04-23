<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'slogan', 'ano_fundacao', 'pontos'];

    public function batalhas()
    {
        return $this->belongsToMany(Batalha::class);
    }

    public function pitches()
{
    return $this->hasMany(Evento::class)->where('tipo', 'pitch');
}

public function bugs()
{
    return $this->hasMany(Evento::class)->where('tipo', 'bugs');
}

public function tracoes()
{
    return $this->hasMany(Evento::class)->where('tipo', 'tracao');
}

public function investidores()
{
    return $this->hasMany(Evento::class)->where('tipo', 'investidor');
}

public function fake_news()
{
    return $this->hasMany(Evento::class)->where('tipo', 'fake_news');
}

}