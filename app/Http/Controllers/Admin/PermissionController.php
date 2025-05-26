<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Show the detail of a permission
     */
    public function show(Permission $permission)
    {
        return view('admin.permissions.show', compact('permission'));
    }
}
