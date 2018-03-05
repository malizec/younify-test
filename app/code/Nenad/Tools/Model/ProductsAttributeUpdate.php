<?php

namespace Nenad\Tools\Model;

use Magento\Framework\App\{ObjectManager, State};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface, InputArgument, InputOption};
use Symfony\Component\Console\Output\OutputInterface;

class ProductsAttributeUpdate extends Command
{
    protected $_storeManager;
    protected $productCollection;

    public function __construct(
        State $state,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Action $action
    )
    {
        // We cannot use core functions (like saving a product) unless the area
        // code is explicitly set.
        try {
            $state->setAreaCode('adminhtml');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // Intentionally left empty.
        }
        $this->objManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->productCollection = $this->objManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $this->_storeManager = $storeManager;

        $this->action = $action;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('product:update_attribute');
        $this->setDescription('Update all products attribute');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // die($input);

        $storeId = $this->_storeManager->getStore()->getId();

        $output->writeln('<info>Importing Products...</info>');
        $objectManager = ObjectManager::getInstance();

        // $collection = $this->productCollection->create()
        //             ->addAttributeToSelect('*')
        //             ->load();
        //             
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        /** Apply filters here */

        $productCollection->load();

        foreach ($productCollection as $product) {
            try{
                // $productItem = $objectManager->create('Magento\Catalog\Model\Product')->load($product->getId()); 
                // $product->setDelivery("YES"); //$value = 1 for yes and $value = 0 for No.
                // $product->getResource()->saveAttribute($product, 'delivery');
                // $product->getResource()->saveAttribute($product, 'visibility');

                $product->setDelivery("NO");
                $product->getResource()->saveAttribute($product, 'delivery');
                $product->setData('delivery', 'NO')->getResource()->saveAttribute($product, 'delivery');
                $product->save();            

                $output->writeln($product->getId() . ' : ' . $product->getName() . ' UPDATED');

            } catch(Exception $e){
                $output->writeln($e->getMessage());
            }
            
        }

    }

}
