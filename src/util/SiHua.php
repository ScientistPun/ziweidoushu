<?php
namespace scientistpun\ziwei\util;

use com\nlf\calendar\Lunar;
use com\nlf\calendar\LunarYear;

class SiHua {
    private Stars $stars;
    private array $birth;

    // 四化 禄 权 科 忌
    private const SI_HUA = [
        '甲' => ['廉贞', '破军', '武曲', '太阳'],
        '乙' => ['天机', '天梁', '紫微', '太阴'],
        '丙' => ['天同', '天机', '文昌', '廉贞'],
        '丁' => ['太阴', '天同', '天机', '巨门'],
        '戊' => ['贪狼', '太阴', '右弼', '天机'],
        '己' => ['武曲', '贪狼', '天梁', '文曲'],
        '庚' => ['太阳', '武曲', '天同', '天相'],
        '辛' => ['巨门', '太阳', '文曲', '文昌'],
        '壬' => ['天梁', '紫微', '左辅', '武曲'],
        '癸' => ['破军', '巨门', '太阴', '贪狼'],
    ];
    

    private function __construct(Lunar $lunar, Stars $stars) {
        $this->stars = $stars;
        $this->birth = $this->calculate($lunar->getYearGan());
    }

    public static function from(Lunar $lunar, Stars $stars) {
        return new SiHua($lunar, $stars);
    }

    // 计算四化位置
    public function calculate($gan) {
        $siHua = self::SI_HUA[$gan];
        $starsPos = $this->stars->getAllStarsPos();

        return [
            'lu' => ['star'=>$siHua[0], 'pos'=>$starsPos[$siHua[0]], 'title'=>'化禄'],
            'quan' => ['star'=>$siHua[1], 'pos'=>$starsPos[$siHua[1]], 'title'=>'化权'],
            'ke' => ['star'=>$siHua[2], 'pos'=>$starsPos[$siHua[2]], 'title'=>'化科'],
            'ji' => ['star'=>$siHua[3], 'pos'=>$starsPos[$siHua[3]], 'title'=>'化忌'],
        ];
    }

    /**
     * 获取本命四化
     */
    public function getBirth() {
        return $this->birth;
    }

    /**
     * 获取流年四化
     */
    public function getLiuYear(int $year = 0) {
        $year = $year ?: intval(date('Y'));
        $lunarYear = LunarYear::fromYear($year);
        return $this->calculate($lunarYear->getGan());
    }


}