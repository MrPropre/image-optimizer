<?php

namespace Spatie\ImageOptimizer;

use Spatie\ImageOptimizer\Optimizers\Cwebp;
use Spatie\ImageOptimizer\Optimizers\Gifsicle;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Pngquant;
use Spatie\ImageOptimizer\Optimizers\Svgo;

class OptimizerChainFactory
{
    public static function create(array $config = []): OptimizerChain
    {
        $jpegQuality = '--max=85';
        $pngQuality = '--quality=85';
        $gifCompression = '--lossy=15';
        if (isset($config['quality'])) {
            $jpegQuality = '--max='.$config['quality'];
            $pngQuality = '--quality='.$config['quality'];
            $gifCompression = '--lossy='.(100 - $config['quality']);
        }

        return (new OptimizerChain())
            ->addOptimizer(new Jpegoptim([
                $jpegQuality,
                '--strip-all',
                '--all-progressive',
            ]))

            ->addOptimizer(new Pngquant([
                $pngQuality,
                '--force',
                '--skip-if-larger',
                '--strip',
            ]))

            ->addOptimizer(new Optipng([
                '-i0',
                '-o2',
                '-quiet',
                '-strip all',
            ]))

            ->addOptimizer(new Svgo([
                '--disable={cleanupIDs,removeViewBox}',
            ]))

            ->addOptimizer(new Gifsicle([
                $gifCompression,
                '-b',
                '-O3',
                '--no-app-extensions',
            ]))
            ->addOptimizer(new Cwebp([
                '-m 6',
                '-pass 10',
                '-mt',
                '-q 80',
            ]));
    }
}
