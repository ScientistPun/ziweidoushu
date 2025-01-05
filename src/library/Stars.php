<?php
namespace scientistpun\ziwei\library;

use com\nlf\calendar\Lunar;
use com\nlf\calendar\util\LunarUtil;
use scientistpun\ziwei\util\Utils;

/**
 * 紫微星曜 - 命盘
 * @link 参考 https://www.gushiwen.cn/guwen/book_d3309a1684f4.aspx
 */
class Stars {
    private Lunar $lunar;
    private WuXing $wuXing;
    private SelfPalace $selfPalace;
    private bool $yinYang;
    private bool $gender;

    // ------ 14主星 ------
    private array $masterStars;
    // 紫薇
    private Star $ziWei;
    // 天机
    private Star $tianJi;
    // 太阳
    private Star $sunStar;
    // 武曲
    private Star $wuQu;
    // 天同
    private Star $tianTong;
    // 廉贞
    private Star $lianZhen;
    // 天府
    private Star $tianFu;
    // 太阴
    private Star $taiYin;
    // 贪狼
    private Star $tanLang;
    // 巨门
    private Star $juMen;
    // 天相
    private Star $tianXiang;
    // 七杀
    private Star $qiSha;
    // 天梁
    private Star $tianLiang;
    // 破军
    private Star $poJun;

    // ------ 六吉星 ------
    private array $luckyStars;
    // 左辅
    private Star $zuoFu;
    // 右弼
    private Star $youBi;
    // 文曲
    private Star $wenQu;
    // 文昌
    private Star $wenChang;

    // 天魁 宫位
    private const TIAN_KUI_PLACE = [ "甲" => "丑", "乙" => "子", "丙" => "亥", "丁" => "亥", "戊" => "丑", "己" => "子", "庚" => "丑", "辛" => "午", "壬" => "卯", "癸" => "卯" ];
    // 天魁
    private Star $tianKui;

    // 天钺 宫位
    private const TIAN_YUE_PLACE  = [ "甲" => "未", "乙" => "申", "丙" => "酉", "丁" => "酉", "戊" => "未", "己" => "申", "庚" => "未", "辛" => "寅", "壬" => "巳", "癸" => "巳" ];
    // 天钺
    private Star $tianYue;


    // ------ 六煞星 ------
    private array $unluckyStars;
    // 擎羊
    private Star $qingYang;
    // 陀羅
    private Star $tuoLuo;
    // 地劫
    private Star $diJie;
    // 地空
    private Star $diKong;

    // 火星 宫位
     private const HUO_XING_PLACE = [ '子'=>'寅', '辰'=>'寅', '申'=>'寅', '卯'=>'酉', '未'=>'酉', '亥'=>'酉', '寅'=>'丑', '午'=>'丑', '戌'=>'丑', '丑'=>'卯', '巳'=>'卯', '酉'=>'卯'];
    // 火星
    private Star $huoXing;

    // 铃星
    private Star $lingXing;



    // ------ 其他辅星 ------
    private array $otherStars;

    // 天马 四马地宫位 年支对应宫支
    private const TIAN_MA_PLACE = [ '子'=>'寅', '辰'=>'寅', '申'=>'寅', '卯'=>'巳', '未'=>'巳', '亥'=>'巳', '寅'=>'申', '午'=>'申', '戌'=>'申', '丑'=>'亥', '巳'=>'亥', '酉'=>'亥'];
    // 天马
    private Star $tianMa;

    // 禄存 宫位 年干对应宫支
    private const LU_CUN_TIAN_GAN = [ '甲'=>'寅', '乙'=>'卯', '丙'=>'巳', '丁'=>'午', '戊'=>'巳', '己'=>'午', '庚'=>'申', '辛'=>'酉', '壬'=>'亥', '癸' => '子'];
    // 祿存
    private Star $luCun;

    // 红鸾
    private Star $hongLuan;
    // 天喜
    private Star $tianXi;

    // 咸池 宫位 年支对应宫支 四败地
    private const XIAN_CHI_PLACE  = [ '子'=>'酉', '辰'=>'酉', '申'=>'酉', '卯'=>'子', '未'=>'子', '亥'=>'子', '寅'=>'卯', '午'=>'卯', '戌'=>'卯', '丑'=>'午', '巳'=>'午', '酉'=>'午'];
    // 咸池
    private Star $xianChi;

    // 天姚
    private Star $tianYao;
    // 天刑
    private Star $tianXing;

    // 阴煞 宫位 出生月对应宫支
    private const YIN_SHA_PLACE  = ['寅', '子', '戌', '申', '午', '辰'];
    // 阴煞
    private Star $yinSha;

    // 天月 出生月对应宫支
    private const TIAN_YUE1_PLACE  = ['戍', '已', '辰', '寅', '未', '卯', '亥', '未', '寅', '午', '戍', '寅'];
    // 天月
    private Star $tianYue1;

    // 天巫 出生月对应宫支
    private const TIAN_WU_PLACE  = ['巳', '申', '亥', '寅'];
    // 天巫
    private Star $tianWu;

    // 天哭
    private Star $tianKu;
    // 天虚
    private Star $tianXu;
    // 龙池
    private Star $longChi;
    // 凤阁
    private Star $fengGe;

    // 华盖 宫位年支对应宫支 四墓地
    private const HUA_GAI_PLACE = [ '子'=>'辰', '辰'=>'辰', '申'=>'辰', '卯'=>'未', '未'=>'未', '亥'=>'未', '寅'=>'戌', '午'=>'戌', '戌'=>'戌', '丑'=>'丑', '巳'=>'丑', '酉'=>'丑'];
    // 华盖
    private Star $huaGai;

