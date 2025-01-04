<?php
namespace scientistpun\ziwei\util;


/** 
 * 命宫
 */
class MingGong {
    private int $pos;
    private string $gan;
    private string $zhi;

    private function __construct(int $pos, string $gan, string $zhi)
    {
        $this->pos = $pos;
        $this->gan = $gan;
        $this->zhi = $zhi;
    }

    public static function from(int $pos, string $gan, string $zhi) {
        return new MingGong( $pos, $gan, $zhi);
    }

    public function getPos() {
        return $this->pos;
    }

    public function getGan() {
        return $this->gan;
    }

    public function getZhi() {
        return $this->zhi;
    }

    public function getGanZhi() {
        return $this->gan.$this->zhi;
    }
}