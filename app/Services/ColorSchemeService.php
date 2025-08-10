<?php

namespace App\Services;

class ColorSchemeService
{
    /**
     * Get predefined color schemes
     */
    public static function getColorSchemes(): array
    {
        return [
            'soft_blue' => [
                'name' => 'Soft Blue',
                'primary_color' => '#6366f1',
                'secondary_color' => '#8b5cf6',
                'accent_color' => '#06b6d4',
                'background_color' => '#f8fafc',
                'text_color' => '#1e293b',
                'description' => 'Calm and professional blue tones'
            ],
            'soft_green' => [
                'name' => 'Soft Green',
                'primary_color' => '#10b981',
                'secondary_color' => '#34d399',
                'accent_color' => '#6ee7b7',
                'background_color' => '#f0fdf4',
                'text_color' => '#064e3b',
                'description' => 'Natural and refreshing green palette'
            ],
            'soft_purple' => [
                'name' => 'Soft Purple',
                'primary_color' => '#8b5cf6',
                'secondary_color' => '#a78bfa',
                'accent_color' => '#c4b5fd',
                'background_color' => '#faf5ff',
                'text_color' => '#581c87',
                'description' => 'Elegant and creative purple shades'
            ],
            'soft_pink' => [
                'name' => 'Soft Pink',
                'primary_color' => '#ec4899',
                'secondary_color' => '#f472b6',
                'accent_color' => '#f9a8d4',
                'background_color' => '#fdf2f8',
                'text_color' => '#831843',
                'description' => 'Warm and friendly pink tones'
            ],
            'soft_orange' => [
                'name' => 'Soft Orange',
                'primary_color' => '#f97316',
                'secondary_color' => '#fb923c',
                'accent_color' => '#fdba74',
                'background_color' => '#fff7ed',
                'text_color' => '#9a3412',
                'description' => 'Energetic and vibrant orange palette'
            ],
            'soft_gray' => [
                'name' => 'Soft Gray',
                'primary_color' => '#6b7280',
                'secondary_color' => '#9ca3af',
                'accent_color' => '#d1d5db',
                'background_color' => '#f9fafb',
                'text_color' => '#111827',
                'description' => 'Clean and minimalist gray scheme'
            ]
        ];
    }

    /**
     * Apply color scheme to settings
     */
    public static function applyColorScheme(string $scheme): bool
    {
        $schemes = self::getColorSchemes();
        
        if (!isset($schemes[$scheme])) {
            return false;
        }

        $colors = $schemes[$scheme];

        // Update color settings
        foreach ($colors as $key => $value) {
            if ($key !== 'name' && $key !== 'description') {
                \App\Models\Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'type' => 'color', 'group' => 'appearance']
                );
            }
        }

        // Update the color scheme setting
        \App\Models\Setting::updateOrCreate(
            ['key' => 'color_scheme'],
            ['value' => $scheme, 'type' => 'select', 'group' => 'appearance']
        );

        return true;
    }

    /**
     * Generate CSS variables for current color scheme
     */
    public static function generateCssVariables(): string
    {
        $css = ":root {\n";
        $css .= "    --primary-color: " . setting('primary_color', '#6366f1') . ";\n";
        $css .= "    --secondary-color: " . setting('secondary_color', '#8b5cf6') . ";\n";
        $css .= "    --accent-color: " . setting('accent_color', '#06b6d4') . ";\n";
        $css .= "    --background-color: " . setting('background_color', '#f8fafc') . ";\n";
        $css .= "    --text-color: " . setting('text_color', '#1e293b') . ";\n";
        $css .= "}\n";

        return $css;
    }
}
