<?php
namespace Sitefrog\View;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\CSSMinFilter;
use Assetic\Filter\ScssphpFilter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Sitefrog\Facades\Context;

class AssetManager
{
    private Collection $css;
    private Collection $js;

    public function __construct()
    {
        $this->css = collect([]);
        $this->js = collect([]);
    }

    private function getListWithCompiled($list)
    {
        $assets = $list->where('external', true);
        $local = $list->where('external', false);

        if (count($local) > 0) {
            $compiled = $this->compile($local);
            $assets[] = $compiled;
        }

        return $assets;
    }
    public function getCss()
    {
        return $this->getListWithCompiled($this->css);
    }

    public function getJs()
    {
        return $this->getListWithCompiled($this->js);
    }


    private function getCacheKey(Collection $assets): string
    {
        return md5($assets->map(function($asset) {
            return $asset['source'].':'.filemtime($asset['source']);
        })->join(','));
    }

    private function compile(Collection $assets)
    {
        $cacheKey = $this->getCacheKey($assets);
        if (!Cache::has($cacheKey)) {
            $assetCollection = new AssetCollection(
                $assets->map(function ($asset) {
                    return new FileAsset($asset['source']);
                })->toArray(),
                [
                    new ScssphpFilter(),
                    new CSSMinFilter(),
                ]
            );
            Cache::put('sitefrog::assets-'.$cacheKey, $assetCollection->dump(), now()->addMinutes(60));
        }
        return [
            'source' => route('sitefrog::assets', $cacheKey),
            'external' => false,
            'inline' => false
        ];
    }

    private function checkFileExistence(string $filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception('File not found: '.$filename);
        }
    }

    private function isExternal($source)
    {
        return Str::startsWith($source, ['http:', 'https:']);
    }


    public function addCss(
        string $source,
        bool $inline = false,
        array $params = []
    ): void
    {
        $external = $this->isExternal($source);
        if (!$external && $inline) {
            $this->checkFileExistence($source);
        }
        $this->css[] = [
            'source' => $source,
            'inline' => $inline,
            'external' => $external,
            'params' => $params
        ];
    }

    public function addJs(
        string $source,
        bool $inline = false,
        array $params = []
    )
    {
        $external = $this->isExternal($source);
        if (!$external && !$inline) {
            $this->checkFileExistence($source);
        }
        $this->js[] = [
            'source' => $source,
            'inline' => $inline,
            'external' => $external,
            'params' => $params
        ];
    }


    public function loadGlobals()
    {
        $css = Context::getParam('css', []);
        foreach ($css as $stylesheet) {
            $this->addCss(
                $stylesheet['source'],
                inline: $script['inline'] ?? false,
                params: $stylesheet['params'] ?? []
            );
        }

        $js = Context::getParam('js', []);
        foreach ($js as $script) {
            $this->addJs(
                $script['source'],
                inline: $script['inline'] ?? false,
                params: $script['params'] ?? []
            );
        }

    }


}
