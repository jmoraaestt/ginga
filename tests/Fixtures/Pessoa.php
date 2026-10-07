<?php

namespace Ginga\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $table = 'pessoas';

    protected $guarded = [];

    public $timestamps = false;
}
