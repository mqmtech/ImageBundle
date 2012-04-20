<?php

namespace MQM\ImageBundle\Model;

use MQM\ImageBundle\Model\ImageInterface;

interface ImageFactoryInterface
{
    /**
     *
     * @return ImageInterface
     */
    public function createImage();

    /**
     *
     * @return string 
     */
    public function getImageClass();
}


