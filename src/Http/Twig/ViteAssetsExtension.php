<?php

namespace App\Http\Twig;

use Psr\Cache\CacheItemPoolInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetsExtension extends AbstractExtension
{

    public const CACHE_KEY = 'vite_asset';
    private ?array $manifestData = null;
    private bool $isDev;
    private string $manifest;
  /**
   * @var CacheItemPoolInterface
   */
    private CacheItemPoolInterface $cache;

    public function __construct(
        bool $isDev,
        string $manifest,
        CacheItemPoolInterface $cache
    ) {
        $this->isDev = $isDev;
        $this->manifest = $manifest;
        $this->cache = $cache;
    }

    public function getFunctions(): array
    {
        return  [
        new TwigFunction('encore_entry_script_tags', [$this, 'asset'], ['is_safe' => ['html']])
        ];
    }

    public function asset(string $entry, ?array $deps = []): string
    {
        if ($this->isDev) {
            return $this->assetDev($entry, $deps);
        }
        return $this->assetProd($entry);
    }

    public function assetDev(string $entry, ?array $deps = []): string
    {
        $html = "<script type=\"module\" src=\"http://localhost:3000/assets/@vite/client\"></script>";

        if (in_array('react', $deps)) {
            $html .= '<script type="module">
            import RefreshRuntime from "http://localhost:3000/assets/@react-refresh"
            RefreshRuntime.injectIntoGlobalHook(window)
            window.$RefreshReg$ = () => {}
            window.$RefreshSig$ = () => (type) => type
            window.__vite_plugin_react_preamble_installed__ = true
              </script>';
        }
        $html .= "<script type=\"module\" src=\"http://localhost:3000/assets/{$entry}\" defer></script>";

        return $html;
    }

    public function assetProd(string $entry): string
    {

        if ($this->manifestData === null) {
            $item = $this->cache->getItem(self::CACHE_KEY);
            if ($item->isHit()) {
                $this->manifestData = $item->get();
            } else {
                $this->manifestData = json_decode(file_get_contents($this->manifest), true);
                $item->set($this->manifestData);
                $this->cache->save($item);
            }
        }
        $file = $this->manifestData[$entry]['file'];
        $css = $this->manifestData[$entry]['css'] ?? [];
        $imports = $this->manifestData[$entry]['imports'] ?? [];

        $html = "<script type=\"module\" src=\"/assets/{$file}\" defer></script>";

        foreach ($css as $cssFile) {
            $html .= "<link rel=\"stylesheet\" media=\"screen\" href=\"/assets/{$cssFile}\"/>";
        }
        foreach ($imports as $import) {
            $html .= "<link rel=\"modulepreload\"  href=\"/assets/{$import}\"/>";
        }

        return $html;
    }
}