    // 劫煞 宫位年支对应宫支 四马地
    private const JIE_SHA_PLACE = ['子'=>'巳', '辰'=>'巳', '申'=>'巳', '卯'=>'申', '未'=>'申', '亥'=>'申', '寅'=>'亥', '午'=>'亥', '戌'=>'亥', '丑'=>'寅', '巳'=>'寅', '酉'=>'寅'];
    // 劫煞
    private Star $jieSha;

    // 孤臣 宫位年支对应宫支 四马地
    private const GU_CHEN_PLACE = ['卯'=>'巳', '寅'=>'巳', '辰'=>'巳', '巳'=>'申', '午'=>'申', '未'=>'申', '申'=>'亥', '酉'=>'亥', '戌'=>'亥', '子'=>'寅', '亥'=>'寅', '丑'=>'寅'];
    // 孤臣
    private Star $guChen;

    // 寡宿 宫位年支对应宫支 四墓地
    private const GUA_SU_PLACE = ['卯'=>'丑', '寅'=>'丑', '辰'=>'丑', '巳'=>'辰', '午'=>'辰', '未'=>'辰', '申'=>'未', '酉'=>'未', '戌'=>'未', '子'=>'戌', '亥'=>'戌', '丑'=>'戌'];
    // 寡宿
    private Star $guaSu;

    // 破碎 宫位年支对应宫支
    private const PO_SUI_PLACE = ['子'=>'已', '午'=>'已', '卯'=>'已', '酉'=>'已', '辰'=>'丑', '戍'=>'丑', '丑'=>'丑', '未'=>'丑', '寅'=>'酉', '申'=>'酉', '已'=>'酉', '亥'=>'酉'];
    // 破碎
    private Star $poSui;

    // 大耗
    private const DA_HAO_PLACE = ['子'=>'未', '寅'=>'酉', '已'=>'戍', '丑'=>'午', '卯'=>'申', '辰'=>'亥', '午'=>'丑', '未'=>'子', '申'=>'卯', '酉'=>'寅', '戍'=>'已', '亥'=>'辰'];
    // 大耗
    private Star $daHao;

    // 天德
    private Star $tianDe;
    // 解神
    private Star $jieShen;

    // 台辅
    private Star $taiFu;

    // 封诰
    private Star $fengGao;
    
    // 蜚廉 宫位
    private const FEI_LIAN_PLACE = ['子'=>'申', '丑'=>'酉', '寅'=>'戍', '卯'=>'已', '辰'=>'午', '巳'=>'未', '午'=>'寅', '未'=>'卯', '申'=>'辰', '酉'=>'亥', '戌'=>'子', '亥'=>'丑'];
    // 蜚廉
    private Star $feiLian;
    
    // 天官 宫位年干对应宫支
    private const TIAN_GUAN_PLACE = ['甲'=>'未', '乙'=>'辰', '丙'=>'已', '丁'=>'寅', '戊'=>'卯', '己'=>'酉', '庚'=>'亥', '辛'=>'酉', '壬'=>'戌', '癸'=>'午'];
    // 天官
    private Star $tianGuan;

    // 天福 宫位年干对应宫支
    private const TIAN_FU1_PLACE = ['甲'=>'酉', '乙'=>'申', '丙'=>'子', '丁'=>'亥', '戊'=>'卯', '己'=>'寅', '庚'=>'午', '辛'=>'巳', '壬'=>'午', '癸'=>'巳'];
    // 天福
    private Star $tianFu1;

    // 截空 宫位年干对应宫支
    private const JIE_KONG_PLACE = ['甲'=>'申', '乙'=>'午', '丙'=>'辰', '丁'=>'寅', '戊'=>'子', '己'=>'酉', '庚'=>'未', '辛'=>'巳', '壬'=>'卯', '癸'=>'丑'];
    // 截空
    private Star $jieKong;

    // 三台
    private Star $sanTai;
    // 八座
    private Star $baZuo;
    // 天贵
    private Star $tianGui;
    // 恩光
    private Star $enGuang;
    // 天才
    private Star $tianCai;
    // 天寿
    private Star $tianShou;
    // 天伤
    private Star $tianShang;
    // 天使
    private Star $tianShi;
    
    // 旬空
    private const XUN_KONG_PLACE = [
        '甲子' => '寅', '甲午' => '申', '丙寅' => '午', '丙申' => '子', '戊辰' => '戌', '戊戌' => '辰', '庚午' => '寅', '庚子' => '申', '壬申' => '午',
        '壬寅' => '子', '甲戌' => '子', '甲辰' => '午', '丙子' => '辰', '丙午' => '戌', '戊寅' => '申', '戊申' => '寅', '庚辰' => '子', '庚戌' => '午', 
        '壬午' => '辰', '壬子' => '戌', '甲申' => '戌', '甲寅' => '辰', '丙戌' => '寅', '丙辰' => '申', '戊子' => '午', '戊午' => '子', '庚寅' => '戌',
        '庚申' => '辰', '壬辰' => '寅', '壬戌' => '申', '乙丑' => '卯', '乙未' => '酉', '丁卯' => '未', '丁酉' => '丑', '己巳' => '亥', '己亥' => '巳',
        '辛未' => '卯', '辛丑' => '酉', '癸酉' => '未', '癸卯' => '丑', '乙亥' => '丑', '乙巳' => '未', '丁丑' => '巳', '丁未' => '亥', '己卯' => '酉',
        '己酉' => '卯', '辛巳' => '丑', '辛亥' => '未', '癸未' => '巳', '癸丑' => '亥', '乙酉' => '亥', '乙卯' => '巳', '丁亥' => '卯', '丁巳' => '酉',
        '己丑' => '未', '己未' => '丑', '辛卯' => '亥', '辛酉' => '巳', '癸巳' => '卯', '癸亥' => '酉'
    ];
    // 旬空
    private Star $xunKong;

