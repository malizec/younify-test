<?php

namespace Nenad\Tools\Model;

use \Magento\Catalog\Model\Product\Action;
use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\{ObjectManager, State};

class ProductsAttributeUpdate extends Command
{
    protected $_storeManager;
    protected $objManager;
    protected $productCollection;
    
    public function __construct(
        \Magento\Framework\App\State $appState,      
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        State $state,
        ProductRepositoryInterface $prepo
    )
    {        
        try {
            $state->setAreaCode('adminhtml');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // Intentionally left empty.
        }
        $this->objManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->productCollection = $this->objManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $this->_storeManager = $storeManager;

        $this->_productRepository = $productRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('product:update_attribute');
        $this->setDescription('Update all products delivery attribute');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $storeId = $this->_storeManager->getStore()->getId();


        $output->writeln('<info>Importing Products...</info>');
        $objectManager = ObjectManager::getInstance();

        $collection = $this->productCollection->create()
                    ->addAttributeToSelect('*')
                    ->load();

        foreach ($collection as $product){
            $productId = $product->getId();
            // if ($this->objManager->create('Magento\Catalog\Model\ResourceModel\Product\Action')->updateAttributes([$productId], ['delivery' => "NO"], $storeId)){
            //     $output->writeln($product->getId() . ':' . $product->getName() . ' HAS BEEN UPDATED TO "NO"');
            // }

            if(!empty($product->getData('sku')))
            {
                $product->setDelivery("YES");
                
                try{
                    $this->objManager->create('Magento\Catalog\Model\ResourceModel\Product\Action')->updateAttributes([$productId], ['delivery' => "YES"], $storeId);
                    $product->save();
                    $output->writeln($product->getId() . ':' . $product->getName() . ' HAS BEEN UPDATED TO "'.$product->getDelivery().'"');
                } catch(Exception $e){
                    $output->writeln($e->getMessage());
                }

            } else {
                $output->writeln($product->getId() . ':' . $product->getName() . ' HAS NOT BEEN UPDATED');
            }

            // $product->setData('delivery', "NO");
            // try{
            //     $product->getResource()->saveAttribute($product, 'delivery');
            // } catch(Exception $e){
            //     $output->writeln($e->getMessage());
            // }
            // 

            // try{
            //     $product->setCustomAttribute('delivery', 'NO');
            //     $product->setDelivery(1);
            //     $this->_productRepository->save($product);
            //     $output->writeln($product->getId() . ':' . $product->getName() . ' HAS BEEN UPDATED TO "NO"');
            // } catch(Exception $e){
            //     $output->writeln($e->getMessage());
            // }


            $output->writeln($product->getDelivery());


            // \Magento\Eav\Setup\EavSetup::updateAttribute();

            // $product->setDelivery('NESTO DRUGO');
            // $product->setDelivery(1);
            // $product->setData('delivery', '2151');
            // $product->save();
            // 
            if ($productId >= 20) {
                exit();
            }
        } 
        
    }

}
