<?php

declare(strict_types=1);

namespace Innomedio\Sulu\ImageOptimizerBundle\EventListener;

use Psr\Log\LoggerInterface;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Event\RequestEvent;

readonly class ImageUploadRequestListener
{
    public function __construct(
        private array $configuration = [],
        private ?LoggerInterface $logger = null
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $uploadRoutes = [
            'sulu_media.post_media',
            'sulu_media.post_media_trigger',
        ];

        if (!in_array($event->getRequest()->attributes->get('_route'), $uploadRoutes)) {
            return;
        }

        /** @var UploadedFile $uploadedFile */
        foreach ($event->getRequest()->files as $uploadedFile) {
            $file = $uploadedFile->getPath().'/'.$uploadedFile->getFilename();

            // Ignore non-image files
            if (!@is_array(getimagesize($file))) {
                return;
            }

            $this->logger?->info('Optimize '.$uploadedFile->getClientOriginalName());

            $optimizer = OptimizerChainFactory::create();

            if ($this->logger instanceof LoggerInterface) {
                $optimizer->useLogger($this->logger);
            }

            if ($this->configuration['enabled'] === true) {
                $image = Image::useImageDriver(ImageDriver::Gd)
                    ->format($uploadedFile->getClientOriginalExtension())
                    ->loadFile($file)
                    ->optimize($optimizer);

                if ($this->configuration['resize']['enabled'] === true) {
                    $maxSize = $this->configuration['resize']['max_size'];
                    $width = $image->getWidth();
                    $height = $image->getHeight();

                    if ($width > $height && $width > $maxSize) {
                        $image->width($maxSize);
                    } elseif ($height > $width && $height > $maxSize) {
                        $image->height($maxSize);
                    }
                }

                $image->save($file);
            }
        }
    }
}
