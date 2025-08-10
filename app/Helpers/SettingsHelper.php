<?php

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('blog_title')) {
    /**
     * Get blog title from settings
     *
     * @return string
     */
    function blog_title()
    {
        return setting('site_title', config('app.name', 'My Blog'));
    }
}

if (!function_exists('blog_description')) {
    /**
     * Get blog description from settings
     *
     * @return string
     */
    function blog_description()
    {
        return setting('site_description', 'A beautiful blog built with Laravel');
    }
}

if (!function_exists('blog_keywords')) {
    /**
     * Get blog keywords from settings
     *
     * @return string
     */
    function blog_keywords()
    {
        return setting('site_keywords', 'blog, laravel, cms');
    }
}
