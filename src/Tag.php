<?php

namespace Messerli90\Taggy;

use Illuminate\Database\Eloquent\Model;
use Messerli90\Taggy\Scopes\TagUsedScopesTrait;

class Tag extends Model
{
    use TagUsedScopesTrait;
}
