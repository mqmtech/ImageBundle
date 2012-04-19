<?php

namespace MQM\ImageBundle\Entity;

use MQM\ImageBundle\Model\ImageManagerInterface;
use MQM\ImageBundle\Model\ImageFactoryInterface;
use MQM\ImageBundle\Model\ImageInterface;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ImageManager implements ImageManagerInterface
{
    private $factory;
    private $entityManager;
    private $repository;
   
    public function __construct(EntityManager $entityManager, ImageFactoryInterface $imageFactory)
    {
        $this->entityManager = $entityManager;
        $this->factory = $imageFactory;
        $imageClass = $imageFactory->getImageClass();
        $this->repository = $entityManager->getRepository($imageClass);
    }
    
    /**
     * {@inheritDoc} 
     */
    public function createImage()
    {
        return $this->getFactory()->createImage();
    }
    
    /**
     * {@inheritDoc} 
     */
    public function saveImage(ImageInterface $image, $andFlush = true)
    {
        $this->getEntityManager()->persist($image);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * {@inheritDoc} 
     */
    public function deleteImage(ImageInterface $image, $andFlush = true)
    {
        $this->getEntityManager()->remove($image);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $this->getEntityManager()->flush();
    }
    
    public function findImageBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }
    
    public function findImages()
    {
        return $this->getRepository()->findAll();
    }
    
    /**
     *
     * @return ImageFactory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }
}