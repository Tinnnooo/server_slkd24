<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generateCaptcha()
    {
        $captchaText = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 6);

        Session::put('captcha', $captchaText);

        $image = imagecreatetruecolor(120, 40);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 50, 50, 50);
        $lineColor = imagecolorallocate($image, 150, 150, 150);

        imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);

        imagestring($image, 5, 35, 10, $captchaText, $textColor);

        for ($i = 0; $i < 5; $i++) {
            imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $lineColor);
        }

        header('Content-Type: image/png');
        imagepng($image);


        imagedestroy($image);
    }

    public function validateCaptcha(Request $request)
    {
        $request->validate([
            'captcha' => 'required|string',
        ]);

        if ($request->captcha === Session::get('captcha')) {
            return response()->json(['message' => 'CAPTCHA validated successfully!']);
        } else {
            return response()->json(['message' => 'CAPTCHA validation failed.'], 422);
        }
    }
}
