<?php
       
namespace MQM\ImageBundle\Command;

ini_set("memory_limit","1024M");

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use MQM\ImageBundle\Model\ImageManagerInterface;
use MQM\AssetBundle\Model\AssetManagerInterface;

class InstallImageToAssetTableCommand extends ContainerAwareCommand
{
    /**
    * @var ImageManagerInterface
    */
    private $imageManager;
    
    /**
     *
     * @var AssetManagerInterface
     */
    private $assetManager;
    
    protected function configure()
    {
        $this
            ->setName('mqm_image:install:image:asset:database')
            ->setDescription('Greet someone')
            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->imageManager = $this->getContainer()->get('mqm_image.image_manager');
        $this->assetManager = $this->getContainer()->get('mqm_asset.asset_manager');
        $images = $this->imageManager->findImages();
        $indexToFreeMemory = 0;
        for ($index = 0, $count = count($images) ; $index < $count ; $index++) {
            $image = $images[$index];
            
            $asset = $this->createAssetFromImage($image);
            if ($asset != null) {
                //$output->writeln($asset->getName());
                $this->assetManager->saveAsset($asset, false);                 
                $indexToFreeMemory++;
            }
            if ($indexToFreeMemory > 10) {
                //$output->writeln('flush');
                $this->assetManager->flush();
                $indexToFreeMemory = 0;
            }
        }        
        $this->assetManager->flush();
    }
    
    private function createAssetFromImage($image)
    {
        $name = $image->getName();
        $absolutePath = $image->getAbsolutePath(); 
        if ($name == null || !file_exists($absolutePath)) {
            return null;
        }
        $data = file_get_contents($absolutePath);
        $asset = $this->assetManager->createAsset();
        $asset->setData($data);
        $asset->setName($name);        
        $asset->setType('image/jpeg');
        
        return $asset;
    }
}