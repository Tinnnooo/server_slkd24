<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Traits\HasCaptcha;
use App\Traits\HasResponseHttp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    use HasResponseHttp, HasCaptcha;

    public function requestToken()
    {
        $uuid = Str::uuid()->toString();
        $explodedUuid = explode('-', $uuid);
        $captchaText = substr($explodedUuid[4], -4);

        Cache::put($explodedUuid[0], $captchaText);

        return $this->success(['token' => $explodedUuid[0]]);
    }

    public function generate(string $token)
    {
        $captcha = Cache::get($token);

        if (!$captcha) throw new NotFoundException();

        $image = imagecreatetruecolor(90, 40);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 50, 50, 50);
        $lineColor = imagecolorallocate($image, 150, 150, 150);

        imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);

        imagestring($image, 5, 28, 10, $captcha, $textColor);

        for ($i = 0; $i < 4; $i++) {
            imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $lineColor);
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();

        imagedestroy($image);
        return response($imageData)->header('Content-Type', 'image/png');
    }

    public function validate(Request $request)
    {
        if (!$this->verifyCaptcha($request)) {
            return $this->unprocess('CAPTCHA validation failed');
        };

        return $this->success(['message' => 'CAPTCHA validated successfully']);
    }
}
