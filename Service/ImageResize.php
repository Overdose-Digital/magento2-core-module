<?php

declare(strict_types=1);

namespace Overdose\Core\Service;

use Generator;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Image\ParamsBuilder;
use Magento\Catalog\Model\Product\Media\ConfigInterface as MediaConfig;
use Magento\Catalog\Model\ResourceModel\Product\Image as ProductImage;
use Magento\Catalog\Model\View\Asset\ImageFactory as AssertImageFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Image;
use Magento\Framework\Image\Factory as ImageFactory;
use Magento\Framework\View\ConfigInterface as ViewConfig;
use Magento\MediaStorage\Helper\File\Storage\Database as FileStorageDatabase;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Model\Config\Customization as ThemeCustomizationConfig;
use Magento\Theme\Model\ResourceModel\Theme\Collection as ThemeCollection;
use Magento\Theme\Model\Theme;
use Overdose\Core\Helper\SvgUploadConfigHelper;

class ImageResize extends \Magento\MediaStorage\Service\ImageResize
{
    /** @var SvgUploadConfigHelper */
    private $svgUploadConfigHelper;

    /**
     * @var State
     */
    private $appState;

    /**
     * @var MediaConfig
     */
    private $imageConfig;

    /**
     * @var ProductImage
     */
    private $productImage;

    /**
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * @var ParamsBuilder
     */
    private $paramsBuilder;

    /**
     * @var ViewConfig
     */
    private $viewConfig;

    /**
     * @var AssertImageFactory
     */
    private $assertImageFactory;

    /**
     * @var ThemeCustomizationConfig
     */
    private $themeCustomizationConfig;

