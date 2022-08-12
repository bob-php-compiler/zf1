<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Filter implements Zend_Filter_Interface
{

    const CHAIN_APPEND  = 'append';
    const CHAIN_PREPEND = 'prepend';

    /**
     * Filter chain
     *
     * @var array
     */
    protected $_filters = array();

    /**
     * Default Namespaces
     *
     * @var array
     */
    protected static $_defaultNamespaces = array();

    /**
     * Adds a filter to the chain
     *
     * @param  Zend_Filter_Interface $filter
     * @param  string $placement
     * @return Zend_Filter Provides a fluent interface
     */
    public function addFilter(Zend_Filter_Interface $filter, $placement = self::CHAIN_APPEND)
    {
        if ($placement == self::CHAIN_PREPEND) {
            array_unshift($this->_filters, $filter);
        } else {
            $this->_filters[] = $filter;
        }
        return $this;
    }

    /**
     * Add a filter to the end of the chain
     *
     * @param  Zend_Filter_Interface $filter
     * @return Zend_Filter Provides a fluent interface
     */
    public function appendFilter(Zend_Filter_Interface $filter)
    {
        return $this->addFilter($filter, self::CHAIN_APPEND);
    }

    /**
     * Add a filter to the start of the chain
     *
     * @param  Zend_Filter_Interface $filter
     * @return Zend_Filter Provides a fluent interface
     */
    public function prependFilter(Zend_Filter_Interface $filter)
    {
        return $this->addFilter($filter, self::CHAIN_PREPEND);
    }

    /**
     * Get all the filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Returns $value filtered through each filter in the chain
     *
     * Filters are run in the order in which they were added to the chain (FIFO)
     *
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        $valueFiltered = $value;
        foreach ($this->_filters as $filter) {
            $valueFiltered = $filter->filter($valueFiltered);
        }
        return $valueFiltered;
    }

    /**
     * Returns the set default namespaces
     *
     * @return array
     */
    public static function getDefaultNamespaces()
    {
        return self::$_defaultNamespaces;
    }

    /**
     * Sets new default namespaces
     *
     * @param array|string $namespace
     * @return null
     */
    public static function setDefaultNamespaces($namespace)
    {
        if (!is_array($namespace)) {
            $namespace = array((string) $namespace);
        }

        self::$_defaultNamespaces = $namespace;
    }

    /**
     * Adds a new default namespace
     *
     * @param array|string $namespace
     * @return null
     */
    public static function addDefaultNamespaces($namespace)
    {
        if (!is_array($namespace)) {
            $namespace = array((string) $namespace);
        }

        self::$_defaultNamespaces = array_unique(array_merge(self::$_defaultNamespaces, $namespace));
    }

    /**
     * Returns true when defaultNamespaces are set
     *
     * @return boolean
     */
    public static function hasDefaultNamespaces()
    {
        return (!empty(self::$_defaultNamespaces));
    }
}
