<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_title',
                'value' => 'My Personal Blog',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Title',
                'description' => 'The title of your website'
            ],
            [
                'key' => 'site_description',
                'value' => 'A beautiful personal blog sharing thoughts, ideas, and stories',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Site Description',
                'description' => 'A brief description of your website'
            ],
            [
                'key' => 'site_keywords',
                'value' => 'blog, personal, articles, stories, insights',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Keywords',
                'description' => 'SEO keywords for your website'
            ],
            [
                'key' => 'site_logo',
                'value' => '',
                'type' => 'file',
                'group' => 'general',
                'label' => 'Site Logo',
                'description' => 'Your website logo'
            ],
            [
                'key' => 'posts_per_page',
                'value' => '10',
                'type' => 'number',
                'group' => 'general',
                'label' => 'Posts Per Page',
                'description' => 'Number of posts to display per page'
            ],

            // Appearance Settings - Soft Color Templates
            [
                'key' => 'color_scheme',
                'value' => 'soft_blue',
                'type' => 'select',
                'group' => 'appearance',
                'label' => 'Color Scheme',
                'description' => 'Choose your website color scheme',
                'options' => json_encode([
                    'soft_blue' => 'Soft Blue',
                    'soft_green' => 'Soft Green',
                    'soft_purple' => 'Soft Purple',
                    'soft_pink' => 'Soft Pink',
                    'soft_orange' => 'Soft Orange',
                    'soft_gray' => 'Soft Gray',
                    'custom' => 'Custom Colors'
                ])
            ],
            [
                'key' => 'primary_color',
                'value' => '#6366f1',
                'type' => 'color',
                'group' => 'appearance',
                'label' => 'Primary Color',
                'description' => 'Primary color for your website'
            ],
            [
                'key' => 'secondary_color',
                'value' => '#8b5cf6',
                'type' => 'color',
                'group' => 'appearance',
                'label' => 'Secondary Color',
                'description' => 'Secondary color for your website'
            ],
            [
                'key' => 'accent_color',
                'value' => '#06b6d4',
                'type' => 'color',
                'group' => 'appearance',
                'label' => 'Accent Color',
                'description' => 'Accent color for highlights and buttons'
            ],
            [
                'key' => 'background_color',
                'value' => '#f8fafc',
                'type' => 'color',
                'group' => 'appearance',
                'label' => 'Background Color',
                'description' => 'Background color for your website'
            ],
            [
                'key' => 'text_color',
                'value' => '#1e293b',
                'type' => 'color',
                'group' => 'appearance',
                'label' => 'Text Color',
                'description' => 'Main text color'
            ],
            [
                'key' => 'font_family',
                'value' => 'inter',
                'type' => 'select',
                'group' => 'appearance',
                'label' => 'Font Family',
                'description' => 'Choose your website font',
                'options' => json_encode([
                    'inter' => 'Inter (Modern)',
                    'roboto' => 'Roboto (Clean)',
                    'open_sans' => 'Open Sans (Friendly)',
                    'lato' => 'Lato (Elegant)',
                    'poppins' => 'Poppins (Rounded)',
                    'nunito' => 'Nunito (Soft)'
                ])
            ],
            [
                'key' => 'layout_style',
                'value' => 'magazine',
                'type' => 'select',
                'group' => 'appearance',
                'label' => 'Layout Style',
                'description' => 'Choose your website layout style',
                'options' => json_encode([
                    'magazine' => 'Magazine Style',
                    'minimal' => 'Minimal Style',
                    'classic' => 'Classic Blog',
                    'modern' => 'Modern Grid'
                ])
            ],

            // SEO Settings
            [
                'key' => 'meta_author',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Meta Author',
                'description' => 'Default author for meta tags'
            ],
            [
                'key' => 'google_analytics',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Google Analytics',
                'description' => 'Google Analytics tracking ID'
            ],
            [
                'key' => 'google_search_console',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Google Search Console',
                'description' => 'Google Search Console verification code'
            ],
            [
                'key' => 'robots_txt',
                'value' => "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml'),
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Robots.txt',
                'description' => 'Content for robots.txt file'
            ],

            // System Settings
            [
                'key' => 'backup_frequency',
                'value' => 'weekly',
                'type' => 'select',
                'group' => 'system',
                'label' => 'Backup Frequency',
                'description' => 'How often to create automatic backups',
                'options' => json_encode([
                    'disabled' => 'Disabled',
                    'daily' => 'Daily',
                    'weekly' => 'Weekly',
                    'monthly' => 'Monthly'
                ])
            ],
            [
                'key' => 'backup_retention',
                'value' => '30',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Backup Retention',
                'description' => 'Number of days to keep backups'
            ],
            [
                'key' => 'cache_lifetime',
                'value' => '3600',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Cache Lifetime',
                'description' => 'Cache lifetime in seconds'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Maintenance Mode',
                'description' => 'Enable maintenance mode'
            ],
            [
                'key' => 'debug_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Debug Mode',
                'description' => 'Enable debug mode (development only)'
            ],
            [
                'key' => 'auto_updates',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Auto Updates',
                'description' => 'Enable automatic security updates'
            ],
            [
                'key' => 'max_upload_size',
                'value' => '10',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Max Upload Size',
                'description' => 'Maximum file upload size in MB'
            ],
            [
                'key' => 'email_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Email Notifications',
                'description' => 'Enable email notifications for system events'
            ],

            // Social Media Settings
            [
                'key' => 'facebook_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Facebook page URL'
            ],
            [
                'key' => 'twitter_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Twitter profile URL'
            ],
            [
                'key' => 'instagram_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Instagram profile URL'
            ],
            [
                'key' => 'linkedin_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'LinkedIn URL',
                'description' => 'LinkedIn profile URL'
            ],
            [
                'key' => 'youtube_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'YouTube channel URL'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
