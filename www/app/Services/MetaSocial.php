<?php

namespace App\Services;

class MetaSocial
{
    private $config = [];

    public function __construct()
    {
        $locale_slug = strtolower(str_replace('_', '-', app()->getLocale()));
        $route_name = request()->route()->getName();
        $locale_config = config(sprintf('metasocial.%s', $locale_slug));

        if (isset($locale_config[$route_name])) {
            $this->config = $locale_config[$route_name];
        } else {
            $this->config = $locale_config['default'];
        }

        $mime = image_mime_type($this->config['image']);
        if (!empty($mime)) {
            $this->config['mime'] = $mime;
        }
    }

    private function getTemplate()
    {
        return '
			<title>{:title}</title>
			<meta name="title" content="{:title}">
			<meta name="description" content="{:description}">
			<meta name="keywords" content="{:keywords}">
			<meta name="theme-color" content="{:theme_color}">

            <!-- IMAGE SIZE -->
            <meta property="og:image:width" content="{:image_width}">
            <meta property="og:image:height" content="{:image_height}">
            <meta property="og:image:type" content="{:mime}">

			<!-- FACEBOOK -->
			<meta property="og:title" content="{:title}">
			<meta property="og:image" content="{:image}">
			<meta property="og:type" content="{:type}">
			<meta property="og:description" content="{:description}">

			<!-- TWITTER -->
			<meta name="twitter:card" content="{:twitter_card}">
			<meta name="twitter:title" content="{:title}">
			<meta name="twitter:creator" content="{:twitter_creator}">
			<meta name="twitter:image:src" content="{:image}">
			<meta name="twitter:description" content="{:description}">
		';
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->config = array_merge($this->config, $key);
            return;
        }

        $this->config[$key] = $value;
    }

    public function build()
    {
        $result = $this->getTemplate();
        $configs = $this->config;

        if (!array_key_exists('type', $configs)) {
            $configs['type'] = 'website';
        }
        if (!array_key_exists('url', $configs)) {
            $configs['url'] = url()->current();
        }
        if (!array_key_exists('twitter_card', $configs)) {
            $configs['summary_large_image'] = '';
        }
        if (!array_key_exists('twitter_creator', $configs)) {
            $configs['twitter_creator'] = '';
        }

        if (array_key_exists('facebook_id', $configs)) {
            if (!empty($configs['facebook_id'])) {
                $result .= PHP_EOL;
                $result .= '			<!-- FACEBOOK APP -->' . PHP_EOL;
                $result .= sprintf('			<meta property="fb:app_id" content="%s">' . PHP_EOL, $configs['facebook_id']);
            }
        }

        foreach ($configs as $key => $value) {
            switch ($key) {
                case 'url':
                    if (empty($value)) {
                        $value = env('APP_URL', '');
                    }
                    break;
                case 'image':
                    if (!empty($value)) {
                        if (strpos($value, 'http') === false) {
                            $value = asset($value);
                        }
                    }
                    break;
                }
                
            $value_type = gettype($value);
            if ($value_type === 'string') {
                $result = str_replace('{:' . $key . '}', $value, $result);
            }
        }

        return $result;
    }

    public function print()
    {
        echo $this->build();
    }
}
