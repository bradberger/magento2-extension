<?php
/**
 * StoreFront Bazaarvoice Extension for Magento
 *
 * PHP Version 5
 *
 * LICENSE: This source file is subject to commercial source code license
 * of StoreFront Consulting, Inc.
 *
 * @category  SFC
 * @package   Bazaarvoice_Ext
 * @author    Dennis Rogers <dennis@storefrontconsulting.com>
 * @copyright 2016 StoreFront Consulting, Inc
 * @license   http://www.storefrontconsulting.com/media/downloads/ExtensionLicense.pdf StoreFront Consulting Commercial License
 * @link      http://www.StoreFrontConsulting.com/bazaarvoice-extension/
 */

namespace Bazaarvoice\Connector\Ui\Component\Listing\Column;


use Bazaarvoice\Connector\Helper\Data;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class Json extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $_helper;

    /**
     * Json constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param Data $helper
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components,
        array $data,
        Data $helper
    )
    {
        $this->_helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (empty($item[$fieldName])) continue;
                $valueData = $this->_helper->jsonDecode($item[$fieldName]);
                if (is_object($valueData) == true ||
                    is_array($valueData) == true) {
                    $html = '';
                    foreach ($valueData as $key => $value) {
                        if (!is_numeric($key)) {
                            $html .= "<strong>$key:</strong> ";
                        }
                        $html .= $this->truncate($value) . '<br/>';
                    }
                    $item[$fieldName] = $html;
                }
            }
        }

        return $dataSource;
    }

    private function truncate($string)
    {
        if (strlen($string) > 45 && substr($string, 0, 4) != 'http') {
            $string = substr($string, 0, 45) . '...';
        }
        return $string;
    }

}