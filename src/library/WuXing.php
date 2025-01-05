<?php
namespace scientistpun\ziwei\library;

use com\nlf\calendar\util\LunarUtil;

/**
 * 五行
 */
class WuXing { 
    // 五行
    public const WU_XING = [
        '甲子' => '金', '甲午' => '金', '丙寅' => '火', '丙申' => '火',
        '戊辰' => '木', '戊戌' => '木', '庚午' => '土', '庚子' => '土',
        '壬申' => '金', '壬寅' => '金', '甲戌' => '火', '甲辰' => '火',
        '丙子' => '水', '丙午' => '水', '戊寅' => '土', '戊申' => '土',
        '庚辰' => '金', '庚戌' => '金', '壬午' => '木', '壬子' => '木',
        '甲申' => '水', '甲寅' => '水', '丙戌' => '土', '丙辰' => '土',
        '戊子' => '火', '戊午' => '火', '庚寅' => '木', '庚申' => '木',
        '壬辰' => '水', '壬戌' => '水', '乙丑' => '金', '乙未' => '金',
        '丁卯' => '火', '丁酉' => '火', '己巳' => '木', '己亥' => '木',
        '辛未' => '土', '辛丑' => '土', '癸酉' => '金', '癸卯' => '金',
        '乙亥' => '火', '乙巳' => '火', '丁丑' => '水', '丁未' => '水',
        '己卯' => '土', '己酉' => '土', '辛巳' => '金', '辛亥' => '金',
        '癸未' => '木', '癸丑' => '木', '乙酉' => '水', '乙卯' => '水',
        '丁亥' => '土', '丁巳' => '土', '己丑' => '火', '己未' => '火',
        '辛卯' => '木', '辛酉' => '木', '癸巳' => '水', '癸亥' => '水'
    ];
    // 局数
    public const NUMBER = [ '水' => 2, '木' => 3, '金' => 4, '土' => 5, '火' => 6 ];

    private string $wuXingJu;
    private int $juShu;
    private string $naYin;

    private function __construct(string $ganZhi) 
    {
        $this->wuXingJu = self::WU_XING[$ganZhi];
        $this->juShu = self::NUMBER[$this->wuXingJu];
        $this->naYin = LunarUtil::$NAYIN[$ganZhi];
    }

    public static function fromGanZhi(string $ganZhi) {
        return new WuXing($ganZhi);
    }

    public function getString() {
        return $this->wuXingJu;
    }

    public function getJuShu() {
        return $this->juShu;
    }

    public function getNaYin() {
        return $this->naYin;
    }

    public function getData() {
        return [
            'wuXingJu' => $this->wuXingJu,
            'juShu' => $this->juShu,
            'naYin' => $this->naYin,
        ];
    }

}