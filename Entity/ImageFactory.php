<?php

namespace MQM\ImageBundle\Entity;

use MQM\ImageBundle\Model\ImageFactoryInterface;

class ImageFactory implements ImageFactoryInterface
{
    private $imageClass;

    
    public function __construct($imageClass) {
        $this->imageClass = $imageClass;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createImage()
    {
        return new $this->imageClass();
    }

    /**
     * {@inheritDoc}
     */
    public function getImageClass()
    {
        return $this->imageClass;
    }
}