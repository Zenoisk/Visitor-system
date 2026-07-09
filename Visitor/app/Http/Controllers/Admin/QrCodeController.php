<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use Illuminate\View\View;

class QrCodeController extends Controller
{
    public function __invoke(QrCodeService $qrCode): View
    {
        $url = $qrCode->publicRegistrationUrl();

        return view('admin.qr.index', [
            'registrationUrl' => $url,
            'qrSvg' => $qrCode->generateSvg($url),
        ]);
    }
}
