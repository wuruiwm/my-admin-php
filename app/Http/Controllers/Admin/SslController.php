<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Models\Ssl;


class SslController extends BaseController
{
    public function index(){
        return View::make('admin.ssl.index',Ssl::getSslData());
    }
}
