<?php
namespace scientistpun\ziwei\util;

class ZiWeiStars {
    // ------ 14主星 ------
    private array $masterStars;
    // 紫薇
    private int $ziWeiPos;
    // 天机
    private int $tianJiPos;
    // 太阳
    private int $sunStarPos;
    // 武曲
    private int $wuQuPos;
    // 天同
    private int $tianTongPos;
    // 廉贞
    private int $lianZhenPos;
    // 天府
    private int $tianFuPos;
    // 太阴
    private int $taiYinPos;
    // 贪狼
    private int $tanLangPos;
    // 巨门
    private int $juMenPos;
    // 天相
    private int $tianXiangPos;
    // 七杀
    private int $qiShaPos;
    // 天梁
    private int $tianLiangPos;
    // 破军
    private int $poJunPos;


    // ------ 6吉星 ------
    private array $luckyStars;
    // 左辅
    private int $zuoFuPos;
    // 右弼
    private int $youBiPos;
    // 文曲
    private int $wenQuPos;
    // 文昌
    private int $wenChangPos;

    /**
     * 定紫微14主星
     */
    public function set14MasterStars ($lunarBirthDay, $wuXingJu) {
        $juShu = ZiWeiUtil::WU_XING_NUM[$wuXingJu];
        $multi = $lunarBirthDay / $juShu;

        // 生日比局数小
        if ($lunarBirthDay <= $juShu) {
            $move = $lunarBirthDay;
            $lack = $juShu - $lunarBirthDay;
        // 可以被整除
        } elseif ($multi == intval($lunarBirthDay / $juShu)) {
            $move = $multi;
            $lack = 0;
        } else {
            $move = ceil($multi);
            $lack = $juShu - ($lunarBirthDay % $juShu);
        }

        // 判断差数的奇偶性并计算新的数字
        $move = $lack % 2 == 0 ? $move + $lack : $move - $lack;

        // 紫微 从寅宫开始顺时针排列
        $pos = ($move + 1) % 12;
        $this->ziWeiPos = $pos; 

        // 天机 紫微逆行一格
        $tianJiPos = $pos > 0 ? $pos - 1:11;
        $this->tianJiPos = $tianJiPos; 

        // 太阳 天机逆行两格
        $sunPos = $tianJiPos - 2;
        if ($sunPos < 0) $sunPos = $sunPos + 12;
        $this->sunStarPos = $sunPos; 

        // 武曲 太阳逆行一格
        $wuQuPos = $sunPos > 0 ? $sunPos - 1:11;
        $this->wuQuPos = $wuQuPos; 

        // 天同 武曲逆行一格
        $tianTongPos = $wuQuPos > 0 ? $wuQuPos - 1:11;
        $this->tianTongPos = $tianTongPos; 

        // 廉贞 天同逆行三格
        $lianZhenPos = $tianTongPos - 3;
        if ($lianZhenPos < 0) $lianZhenPos = $lianZhenPos + 12;
        $this->lianZhenPos = $lianZhenPos; 

        // 七杀 和紫微形成斜对面
        $qiShaPos = $pos > 10 ? 11:10 - $pos;
        $this->qiShaPos = $qiShaPos; 

        // 天梁 七杀逆行一格
        $tianLiangPos = $qiShaPos > 0 ? $qiShaPos - 1:11;
        $this->tianLiangPos = $tianLiangPos; 

        // 巨门 天梁逆行两格
        $juMenPos = $tianLiangPos - 2;
        if ($juMenPos < 0) $juMenPos = $juMenPos + 12;
        $this->juMenPos = $juMenPos; 

        // 贪狼 巨门逆行一格
        $tanLangPos = $juMenPos > 0 ? $juMenPos - 1:11;
        $this->tanLangPos = $tanLangPos; 

        // 太阴 贪狼逆行一格
        $taiYinPos = $tanLangPos > 0 ? $tanLangPos - 1:11;
        $this->taiYinPos = $taiYinPos; 

        // 破军 太阴逆行三格
        $poJunPos = $taiYinPos - 3;
        if ($poJunPos < 0) $poJunPos = $poJunPos + 12;
        $this->poJunPos = $poJunPos; 

        // 天府 对宫七杀
        $tianFuPos = $qiShaPos >= 6 ? $qiShaPos - 6:$qiShaPos + 6;
        $this->tianFuPos = $tianFuPos; 

        // 天相 对宫破军
        $tianXiangPos = $poJunPos >= 6 ? $poJunPos - 6:$poJunPos + 6;
        $this->tianXiangPos = $tianXiangPos;

        $stars = [];
        for ($i=0; $i < 12; $i++) { 
            $stars[$i] = [];
            if ($pos == $i) $stars[$i][] = '紫微';
            if ($tianJiPos == $i) $stars[$i][] = '天机';
            if ($sunPos == $i) $stars[$i][] = '太阳';
            if ($wuQuPos == $i) $stars[$i][] = '武曲';
            if ($tianTongPos == $i) $stars[$i][] = '天同';
            if ($lianZhenPos == $i) $stars[$i][] = '廉贞';
            if ($qiShaPos == $i) $stars[$i][] = '七杀';
            if ($tianLiangPos == $i) $stars[$i][] = '天梁';
            if ($juMenPos == $i) $stars[$i][] = '巨门';
            if ($tanLangPos == $i) $stars[$i][] = '贪狼';
            if ($taiYinPos == $i) $stars[$i][] = '太阴';
            if ($poJunPos == $i) $stars[$i][] = '破军';
            if ($tianFuPos == $i) $stars[$i][] = '天府';
            if ($tianXiangPos == $i) $stars[$i][] = '天相';
        }
        $this->masterStars = $stars;
    }

    /**
     * 定6吉星
     */
    public function set6LuckyStars ($monthZhiIdx, $hourZhiIndex) {
        // 左辅
        $zuoFuPos = 4 + $monthZhiIdx;
        if ($zuoFuPos > 11) $zuoFuPos = $zuoFuPos - 12;

        // 右弼
        $youBiPos = $monthZhiIdx > 10 ? 11:10 - $monthZhiIdx;

        // 文曲
        $wenQuPos = 4 + $hourZhiIndex;
        if ($wenQuPos > 11) $wenQuPos = $wenQuPos - 12;

        // 文昌
        $wenChangPos = $hourZhiIndex > 10 ? 11:10 - $hourZhiIndex;

        $this->zuoFuPos = $zuoFuPos;
        $this->youBiPos = $youBiPos;
        $this->wenQuPos = $wenQuPos;
        $this->wenChangPos = $wenChangPos;

        $stars = [];
        for ($i=0; $i < 12; $i++) { 
            $stars[$i] = [];
            if ($zuoFuPos == $i) $stars[$i][] = '左辅';
            if ($youBiPos == $i) $stars[$i][] = '右弼';
            if ($wenQuPos == $i) $stars[$i][] = '文曲';
            if ($wenChangPos == $i) $stars[$i][] = '文昌';
        }
        $this->luckyStars = $stars;
    }

    /**
     * 获取14主星
     */
    public function getMasterStars() {
        return $this->masterStars;
    }

    /**
     * 获取6吉星
     */
    public function getLuckyStars() {
        return $this->luckyStars;
    }


    /**
     * 获取所有星曜
     */
    public function getAllStars() {
        return [
            'masterStars' => $this->masterStars,
            'luckyStars' => $this->luckyStars
        ];
    }
}