    /**
     * @var ThemeCollection
     */
    private $themeCollection;

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var FileStorageDatabase
     */
    private $fileStorageDatabase;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param State $appState
     * @param MediaConfig $imageConfig
     * @param ProductImage $productImage
     * @param ImageFactory $imageFactory
     * @param ParamsBuilder $paramsBuilder
     * @param ViewConfig $viewConfig
     * @param AssertImageFactory $assertImageFactory
     * @param ThemeCustomizationConfig $themeCustomizationConfig
     * @param ThemeCollection $themeCollection
     * @param Filesystem $filesystem
     * @param SvgUploadConfigHelper $svgUploadConfigHelper
     * @param FileStorageDatabase|null $fileStorageDatabase
     * @param StoreManagerInterface|null $storeManager
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        State $appState,
        MediaConfig $imageConfig,
        ProductImage $productImage,
        ImageFactory $imageFactory,
        ParamsBuilder $paramsBuilder,
        ViewConfig $viewConfig,
        AssertImageFactory $assertImageFactory,
        ThemeCustomizationConfig $themeCustomizationConfig,
        ThemeCollection $themeCollection,
        Filesystem $filesystem,
        SvgUploadConfigHelper $svgUploadConfigHelper,
        FileStorageDatabase $fileStorageDatabase = null,
        StoreManagerInterface $storeManager = null
    ) {
        parent::__construct(
            $appState,
            $imageConfig,
            $productImage,
            $imageFactory,
            $paramsBuilder,
            $viewConfig,
            $assertImageFactory,
            $themeCustomizationConfig,
            $themeCollection,
            $filesystem,
            $fileStorageDatabase,
            $storeManager
        );
        $this->appState = $appState;
        $this->imageConfig = $imageConfig;
        $this->productImage = $productImage;
        $this->imageFactory = $imageFactory;
        $this->paramsBuilder = $paramsBuilder;
        $this->viewConfig = $viewConfig;
        $this->assertImageFactory = $assertImageFactory;
        $this->themeCustomizationConfig = $themeCustomizationConfig;
        $this->themeCollection = $themeCollection;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->fileStorageDatabase = $fileStorageDatabase ?:
            ObjectManager::getInstance()->get(FileStorageDatabase::class);
        $this->storeManager = $storeManager ?? ObjectManager::getInstance()->get(StoreManagerInterface::class);
        $this->svgUploadConfigHelper = $svgUploadConfigHelper;
    }

    /**
     * @param array|null $themes
     * @return Generator
     * @throws NotFoundException
     */
    public function resizeFromThemes(array $themes = null): Generator
    {
        $count = $this->productImage->getCountUsedProductImages();
        if (!$count) {
            throw new NotFoundException(__('Cannot resize images - product images not found'));
        }

        $productImages = $this->productImage->getUsedProductImages();
        $viewImages = $this->getViewImages($themes ?? $this->getThemesInUse());

        foreach ($productImages as $image) {
            $error = '';
            $originalImageName = $image['filepath'];

            $mediastoragefilename = $this->imageConfig->getMediaPath($originalImageName);
            $originalImagePath = $this->mediaDirectory->getAbsolutePath($mediastoragefilename);

            //<custom>
            if ($this->svgUploadConfigHelper->isSvgImage($originalImagePath)) {
                continue;
            }
            //</custom>

            if ($this->fileStorageDatabase->checkDbUsage()) {
                $this->fileStorageDatabase->saveFileToFilesystem($mediastoragefilename);
            }
            if ($this->mediaDirectory->isFile($originalImagePath)) {
                try {
                    foreach ($viewImages as $viewImage) {
                        $this->resize($viewImage, $originalImagePath, $originalImageName);
                    }
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            } else {
                $error = __('Cannot resize image "%1" - original image not found', $originalImagePath);
            }

            yield ['filename' => $originalImageName, 'error' => (string) $error] => $count;
        }
    }

    /**
     * @param array $themes
     * @return array
     */
    private function getViewImages(array $themes): array
    {
        $viewImages = [];
        $stores = $this->storeManager->getStores(true);
        /** @var Theme $theme */
        foreach ($themes as $theme) {
            $config = $this->viewConfig->getViewConfig(
                [
                    'area' => Area::AREA_FRONTEND,
                    'themeModel' => $theme,
                ]
            );
            $images = $config->getMediaEntities('Magento_Catalog', ImageHelper::MEDIA_TYPE_CONFIG_NODE);
            foreach ($images as $imageId => $imageData) {
                foreach ($stores as $store) {
                    $data = $this->paramsBuilder->build($imageData, (int) $store->getId());
                    $uniqIndex = $this->getUniqueImageIndex($data);
                    $data['id'] = $imageId;
                    $viewImages[$uniqIndex] = $data;
                }
            }
        }
        return $viewImages;
    }

    /**
     * @return array
     */
    private function getThemesInUse(): array
    {
        $themesInUse = [];
        $registeredThemes = $this->themeCollection->loadRegisteredThemes();
        $storesByThemes = $this->themeCustomizationConfig->getStoresByThemes();
        $keyType = is_integer(key($storesByThemes)) ? 'getId' : 'getCode';
        foreach ($registeredThemes as $registeredTheme) {
            if (array_key_exists($registeredTheme->$keyType(), $storesByThemes)) {
                $themesInUse[] = $registeredTheme;
            }
        }
        return $themesInUse;
    }

    /**
     * @param array $imageParams
     * @param string $originalImagePath
     * @param string $originalImageName
     */
    private function resize(array $imageParams, string $originalImagePath, string $originalImageName)
    {
        unset($imageParams['id']);
        $imageAsset = $this->assertImageFactory->create(
            [
                'miscParams' => $imageParams,
                'filePath' => $originalImageName,
            ]
        );
        $imageAssetPath = $imageAsset->getPath();
        $usingDbAsStorage = $this->fileStorageDatabase->checkDbUsage();
        $mediaStorageFilename = $this->mediaDirectory->getRelativePath($imageAssetPath);

        $alreadyResized = $usingDbAsStorage ?
            $this->fileStorageDatabase->fileExists($mediaStorageFilename) :
            $this->mediaDirectory->isFile($imageAssetPath);

        if (!$alreadyResized) {
            $this->generateResizedImage(
                $imageParams,
                $originalImagePath,
                $imageAssetPath,
                $usingDbAsStorage,
                $mediaStorageFilename
            );
        }
    }

    /**
     * @param array $imageParams
     * @param string $originalImagePath
     * @param string $imageAssetPath
     * @param bool $usingDbAsStorage
     * @param string $mediaStorageFilename
     * @throws \Exception
     */
    private function generateResizedImage(
        array $imageParams,
        string $originalImagePath,
        string $imageAssetPath,
        bool $usingDbAsStorage,
        string $mediaStorageFilename
    ) {
        $image = $this->makeImage($originalImagePath, $imageParams);

        if ($imageParams['image_width'] !== null && $imageParams['image_height'] !== null) {
            $image->resize($imageParams['image_width'], $imageParams['image_height']);
        }

        if (isset($imageParams['watermark_file'])) {
            if ($imageParams['watermark_height'] !== null) {
                $image->setWatermarkHeight($imageParams['watermark_height']);
            }

            if ($imageParams['watermark_width'] !== null) {
                $image->setWatermarkWidth($imageParams['watermark_width']);
            }

            if ($imageParams['watermark_position'] !== null) {
                $image->setWatermarkPosition($imageParams['watermark_position']);
            }

            if ($imageParams['watermark_image_opacity'] !== null) {
                $image->setWatermarkImageOpacity($imageParams['watermark_image_opacity']);
            }

            $image->watermark($this->getWatermarkFilePath($imageParams['watermark_file']));
        }

        $image->save($imageAssetPath);

        if ($usingDbAsStorage) {
            $this->fileStorageDatabase->saveFile($mediaStorageFilename);
        }
    }

    /**
     * @param array $imageData
     * @return string
     */
    private function getUniqueImageIndex(array $imageData): string
    {
        ksort($imageData);
        unset($imageData['type']);
        // phpcs:disable Magento2.Security.InsecureFunction
        return md5(json_encode($imageData));
    }

    /**
     * @param string $file
     * @return string
     */
    private function getWatermarkFilePath($file)
    {
        $path = $this->imageConfig->getMediaPath('/watermark/' . $file);
        return $this->mediaDirectory->getAbsolutePath($path);
    }

    /**
     * @param string $originalImagePath
     * @param array $imageParams
     * @return Image
     */
    private function makeImage(string $originalImagePath, array $imageParams): Image
    {
        $image = $this->imageFactory->create($originalImagePath);
        $image->keepAspectRatio($imageParams['keep_aspect_ratio']);
        $image->keepFrame($imageParams['keep_frame']);
        $image->keepTransparency($imageParams['keep_transparency']);
        $image->constrainOnly($imageParams['constrain_only']);
        $image->backgroundColor($imageParams['background']);
        $image->quality($imageParams['quality']);
        return $image;
    }
}
