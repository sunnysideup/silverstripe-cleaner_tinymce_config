<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Formats;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;

class TinyMCEConfigExtension extends Extension
{
    public static function parse_yaml_formats(string $identifier = 'cms'): array
    {
        $yamlFormats = TinyMCEConfig::config()->get('formats');

        if (! isset($yamlFormats[$identifier])) {
            user_error('no editor formats for ' . $identifier);
            return [];
        }

        $parsedFormats = [];

        foreach ($yamlFormats[$identifier] as $sectionTitle => $sectionFormats) {
            $formats = [];
            $sort = 100;

            foreach ($sectionFormats as $sTitle => $sFormat) {
                if (isset($sFormat['disabled']) && $sFormat['disabled']) {
                    continue;
                }

                $title = $sTitle;

                if (isset($sFormat['title'])) {
                    $title = $sFormat['title'];
                }

                if (! isset($sFormat['sort'])) {
                    $sFormat['sort'] = $sort;
                }

                $formats[] = ['title' => $title] + $sFormat;
            }

            usort($formats, function ($x, $y) {
                return $x['sort'] <=> $y['sort'];
            });

            $parsedFormats[] = [
                'title' => $sectionTitle,
                'items' => $formats,
            ];
        }

        return $parsedFormats;
    }
}
