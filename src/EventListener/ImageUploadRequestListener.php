<?php

declare(strict_types=1);

namespace Innomedio\Sulu\ImageOptimizerBundle\EventListener;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Psr\Log\LoggerInterface;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ImageUploadRequestListener
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

        if ($event->getRequest()->attributes->get('_route') !== 'sulu_media.post_media') {
            return;
        }

        /** @var UploadedFile $uploadedFile */
        foreach ($event->getRequest()->files as $uploadedFile) {
            $file = $uploadedFile->getPath().'/'.$uploadedFile->getFilename();

            // Ignore non-image files
            if (!@is_array(getimagesize($file))) {
                return;
            }

            $extension = $uploadedFile->getClientOriginalExtension();

            if ($this->configuration['enabled'] === true) {
                $this->logger->info('Optimize '.$uploadedFile->getClientOriginalName());

                $optimizer = OptimizerChainFactory::create();

                if ($this->logger instanceof LoggerInterface) {
                    $optimizer->useLogger($this->logger);
                }

                $optimizer
                    ->optimize($file);
            }

            if ($this->configuration['resize']['enabled'] === true) {
                $size = $this->configuration['resize']['max_size'];

                $image = (new Imagine())->open($file);
                $sizes = $image->getSize();
                $width = $sizes->getWidth();
                $height = $sizes->getHeight();

                $this->logger->info(
                    sprintf('Resize %s - Original size is %dx%d', $uploadedFile->getClientOriginalName(), $width, $height)
                );

                if ($width > $height && $width > $size) {
                    $ratio = $width / $size;
                    $newHeight = $height / $ratio;

                    $image
                        ->resize(new Box($size, $newHeight))
                        ->save($file, [
                            'format' => $extension,
                        ]);

                    return;
                }

                if ($height > $width && $height > $size) {
                    $ratio = $height / $size;
                    $newWidth = $width / $ratio;

                    $image
                        ->resize(new Box($newWidth, $size))
                        ->save($file, [
                            'format' => $extension,
                        ]);
                }
            }
        }
    }
}
