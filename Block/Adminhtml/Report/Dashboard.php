<?php
/**
 * Copyright 2016 Henrik Hedelund
 *
 * This file is part of Henhed_Piwik.
 *
 * Henhed_Piwik is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Henhed_Piwik is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Henhed_Piwik.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Henhed\Piwik\Block\Adminhtml\Report;

/**
 * Piwik report dashboard block
 *
 */
class Dashboard extends \Magento\Backend\Block\Template
{

    /**
     * Piwik Reporting API helper
     *
     * @var \Henhed\Piwik\Helper\Reporting $_reportingHelper
     */
    protected $_reportingHelper;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Henhed\Piwik\Helper\Reporting $reportingHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Henhed\Piwik\Helper\Reporting $reportingHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_reportingHelper = $reportingHelper;
    }

    /**
     * Get current store ID from store switcher
     *
     * @return int
     */
    public function getStoreId()
    {
        if (!$this->hasData('store_id')) {
            $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
            $switcherBlock = $this->getLayout()->getBlock('store.switcher');
            /* @var $switcherBlock \Magento\Backend\Block\Store\Switcher */
            if ($switcherBlock) {
                $storeId = $switcherBlock->getStoreId();
            }
            $this->setData('store_id', $storeId);
        }
        return $this->getData('store_id');
    }

    /**
     * Get Piwik dashboard iframe URL
     *
     * @return string
     */
    public function getIframeUrl()
    {
        return $this->_reportingHelper->getApiUrl([
            'action'            => 'iframe',
            'actionToWidgetize' => 'index',
            'module'            => 'Widgetize',
            'moduleToWidgetize' => 'Dashboard',
            'disableLink'       => 1
        ], $this->getStoreId());
    }
}