    // -------- 博士十二神 --------
    private array $boShiStars;
    // 博士
    private Star $boShi;
    // 力士
    private Star $liShi;
    // 青龙
    private Star $qingLong;
    // 小耗
    private Star $xiaoHao;
    // 将军
    private Star $jiangJun;
    // 奏书
    private Star $zouShu;
    // 飞廉
    private Star $feiLian1;
    // 喜神
    private Star $xiShen;
    // 病符
    private Star $bingFu;
    // 大耗
    private Star $daHao1;
    // 伏兵
    private Star $fuBing;
    // 官符
    private Star $guanFu;

    // -------- 长生十二神 --------
    private array $changShengStars;
    // 长生 宫位五行对应宫支
    private const CHANG_SHENG_PLACE = ['金'=>'巳', '木'=>'亥', '火'=>'寅', '水'=>'申', '土'=>'申'];
    // 长生
    private Star $changSheng;

    // 沐浴
    private Star $muYu;
    // 冠带
    private Star $guanDai;
    // 临官
    private Star $linGuan;
    // 帝旺
    private Star $diWang;
    // 衰
    private Star $shuai;
    // 病
    private Star $bing;
    // 死
    private Star $si;
    // 墓
    private Star $mu;
    // 绝
    private Star $jue;
    // 胎
    private Star $tai;
    // 养
    private Star $yang;


    // -------- 将前十二星 --------
    private array $jiangQianStars;
    // 将星 宫位年支对应宫支
    private const JIANG_XING_PLACE = ['子'=>'子', '辰'=>'子', '申'=>'子', '卯'=>'卯', '未'=>'卯', '亥'=>'卯', '寅'=>'午', '午'=>'午', '戌'=>'午', '丑'=>'酉', '巳'=>'酉', '酉'=>'酉'];
    // 将星
    private Star $jiangXing;
    // 攀鞍
    private Star $panAn;
    // 岁驿
    private Star $suiYi;
    // 息神
    private Star $xiShen1;
    // 华盖
    private Star $huaGai1;
    // 劫煞
    private Star $jieSha1;
    // 灾煞
    private Star $zaiSha;
    // 天煞
    private Star $tianSha;
    // 指背
    private Star $zhiBei;
    // 咸池
    private Star $xianChi1;
    // 月煞
    private Star $yueSha;
    // 亡神
    private Star $wangShen;


    // -------- 岁前十二星 --------
    private array $suiQianStars;
    // 岁建
    private Star $suiJian;
    // 晦气
    private Star $huiQi;
    // 丧门
    private Star $sangMen;
    // 贯索
    private Star $guanSuo;
    // 官府
    private Star $guanFu1;
    // 小耗
    private Star $xiaoHao1;
    // 大耗
    private Star $daHao2;
    // 龙德
    private Star $longDe;
    // 白虎
    private Star $baiHu;
    // 天德
    private Star $tianDe1;
    // 吊客
    private Star $diaoKe;
    // 病符
    private Star $bingFu2;

    private function __construct(bool $yinYang, bool $gender, Lunar $lunar, WuXing $wuXing, SelfPalace $selfPalace) {
        $this->lunar = $lunar;
        $this->wuXing = $wuXing;
        $this->selfPalace = $selfPalace;
        $this->yinYang = $yinYang;
        $this->gender = $gender;

        $this->set14MasterStars();
        $this->setLuckyStars();
        $this->setOtherStars();
        $this->setUnluckyStars();
        $this->setBoShiStars();
        $this->setChangShengStars();
        $this->setJiangQianStars();
        $this->setSuiQianStars();
        $this->settleByType();
    }

    public static function from (bool $yinYang, bool $gender, Lunar $lunar, WuXing $wuXing, SelfPalace $selfPalace) {
        return new Stars($yinYang, $gender, $lunar, $wuXing, $selfPalace);
    }


    /**
     * 按类型安星
     */
    private function settleByType () {
        $allStars = $this->getAllStars();
        foreach ($allStars as $star) {
            if ($star->getType() == Star::TYPE_MASTER) {
                $this->masterStars[$star->getPos()][] = $star;
            } elseif ($star->getType() == Star::TYPE_LUCKY) {
                $this->luckyStars[$star->getPos()][] = $star;
            } elseif ($star->getType() == Star::TYPE_UNLUCKY) {
                $this->unluckyStars[$star->getPos()][] = $star;
            } elseif ($star->getType() == Star::TYPE_OTHER) {
                $this->otherStars[$star->getPos()][] = $star;
            } elseif ($star->getType() == Star::TYPE_BO_SHI) {
                $this->boShiStars[$star->getPos()][] = $star;
            } elseif ($star->getType() == Star::TYPE_CHANG_SHENG) {
                $this->changShengStars[$star->getPos()][] = $star;
            } elseif ($star->getType() == Star::TYPE_JIANG_QIAN) {
                $this->jiangQianStars[$star->getPos()][] = $star;
            } elseif ($star->getType() == Star::TYPE_SUI_QIAN) {
                $this->suiQianStars[$star->getPos()][] = $star;
            }
        }
    }

    /**
     * 顺/逆时针移动
     */
    public function clockwiseMove(string $diZhi = '寅', int $move, $isClockwise = true){
        $pos = Utils::getIndexByZhi($diZhi);

        $move %= 12;
        if ($isClockwise) {
            $pos += $move;
            if ($pos > 11) $pos -= 12;
        } else {
            $pos -= $move;
            if ($pos < 0) $pos += 12;
        }
        return $pos;
    }

