<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an e-mail
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Log
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Customer log resource
 *
 * @category   Magento
 * @package    Magento_Log
 * @author     Magento Core Team <core@magentocommerce.com>
 */

namespace Magento\Log\Model\Resource;

class Customer extends \Magento\Core\Model\Resource\Db\AbstractDb
{
    /**
     * Visitor data table name
     *
     * @var string
     */
    protected $_visitorTable;

    /**
     * Visitor info data table
     *
     * @var string
     */
    protected $_visitorInfoTable;

    /**
     * Customer data table
     *
     * @var string
     */
    protected $_customerTable;

    /**
     * Url info data table
     *
     * @var string
     */
    protected $_urlInfoTable;

    /**
     * Log URL data table name.
     *
     * @var string
     */
    protected $_urlTable;

    /**
     * Log quote data table name.
     *
     * @var string
     */
    protected $_quoteTable;

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('log_customer', 'log_id');

        $this->_visitorTable        = $this->getTable('log_visitor');
        $this->_visitorInfoTable    = $this->getTable('log_visitor_info');
        $this->_urlTable            = $this->getTable('log_url');
        $this->_urlInfoTable        = $this->getTable('log_url_info');
        $this->_customerTable       = $this->getTable('log_customer');
        $this->_quoteTable          = $this->getTable('log_quote');
    }

    /**
     * Retrieve select object for load object data
     * 
     * @param string $field
     * @param mixed $value
     * @param \Magento\Log\Model\Customer $object
     * @return \Magento\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($field == 'customer_id') {
            // load additional data by last login
            $table  = $this->getMainTable();
            $select
                ->joinInner(
                    array('lvt' => $this->_visitorTable),
                    "lvt.visitor_id = {$table}.visitor_id",
                    array('last_visit_at'))
                ->joinInner(
                    array('lvit' => $this->_visitorInfoTable),
                    'lvt.visitor_id = lvit.visitor_id',
                    array('http_referer', 'remote_addr'))
                ->joinInner(
                    array('luit' => $this->_urlInfoTable),
                    'luit.url_id = lvt.last_url_id',
                    array('url'))
                ->order("{$table}.login_at DESC")
                ->limit(1);
        }
        return $select;
    }
}