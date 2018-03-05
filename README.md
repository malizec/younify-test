# Test for [Younify.eu](http://www.younify.eu)
--------------

Repo for testing some skills from Younify.eu

--------------
## Introduction
--------------

The test is divided into two parts:
1. Create product attribute with attribute code 'delivery', attribute type dropdown, scope storeview, options YES/NO

2.create CLI command to update this attribute for all products
respecting parameter value (parameter determins if it will set
attribute values to YES or NO)

--------------
###Instruction:
--------------

Create CLI Command in magento 2 for updating product attribut value for all products
--------------

## Installation
1. Add this code in /app/etc/di.xml

<code>
        <type name="Magento\Framework\Console\CommandList">
        <arguments>
            <argument name="commands" xsi:type="array">
                <item name="update_attribute" xsi:type="object">Nenad\Tools\Model\ProductsAttributeUpdate</item>
            </argument>
        </arguments>
    </type>
</code>

at the bottom of file before </config> closing tag

2. Upgrade magento 2 with command 
    <code>
        php bin/magento setup:upgrade
    </code>

3. Use command in magento root directory with command 
    <code>
        php bin/magento product:update_attribute
    </code>