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

namespace Henhed\Piwik\Controller\Adminhtml\Report;

/**
 * Piwik dashboard controller
 */
class Dashboard extends \Magento\Backend\App\Action
{

    /**
     * Result page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory $_resultPageFactory
     */
    protected $_resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        /* @var $resultPage \Magento\Backend\Model\View\Result\Page */
        $resultPage->setActiveMenu('Henhed_Piwik::piwik_dashboard')
            ->addBreadcrumb(__('Reports'), __('Reports'))
            ->addBreadcrumb(__('Piwik'), __('Piwik'));
        $resultPage->getConfig()->getTitle()->prepend(__('Dashboard'));
        $titleBlock = $resultPage->getLayout()->getBlock('page.title');
        if ($titleBlock) {
            $titleBlock->setPageTitle(__('Piwik Dashboard'));
        }
        return $resultPage;
    }

    /**
     * Check whether ACL allows current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Henhed_Piwik::piwik');
    }
}
