<?php

/**
 * Class provide static methods to manage tree cache
 */
class cre8PropelTreeCache {
    const CONFIG_CACHE_DIR = '/cre8_propel_category_tree_plugin';
    const CONFIG_CACHE_LIFETIME = 157680000; // 5 years
    const CONFIG_CACHE_FILE_PREFIX = 'node_';

    /**
     *
     * @param integer $nodeId
     * @param string $data
     * @return boolean
     */
    public static function set($nodeId, $data) {
        try {
            if ($data) {
                $cache = new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir') . self::CONFIG_CACHE_DIR));
                $cache->set(self::getCacheKey($nodeId), $data, self::CONFIG_CACHE_LIFETIME);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param integer $nodeId
     * @return string
     */
    public static function get($nodeId) {
        try {
            $cache = new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir') . self::CONFIG_CACHE_DIR));
            $cacheKey = self::getCacheKey($nodeId);

            if ($cache->has($cacheKey)) {
                return $cache->get($cacheKey);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     *
     * @param integer $cacheId
     * @return string
     */
    public static function getCacheKey($cacheId) {
        return self::CONFIG_CACHE_FILE_PREFIX . $cacheId;
    }

}