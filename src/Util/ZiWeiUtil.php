<?php
namespace scientistpun\ziwei\util;

use com\nlf\calendar\util\LunarUtil;

class ZiWeiUtil {

    public static function getIndexByZhi($zhi) {
        return array_search($zhi, LunarUtil::$ZHI) - 1;
    }

    public static function getZhiByIndex($idx) {
        return LunarUtil::$ZHI[$idx + 1];
    }

    public static function getIndexByGan($gan) {
        return array_search($gan, LunarUtil::$GAN) - 1;
    }

    public static function getGanByIndex($idx) {
        return LunarUtil::$GAN[$idx + 1];
    }
}