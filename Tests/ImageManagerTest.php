<?php

namespace MQM\ImageBundle\Test\Image;


use MQM\ImageBundle\Model\ImageManagerInterface;


class ImageManagerTest extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{   
    protected $_container;
    
    /**
     * @var ImageManagerInterface
     */
    private $imageManager;


    public function __construct()
    {
        parent::__construct();
        
        $client = static::createClient();
        $container = $client->getContainer();
        $this->_container = $container;  
    }
    
    protected function setUp()
    {
        $this->imageManager = $this->get('mqm_image.image_manager');
    }

    protected function tearDown()
    {
        $this->resetImages();
    }

    protected function get($service)
    {
        return $this->_container->get($service);
    }
    
    public function testGetAssertManager()
    {
        $this->assertNotNull($this->imageManager);
    }
    
    private function resetImages()
    {
        $categories = $this->imageManager->findImages();
        foreach ($categories as $image) {
            $this->imageManager->deleteImage($image, false);
        }
        $this->imageManager->flush();
    }
}
