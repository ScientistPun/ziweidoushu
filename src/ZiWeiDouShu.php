<?php
namespace scientistpun\ziwei;

use com\nlf\calendar\Lunar;
use com\nlf\calendar\LunarTime;
use com\nlf\calendar\LunarYear;
use com\nlf\calendar\Solar;
use scientistpun\ziwei\util\DaXian;
use scientistpun\ziwei\util\MingGong;
use scientistpun\ziwei\util\SiHua;
use scientistpun\ziwei\util\Stars;
use scientistpun\ziwei\util\WuXing;
use scientistpun\ziwei\util\Utils;

/**
 * 紫微排盘
 * @author scientist pun
 * @desc 紫微斗数九宫
 */
class ZiWeiDouShu {
    // 十二宫名称
    private const PLACE_NAME = ["命宫", "兄弟宫", "夫妻宫", "子女宫", "财帛宫", "疾厄宫", "迁移宫", "仆役宫", "官禄宫", "田宅宫", "福德宫", "父母宫"];

    private Lunar $lunar;
    private LunarTime $lunarTime;

    // true 男 false 女
    private bool $gender;
    // true 阳 false 阴
    private bool $yinYang;

    // 宫位起始位置
    private MingGong $mingGong;
    // 排好序的宫位
    private array $twelvePlace;

    // 立身宫
    private string $shenPlace;
    private int $shenPlacePos;
    // 来因宫
    private string $laiYin;
    private int $laiYinPos;
    // 宫干
    private array $gongGan;
    // 五行
    private WuXing $wuXing;
    private string $naYin;

    // 紫微星曜
    private Stars $stars;

    // 大限
    private DaXian $daXian;

    // 四化
    private SiHua $siHua;

    private function __construct(int $lunarYear, int $lunarMonth, int $lunarDay, int $hour, bool $gender) {
        $this->gender = $gender;
        $lunar = Lunar::fromYmdHms($lunarYear, $lunarMonth, $lunarDay, $hour, 1, 0);
        $lunarTime = $lunar->getTime();
        

        $this->lunar = $lunar;
        $this->lunarTime = $lunarTime;

        $this->setGongGan();
        $this->calculateMingPlace();
        $this->calculateYinYang();
        $this->calculateShenPlace();
        $this->calculateLaiYin();
        $this->setWuXing();
        $this->setStars();
        $this->setDaXian();
        $this->setSiHua();
    } 

    public static function fromYmdH(int $year, int $month, int $day, int $hour, bool $gender = true) {
        $solar = Solar::fromYmdHms($year, $month, $day, $hour, 1, 0);
        $lunar = $solar->getLunar();
        return new ZiWeiDouShu($lunar->getYear(), $lunar->getMonth(), $lunar->getDay(), $hour, $gender);
    }

    public static function fromTimeStamp(int $timeStamp, bool $gender = true) {
        list($year, $month, $day, $hour) = explode('-', date('Y-m-d-H', $timeStamp));
        return self::fromYmdH($year, $month, $day, $hour, $gender);
    }

    /**
     * 定阴阳
     */
    private function calculateYinYang() {
        $this->yinYang = $this->lunar->getYearGanIndex() % 2 == 0;
    }

    /**
     * 安十二宫宫干
     */
    private const GONG_GAN_PLACE = ['甲'=>'丙', '乙'=>'戊', '丙'=>'庚', '丁'=>'壬', '壬'=>'壬', '戊'=>'甲', '己'=>'丙', '庚'=>'戊', '辛'=>'庚', '癸'=>'甲'];
    private function setGongGan() {
        $startGan = self::GONG_GAN_PLACE[$this->lunar->getYearGan()];
        $startPos = Utils::getIndexByGan($startGan);

        $tianGan = [];
        for ($i=0; $i < 12; $i++) { 
            $tianGan[($i+2) % 12] = Utils::getGanByIndex(($startPos+$i) % 10);
        }
        $this->gongGan = $tianGan;
    }

    /**
     * 起命盘
     */
    private function calculateMingPlace() {
        $mingGongPos = abs($this->lunar->getMonthZhiIndex() - $this->lunarTime->getZhiIndex());
        $this->twelvePlace = $this->calculatePlace($mingGongPos);
        $this->mingGong = MingGong::from($mingGongPos, $this->gongGan[$mingGongPos], Utils::getZhiByIndex($mingGongPos));
    }

    /**
     * 计算宫位
     */
    private function calculatePlace($pos) {
        // 宫位
        $places = [];
        $idx = 0;
        for ($i=$pos; $i >= 0; $i--,$idx++) { 
            $places[$i] = self::PLACE_NAME[$idx];
        }
        for ($i=11; $i > $pos; $i--,$idx++) { 
            $places[$i] = self::PLACE_NAME[$idx];
        }
        return $places;
    }

