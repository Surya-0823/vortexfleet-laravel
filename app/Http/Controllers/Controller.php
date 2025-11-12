<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // This is the default Laravel 11 base controller.
    // We will use middleware for authentication instead of a custom checkAuth().
    // We will use response()->json() instead of a custom jsonResponse().
}