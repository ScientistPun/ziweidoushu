<?php
namespace scientistpun\ziwei;

use com\nlf\calendar\Lunar;
use com\nlf\calendar\LunarTime;
use com\nlf\calendar\LunarYear;
use com\nlf\calendar\Solar;
use scientistpun\ziwei\library\DaXian;
use scientistpun\ziwei\library\Palace;
use scientistpun\ziwei\library\SelfPalace;
use scientistpun\ziwei\library\ShenPalace;
use scientistpun\ziwei\library\SiHua;
use scientistpun\ziwei\library\Stars;
use scientistpun\ziwei\library\TwelvePalace;
use scientistpun\ziwei\library\WuXing;
use scientistpun\ziwei\util\Utils;

/**
 * 紫微排盘
 * @author scientist pun
 * @desc 紫微斗数九宫
 */
class ZiWeiDouShu {
    // 十二宫名称
    private const PALACE_NAME = ["命宫", "兄弟", "夫妻", "子女", "财帛", "疾厄", "迁移", "仆役", "官禄", "田宅", "福德", "父母"];

    private Lunar $lunar;
    private LunarTime $lunarTime;

    // true 男 false 女
    private bool $gender;
    // true 阳 false 阴
    private bool $yinYang;

    // 命宫
    private SelfPalace $selfPalace;

    // 排好序的宫位
    private array $twelvePalaces;

    // 身宫
    private ShenPalace $shenPalace;
    // 来因宫
    private Palace $laiYinPalace;
    // 宫干
    private array $palaceGan;
    // 五行
    private WuXing $wuXing;

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

        $this->setPalaceGan();
        $this->initPalaces();
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
    private const PALACE_GAN = ['甲'=>'丙', '乙'=>'戊', '丙'=>'庚', '丁'=>'壬', '壬'=>'壬', '戊'=>'甲', '己'=>'丙', '庚'=>'戊', '辛'=>'庚', '癸'=>'甲'];
    private function setPalaceGan() {
        $startGan = self::PALACE_GAN[$this->lunar->getYearGan()];
        $startPos = Utils::getIndexByGan($startGan);

        $tianGan = [];
        for ($i=0; $i < 12; $i++) { 
            $tianGan[($i+2) % 12] = Utils::getGanByIndex(($startPos+$i) % 10);
        }
        $this->palaceGan = $tianGan;
    }

    /**
     * 起十二宫
     */
    private function initPalaces() {
        $selfPalacePos = abs($this->lunar->getMonthZhiIndex() - $this->lunarTime->getZhiIndex());
        $this->selfPalace = SelfPalace::create($selfPalacePos, $this->palaceGan[$selfPalacePos]);
        $palaces = $this->calculatePalace($selfPalacePos);
        foreach ($palaces as $idx => $name) {
            $this->twelvePalaces[$idx] = TwelvePalace::build($name, $idx, $this->palaceGan[$idx]);
        }
    }

    /**
     * 计算宫位
     */
    public static function calculatePalace($startPos) {
        // 宫位
        $places = [];
        $idx = 0;
        for ($i=$startPos; $i >= 0; $i--,$idx++) { 
            $places[$i] = self::PALACE_NAME[$idx];
        }
        for ($i=11; $i > $startPos; $i--,$idx++) { 
            $places[$i] = self::PALACE_NAME[$idx];
        }
        return $places;
    }

    /**
     * 立身宫
     */
    private const SHEN_PLACE = ["命宫", "福德", "官禄", "迁移", "财帛", "夫妻"];
    private function calculateShenPlace() {
        $palaceName = self::SHEN_PLACE[$this->lunarTime->getZhiIndex() % 6];
        foreach ($this->twelvePalaces as $palace) {
            if ($palaceName == $palace->getName()) {
                $this->shenPalace = ShenPalace::create($palace->getPos(), $this->palaceGan[$palace->getPos()]);
                break;
            }
        }

        $this->shenPalace->setZhu($this->lunar->getYearZhi());
    }

