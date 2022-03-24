<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

class Service
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
