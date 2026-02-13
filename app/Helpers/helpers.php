<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasRole')) {
    function hasRole($roles)
    {
        if (!Auth::check()) {
            return false;
        }

        return in_array(
            Auth::user()->role->nama,
            (array) $roles
        );
    }
}
