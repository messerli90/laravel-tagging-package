<?php

use Illuminate\Database\Eloquent\Model;
use Messerli90\Taggy\TaggableTrait;

class PostStub extends Model
{
    use TaggableTrait;

    protected $connection = 'testbench';

    public $table = 'posts';
}
