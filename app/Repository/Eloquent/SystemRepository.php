<?php

namespace App\Repository\Eloquent;

use App\Models\System;
use App\Repository\SystemRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SystemRepository extends Repository implements SystemRepositoryInterface
{
    public function __construct(System $model)
    {
        parent::__construct($model);
    }
}
