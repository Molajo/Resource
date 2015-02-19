<?php
/**
 * Cache
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Resource\Adapter;

/**
 * Cache for Configuration
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
abstract class Cache
{
    /**
     * Cache Trait
     *
     * @var     object  CommonApi\Cache\CacheTrait
     * @since   1.0.0
     */
    use \CommonApi\Cache\CacheTrait;

    /**
     * Cached Result
     *
     * @var    mixed
     * @since  1.0.0
     */
    protected $cached_result = null;

    /**
     * Class Constructor
     *
     * @param  callable $get_cache_callback
     * @param  callable $set_cache_callback
     * @param  callable $delete_cache_callback
     *
     * @since  1.0
     */
    public function __construct(
        $get_cache_callback = null,
        $set_cache_callback = null,
        $delete_cache_callback = null
    ) {
        $this->get_cache_callback    = $get_cache_callback;
        $this->set_cache_callback    = $set_cache_callback;
        $this->delete_cache_callback = $delete_cache_callback;
        $this->cache_type            = 'Cacheconfiguration';
    }

    /**
     * Get Cache Item if it exists
     *
     * @param   string $key
     *
     * @return  mixed
     * @since   1.0.0
     */
    protected function getConfigurationCache($key)
    {
        if ($this->useConfigurationCache() === false) {
            return null;
        }

        $cache_item = $this->getCache(md5($key));

        if ($cache_item->isHit() === true) {
            $this->cached_result = $cache_item->getValue();
            return true;
        }

        $this->cached_result = null;

        return false;
    }

    /**
     * Set Cache if it is to be used for Model Registry
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setConfigurationCache($key, $value)
    {
        if ($this->useConfigurationCache() === false) {
            return $this;
        }

        $this->setCache(md5($key), $value);

        return $this;
    }

    /**
     * Delete Cache for a specific item or all of this type
     *
     * @param   string $key
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function deleteConfigurationCache($key = null)
    {
        if ($key === null) {
            return $this->clearCache();
        }

        $this->deleteCache(md5($key));

        return $this;
    }

    /**
     * Determine if Cache should be used
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function useConfigurationCache()
    {
        return $this->useCache();
    }
}