    /**
     * 定紫微14主星
     */
    private function set14MasterStars () {
        $birthDay = $this->lunar->getDay();
        $juShu = $this->wuXing->getJuShu();
        $multi = $birthDay / $juShu;

        // 生日比局数小
        if ($birthDay <= $juShu) {
            $move = $birthDay;
            $lack = $juShu - $birthDay;
        // 可以被整除
        } elseif ($multi == intval($birthDay / $juShu)) {
            $move = $multi;
            $lack = 0;
        } else {
            $move = ceil($multi);
            $lack = $juShu - ($birthDay % $juShu);
        }

        // 判断差数的奇偶性并计算新的数字
        $move = $lack % 2 == 0 ? $move + $lack : $move - $lack;

        // 紫微 从寅宫开始顺时针排列
        $pos = ($move + 1) % 12;
        $this->ziWei = Star::create('紫微', $pos, Star::TYPE_MASTER); 

        // 天机 紫微逆行一格
        $tianJiPos = $pos > 0 ? $pos - 1:11;
        $this->tianJi = Star::create('天机', $tianJiPos, Star::TYPE_MASTER); 

        // 太阳 天机逆行两格
        $sunPos = $tianJiPos - 2;
        if ($sunPos < 0) $sunPos = $sunPos + 12;
        $this->sunStar = Star::create('太阳', $sunPos, Star::TYPE_MASTER); 

        // 武曲 太阳逆行一格
        $wuQuPos = $sunPos > 0 ? $sunPos - 1:11;
        $this->wuQu = Star::create('武曲', $wuQuPos, Star::TYPE_MASTER); 

        // 天同 武曲逆行一格
        $tianTongPos = $wuQuPos > 0 ? $wuQuPos - 1:11;
        $this->tianTong = Star::create('天同', $tianTongPos, Star::TYPE_MASTER);

        // 廉贞 天同逆行三格
        $lianZhenPos = $tianTongPos - 3;
        if ($lianZhenPos < 0) $lianZhenPos += 12;
        $this->lianZhen = Star::create('廉贞', $lianZhenPos, Star::TYPE_MASTER);

        // 七杀 和紫微形成斜对面
        $qiShaPos = $pos > 10 ? 11:10 - $pos;
        $this->qiSha = Star::create('七杀', $qiShaPos, Star::TYPE_MASTER);

        // 天梁 七杀逆行一格
        $tianLiangPos = $qiShaPos > 0 ? $qiShaPos - 1:11;
        $this->tianLiang = Star::create('天梁', $qiShaPos, Star::TYPE_MASTER);

        // 巨门 天梁逆行两格
        $juMenPos = $tianLiangPos - 2;
        if ($juMenPos < 0) $juMenPos = $juMenPos + 12;
        $this->juMen = Star::create('巨门', $juMenPos, Star::TYPE_MASTER);

        // 贪狼 巨门逆行一格
        $tanLangPos = $juMenPos > 0 ? $juMenPos - 1:11;
        $this->tanLang = Star::create('贪狼', $tanLangPos, Star::TYPE_MASTER);

        // 太阴 贪狼逆行一格
        $taiYinPos = $tanLangPos > 0 ? $tanLangPos - 1:11;
        $this->taiYin = Star::create('太阴', $taiYinPos, Star::TYPE_MASTER);

        // 破军 太阴逆行三格
        $poJunPos = $taiYinPos - 3;
        if ($poJunPos < 0) $poJunPos = $poJunPos + 12;
        $this->poJun = Star::create('破军', $poJunPos, Star::TYPE_MASTER);

        // 天府 对宫七杀
        $tianFuPos = $qiShaPos >= 6 ? $qiShaPos - 6:$qiShaPos + 6;
        $this->tianFu = Star::create('天府', $tianFuPos, Star::TYPE_MASTER);

        // 天相 对宫破军
        $tianXiangPos = $poJunPos >= 6 ? $poJunPos - 6:$poJunPos + 6;
        $this->tianXiang = Star::create('天相', $tianXiangPos, Star::TYPE_MASTER);

    }

    public function getMasterStars() {
        return $this->masterStars;
    }

    /**
     * 定6吉星
     */
    public function setLuckyStars () {
        $month = $this->lunar->getMonth();
        $yearGan = $this->lunar->getYearGan();
        $hourZhiIdx = $this->lunar->getTime()->getZhiIndex();

        // 左辅
        $this->zuoFu = Star::create('左辅', $this->clockwiseMove('辰', $month - 1), Star::TYPE_LUCKY);

        // 右弼
        $this->youBi = Star::create('右弼', $this->clockwiseMove('戌', $month - 1, false), Star::TYPE_LUCKY);

        // 文曲
        $this->wenQu = Star::create('文曲', $this->clockwiseMove('辰', $hourZhiIdx), Star::TYPE_LUCKY);

        // 文昌
        $this->wenChang = Star::create('文昌', $this->clockwiseMove('戌', $hourZhiIdx, false), Star::TYPE_LUCKY);

        // 天魁
        $tianKuiPos = Utils::getIndexByZhi(self::TIAN_KUI_PLACE[$yearGan]);
        $this->tianKui = Star::create('天魁', $tianKuiPos, Star::TYPE_LUCKY);

        // 天钺
        $tianYuePos = Utils::getIndexByZhi(self::TIAN_YUE_PLACE[$yearGan]);
        $this->tianYue = Star::create('天钺', $tianYuePos, Star::TYPE_LUCKY);
    }

    public function getLuckyStars() {
        return $this->luckyStars;
    }

