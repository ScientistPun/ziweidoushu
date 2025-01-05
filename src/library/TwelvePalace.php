<?php
namespace scientistpun\ziwei\library;

use scientistpun\ziwei\util\Utils;

/** 
 * 宫位 -- 十二宫
 */
class TwelvePalace {
    protected string $name;
    protected int $pos;
    protected string $gan;
    protected string $zhi;


    private function __construct(string $name, int $pos, string $gan, string $zhi = null) {
        $this->name = $name;
        $this->pos = $pos;
        $this->gan = $gan;
        $this->zhi = $zhi ?? Utils::getZhiByIndex($pos);
    }

    public static function build(string $name, int $pos, string $gan, string $zhi = null) {
        return new TwelvePalace($name, $pos, $gan, $zhi);
    }

    public function getName() {
        return $this->name;
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
            'name' => $this->name,
            'pos' => $this->pos,
            'gan' => $this->gan,
            'zhi' => $this->zhi,
        ];
    }
}