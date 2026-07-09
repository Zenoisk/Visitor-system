<?php

namespace App\Services;

class QrCodeService
{
    public function publicRegistrationUrl(): string
    {
        return route('visitor-register');
    }

    public function generateSvg(string $url, int $size = 320): string
    {
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)->generate($url);
    }
}