    /**
     * 定六煞星
     */
    public function setUnluckyStars () {
        $hourZhiIdx = $this->lunar->getTime()->getZhiIndex();
        $yearZhi = $this->lunar->getYearZhi();

        // 陀罗 在禄存后面
        $pos = $this->luCun->getPos() == 0 ? 11:$this->luCun->getPos() - 1;
        $this->tuoLuo = Star::create('陀罗', $pos, Star::TYPE_UNLUCKY);

        // 擎羊 在禄存前面
        $pos = $this->luCun->getPos() == 1 ? 0:$this->luCun->getPos() + 1;
        $this->qingYang = Star::create('擎羊', $pos, Star::TYPE_UNLUCKY);

        // 地劫
        $this->diJie = Star::create('地劫', $this->clockwiseMove('亥', $hourZhiIdx), Star::TYPE_UNLUCKY);

        // 地空
        $this->diKong = Star::create('地空', $this->clockwiseMove('亥', $hourZhiIdx, false), Star::TYPE_UNLUCKY);

        // 火星
        $this->huoXing = Star::create('火星', $this->clockwiseMove(self::HUO_XING_PLACE[$yearZhi], $hourZhiIdx), Star::TYPE_UNLUCKY);

        // 铃星 寅午戌在卯开始数，其余在戌数
        $this->lingXing = Star::create('铃星', $this->clockwiseMove(in_array($yearZhi, ['寅', '午', '戌']) ? '卯':'戌', $hourZhiIdx), Star::TYPE_UNLUCKY);
    }

    public function getUnluckyStars() {
        return $this->unluckyStars;
    }

    /**
     * 定辅星
     */
    public function setOtherStars () {
        $yearGan = $this->lunar->getYearGan();
        $yearZhi = $this->lunar->getYearZhi();
        $yearZhiIdx = $this->lunar->getYearZhiIndex();
        $day = $this->lunar->getDay();
        $hourZhiIdx = $this->lunar->getTime()->getZhiIndex();
        $month = $this->lunar->getMonth();

        // 禄存
        $luCunPos = array_search(self::LU_CUN_TIAN_GAN[$this->lunar->getYearGan()], LunarUtil::$ZHI) - 1;
        $this->luCun = Star::create('禄存', $luCunPos, Star::TYPE_OTHER);
        
        // 天马
        $tianMaPos = Utils::getIndexByZhi(self::TIAN_MA_PLACE[$this->lunar->getYearZhi()]);
        $this->tianMa = Star::create('天马', $tianMaPos, Star::TYPE_OTHER);
        
        // 红鸾 从卯宫属到自己生肖的格数
        $this->hongLuan = Star::create('红鸾', $this->clockwiseMove('卯', $yearZhiIdx, false), Star::TYPE_OTHER);
        
        // 天喜 红鸾的对宫
        $this->tianXi = Star::create('天喜', $this->clockwiseMove($this->hongLuan->getZhi(), 7), Star::TYPE_OTHER);

        // 天刑
        $this->tianXing = Star::create('天刑', $this->clockwiseMove('酉', $month - 1), Star::TYPE_OTHER);

        // 天姚
        $this->tianYao = Star::create('天姚', $this->clockwiseMove('丑', $month - 1), Star::TYPE_OTHER);

        // 阴煞
        $yinShaPos = Utils::getIndexByZhi(self::YIN_SHA_PLACE[($month - 1) % 6]);
        $this->yinSha = Star::create('阴煞', $yinShaPos, Star::TYPE_OTHER);

        // 天月
        $tianYue1Pos = Utils::getIndexByZhi(self::TIAN_YUE1_PLACE[($month - 1)]);
        $this->tianYue1 = Star::create('天月', $tianYue1Pos, Star::TYPE_OTHER);

        // 天巫
        $tianWuPos = Utils::getIndexByZhi(self::TIAN_WU_PLACE[($month - 1) % 4]);
        $this->tianWu = Star::create('天巫', $tianWuPos, Star::TYPE_OTHER);

        // 天虚
        $this->tianXu = Star::create('天虚', $this->clockwiseMove('午', $yearZhiIdx), Star::TYPE_OTHER);

        // 天哭
        $this->tianKu = Star::create('天哭', $this->clockwiseMove('午', $yearZhiIdx, false), Star::TYPE_OTHER);

        // 龙池
        $this->longChi = Star::create('龙池', $this->clockwiseMove('辰', $yearZhiIdx), Star::TYPE_OTHER);

        // 凤阁
        $this->fengGe = Star::create('凤阁', $this->clockwiseMove('戌', $yearZhiIdx, false), Star::TYPE_OTHER);

        // 华盖
        $huaGaiPos = Utils::getIndexByZhi(self::HUA_GAI_PLACE[$yearZhi]);
        $this->huaGai = Star::create('华盖', $huaGaiPos, Star::TYPE_OTHER);

        // 劫煞
        $jieShaPos = Utils::getIndexByZhi(self::JIE_SHA_PLACE[$yearZhi]);
        $this->jieSha = Star::create('劫煞', $jieShaPos, Star::TYPE_OTHER);

        // 咸池
        $xianChiPos = Utils::getIndexByZhi(self::XIAN_CHI_PLACE[$this->lunar->getYearZhi()]);
        $this->xianChi = Star::create('咸池', $xianChiPos, Star::TYPE_OTHER);

        // 孤臣
        $guChenPos = Utils::getIndexByZhi(self::GU_CHEN_PLACE[$yearZhi]);
        $this->guChen = Star::create('孤臣', $guChenPos, Star::TYPE_OTHER);

        // 寡宿
        $guaSuPos = Utils::getIndexByZhi(self::GUA_SU_PLACE[$yearZhi]);
        $this->guaSu = Star::create('寡宿', $guaSuPos, Star::TYPE_OTHER);

        // 破碎
        $poSuiPos = Utils::getIndexByZhi(self::PO_SUI_PLACE[$yearZhi]);
        $this->poSui = Star::create('破碎', $poSuiPos, Star::TYPE_OTHER);

        // 大耗
        $daHaoPos = Utils::getIndexByZhi(self::DA_HAO_PLACE[$yearZhi]);
        $this->daHao = Star::create('大耗', $daHaoPos, Star::TYPE_OTHER);

        // 天德
        $this->tianDe = Star::create('天德', $this->clockwiseMove('酉', $yearZhiIdx), Star::TYPE_OTHER);

        // 解神
        $this->jieShen = Star::create('解神', $this->clockwiseMove('戌', $yearZhiIdx, false), Star::TYPE_OTHER);

        // 台辅
        $this->taiFu = Star::create('台辅', $this->clockwiseMove('午', $hourZhiIdx), Star::TYPE_OTHER);
        
        // 封诰
        $this->fengGao = Star::create('封诰', $this->clockwiseMove('寅', $hourZhiIdx), Star::TYPE_OTHER);

        // 蜚廉
        $this->feiLian = Star::create('蜚廉', Utils::getIndexByZhi(self::FEI_LIAN_PLACE[$yearZhi]), Star::TYPE_OTHER);

        // 天官
        $this->tianGuan = Star::create('天官', Utils::getIndexByZhi(self::TIAN_GUAN_PLACE[$yearGan]), Star::TYPE_OTHER);

        // 天福
        $this->tianFu1 = Star::create('天福', Utils::getIndexByZhi(self::TIAN_FU1_PLACE[$yearGan]), Star::TYPE_OTHER);

        // 截空
        $this->jieKong = Star::create('截空', Utils::getIndexByZhi(self::JIE_KONG_PLACE[$yearGan]), Star::TYPE_OTHER);

        // 三台
        $this->sanTai = Star::create('三台', $this->clockwiseMove($this->zuoFu->getZhi(), $day - 1), Star::TYPE_OTHER);
        
        // 八座
        $this->baZuo = Star::create('八座', $this->clockwiseMove($this->youBi->getZhi(), $day - 1, false), Star::TYPE_OTHER);

        // 天贵
        $this->tianGui = Star::create('天贵', $this->clockwiseMove($this->wenQu->getZhi(), $day - 2), Star::TYPE_OTHER);
        
        // 恩光
        $this->enGuang = Star::create('恩光', $this->clockwiseMove($this->wenChang->getZhi(), $day - 2), Star::TYPE_OTHER);
        
        // 天才
        $this->tianCai = Star::create('天才', $this->clockwiseMove($this->selfPalace->getZhi(), $yearZhiIdx), Star::TYPE_OTHER);
        
        // 天寿
        $this->tianShou = Star::create('天寿', $this->clockwiseMove($this->selfPalace->getZhi(), $yearZhiIdx), Star::TYPE_OTHER);
        
        // 天伤
        $this->tianShang = Star::create('天伤', $this->clockwiseMove($this->selfPalace->getZhi(), 7), Star::TYPE_OTHER);
        
        // 天使
        $this->tianShi = Star::create('天使', $this->clockwiseMove($this->selfPalace->getZhi(), 5), Star::TYPE_OTHER);
        
        // 旬空
        $this->xunKong = Star::create('旬空', Utils::getIndexByZhi(self::XUN_KONG_PLACE[$this->selfPalace->getGanZhi()]), Star::TYPE_OTHER);
    }

