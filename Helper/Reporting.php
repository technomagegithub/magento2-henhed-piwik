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

/**
 * Piwik Reporting API helper
 *
 */
class Reporting
{

    /**
     * Reporting API parameter names
     */
    const PARAM_SITE_ID = 'idSite';
    const PARAM_AUTH_TOKEN = 'token_auth';
    const PARAM_DATE = 'date';
    const PARAM_PERIOD = 'period';
    const PARAM_LANGUAGE = 'language';

    /**
     * Piwik data helper
     *
     * @var \Henhed\Piwik\Helper\Data $_dataHelper
     */
    protected $_dataHelper;

    /**
     * Locale resolver
     *
     * @var \Magento\Framework\Locale\ResolverInterface $_localeResolver
     */
    protected $_localeResolver;

    /**
     * Constructor
     *
     * @param \Henhed\Piwik\Helper\Data $dataHelper
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     */
    public function __construct(
        \Henhed\Piwik\Helper\Data $dataHelper,
        \Magento\Framework\Locale\ResolverInterface $localeResolver
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_localeResolver = $localeResolver;
    }

    /**
     * Get Reporting API endpoint URL
     *
     * @param null|string|bool|int|\Magento\Store\Model\Store $store
     * @param null|bool $secure
     * @return string|false
     */
    public function getApiEndpoint($store = null, $secure = null)
    {
        if (is_null($secure)
            && $this->_dataHelper->useSecureReporting($store)
        ) {
            $secure = true;
        }
        $baseUrl = $this->_dataHelper->getBaseUrl($store, $secure);
        return !empty($baseUrl)
            ? $baseUrl . 'index.php'
            : false;
    }

    /**
     * Get default parameters for the Reporting API
     *
     * @param null|string|bool|int|\Magento\Store\Model\Store $store
     * @return array
     */
    public function getDefaultApiParams($store = null)
    {
        $data = $this->_dataHelper;
        $localeParts = explode('_', $this->_localeResolver->getLocale());
        return array_filter([
            self::PARAM_SITE_ID => $data->getSiteId($store),
            self::PARAM_AUTH_TOKEN => $data->getAuthToken($store),
            self::PARAM_DATE => $data->getReportingDate($store),
            self::PARAM_PERIOD => $data->getReportingPeriod($store),
            self::PARAM_LANGUAGE => reset($localeParts)
        ]);
    }

    /**
     * Build Piwik Reporting API URL from given parameters
     *
     * @param array $params
     * @param null|string|bool|int|\Magento\Store\Model\Store $store
     * @param null|bool $secure
     * @return string|false
     */
    public function getApiUrl(array $params = [], $store = null, $secure = null)
    {
        $endpoint = $this->getApiEndpoint($store, $secure);
        if (empty($endpoint)) {
            return false;
        }
        $query = array_merge($this->getDefaultApiParams($store), $params);
        return $endpoint . '?' . http_build_query($query);
    }
}
