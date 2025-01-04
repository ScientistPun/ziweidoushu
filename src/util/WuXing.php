<?php
namespace scientistpun\ziwei\util;

use com\nlf\calendar\util\LunarUtil;

/**
 * 五行
 */
class WuXing { 
    // 五行
    public const WU_XING = [
        '甲子' => '金四局', '甲午' => '金四局', '丙寅' => '火六局', '丙申' => '火六局',
        '戊辰' => '木三局', '戊戌' => '木三局', '庚午' => '土五局', '庚子' => '土五局',
        '壬申' => '金四局', '壬寅' => '金四局', '甲戌' => '火六局', '甲辰' => '火六局',
        '丙子' => '水二局', '丙午' => '水二局', '戊寅' => '土五局', '戊申' => '土五局',
        '庚辰' => '金四局', '庚戌' => '金四局', '壬午' => '木三局', '壬子' => '木三局',
        '甲申' => '水二局', '甲寅' => '水二局', '丙戌' => '土五局', '丙辰' => '土五局',
        '戊子' => '火六局', '戊午' => '火六局', '庚寅' => '木三局', '庚申' => '木三局',
        '壬辰' => '水二局', '壬戌' => '水二局', '乙丑' => '金四局', '乙未' => '金四局',
        '丁卯' => '火六局', '丁酉' => '火六局', '己巳' => '木三局', '己亥' => '木三局',
        '辛未' => '土五局', '辛丑' => '土五局', '癸酉' => '金四局', '癸卯' => '金四局',
        '乙亥' => '火六局', '乙巳' => '火六局', '丁丑' => '水二局', '丁未' => '水二局',
        '己卯' => '土五局', '己酉' => '土五局', '辛巳' => '金四局', '辛亥' => '金四局',
        '癸未' => '木三局', '癸丑' => '木三局', '乙酉' => '水二局', '乙卯' => '水二局',
        '丁亥' => '土五局', '丁巳' => '土五局', '己丑' => '火六局', '己未' => '火六局',
        '辛卯' => '木三局', '辛酉' => '木三局', '癸巳' => '水二局', '癸亥' => '水二局'
    ];
    // 局数
    public const NUMBER = [ '水二局' => 2, '木三局' => 3, '金四局' => 4, '土五局' => 5, '火六局' => 6 ];

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