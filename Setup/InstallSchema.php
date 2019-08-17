<?php

namespace Suson\CountryWiseCms\Setup;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    public function __construct(
        ResourceConnection $resourceConnection
    )
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $this->resourceConnection->getConnection();

        /* Creating mapping table for country */
        $tableName = 'cms_page_country';
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable($tableName)
            )->addColumn(
                'page_id',
                Table::TYPE_SMALLINT,
                null,
                [
                    'unsigned' => false,
                    'nullable' => false,
                    'primary' => true,
                    'default' => '0'
                ],
                'Page Id'
            )->addColumn(
                'country_id',
                Table::TYPE_TEXT,
                6,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                    'default' => 0
                ],
                'Country Id'
            )->addIndex(
                $installer->getIdxName($tableName, ['country_id']),
                ['country_id']
            )->addForeignKey(
                $installer->getFkName(
                    $tableName,
                    'page_id',
                    'cms_page',
                    'page_id'
                ),
                'page_id',
                $installer->getTable('cms_page'),
                'page_id'
            );

            $installer->getConnection()->createTable($table);
        }

        /* Copying pre existing data to mapping table */
        $newTableName = $connection->getTableName($tableName);
        $rawSql = 'SELECT page_id FROM `' . $connection->getTableName('cms_page') . '`';
        $result = $connection->query($rawSql);
        $resultData = $result->fetchAll();
        foreach ($resultData as $item) {
            $insertRawSql = "INSERT INTO `" . $newTableName . "` (`page_id`, `country_id`) VALUES ('" . $item['page_id'] . "', '" . \Suson\CountryWiseCms\Model\Ui\Source\Countries::DEFAULT . "');";
            $connection->query($insertRawSql);
        }

        /* Creating mapping table for country */
        $tableName = 'cms_block_country';
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable($tableName)
            )->addColumn(
                'block_id',
                Table::TYPE_SMALLINT,
                null,
                [
                    'unsigned' => false,
                    'nullable' => false,
                    'primary' => true,
                    'default' => '0'
                ],
                'Block Id'
            )->addColumn(
                'country_id',
                Table::TYPE_TEXT,
                6,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                    'default' => 0
                ],
                'Country Id'
            )->addIndex(
                $installer->getIdxName($tableName, ['country_id']),
                ['country_id']
            )->addForeignKey(
                $installer->getFkName(
                    $tableName,
                    'block_id',
                    'cms_block',
                    'block_id'
                ),
                'block_id',
                $installer->getTable('cms_block'),
                'block_id'
            );

            $installer->getConnection()->createTable($table);
        }

        /* Copying pre existing data to mapping table */
        $newTableName = $connection->getTableName($tableName);
        $rawSql = 'SELECT block_id FROM `' . $connection->getTableName('cms_block') . '`';
        $result = $connection->query($rawSql);
        $resultData = $result->fetchAll();
        foreach ($resultData as $item) {
            $insertRawSql = "INSERT INTO `" . $newTableName . "` (`block_id`, `country_id`) VALUES ('" . $item['block_id'] . "', '" . \Suson\CountryWiseCms\Model\Ui\Source\Countries::DEFAULT . "');";
            $connection->query($insertRawSql);
        }

        $installer->endSetup();
    }
}