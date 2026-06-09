# SuluImageOptimizerBundle

This bundle optimizes and optionally resizes images **before** they're added to the Sulu media library. 

Its especially useful when working with large files on shared hosting environments, where Sulu's default image 
processing (like cropping) may hit server resource limits.

## ✨ Features

- Automatically optimizes uploaded images
- Optional resizing to limit image dimensions
- Option to ignore specific image types (e.g. GIFs)

## 📦 Installation

Install via Composer:

```bash
composer require innomedio/sulu-image-optimizer-bundle
```

Make sure the bundle is registered in `config/bundles.php` (usually automatic):

```
Innomedio\Sulu\ImageOptimizerBundle\InnomedioSuluImageOptimizerBundle::class => ['all' => true],  
```

## ⚙️ Configuration

Add the configuration to your Symfony config, e.g. in `config/packages/innomedio_sulu_image_optimizer.yaml`:

```
innomedio_sulu_image_optimize_config:
  enabled: true

  logger: 'monolog.logger.image_optimizer' # optional logger

  resize:
    enabled: true
    max_size: 4000 # pixels, based on the longest side

  ignore_types:
    - gif
    - webp
```

### Configuration Reference

| Option              | Type        | Default  | Description                                                                 |
|---------------------|-------------|----------|-----------------------------------------------------------------------------|
| `enabled`           | boolean     | `true`   | Whether optimization is active                                              |
| `logger`            | string/null | `null`   | (Optional) PSR logger service ID                                            |
| `ignore_types`      | array       | `[]`     | List of image formats to skip optimization (e.g. `gif`, `webp`). Formats are detected from file content, not filename. Unsupported formats (e.g. `bmp`) are skipped automatically. |
| `resize.enabled`    | boolean     | `true`   | Whether resizing is active                                                  |
| `resize.max_size`   | integer     | `4000`   | Maximum width or height in pixels                                           |

## 🔧 How It Works

The bundle listens to image upload requests on Sulu routes like:

- `sulu_media.post_media`
- `sulu_media.post_media_trigger`

Once detected, it:

1. Validates the file is an image
2. Optionally skips optimization based on detected image format (`ignore_types`)
3. Optimizes the file using [Spatie Image Optimizer](https://github.com/spatie/image-optimizer)
4. Optionally resizes the image if it exceeds the configured `max_size`
5. Saves the modified image before it's passed to Sulu