    /**
     * 立身宫
     */
    private const SHEN_PLACE = ["命宫", "福德宫", "官禄宫", "迁移宫", "财帛宫", "夫妻宫"];
    private function calculateShenPlace() {
        $this->shenPlace = self::SHEN_PLACE[$this->lunarTime->getZhiIndex() % 6];
        $this->shenPlacePos = array_search($this->shenPlace, $this->twelvePlace);
    }

    /**
     * 来因宫
     */
    private const LAIYIN_PLACE = [ '甲'=>'戌', '乙'=>'酉', '丙'=>'申', '丁'=>'未', '戊'=>'午', '己'=>'巳', '庚'=>'辰', '辛'=>'卯', '壬'=>'寅', '癸' => '亥'];
    private function calculateLaiYin() {
        $this->laiYin = self::LAIYIN_PLACE[$this->lunar->getYearGan()];
        $this->laiYinPos = Utils::getIndexByZhi($this->laiYin);
    }

    /**
     * 定五行局
     */
    private function setWuXing() {
        $this->wuXing = WuXing::fromGanZhi($this->mingGong->getGanZhi());
    }

    /**
     * 定星曜
     */
    private function setStars() {
        $this->stars = Stars::from($this->lunar, $this->wuXing, $this->mingGong);
    }

    /**
     * 排大限
     */
    private function setDaXian() {
        $this->daXian = DaXian::from($this->yinYang, $this->gender, $this->lunar, $this->wuXing, $this->mingGong, $this->gongGan);
    }

    /**
     * 计算四化
     */
    private function setSiHua() {
        $this->siHua = SiHua::from($this->lunar, $this->stars);
    }

    /**
     * 个人信息
     */
    public function getPersonal() {
        $bazi = $this->lunar->getBaZi();
        $bazi[3] = $this->lunarTime->getGanZhi();

        return [
            'yinYang'   => $this->yinYang,
            'gender'    => $this->gender,
            'bazi'      => $bazi,
            'solar'     => $this->lunar->getSolar()->toString() . ' ' . $this->lunar->getSolar()->getHour() . ':00',
            'xingZuo'   => $this->lunar->getSolar()->getXingZuo(),
            'lunar'     => $this->lunar->toString() . ' ' . $this->lunar->getTime()->getZhi() . '时',
            'wuXing'    => $this->wuXing->getData(),
            'daXianRange'    => $this->daXian->getRange(),
        ];
    }

    /**
     * 获取十二宫信息 - 排盘
     */
    public function getTwelvePlace() {
        $stars = $this->stars->getFormatAllStars();

        $daXianRange = $this->daXian->getRange();

        $twelvePlace = [];
        for ($i=0; $i < 12; $i++) {
            $twelvePlace[] = [
                'name'     => $this->twelvePlace[$i],
                'tianGan'   => $this->gongGan[$i],
                'diZhi'     => Utils::getZhiByIndex($i),
                'stars'    => [
                    'masterStars' => $stars['masterStars'][$i], 
                    'luckyStars' => $stars['luckyStars'][$i],
                    'unluckyStars' => $stars['unluckyStars'][$i],
                    'otherStars' => $stars['otherStars'][$i]
                ],
                'daXian' => $daXianRange[$i],
            ];
        }

        return $twelvePlace;
    }

    /**
     * 获取本命盘信息
     */
    public function getMingPan() {
        return [
            'shenPlaceZhi'  => Utils::getZhiByIndex($this->shenPlacePos),
            'laiYinZhi'     => Utils::getZhiByIndex($this->laiYinPos),
            'mingGong' => [
                'pos' => $this->mingGong->getPos(),
                'gan' => $this->mingGong->getGan(),
                'zhi' => $this->mingGong->getZhi(),
            ],
            'siHua'     => $this->siHua->getBirth(),
        ];
    }


    /**
     * 大限盘
     */
    public function getDaXianPan(int $daXianPos = 0) {
        $places = $this->calculatePlace($daXianPos);
        $year = $this->daXian->getYearByPos($daXianPos);
        
        return [
            'places'    => $places,
            'mingGong'  => [
                'pos'   => $daXianPos,
                'gan'   => $this->gongGan[$daXianPos],
                'zhi'   => Utils::getZhiByIndex($daXianPos),
            ],
            'siHua'     => $this->siHua->calculate($this->gongGan[$daXianPos])
        ];
    }

    /**
     * 流年盘
     */
    public function getLiuNianPan(int $year = 0) {
        $lunarYear = LunarYear::fromYear($year);
        $pos = $lunarYear->getZhiIndex();
        $places = $this->calculatePlace($pos);
        
        return [
            'places'    => $places,
            'mingGong'  => [
                'pos'   => $pos,
                'gan'   => $this->gongGan[$pos],
                'zhi'   => Utils::getZhiByIndex($pos),
            ],
            'siHua'     => $this->siHua->getLiuYear($year)
        ];

    }
    
}