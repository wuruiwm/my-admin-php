<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Models\Ssl;
use App\Http\Requests\SslDownloadRequest;

class SslController extends BaseController
{
    public function index(){
        return View::make('admin.ssl.index',Ssl::getSslData());
    }
    public function download(SslDownloadRequest $request){
        $type = $request->validated()['type'];
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$type.'.txt"');
        header('Content-Transfer-Encoding: binary');
        echo Ssl::getSslData()[$type];
    }
}
