<?php
namespace Sitefrog\View;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\CSSMinFilter;
use Assetic\Filter\ScssphpFilter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sitefrog\Facades\Context;

class AssetManager
{
    private Collection $css;
    private Collection $js;

    private Collection $hyperscript;

    public function __construct()
    {
        $this->css = collect([]);
        $this->js = collect([]);
        $this->hyperscript = collect([]);
    }

    private function getFilters($type)
    {
        if ($type == 'css') {
            return [
                new ScssphpFilter(),
                new CSSMinFilter(),
            ];
        }

        return [];
    }

    private function getListWithCompiled($list, $type)
    {
        $assets = $list->where('external', true);
        $local = $list->where('external', false);

        if (count($local) > 0) {
            $compiled = $this->compile($local, $type);
            $assets[] = $compiled;
        }

        return $assets;
    }
    public function getCss()
    {
        return $this->getListWithCompiled($this->css, 'css');
    }

    public function getJs()
    {
        return $this->getListWithCompiled($this->js, 'js');
    }

    public function getHyperscript()
    {
        return $this->getListWithCompiled($this->hyperscript, 'hyperscript');
    }



    private function getCacheKey(Collection $assets): string
    {
        return md5($assets->map(function($asset) {
            return $asset['source'].':'.filemtime($this->getFullPath($asset['source']));
        })->join(','));
    }

    private function compile(Collection $assets, $type)
    {
        $cacheKey = $this->getCacheKey($assets);
        if (!Cache::has($cacheKey)) {
            $assetCollection = new AssetCollection(
                $assets->map(function ($asset) {
                    return new FileAsset($this->getFullPath($asset['source']));
                })->toArray(),
                $this->getFilters($type)
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
        $fullPath = $this->getFullPath($filename);
        if (!file_exists($fullPath)) {
            throw new \Exception('File not found: '.$fullPath);
        }
    }

    private function isExternal(string $source)
    {
        return Str::startsWith($source, ['http:', 'https:']);
    }

    private function getFullPath(string $source)
    {
        if (!file_exists($source)) {
            return Storage::disk('sitefrog')->path($source);
        }
        return $source;
    }

    private function addAsset(
        Collection $assets,
        string $source,
        bool $inline = false,
        array $params = [],

    )
    {
        $external = $this->isExternal($source);
        if (!$external && !$inline) {
            $this->checkFileExistence($source);
        }
        $assets->push([
            'source' => $source,
            'inline' => $inline,
            'external' => $external,
            'params' => $params
        ]);
    }


    public function addCss(
        string $source,
        bool $inline = false,
        array $params = []
    ): void
    {
       $this->addAsset($this->css, $source, $inline, $params);
    }

    public function addJs(
        string $source,
        bool $inline = false,
        array $params = []
    )
    {
        $this->addAsset($this->js, $source, $inline, $params);
    }

    public function addHyperscript(
        string $source,
        bool $inline = false,
        array $params = []
    )
    {
        $this->addAsset($this->hyperscript, $source, $inline, $params);
    }


    public function loadGlobals()
    {
        $css = Context::getParam('css', null, []);
        foreach ($css as $stylesheet) {
            $this->addCss(
                $stylesheet['source'],
                inline: $script['inline'] ?? false,
                params: $stylesheet['params'] ?? []
            );
        }

        $js = Context::getParam('js', null, []);
        foreach ($js as $script) {
            $this->addJs(
                $script['source'],
                inline: $script['inline'] ?? false,
                params: $script['params'] ?? []
            );
        }

        $hyperscript = Context::getParam('hyperscript', null, []);
        foreach ($hyperscript as $script) {
            $this->addHyperscript(
                $script['source'],
                inline: $script['inline'] ?? false,
                params: $script['params'] ?? []
            );
        }

    }


}
