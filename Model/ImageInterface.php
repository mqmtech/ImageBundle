<?php

namespace MQM\ImageBundle\Model;

use MQM\AssetBundle\Model\FileAssetInterface;

interface ImageInterface extends FileAssetInterface
{    
    const DEF_WIDTH = 68;
    const DEF_HEIGHT = 68;
    const DEF_H_PADDING = 0;
    const DEF_V_PADDING = 0;
    
    /**
     * @return float
     */
    public function getWidth();
    
    /**
     * @return float
     */
    public function getHeight();
    
    /**
     * @param float $maxWidth
     * @param float $maxHeight
     * @return Array 
     */
    public function getImageSize($maxWidth=null, $maxHeight=null);   
}