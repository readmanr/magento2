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
 * @package     Magento_Review
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Review Product Collection
 *
 * @category    Magento
 * @package     Magento_Review
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Review\Model\Resource\Review\Product;

class Collection extends \Magento\Catalog\Model\Resource\Product\Collection
{
    /**
     * Entities alias
     *
     * @var array
     */
    protected $_entitiesAlias        = array();

    /**
     * Review store table
     *
     * @var string
     */
    protected $_reviewStoreTable;

    /**
     * Add store data flag
     *
     * @var boolean
     */
    protected $_addStoreDataFlag     = false;


    /**
     * Filter by stores for the collection
     *
     * @var array
     */
    protected $_storesIds           = array();

    /**
     * @var \Magento\Core\Model\Resource
     */
    protected $_resourceModel;

    /**
     * @var \Magento\Rating\Model\RatingFactory
     */
    protected $_ratingFactory;

    /**
     * @var \Magento\Rating\Model\Rating\Option\VoteFactory
     */
    protected $_voteFactory;

    /**
     * Construct
     *
     * @param \Magento\Core\Model\Event\Manager $eventManager
     * @param \Magento\Core\Model\Logger $logger
     * @param \Magento\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Core\Model\EntityFactory $entityFactory
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Core\Model\Resource $coreResource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     * @param \Magento\Validator\UniversalFactory $universalFactory
     * @param \Magento\Core\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Helper\Data $catalogData
     * @param \Magento\Catalog\Helper\Product\Flat $catalogProductFlat
     * @param \Magento\Core\Model\Store\Config $coreStoreConfig
     * @param \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory
     * @param \Magento\Catalog\Model\Resource\Url $catalogUrl
     * @param \Magento\Core\Model\LocaleInterface $locale
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Model\Resource\Helper $resourceHelper
     * @param \Magento\Core\Model\Resource $resourceModel
     * @param \Magento\Rating\Model\RatingFactory $ratingFactory
     * @param \Magento\Rating\Model\Rating\Option\VoteFactory $voteFactory
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Core\Model\Event\Manager $eventManager,
        \Magento\Core\Model\Logger $logger,
        \Magento\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Core\Model\EntityFactory $entityFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Core\Model\Resource $coreResource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Validator\UniversalFactory $universalFactory,
        \Magento\Core\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Catalog\Helper\Product\Flat $catalogProductFlat,
        \Magento\Core\Model\Store\Config $coreStoreConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\Resource\Url $catalogUrl,
        \Magento\Core\Model\LocaleInterface $locale,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\Resource\Helper $resourceHelper,
        \Magento\Core\Model\Resource $resourceModel,
        \Magento\Rating\Model\RatingFactory $ratingFactory,
        \Magento\Rating\Model\Rating\Option\VoteFactory $voteFactory
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_ratingFactory = $ratingFactory;
        $this->_voteFactory = $voteFactory;
        parent::__construct($eventManager, $logger, $fetchStrategy, $entityFactory, $eavConfig, $coreResource,
            $eavEntityFactory, $universalFactory, $storeManager, $catalogData, $catalogProductFlat, $coreStoreConfig,
            $productOptionFactory, $catalogUrl, $locale, $customerSession, $resourceHelper
        );
    }

    /**
     * Define module
     */
    protected function _construct()
    {
        $this->_init('Magento\Catalog\Model\Product', 'Magento\Catalog\Model\Resource\Product');
        $this->setRowIdFieldName('review_id');
        $this->_reviewStoreTable = $this->_resourceModel->getTableName('review_store');
        $this->_initTables();
    }