    /**
     * 来因宫
     */
    private const LAIYIN_PLACE = [ '甲'=>'戌', '乙'=>'酉', '丙'=>'申', '丁'=>'未', '戊'=>'午', '己'=>'巳', '庚'=>'辰', '辛'=>'卯', '壬'=>'寅', '癸' => '亥'];
    private function calculateLaiYin() {
        $pos = Utils::getIndexByZhi(self::LAIYIN_PLACE[$this->lunar->getYearGan()]);
        $this->laiYinPalace = Palace::create($pos, $this->palaceGan[$pos]);
    }

    /**
     * 定五行局
     */
    private function setWuXing() {
        $this->wuXing = WuXing::fromGanZhi($this->selfPalace->getGanZhi());
    }

    public function getWuXing() {
        return $this->wuXing;
    }

    /**
     * 定星曜
     */
    private function setStars() {
        $this->stars = Stars::from($this->yinYang, $this->gender, $this->lunar, $this->wuXing, $this->selfPalace, $this->shenPalace);
    }

    /**
     * 排大限
     */
    private function setDaXian() {
        $this->daXian = DaXian::from($this->yinYang, $this->gender, $this->lunar, $this->wuXing, $this->selfPalace, $this->palaceGan);
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
            'mingZhu'   => $this->selfPalace->getZhu(),
            'shenZhu'   => $this->shenPalace->getZhu(),
        ];
    }

    /**
     * 获取十二宫信息 - 排盘
     */
    public function paiPan() {
        $stars = $this->stars->getFormatAllStars();

        $daXianRange = $this->daXian->getRange();

        $twelvePalaces = [];
        for ($i=0; $i < 12; $i++) {
            $twelvePalaces[] = array_merge($this->twelvePalaces[$i]->getFullData(), [
                'stars'    => [
                    'masterStars' => $stars['masterStars'][$i], 
                    'luckyStars' => $stars['luckyStars'][$i],
                    'unluckyStars' => $stars['unluckyStars'][$i],
                    'otherStars' => $stars['otherStars'][$i],
                    'boShiStars' => $stars['boShiStars'][$i],
                    'changShengStars' => $stars['changShengStars'][$i],
                    'jiangQianStars' => $stars['jiangQianStars'][$i],
                    'suiQianStars' => $stars['suiQianStars'][$i],
                    'liuYaoStars' => $stars['liuYaoStars'][$i],
                ],
                'daXian' => $daXianRange[$i],
            ]);
        }

        return $twelvePalaces;
    }

    /**
     * 获取本命盘信息
     */
    public function getMingPan() {
        return [
            'shen'  => $this->shenPalace->getFullData(),
            'laiYin'     => $this->laiYinPalace->getFullData(),
            'selfPalace' => $this->selfPalace->getFullData(),
            'siHua'     => $this->siHua->getBirth(),
        ];
    }


    /**
     * 大限盘
     */
    public function getDaXianPan(int $daXianPos = 0) {
        $places = self::calculatePalace($daXianPos);
        $daXianPalace = Palace::create($daXianPos, $this->palaceGan[$daXianPos]);
        
        return [
            'palaces'       => $places,
            'selfPalace'    => $daXianPalace->getFullData(),
            'siHua'         => $this->siHua->calculate($this->palaceGan[$daXianPos])
        ];
    }

    /**
     * 流年盘
     */
    public function getLiuNianPan(int $year = 0) {
        $lunarYear = LunarYear::fromYear($year);
        $pos = $lunarYear->getZhiIndex();
        $places = self::calculatePalace($pos);

        $liuNianPalace = Palace::create($pos, $this->palaceGan[$pos]);
        
        return [
            'palaces'       => $places,
            'selfPalace'  => $liuNianPalace->getFullData(),
            'siHua'     => $this->siHua->getLiuYear($year)
        ];
    }
    
}