    public function getOtherStars() {
        return $this->otherStars;
    }

    /**
     * 安生年博士十二神
     */
    public function setBoShiStars() {
        // 阳男阴女 -> 顺时针  阴男阳女 -> 逆时针 
        $isClockwise = ($this->yinYang && $this->gender) || (!$this->yinYang && !$this->gender);
            
        // 博士
        $this->boShi = Star::create('博士', $this->luCun->getPos(), Star::TYPE_BO_SHI);

        // 力士
        $this->liShi = Star::create('力士', $this->clockwiseMove($this->luCun->getZhi(), 1, $isClockwise), Star::TYPE_BO_SHI);

        // 青龙
        $this->qingLong = Star::create('青龙', $this->clockwiseMove($this->luCun->getZhi(), 2, $isClockwise), Star::TYPE_BO_SHI);

        // 小耗
        $this->xiaoHao = Star::create('小耗', $this->clockwiseMove($this->luCun->getZhi(), 3, $isClockwise), Star::TYPE_BO_SHI);

        // 将军
        $this->jiangJun = Star::create('将军', $this->clockwiseMove($this->luCun->getZhi(), 4, $isClockwise), Star::TYPE_BO_SHI);

        // 奏书
        $this->zouShu = Star::create('奏书', $this->clockwiseMove($this->luCun->getZhi(), 5, $isClockwise), Star::TYPE_BO_SHI);

        // 飞廉
        $this->feiLian1 = Star::create('飞廉', $this->clockwiseMove($this->luCun->getZhi(), 6, $isClockwise), Star::TYPE_BO_SHI);

        // 喜神
        $this->xiShen = Star::create('喜神', $this->clockwiseMove($this->luCun->getZhi(), 7, $isClockwise), Star::TYPE_BO_SHI);

        // 病符
        $this->bingFu = Star::create('病符', $this->clockwiseMove($this->luCun->getZhi(), 8, $isClockwise), Star::TYPE_BO_SHI);

        // 大耗
        $this->daHao1 = Star::create('大耗', $this->clockwiseMove($this->luCun->getZhi(), 9, $isClockwise), Star::TYPE_BO_SHI);

        // 伏兵
        $this->fuBing = Star::create('伏兵', $this->clockwiseMove($this->luCun->getZhi(), 10, $isClockwise), Star::TYPE_BO_SHI);

        // 官符
        $this->guanFu = Star::create('官符', $this->clockwiseMove($this->luCun->getZhi(), 11, $isClockwise), Star::TYPE_BO_SHI);
    }

