<?php
namespace scientistpun\ziwei\library;

use scientistpun\ziwei\util\Utils;

class Star {

    // 亮度
    private const BRIGHT_TITLE = [7=>'庙', 6=>'旺', 5=>'得', 4=>'利', 3=>'平', 2=>'不得地', 1=>'陷', 0=>''];
    private const BRIGHT_SUBTITLE = [7=>'庙', 6=>'旺', 5=>'得', 4=>'利', 3=>'平', 2=>'不', 1=>'陷', 0=>''];
    // 星曜亮度
    private const BRIGHT_IN_PLACE = [
        '紫微' => ['寅'=>'旺', '卯'=>'旺', '辰'=>'得', '巳'=>'旺', '午'=>'庙', '未'=>'庙', '申'=>'旺', '酉'=>'旺', '戌'=>'得', '亥'=>'旺', '子'=>'平', '丑'=>'庙'],
        '天机' => ['寅'=>'得', '卯'=>'旺', '辰'=>'利', '巳'=>'平', '午'=>'庙', '未'=>'陷', '申'=>'得', '酉'=>'旺', '戌'=>'利', '亥'=>'平', '子'=>'庙', '丑'=>'陷'],
        '太阳' => ['寅'=>'旺', '卯'=>'庙', '辰'=>'旺', '巳'=>'旺', '午'=>'旺', '未'=>'得', '申'=>'得', '酉'=>'陷', '戌'=>'不', '亥'=>'陷', '子'=>'陷', '丑'=>'不'],
        '武曲' => ['寅'=>'得', '卯'=>'利', '辰'=>'庙', '巳'=>'平', '午'=>'旺', '未'=>'庙', '申'=>'得', '酉'=>'利', '戌'=>'庙', '亥'=>'平', '子'=>'旺', '丑'=>'庙'],
        '天同' => ['寅'=>'利', '卯'=>'平', '辰'=>'平', '巳'=>'庙', '午'=>'陷', '未'=>'不', '申'=>'旺', '酉'=>'平', '戌'=>'平', '亥'=>'庙', '子'=>'旺', '丑'=>'不'],
        '廉贞' => ['寅'=>'庙', '卯'=>'平', '辰'=>'利', '巳'=>'陷', '午'=>'平', '未'=>'利', '申'=>'庙', '酉'=>'平', '戌'=>'利', '亥'=>'陷', '子'=>'平', '丑'=>'利'],
        '天府' => ['寅'=>'庙', '卯'=>'得', '辰'=>'庙', '巳'=>'得', '午'=>'旺', '未'=>'庙', '申'=>'得', '酉'=>'旺', '戌'=>'庙', '亥'=>'得', '子'=>'庙', '丑'=>'庙'],
        '太阴' => ['寅'=>'旺', '卯'=>'陷', '辰'=>'陷', '巳'=>'陷', '午'=>'不', '未'=>'不', '申'=>'利', '酉'=>'不', '戌'=>'旺', '亥'=>'庙', '子'=>'庙', '丑'=>'庙'],
        '贪狼' => ['寅'=>'平', '卯'=>'利', '辰'=>'庙', '巳'=>'陷', '午'=>'旺', '未'=>'庙', '申'=>'平', '酉'=>'利', '戌'=>'庙', '亥'=>'陷', '子'=>'旺', '丑'=>'庙'],
        '巨门' => ['寅'=>'庙', '卯'=>'庙', '辰'=>'陷', '巳'=>'旺', '午'=>'旺', '未'=>'不', '申'=>'庙', '酉'=>'庙', '戌'=>'陷', '亥'=>'旺', '子'=>'旺', '丑'=>'不'],
        '天相' => ['寅'=>'庙', '卯'=>'陷', '辰'=>'得', '巳'=>'得', '午'=>'庙', '未'=>'得', '申'=>'庙', '酉'=>'陷', '戌'=>'得', '亥'=>'得', '子'=>'庙', '丑'=>'庙'],
        '天梁' => ['寅'=>'庙', '卯'=>'庙', '辰'=>'庙', '巳'=>'陷', '午'=>'庙', '未'=>'旺', '申'=>'陷', '酉'=>'得', '戌'=>'庙', '亥'=>'陷', '子'=>'庙', '丑'=>'旺'],
        '七杀' => ['寅'=>'庙', '卯'=>'旺', '辰'=>'庙', '巳'=>'平', '午'=>'旺', '未'=>'庙', '申'=>'庙', '酉'=>'庙', '戌'=>'庙', '亥'=>'平', '子'=>'旺', '丑'=>'庙'],
        '破军' => ['寅'=>'得', '卯'=>'陷', '辰'=>'旺', '巳'=>'平', '午'=>'庙', '未'=>'旺', '申'=>'得', '酉'=>'陷', '戌'=>'旺', '亥'=>'平', '子'=>'庙', '丑'=>'旺'],
        '文昌' => ['寅'=>'陷', '卯'=>'利', '辰'=>'得', '巳'=>'庙', '午'=>'陷', '未'=>'利', '申'=>'得', '酉'=>'庙', '戌'=>'陷', '亥'=>'利', '子'=>'得', '丑'=>'庙'],
        '文曲' => ['寅'=>'平', '卯'=>'旺', '辰'=>'得', '巳'=>'庙', '午'=>'陷', '未'=>'旺', '申'=>'得', '酉'=>'庙', '戌'=>'陷', '亥'=>'旺', '子'=>'得', '丑'=>'庙'],
        '火星' => ['寅'=>'庙', '卯'=>'利', '辰'=>'陷', '巳'=>'得', '午'=>'庙', '未'=>'利', '申'=>'陷', '酉'=>'得', '戌'=>'庙', '亥'=>'利', '子'=>'陷', '丑'=>'得'],
        '铃星' => ['寅'=>'庙', '卯'=>'利', '辰'=>'陷', '巳'=>'得', '午'=>'庙', '未'=>'利', '申'=>'陷', '酉'=>'得', '戌'=>'庙', '亥'=>'利', '子'=>'陷', '丑'=>'得'],
        '擎羊' => ['寅'=>'', '卯'=>'陷', '辰'=>'庙', '巳'=>'', '午'=>'陷', '未'=>'庙', '申'=>'', '酉'=>'陷', '戌'=>'庙', '亥'=>'', '子'=>'陷', '丑'=>'庙'],
        '陀罗' => ['寅'=>'陷', '卯'=>'', '辰'=>'庙', '巳'=>'陷', '午'=>'', '未'=>'庙', '申'=>'陷', '酉'=>'', '戌'=>'庙', '亥'=>'陷', '子'=>'', '丑'=>'庙']
    ];

