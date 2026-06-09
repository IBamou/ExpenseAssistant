<?php

namespace App\Http\Controllers;

use Laravel\Telescope\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
}