    public function getBoShiStars() {
        return $this->boShiStars;
    }

    /**
     * 安长生十二神
     */
    public function setChangShengStars() {
        // 男顺女逆
        $isClockwise = $this->gender;

        // 长生
        $pos = Utils::getIndexByZhi(self::CHANG_SHENG_PLACE[$this->wuXing->getString()]);
        $this->changSheng = Star::create('长生', $pos, Star::TYPE_CHANG_SHENG);

        // 沐浴
        $this->muYu = Star::create('沐浴', $this->clockwiseMove($this->changSheng->getZhi(), 1, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 冠带
        $this->guanDai = Star::create('冠带', $this->clockwiseMove($this->changSheng->getZhi(), 2, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 临官
        $this->linGuan = Star::create('临官', $this->clockwiseMove($this->changSheng->getZhi(), 3, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 帝旺
        $this->diWang = Star::create('帝旺', $this->clockwiseMove($this->changSheng->getZhi(), 4, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 衰
        $this->shuai = Star::create('衰', $this->clockwiseMove($this->changSheng->getZhi(), 5, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 病
        $this->bing = Star::create('病', $this->clockwiseMove($this->changSheng->getZhi(), 6, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 死
        $this->si = Star::create('死', $this->clockwiseMove($this->changSheng->getZhi(), 7, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 墓
        $this->mu = Star::create('墓', $this->clockwiseMove($this->changSheng->getZhi(), 8, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 绝
        $this->jue = Star::create('绝', $this->clockwiseMove($this->changSheng->getZhi(), 9, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 胎
        $this->tai = Star::create('胎', $this->clockwiseMove($this->changSheng->getZhi(), 10, $isClockwise), Star::TYPE_CHANG_SHENG);
        // 养
        $this->yang = Star::create('养', $this->clockwiseMove($this->changSheng->getZhi(), 11, $isClockwise), Star::TYPE_CHANG_SHENG);
    }

    public function getChangShengStars() {
        return $this->changShengStars;
    }

    /**
     * 安将前星
     */
    public function setJiangQianStars() {
        // 将星
        $pos = Utils::getIndexByZhi(self::JIANG_XING_PLACE[$this->lunar->getYearZhi()]);
        $this->jiangXing = Star::create('将星', $pos, Star::TYPE_JIANG_QIAN);

        // 攀鞍
        $this->panAn = Star::create('攀鞍', $this->clockwiseMove($this->jiangXing->getZhi(), 1), Star::TYPE_JIANG_QIAN);
        // 岁驿
        $this->suiYi = Star::create('岁驿', $this->clockwiseMove($this->jiangXing->getZhi(), 2), Star::TYPE_JIANG_QIAN);
        // 息神
        $this->xiShen1 = Star::create('息神', $this->clockwiseMove($this->jiangXing->getZhi(), 3), Star::TYPE_JIANG_QIAN);
        // 华盖
        $this->huaGai1 = Star::create('华盖', $this->clockwiseMove($this->jiangXing->getZhi(), 4), Star::TYPE_JIANG_QIAN);
        // 劫煞
        $this->jieSha1 = Star::create('劫煞', $this->clockwiseMove($this->jiangXing->getZhi(), 5), Star::TYPE_JIANG_QIAN);
        // 灾煞
        $this->zaiSha = Star::create('灾煞', $this->clockwiseMove($this->jiangXing->getZhi(), 6), Star::TYPE_JIANG_QIAN);
        // 天煞
        $this->tianSha = Star::create('天煞', $this->clockwiseMove($this->jiangXing->getZhi(), 7), Star::TYPE_JIANG_QIAN);
        // 指背
        $this->zhiBei = Star::create('指背', $this->clockwiseMove($this->jiangXing->getZhi(), 8), Star::TYPE_JIANG_QIAN);
        // 咸池
        $this->xianChi1 = Star::create('咸池', $this->clockwiseMove($this->jiangXing->getZhi(), 9), Star::TYPE_JIANG_QIAN);
        // 月煞
        $this->yueSha = Star::create('月煞', $this->clockwiseMove($this->jiangXing->getZhi(), 10), Star::TYPE_JIANG_QIAN);
        // 亡神
        $this->wangShen = Star::create('亡神', $this->clockwiseMove($this->jiangXing->getZhi(), 11), Star::TYPE_JIANG_QIAN);
    }

    public function getJiangQianStars() {
        return $this->jiangQianStars;
    }

    /**
     * 安岁前星
     */
    public function setSuiQianStars() {
        // 岁建
        $this->suiJian = Star::create('岁建', Utils::getIndexByZhi($this->lunar->getYearZhi()), Star::TYPE_SUI_QIAN);
        // 晦气
        $this->huiQi = Star::create('晦气', $this->clockwiseMove($this->suiJian->getZhi(), 1), Star::TYPE_SUI_QIAN);
        // 丧门
        $this->sangMen = Star::create('丧门', $this->clockwiseMove($this->suiJian->getZhi(), 2), Star::TYPE_SUI_QIAN);
        // 贯索
        $this->guanSuo = Star::create('贯索', $this->clockwiseMove($this->suiJian->getZhi(), 3), Star::TYPE_SUI_QIAN);
        // 官府
        $this->guanFu1 = Star::create('官府', $this->clockwiseMove($this->suiJian->getZhi(), 4), Star::TYPE_SUI_QIAN);
        // 小耗
        $this->xiaoHao1 = Star::create('小耗', $this->clockwiseMove($this->suiJian->getZhi(), 5), Star::TYPE_SUI_QIAN);
        // 大耗
        $this->daHao2 = Star::create('大耗', $this->clockwiseMove($this->suiJian->getZhi(), 6), Star::TYPE_SUI_QIAN);
        // 龙德
        $this->longDe = Star::create('龙德', $this->clockwiseMove($this->suiJian->getZhi(), 7), Star::TYPE_SUI_QIAN);
        // 白虎
        $this->baiHu = Star::create('白虎', $this->clockwiseMove($this->suiJian->getZhi(), 8), Star::TYPE_SUI_QIAN);
        // 天德
        $this->tianDe1 = Star::create('天德', $this->clockwiseMove($this->suiJian->getZhi(), 9), Star::TYPE_SUI_QIAN);
        // 吊客
        $this->diaoKe = Star::create('吊客', $this->clockwiseMove($this->suiJian->getZhi(), 10), Star::TYPE_SUI_QIAN);
        // 病符
        $this->bingFu2 = Star::create('病符', $this->clockwiseMove($this->suiJian->getZhi(), 11), Star::TYPE_SUI_QIAN);
    }

    public function getSuiQianStars() {
        return $this->suiQianStars;
    }

    /**
     * 获取所有星曜
     */
    public function getFormatAllStars() {
        return [
            'masterStars' => $this->formatToArray($this->masterStars),
            'luckyStars' => $this->formatToArray($this->luckyStars),
            'unluckyStars' => $this->formatToArray($this->unluckyStars),
            'otherStars' => $this->formatToArray($this->otherStars),
            'boShiStars' => $this->formatToArray($this->boShiStars),
            'changShengStars' => $this->formatToArray($this->changShengStars),
            'jiangQianStars' => $this->formatToArray($this->jiangQianStars),
            'suiQianStars' => $this->formatToArray($this->suiQianStars),
        ];
    }

    private function formatToArray($starsInPlace) {
        foreach ($starsInPlace as $i => $stars) {
            foreach ($stars as $p => $star) {
                $starsInPlace[$i][$p] = $star->getInfo();
            }
        }
        return $starsInPlace;
    }

    /**
     * 获取所有星曜
     */
    public function getAllStars() {
        $stars = [
            $this->ziWei,
            $this->tianJi,
            $this->sunStar,
            $this->wuQu,
            $this->tianTong,
            $this->lianZhen,
            $this->tianFu,
            $this->taiYin,
            $this->tanLang,
            $this->juMen,
            $this->tianXiang,
            $this->qiSha,
            $this->tianLiang,
            $this->poJun,

            $this->zuoFu,
            $this->youBi,
            $this->wenQu,
            $this->wenChang,
            $this->tianKui,
            $this->tianYue,
            $this->qingYang,
            $this->tuoLuo,
            $this->diJie,
            $this->diKong,
            $this->huoXing,
            $this->lingXing,

            $this->tianMa,
            $this->luCun,
            $this->hongLuan,
            $this->tianXi,
            $this->xianChi,
            $this->tianYao,
            $this->tianXing,
            $this->yinSha,
            $this->tianYue1,
            $this->tianWu,
            $this->tianKu,
            $this->tianXu,
            $this->longChi,
            $this->fengGe,
            $this->huaGai,
            $this->jieSha,
            $this->guChen,
            $this->guaSu,
            $this->poSui,
            $this->daHao,
            $this->tianDe,
            $this->jieShen,
            $this->taiFu,
            $this->fengGao,
            $this->feiLian,
            $this->tianGuan,
            $this->tianFu1,
            $this->jieKong,
            $this->sanTai,
            $this->baZuo,
            $this->tianGui,
            $this->enGuang,
            $this->tianCai,
            $this->tianShou,
            $this->tianShang,
            $this->tianShi,
            $this->xunKong,

            $this->boShi,
            $this->liShi,
            $this->qingLong,
            $this->xiaoHao,
            $this->jiangJun,
            $this->zouShu,
            $this->feiLian1,
            $this->xiShen,
            $this->bingFu,
            $this->daHao1,
            $this->fuBing,
            $this->guanFu,

            $this->changSheng,
            $this->muYu,
            $this->guanDai,
            $this->linGuan,
            $this->diWang,
            $this->shuai,
            $this->bing,
            $this->si,
            $this->mu,
            $this->jue,
            $this->tai,
            $this->yang,

            $this->jiangXing,
            $this->panAn,
            $this->suiYi,
            $this->xiShen1,
            $this->huaGai1,
            $this->jieSha1,
            $this->zaiSha,
            $this->tianSha,
            $this->zhiBei,
            $this->xianChi1,
            $this->yueSha,
            $this->wangShen,

            $this->suiJian,
            $this->huiQi,
            $this->sangMen,
            $this->guanSuo,
            $this->guanFu1,
            $this->xiaoHao1,
            $this->daHao2,
            $this->longDe,
            $this->baiHu,
            $this->tianDe1,
            $this->diaoKe,
            $this->bingFu2,
        ];

        return $stars;
    }

    /**
     * 获取所有星的位置
     */
    public function getAllStarsPos() {
        $stars = [];
        foreach ($this->getAllStars() as $star) {
            $stars[$star->getName()] = $star->getPos();
        }
    }
}
