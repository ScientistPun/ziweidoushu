<?php
namespace scientistpun\ziwei\library;

use scientistpun\ziwei\util\Utils;

/** 
 * 宫
 */
class Palace {
    protected int $pos;
    protected string $gan;
    protected string $zhi;

    private function __construct(int $pos, string $gan, string $zhi = null) {
        $this->_init($pos, $gan, $zhi);
    }

    protected function _init(int $pos, string $gan, string $zhi = null) {
        $this->pos = $pos;
        $this->gan = $gan;
        $this->zhi = $zhi ?? Utils::getZhiByIndex($pos);
    }

    public static function create(int $pos, string $gan, string $zhi = null) {
        return new Palace( $pos, $gan, $zhi);
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

    public function getFullData() {
        return [
            'pos' => $this->pos,
            'gan' => $this->gan,
            'zhi' => $this->zhi,
        ];
    }
}