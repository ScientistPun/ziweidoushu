<?php
namespace scientistpun\ziwei\library;


/** 
 * 本命宫
 */
class SelfPalace extends Palace {
    private const MING_ZHU = ['子'=>'贪狼', '丑'=>'巨门', '亥'=>'巨门', '寅'=>'禄存', '戌'=>'禄存', '卯'=>'文曲', '酉'=>'文曲', '巳'=>'武曲', '未'=>'武曲', '辰'=>'廉贞', '申'=>'廉贞', '午'=>'破军'];
    private string $zhu;

    private function __construct(int $pos, string $gan, ?string $zhi = null) {
        $this->_init($pos, $gan, $zhi);
        $this->zhu = self::MING_ZHU[$this->zhi];
    }

    public static function create(int $pos, string $gan, ?string $zhi = null) {
        return new SelfPalace( $pos, $gan, $zhi);
    }

    public function getZhu() {
        return $this->zhu;
    }
    
}