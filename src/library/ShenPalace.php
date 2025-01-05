<?php
namespace scientistpun\ziwei\library;


/** 
 * 身宫
 */
class ShenPalace extends Palace {
    private const SHEN_ZHU = ['子'=>'火星', '午'=>'火星', '丑'=>'天相', '未'=>'天相', '寅'=>'天梁', '申'=>'天梁', '卯'=>'天同', '酉'=>'天同', '巳'=>'天机', '亥'=>'天机', '辰'=>'文昌', '戌'=>'文昌'];
    private string $zhu;

    private function __construct(int $pos, string $gan, ?string $zhi = null) {
        $this->_init($pos, $gan, $zhi);
    }

    public static function create(int $pos, string $gan, ?string $zhi = null) {
        return new ShenPalace( $pos, $gan, $zhi);
    }

    public function setZhu(string $yearZhi) {
        $this->zhu = self::SHEN_ZHU[$yearZhi];
    }

    public function getZhu() {
        return $this->zhu;
    }
}