<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\WaSetting;

class WhatsAppService
{
    /**
     * Send WhatsApp message using Fonnte API.
     *
     * @param string $target Phone number (e.g., '08123456789' or '628123456789')
     * @param string $message The message content
     * @return bool True if successful, false otherwise
     */
    public static function sendMessage($target, $message)
    {
        $setting = WaSetting::first();

        if (!$setting || !$setting->is_active || empty($setting->fonnte_token)) {
            Log::info("WhatsApp Service is disabled or token is missing.");
            return false;
        }

        if (empty($target)) {
            Log::error("WhatsApp Service: Target number is empty.");
            return false;
        }

        // Sanitasi nomor HP: buang spasi, strip, atau karakter non-angka
        $target = preg_replace('/[^0-9]/', '', $target);

        // Ubah awalan 0 menjadi 62 (Standar WhatsApp internasional)
        if (str_starts_with($target, '0')) {
            $target = '62' . substr($target, 1);
        }

        try {
            // Fonnte direkomendasikan menggunakan body Form (x-www-form-urlencoded)
            $response = Http::asForm()->withHeaders([
                'Authorization' => trim($setting->fonnte_token),
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] === true) {
                Log::info("WhatsApp message sent successfully to $target");
                return true;
            } else {
                Log::error("WhatsApp Service Fonnte Error: " . ($result['reason'] ?? 'Unknown error'), [
                    'response' => $result,
                    'target' => $target
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp Service Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate message from template by replacing variables.
     *
     * @param string $template The template string
     * @param array $data Variables to replace
     * @return string
     */
    public static function buildMessage($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }
}
