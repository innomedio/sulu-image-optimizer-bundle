# SuluImageOptimizerBundle

Because of server/hosting limitations you might have issues with Sulu cropping very large images 
(in size or in pixels).

This bundle optimizes (and/or resizes) images before they are added to the Sulu media library so that
they are much easier to handle.

## Installation

Install using composer:

```
composer require innomedio/sulu-image-optimizer-bundle
```

Add the bundle to ``config/bundles.php`` if wasn't added automatically:
```
Innomedio\Sulu\ImageOptimizerBundle\InnomedioSuluImageOptimizerBundle::class => ['all' => true],  
```

## Usage

When installed, image optimization and resizing is enabled by default. These are the available settings:

```
innomedio_sulu_image_optimizer:
    enabled: true
    resize:
        enabled: true
        max_size: 4000 # Means if the width or height > 4000, the image will be resized to 4000
```

If you'd like to log all that's happening you can define your logger like this:

```
innomedio_sulu_image_optimizer:
    logger: 'monolog.logger.YOUR_CHANNEL'
```