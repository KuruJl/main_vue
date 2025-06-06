<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // <-- Убедитесь, что эта строка есть

class Controller extends BaseController // <-- Убедитесь, что ваш Controller расширяет BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}