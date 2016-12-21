<?php

use Illuminate\Database\Eloquent\Model;
use \Messerli90\Taggy\Scopes\TagUsedScopesTrait;

class TagStub extends Model
{
    use TagUsedScopesTrait;

    protected $connection = 'testbench';

    public $table = 'tags';
}
