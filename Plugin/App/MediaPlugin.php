<?php

namespace Overdose\Core\Plugin\App;

use Magento\Catalog\Model\Product\Media\ConfigInterface as MediaConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\App\Media;
use Overdose\Core\Helper\SvgUploadConfigHelper;
use Psr\Log\LoggerInterface;

/**
 * svg image rendering work on FE
 * Instead of resizing svg image, just copy to cache folder.
 */
class MediaPlugin
{
    /** @var SvgUploadConfigHelper */
    private $svgImageHelper;

    /** @var MediaConfig */
    private $imageConfig;

    /** @var Http */
    private $request;

    /** @var LoggerInterface */
    private $logger;

    /** @var WriteInterface */
    private $directoryPub;

    /** @var WriteInterface */
    private $directoryMedia;

    /**
     * @param SvgUploadConfigHelper $svgImageHelper
     * @param Filesystem $filesystem
     * @param MediaConfig $imageConfig
     * @param Http $request
     * @param LoggerInterface $logger
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        SvgUploadConfigHelper $svgImageHelper,
        Filesystem $filesystem,
        MediaConfig $imageConfig,
        Http $request,
        LoggerInterface $logger
    ) {
        $this->svgImageHelper = $svgImageHelper;
        $this->imageConfig = $imageConfig;
        $this->request = $request;
        $this->logger = $logger;
        $this->directoryPub = $filesystem->getDirectoryWrite(
            DirectoryList::PUB,
            Filesystem\DriverPool::FILE
        );
        $this->directoryMedia = $filesystem->getDirectoryWrite(
            DirectoryList::MEDIA,
            Filesystem\DriverPool::FILE
        );
    }

    /**
     * @param Media $subject
     * @return array
     */
    public function beforeLaunch(Media $subject): array
    {
        try {
            $relativeFileName = $this->getRelativeFileName();

            if ($this->svgImageHelper->isSvgImage($relativeFileName)) {
                $originalImage = $this->getOriginalImage($relativeFileName);
                $originalImagePath = $this->directoryMedia->getAbsolutePath(
                    $this->imageConfig->getMediaPath($originalImage)
                );
                /**
                 * Copy svg image to cache directory instead of generating cache.
                 */
                $this->directoryMedia->copyFile(
                    $originalImagePath,
                    $this->directoryPub->getAbsolutePath($relativeFileName)
                );
            }
        } catch (\Exception $e) {
            $this->logger->error('Could not process svg image', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    /**
     * @return string
     */
    private function getRelativeFileName(): string
    {
        return str_replace('..', '', ltrim($this->request->getPathInfo(), '/'));
    }

    /**
     * @param string $resizedImagePath
     * @return string
     */
    private function getOriginalImage(string $resizedImagePath): string
    {
        return preg_replace('|^.*?((?:/([^/])/([^/])/\2\3)?/?[^/]+$)|', '$1', $resizedImagePath);
    }
}
