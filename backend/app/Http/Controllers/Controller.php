<?php

namespace App\Http\Controllers;

use App\Traits\ControllerHelpers;
use App\Traits\NextLightHelpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use ControllerHelpers;
    use NextLightHelpers;
}
