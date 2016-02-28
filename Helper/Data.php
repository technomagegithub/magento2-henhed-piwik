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

namespace Henhed\Piwik\Helper;

use Magento\Store\Model\Store;

/**
 * Piwik data helper
 *
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * System config XML paths
     */
    const XML_PATH_ENABLED = 'piwik/tracking/enabled';
    const XML_PATH_HOSTNAME = 'piwik/tracking/hostname';
    const XML_PATH_SECURE_HOSTNAME = 'piwik/tracking/secure_hostname';
    const XML_PATH_SITE_ID = 'piwik/tracking/site_id';
    const XML_PATH_LINK_ENABLED = 'piwik/tracking/link_enabled';
    const XML_PATH_LINK_DELAY = 'piwik/tracking/link_delay';
    const XML_PATH_AUTH_TOKEN = 'piwik/reporting/auth_token';
    const XML_PATH_REPORTING_USE_SECURE = 'piwik/reporting/use_secure';
    const XML_PATH_REPORTING_PERIOD = 'piwik/reporting/period';
    const XML_PATH_REPORTING_DATE = 'piwik/reporting/date';

    /**
     * Check if Piwik is enabled
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isTrackingEnabled($store = null)
    {
        $hostname = $this->getHostname($store);
        $siteId = $this->getSiteId($store);
        return $hostname && $siteId && $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Piwik hostname
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getHostname($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_HOSTNAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Piwik hostname for secure connections
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getSecureHostname($store = null)
    {
        $hostname = trim($this->scopeConfig->getValue(
            self::XML_PATH_SECURE_HOSTNAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        ));
        return $hostname ?: $this->getHostname($store);
    }

    /**
     * Retrieve Piwik base URL
     *
     * @param null|string|bool|int|Store $store
     * @param null|bool $secure
     * @return string
     */
    public function getBaseUrl($store = null, $secure = null)
    {
        if (is_null($secure)) {
            $secure = $this->_request->isSecure();
        }
        return $secure
            ? 'https://' . rtrim($this->getSecureHostname($store), '/') . '/'
            : 'http://' . rtrim($this->getHostname($store), '/') . '/';
    }

    /**
     * Retrieve Piwik site ID
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getSiteId($store = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_SITE_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Piwik authorization token
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getAuthToken($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_AUTH_TOKEN,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Check if Piwik link tracking is enabled
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isLinkTrackingEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_LINK_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        ) && $this->isTrackingEnabled($store);
    }

    /**
     * Retrieve Piwik link tracking delay in milliseconds
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getLinkTrackingDelay($store = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_LINK_DELAY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Check whether to use HTTPS for Reporting API requests
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function useSecureReporting($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_REPORTING_USE_SECURE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve default period for the Reporting API
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getReportingPeriod($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_REPORTING_PERIOD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve default date for the Reporting API
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getReportingDate($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_REPORTING_DATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
