<?php

namespace MQM\ImageBundle\Model;

use MQM\ImageBundle\Model\ImageInterface;

interface ImageManagerInterface
{
    /**
     * @return ImageInterface
     */
    public function createImage();
    
    /**
     *
     * @param ImageInterface $image
     * @param boolean $andFlush 
     */
    public function saveImage(ImageInterface $image, $andFlush = true);
    
    /**
     *
     * @param ImageInterface $image
     * @param boolean $andFlush 
     */
    public function deleteImage(ImageInterface $image, $andFlush = true);
    
    /**
     * @return ImageManagerInterface
     */
    public function flush();
    
    /**
     * @param array 
     * @return ImageInterface
     */
    public function findImageBy(array $criteria);
    
    /**
     * @return array 
     */
    public function findImages();
}