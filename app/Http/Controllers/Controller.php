<?php

namespace App\Http\Controllers;

use App\Services\TenantService;

abstract class Controller
{
    public function __construct(protected readonly TenantService $tenantService) { }
}
