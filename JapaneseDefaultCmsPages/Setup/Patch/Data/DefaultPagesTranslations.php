<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\JapaneseDefaultCmsPages\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Phrase\Renderer\Translate as TranslatePhraseRenderer;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Translate;

/**
 * Patch to apply Japanese translations of Magento Default CMS Pages.
 *
 * Allows also provide default CMS pages translation for any locale and use system locale to select which to apply.
 */
class DefaultPagesTranslations implements DataPatchInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var ComponentRegistrarInterface
     */
    private $componentRegistrar;

    /**
     * @var Translate
     */
    private $translate;

    /**
     * @var TranslatePhraseRenderer
     */
    private $translatePhraseRenderer;

    /**
     * @var State
     */
    private $appState;

    /**
     * @param PageFactory $pageFactory
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param Translate $translate
     * @param TranslatePhraseRenderer $translatePhraseRenderer
     * @param State $appState
     */
    public function __construct(
        PageFactory $pageFactory,
        ComponentRegistrarInterface $componentRegistrar,
        Translate $translate,
        TranslatePhraseRenderer $translatePhraseRenderer,
        State $appState
    ) {
        $this->pageFactory = $pageFactory;
        $this->componentRegistrar = $componentRegistrar;
        $this->translate = $translate;
        $this->translatePhraseRenderer = $translatePhraseRenderer;
        $this->appState = $appState;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->appState->emulateAreaCode(
            Area::AREA_FRONTEND,
            function () {
                $this->runWithTranslation(function () {
                    foreach ($this->getPagesTranslations() as $id => $translations) {
                        $contentTranslation = $this->getPageContentTranslationLocation($id);
                        if ($contentTranslation !== null) {
                            $translations['content'] = $contentTranslation;
                        }

                        $this->translatePage($id, $translations);
                    }
                });
            }
        );
    }

    /**
     * Run task with possibility to use translation.
     *
     * By default setup scripts not use translation.
     * This wrapper adds possibility to use translatable phrases during setup.
     *
     * @param \Closure $task
     */
    private function runWithTranslation(\Closure $task)
    {
        $originalPhraseRender = Phrase::getRenderer();
        try {
            $this->translate->loadData();
            Phrase::setRenderer(new Phrase\Renderer\Composite([
                $this->translatePhraseRenderer,
                $originalPhraseRender,
            ]));
            $task();
        } finally {
            Phrase::setRenderer($originalPhraseRender);
        }
    }

    /**
     * Get what should be translated for pages.
     *
     * Usage of __ function allows to discover phrases that should be translated for i18n:collect-phrases command.
     *
     * @return array
     */
    private function getPagesTranslations(): array
    {
        return [
            'no-route' => [
                'title' => __('404 Not Found'),
                'content_heading' => __('Whoops, our bad...'),
            ],
            'home' => [
                'title' => __('Home page'),
                'content_heading' => __('Home Page'),
            ],
            'enable-cookies' => [
                'title' => __('Enable Cookies'),
                'content_heading' => __('What are Cookies?'),
            ],
            'privacy-policy-cookie-restriction-mode' => [
                'title' => __('Privacy and Cookie Policy'),
                'content_heading' => __('Privacy and Cookie Policy'),
            ],
        ];
    }

    /**
     * Create phrase of page content.
     *
     * Phrase also may be locale-aware path to file inside registered Magento component.
     *
     * @param string $pageIdentifier
     * @return Phrase|null
     */
    private function getPageContentTranslationLocation(string $pageIdentifier) :? Phrase
    {
        $phrase = __(
            'magento://CommunityEngineering_JapaneseDefaultCmsPages::i18n/en_US/%1.html',
            [$pageIdentifier]
        );

        // Default English text already stored in DB.
        // Skip content translation if alternative translation is not provided.
        if ((string)$phrase === str_replace('%1', $pageIdentifier, $phrase->getText())) {
            return null;
        }
        return $phrase;
    }

    /**
     * Translate page and save updated version.
     *
     * @param string $pageIdentifier
     * @param array $translations
     * @return bool
     * @throws \Exception
     */
    private function translatePage(string $pageIdentifier, array $translations): bool
    {
        $page = $this->pageFactory->create()->load($pageIdentifier, 'identifier');
        $page->getId();
        if (!$page->getId()) {
            return false;
        }

        foreach ($translations as $property => $phrase) {
            $translation = $this->translate($phrase);
            if (!empty($translation)) {
                $page->setData($property, $translation);
            }
        }

        $page->save();
        return true;
    }

    /**
     * Translate phrase.
     *
     * If phrase is recognized as path to Magento component file then content of the file will be used as translation.
     *
     * @param Phrase $phrase
     * @return string
     */
    private function translate(Phrase $phrase): string
    {
        $translation = (string)$phrase;

        if (0 !== strpos($translation, 'magento://')) {
            return $translation;
        }

        $translationFile = $this->transformMagentoToFilePath($translation);
        // phpcs:disable Magento2.Functions.DiscouragedFunction
        $translationText = file_get_contents($translationFile);
        // phpcs:enable
        if (false === $translationText) {
            throw new \InvalidArgumentException(sprintf(
                'Translation is expected in "%s" but file "%s" not found or is not readable.',
                $translation,
                $translationFile
            ));
        }
        return $translationText;
    }

    /**
     * Build file path from path inside Magento component.
     *
     * @param string $magentoPath
     * @return string
     */
    private function transformMagentoToFilePath(string $magentoPath): string
    {
        $magentoPathInfo = $this->parseMagentoPath($magentoPath);
        $componentPath = $this->componentRegistrar->getPath(
            $magentoPathInfo['componentType'],
            $magentoPathInfo['componentName']
        );
        if ($componentPath === null) {
            throw new \InvalidArgumentException(sprintf(
                'Unable to locate Magento component for path "%s".',
                $magentoPath
            ));
        }
        $path = $componentPath . DIRECTORY_SEPARATOR . $magentoPathInfo['path'];
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        return $path;
    }

    /**
     * Parse Magento path.
     *
     * Format: [magento://][<component_type>:]<component_name>[::path_to_file_inside_component].
     * Parts are (all always present in result):
     * - componentType string Type of Magento component (if not specified in path then "module")
     * - componentName string Magento Component Name
     * - path string path to file inside Magento Component
     *
     * @param string $magentoPath
     * @return array
     */
    private function parseMagentoPath(string $magentoPath): array
    {
        $schema = 'magento://';
        if (0 === strpos($magentoPath, $schema)) {
            $magentoPath = substr($magentoPath, strlen($schema));
        }

        $parts = explode('::', $magentoPath, 2);
        $path = $parts[1] ?? '';
        $parts = explode(':', $parts[0], 2);
        if (count($parts) === 2) {
            $componentType = $parts[0];
            $componentName = $parts[1];
        } else {
            $componentType = ComponentRegistrar::MODULE;
            $componentName = $parts[0];
        }

        return [
            'componentType' => $componentType,
            'componentName' => $componentName,
            'path' => $path,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
