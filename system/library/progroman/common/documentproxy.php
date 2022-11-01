<?php
namespace progroman\Common;

use progroman\CityManager\CityManager;

class DocumentProxy extends \Document {

    public function setTitle($title) {
        parent::setTitle(CityManager::instance()->replaceBlanks($title));
    }

    public function setDescription($description) {
        parent::setDescription(CityManager::instance()->replaceBlanks($description));
    }

    public function setKeywords($keywords) {
        parent::setKeywords(CityManager::instance()->replaceBlanks($keywords));
    }

    /**
     * Копирует данные из другого документа
     * @param \Document $document
     */
    public function copy($document) {
        foreach ($document->getScripts() as $script) {
            $this->addScript($script);
        }

        foreach ($document->getStyles() as $style) {
            $this->addStyle($style['href'], $style['rel'], $style['media']);
        }

        foreach ($document->getLinks() as $link) {
            $this->addLink($link['href'], $link['rel']);
        }
    }
}