    private string $name;
    private int $pos;
    private string $diZhi;
    private int $bright;
    private string $brightTitle;
    private string $brightSubTitle;

    private string $type;
    public const TYPE_MASTER = 'masterStars';
    public const TYPE_LUCKY = 'luckyStars';
    public const TYPE_UNLUCKY = 'unluckyStars';
    public const TYPE_OTHER = 'otherStars';
    public const TYPE_BO_SHI = 'boShiStars';
    public const TYPE_CHANG_SHENG = 'changShengStars';
    public const TYPE_JIANG_QIAN = 'jiangQianStars';
    public const TYPE_SUI_QIAN = 'suiQianStars';


    private function __construct(string $name, int $pos, string $type, int $bright = 0) 
    {
        $this->name = $name;
        $this->type = $type;
        $this->pos = $pos;
        $this->diZhi = Utils::getZhiByIndex($pos);
        $this->bright = $bright;
        $this->brightTitle = self::BRIGHT_TITLE[$bright];
        $this->brightSubTitle = self::BRIGHT_SUBTITLE[$bright];
        $this->calculateBright();
    }

    public static function create(string $name, int $pos, string $type, int $bright = 0) {
        return new Star($name, $pos, $type, $bright);
    }

    private function calculateBright () {
        if ($this->bright != 0 || !isset(self::BRIGHT_IN_PLACE[$this->name])) return ;
        $placeBright = self::BRIGHT_IN_PLACE[$this->name];
        $this->setBrightFromSubTitle($placeBright[$this->diZhi]);
    }

    /**
     * 设置亮度
     */
    public function setBrightFromSubTitle(string $subTitle) {
        if (!in_array($subTitle, self::BRIGHT_SUBTITLE)) return ;

        $bright = array_search($subTitle, self::BRIGHT_SUBTITLE);
        $this->bright = $bright;
        $this->brightTitle = self::BRIGHT_TITLE[$bright];
        $this->brightSubTitle = $subTitle;
    }


    public function getName () {
        return $this->name;
    }

    /**
     * 十二宫的位置
     */
    public function getPos () {
        return $this->pos;
    }

    public function getZhi () {
        return $this->diZhi;
    }

    public function getType () {
        return $this->type;
    }

    public function getBright() {
        return $this->bright;
    }

    public function getInfo() {
        return [
            'name'          => $this->name,
            'pos'           => $this->pos,
            'bright'        => $this->bright,
            'brightTitle'   => $this->brightTitle,
            'brightSubTitle' => $this->brightSubTitle,
        ];
    }
}