    /**
     * init select
     *
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->_joinFields();
        return $this;
    }

    /**
     * Adds store filter into array
     *
     * @param mixed $storeId
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addStoreFilter($storeId = null)
    {
        if (null === $storeId) {
            $storeId = $this->getStoreId();
        }

        parent::addStoreFilter($storeId);

        if (!is_array($storeId)) {
            $storeId = array($storeId);
        }

        if (!empty($this->_storesIds)) {
            $this->_storesIds = array_intersect($this->_storesIds, $storeId);
        } else {
            $this->_storesIds = $storeId;
        }

        return $this;
    }

    /**
     * Adds specific store id into array
     *
     * @param array $storeId
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function setStoreFilter($storeId)
    {
        if (is_array($storeId) && isset($storeId['eq'])) {
            $storeId = array_shift($storeId);
        }

        if (!is_array($storeId)) {
            $storeId = array($storeId);
        }

        if (!empty($this->_storesIds)) {
            $this->_storesIds = array_intersect($this->_storesIds, $storeId);
        } else {
            $this->_storesIds = $storeId;
        }

        return $this;
    }

    /**
     * Applies all store filters in one place to prevent multiple joins in select
     *
     * @param null|Zend_Db_Select $select
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    protected function _applyStoresFilterToSelect(\Zend_Db_Select $select = null)
    {
        $adapter = $this->getConnection();
        $storesIds = $this->_storesIds;
        if (null === $select) {
            $select = $this->getSelect();
        }

        if (is_array($storesIds) && (count($storesIds) == 1)) {
            $storesIds = array_shift($storesIds);
        }

        if (is_array($storesIds) && !empty($storesIds)) {
            $inCond = $adapter->prepareSqlCondition('store.store_id', array('in' => $storesIds));
            $select->join(array('store' => $this->_reviewStoreTable),
                'rt.review_id=store.review_id AND ' . $inCond,
                array())
            ->group('rt.review_id');
        } else {
            $select->join(array('store' => $this->_reviewStoreTable),
                $adapter->quoteInto('rt.review_id=store.review_id AND store.store_id = ?', (int)$storesIds),
                array());
        }

        return $this;
    }

    /**
     * Add stores data
     *
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addStoreData()
    {
        $this->_addStoreDataFlag = true;
        return $this;
    }

    /**
     * Add customer filter
     *
     * @param int $customerId
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addCustomerFilter($customerId)
    {
        $this->getSelect()
            ->where('rdt.customer_id = ?', $customerId);
        return $this;
    }

    /**
     * Add entity filter
     *
     * @param int $entityId
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addEntityFilter($entityId)
    {
        $this->getSelect()
            ->where('rt.entity_pk_value = ?', $entityId);
        return $this;
    }

    /**
     * Add status filter
     *
     * @param mixed $status
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addStatusFilter($status)
    {
        $this->getSelect()
            ->where('rt.status_id = ?', $status);
        return $this;
    }

    /**
     * Set date order
     *
     * @param string $dir
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function setDateOrder($dir = 'DESC')
    {
        $this->setOrder('rt.created_at', $dir);
        return $this;
    }

    /**
     * Add review summary
     *
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addReviewSummary()
    {
        foreach ($this->getItems() as $item) {
            $model = $this->_ratingFactory->create();
            $model->getReviewSummary($item->getReviewId());
            $item->addData($model->getData());
        }
        return $this;
    }

    /**
     * Add rote votes
     *
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addRateVotes()
    {
        foreach ($this->getItems() as $item) {
            $votesCollection = $this->_voteFactory->create()
                ->getResourceCollection()
                ->setEntityPkFilter($item->getEntityId())
                ->setStoreFilter($this->_storeManager->getStore()->getId())
                ->load();
            $item->setRatingVotes($votesCollection);
        }
        return $this;
    }

    /**
     * join fields to entity
     *
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    protected function _joinFields()
    {
        $reviewTable = $this->_resourceModel->getTableName('review');
        $reviewDetailTable = $this->_resourceModel->getTableName('review_detail');

        $this->addAttributeToSelect('name')
            ->addAttributeToSelect('sku');

        $this->getSelect()
            ->join(array('rt' => $reviewTable),
                'rt.entity_pk_value = e.entity_id',
                array('rt.review_id', 'review_created_at'=> 'rt.created_at', 'rt.entity_pk_value', 'rt.status_id'))
            ->join(array('rdt' => $reviewDetailTable),
                'rdt.review_id = rt.review_id',
                array('rdt.title','rdt.nickname', 'rdt.detail', 'rdt.customer_id', 'rdt.store_id'));
        return $this;
    }

    /**
     * Retrive all ids for collection
     *
     * @param null|int|string $limit
     * @param null|int|string $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(\Zend_Db_Select::ORDER);
        $idsSelect->reset(\Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(\Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(\Zend_Db_Select::COLUMNS);
        $idsSelect->columns('rt.review_id');
        return $this->getConnection()->fetchCol($idsSelect);
    }

    /**
     * Get result sorted ids
     *
     * @return array
     */
    public function getResultingIds()
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(\Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(\Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(\Zend_Db_Select::COLUMNS);
        $idsSelect->reset(\Zend_Db_Select::ORDER);
        $idsSelect->columns('rt.review_id');
        return $this->getConnection()->fetchCol($idsSelect);
    }

    /**
     * Render SQL for retrieve product count
     *
     * @return string
     */
    public function getSelectCountSql()
    {
        $select = parent::getSelectCountSql();
        $select->reset(\Zend_Db_Select::COLUMNS)
            ->columns('COUNT(e.entity_id)')
            ->reset(\Zend_Db_Select::HAVING);

        return $select;
    }

    /**
     * Set order to attribute
     *
     * @param string $attribute
     * @param string $dir
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function setOrder($attribute, $dir = 'DESC')
    {
        switch($attribute) {
            case 'rt.review_id':
            case 'rt.created_at':
            case 'rt.status_id':
            case 'rdt.title':
            case 'rdt.nickname':
            case 'rdt.detail':
                $this->getSelect()->order($attribute . ' ' . $dir);
                break;
            case 'stores':
                // No way to sort
                break;
            case 'type':
                $this->getSelect()->order('rdt.customer_id ' . $dir);
                break;
            default:
                parent::setOrder($attribute, $dir);
                break;
        }
        return $this;
    }

    /**
     * Add attribute to filter
     *
     * @param \Magento\Eav\Model\Entity\Attribute\AbstractAttribute|string $attribute
     * @param array $condition
     * @param string $joinType
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        switch($attribute) {
            case 'rt.review_id':
            case 'rt.created_at':
            case 'rt.status_id':
            case 'rdt.title':
            case 'rdt.nickname':
            case 'rdt.detail':
                $conditionSql = $this->_getConditionSql($attribute, $condition);
                $this->getSelect()->where($conditionSql);
                break;
            case 'stores':
                $this->setStoreFilter($condition);
                break;
            case 'type':
                if ($condition == 1) {
                    $conditionParts = array(
                        $this->_getConditionSql('rdt.customer_id', array('is' => new \Zend_Db_Expr('NULL'))),
                        $this->_getConditionSql(
                            'rdt.store_id',
                            array('eq' => \Magento\Core\Model\AppInterface::ADMIN_STORE_ID)
                        )
                    );
                    $conditionSql = implode(' AND ', $conditionParts);
                } elseif ($condition == 2) {
                    $conditionSql = $this->_getConditionSql('rdt.customer_id', array('gt' => 0));
                } else {
                    $conditionParts = array(
                        $this->_getConditionSql('rdt.customer_id', array('is' => new \Zend_Db_Expr('NULL'))),
                        $this->_getConditionSql(
                            'rdt.store_id',
                            array('neq' => \Magento\Core\Model\AppInterface::ADMIN_STORE_ID)
                        )
                    );
                    $conditionSql = implode(' AND ', $conditionParts);
                }
                $this->getSelect()->where($conditionSql);
                break;

            default:
                parent::addAttributeToFilter($attribute, $condition, $joinType);
                break;
        }
        return $this;
    }

    /**
     * Retrieves column values
     *
     * @param string $colName
     * @return array
     */
    public function getColumnValues($colName)
    {
        $col = array();
        foreach ($this->getItems() as $item) {
            $col[] = $item->getData($colName);
        }
        return $col;
    }

    /**
     * Action after load
     *
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($this->_addStoreDataFlag) {
            $this->_addStoreData();
        }
        return $this;
    }

    /**
     * Add store data
     *
     */
    protected function _addStoreData()
    {
        $adapter = $this->getConnection();
        //$this->_getConditionSql('rdt.customer_id', array('null' => null));
        $reviewsIds = $this->getColumnValues('review_id');
        $storesToReviews = array();
        if (count($reviewsIds)>0) {
            $reviewIdCondition = $this->_getConditionSql('review_id', array('in' => $reviewsIds));
            $storeIdCondition = $this->_getConditionSql('store_id', array('gt' => 0));
            $select = $adapter->select()
                ->from($this->_reviewStoreTable)
                ->where($reviewIdCondition)
                ->where($storeIdCondition);
            $result = $adapter->fetchAll($select);
            foreach ($result as $row) {
                if (!isset($storesToReviews[$row['review_id']])) {
                    $storesToReviews[$row['review_id']] = array();
                }
                $storesToReviews[$row['review_id']][] = $row['store_id'];
            }
        }

        foreach ($this as $item) {
            if (isset($storesToReviews[$item->getReviewId()])) {
                $item->setData('stores', $storesToReviews[$item->getReviewId()]);
            } else {
                $item->setData('stores', array());
            }

        }
    }

    /**
     * Redeclare parent method for store filters applying
     *
     * @return \Magento\Review\Model\Resource\Review\Product\Collection
     */
    protected function _beforeLoad()
    {
        parent::_beforeLoad();
        $this->_applyStoresFilterToSelect();

        return $this;
    }
}