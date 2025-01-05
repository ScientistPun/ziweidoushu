<?php
namespace scientistpun\ziwei\library;

use com\nlf\calendar\Lunar;
use scientistpun\ziwei\util\Utils;

/** 
 * 大限
 */
class DaXian {
    private Lunar $lunar;
    private SelfPalace $selfPalace;
    private WuXing $wuXing;
    private bool $yinYang;
    private bool $gender;
    private array $palaceGan;

    private array $range;

    
    private function __construct(bool $yinYang, bool $gender, Lunar $lunar, WuXing $wuXing, SelfPalace $selfPalace, array $palaceGan)
    {
        $this->lunar = $lunar;
        $this->yinYang = $yinYang;   
        $this->gender = $gender;   
        $this->wuXing = $wuXing;   
        $this->selfPalace = $selfPalace;
        $this->palaceGan = $palaceGan;

        $this->calculateRange();
    }

    public static function from (bool $yinYang, bool $gender, Lunar $lunar, WuXing $wuXing, SelfPalace $selfPalace, array $palaceGan) {
        return new DaXian( $yinYang, $gender, $lunar, $wuXing, $selfPalace, $palaceGan);
    }

    private function calculateRange() {
        $age = $this->wuXing->getJuShu();
        $agePlace = [];
        for ($i=0; $i < 12; $i++) {
            $years = [];
            for ($j=0; $j < 10; $j++) { 
                $years[] = $this->lunar->getYear() + $age + $j - 1;
            }
            $agePlace[] = ['begin'=>$age, 'end'=>$age+9, 'years' => $years];
            $age += 10;
        }
        
        // 阳男阴女 -> 顺时针  阴男阳女 -> 逆时针 
        $clockwise = ($this->yinYang && $this->gender) || (!$this->yinYang && !$this->gender);
        $range = [];
        if ($clockwise) {
            for ($i=0; $i < 12; $i++) { 
                $range[($i + $this->selfPalace->getPos()) % 12] = array_merge($agePlace[$i], ['gan'=>$this->palaceGan[$i], 'zhi'=>Utils::getIndexByZhi($i)]);
            }
        } else {
            $idx = 0;
            for ($i=$this->selfPalace->getPos(); $i >= 0; $i--,$idx++) { 
                $range[$i] = array_merge($agePlace[$idx], ['gan'=>$this->palaceGan[$idx], 'zhi'=>Utils::getIndexByZhi($idx)]);
            }
            for ($i=11; $i > $this->selfPalace->getPos(); $i--,$idx++) { 
                $range[$i] = array_merge($agePlace[$idx], ['gan'=>$this->palaceGan[$idx], 'zhi'=>Utils::getIndexByZhi($idx)]);
            }
        }

        $this->range = $range;
    }

    public function locateByYear(int $year = 0) {
        $year = $year > $this->lunar->getYear() ? $year:intval($year);
        $age =  $year - $this->lunar->getYear() + 1;

        foreach ($this->range as $place) {
            if ($age < $place['begin']) continue;
            if ($age > $place['end']) continue;
            return $place;
        }

        return null;
    }

    public function getRange() {
        return $this->range;
    }
}