<?php

namespace App\Helpers;

class SiteHelper
{
    public static function phone(): string
    {
        return (string) config('site.phone', '');
    }

    public static function phoneDisplay(): string
    {
        return (string) (config('site.phone_display') ?: config('site.phone', ''));
    }

    public static function email(): string
    {
        return (string) config('site.email', '');
    }

    public static function whatsappDigits(): string
    {
        $raw = (string) (config('site.whatsapp') ?: config('site.phone', ''));

        return preg_replace('/\D+/', '', $raw) ?? '';
    }

    public static function telHref(): string
    {
        $phone = preg_replace('/[^\d+]/', '', self::phone());

        return filled($phone) ? 'tel:'.$phone : '#';
    }

    public static function mailtoHref(): string
    {
        return filled(self::email()) ? 'mailto:'.self::email() : '#';
    }

    public static function whatsappHref(?string $message = null): string
    {
        $digits = self::whatsappDigits();

        if ($digits === '') {
            return '#';
        }

        $url = 'https://wa.me/'.$digits;

        if ($message !== null && $message !== '') {
            $url .= '?text='.urlencode($message);
        }

        return $url;
    }
}
