<?php

namespace MQM\ImageBundle\Entity;

use MQM\AssetBundle\Entity\FileAsset;
use MQM\ImageBundle\Model\ImageInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="shop_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image extends FileAsset implements ImageInterface
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setType('image/jpeg');
    }
    
    function __clone()
    {
        $path = uniqid().'.jpg';//.$this->data->guessExtension());
        copy($this->getAbsolutePath(), $this->getAssetRootDir() . '/' . $path);
        $this->setName($path);
        if ($this->getName() == null) {
            $this->setName($this->getName());
        }
    }
    
    /**
     * @return {@inheritDoc}
     */
    public function getAbsolutePath()
    {   
        if ($this->getName() == null) {
            $name = 'image_nd.jpg';
             return $this->getAlternativeRootDir() . '/' . $name;
        }
        else{
            return null === $this->getName() ? null : $this->getAssetRootDir() . '/' . $this->getName();
        }
    }
    
    /**
     * @return {@inheritDoc}
     */
    public function getWebPath()
    {
        if ($this->getName() == null) {
            $name = 'image_nd.jpg';
            
            return $this->getAlternativeDir() . '/' . $name;
        }
        else {
            return $this->getAssetDir() . '/' . $this->getName();
        }
    }
    
    /**
     * @return string
     */
    protected function getAlternativeRootDir()
    {        
        return __DIR__ . '/../../../../../web/' . $this->getAlternativeDir();
    }
    
    /**
     *
     * @return string get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
     */
    protected function getAlternativeDir()
    {       
        return 'bundles/mqmshop/images';
    }
    
    /**
     * @return {@inheritDoc}
     */
    public function getWidth()
    {
        $path = $this->getAbsolutePath();
        if ($path == null) {
            return null;
        }
        list($width, $height, $type, $attr) = getimagesize($path);
        
        return $width;
    }
    
    /**
     * @return {@inheritDoc}
     */
    public function getHeight()
    {
        $path = $this->getAbsolutePath();
        if ($path == null) {
            return null;
        }
        list($width, $height, $type, $attr) = getimagesize($path);
        
        return $height;
    }
    
    /**
     * @return {@inheritDoc}
     */
    public function getImageSize($maxWidth=null, $maxHeight=null)
    {
        $path = $this->getAbsolutePath();
        try{
            list($width, $height, $type, $attr) = getimagesize($path);
        }
        catch (\Exception $e) {
            return array(
            'width' => self::DEF_WIDTH,
            'height' => self::DEF_HEIGHT,
            'hPadding' => self::DEF_H_PADDING,
            'vPadding' => self::DEF_V_PADDING,
            );
        }
        
        $width += 0.0;
        $height += 0.0;
        $newWidth = $width + 0.0;
        $newHeight = $height + 0.0;        
        $vPadding = 0.0;
        $hPadding = 0.0;        
        if ($maxWidth != null && $maxHeight != null) {
            if ($width > $height) {
                $newWidth = $maxWidth;
                $proportion = ($newWidth / $width) + 0.0;
                $newHeight = $height * $proportion;
            }
            else {
                $newHeight = $maxHeight;
                $proportion = ($newHeight / $height) + 0.0;
                $newWidth = $width * $proportion;
            }            
            $hPadding = ($maxWidth - $newWidth) / 2.0;
            $vPadding = ($maxHeight - $newHeight) / 2.0;
        }
        
        return array(
            'width' => $newWidth,
            'height' => $newHeight,
            'hPadding' => $hPadding,
            'vPadding' => $vPadding,
        );
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->data) {
            return;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->data->move($this->getAssetRootDir(), $this->getName());
        unset($this->data);
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->modifiedAt = new \DateTime();
        if (null != $this->data) {
            $this->setName(uniqid().'.jpg');//.$this->data->guessExtension());
        }
        if ($this->getName() == null) {
            $this->setName($this->getName());
        }
    }
    
     /**
     * Set the absolute path in the non-persistent variable file to be used in the postRemove if the remove is succesful
     * 
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $name = $this->getName();        
        if ($name != null) {
            $this->data = $this->getAbsolutePath();
        }
    }
    
     /**
     * Deletes the file from the system completely
     * Reads the file variable which must have temporally the absolute path of the path setted in the PreRemove listener
     *
     * @ORM\PostRemove()
     */
    public function postRemove()
    {
        $absolutePath = $this->getData();        
        if ($absolutePath != null) {
            try {
                unlink($absolutePath);
            }
            catch (\Exception $e) {
                
            }
        }
    }
}