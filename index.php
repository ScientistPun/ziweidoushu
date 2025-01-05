<?php
require 'vendor/autoload.php';

use scientistpun\ziwei\ZiWeiDouShu;



$searchDate = strtotime('1996-5-25');
$searchHour = 10;
$searchGender = false;

$type = $_POST['type'] ?? 'solar';
$birthday = $_POST['birthday'] ?? date('Y-m-d', $searchDate);
list($year, $month, $day) = explode('-', $birthday);
$hour = $_POST['hour'] ?? $searchHour;
$gender = isset($_POST['gender']) ? $_POST['gender'] == 1:$searchGender;

$ziwei = ZiWeiDouShu::fromYmdH($year, $month, $day, $hour, $gender);
$profile = $ziwei->getPersonal();
$twelvePalace = $ziwei->paiPan();
$mingPan = $ziwei->getMingPan();

$daXianPos = isset($_POST['da_xian_pos']) ? intval($_POST['da_xian_pos']):2;
$liuNian = isset($_POST['liu_year']) ? intval($_POST['liu_year']) : date('Y');

$daXianPan = $ziwei->getDaXianPan($daXianPos);
$liuNianPan = $ziwei->getLiuNianPan($liuNian);

echo '<pre>';
// print_r($daXianPan);
echo '</pre>';

// 矩阵
static $ROW_POS = [3, 3, 3, 2, 1, 0, 0, 0, 0, 1, 2, 3];
static $COL_POS = [2, 1, 0, 0, 0, 0, 1, 2, 3, 3, 3, 3];
for ($i=0; $i < 12; $i++) { 
    $places[$ROW_POS[$i]][$COL_POS[$i]] = array_merge($twelvePalace[$i], ['dxPlace'=>'大'.mb_substr($daXianPan['palaces'][$i], 0, 1), 'yearPlace'=>'年'.mb_substr($liuNianPan['palaces'][$i], 0, 1)]);
}
for ($row=0; $row < 4; $row++) { 
    for ($col=0; $col < 4; $col++) {
        if (!isset($places[$row][$col])) {
            $places[$row][$col] = null;
        }
    }
}

echo '<pre>';

// print_r($twelvePalace);
// print_r($liuNianPan);
echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="//unpkg.com/layui@2.9.21-rc.3/dist/css/layui.css" rel="stylesheet">
    <style>
        .grid{
            padding: 20px 10px;
            width: 590px;
            margin:0px auto 400px;
        }
        .grid .grid-item{
            float: left;
            border: 1px solid #000;
            height: 208px;
            width: 138px;
            border-left: none;
            border-bottom: none;
        }
        .grid .layui-row .grid-item:first-of-type{
            border-left: 1px solid #000;
        }
        .grid .layui-row:last-of-type .grid-item{
            border-bottom: 1px solid #000;
        }
        .grid-item-desc{color: #0000aa;}
        .star{width: 14px; margin-left: 1px; display: inline-block; float: right; font-size: 13px;}
        .master-star{color: red;}
        .lucky-star{color:#7100bc;}
        .unlucky-star{color: #2f363c;}
        .other-star{color:#00589c;}
        .star > .layui-badge {font-size: 12px; padding: 0 1px; margin-top: 1px;} 
    </style>
</head>
<body>
    <div class="grid">
        
        <form class="layui-form" action="" method="post">
            <div class="layui-row">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label" style="width: 60px; padding: 9px 5px;">出生时间</label>

                        <div class="layui-input-inline layui-input-wrap" style="padding: 0; margin-right: 4px; border: none; width: 80px;">
                            <select name="type" lay-filter="type">
                                <option value="solar" <?php if ($type == 'solar') echo 'selected'; ?>>新历</option>
                                <option value="lunar" <?php if ($type == 'lunar') echo 'selected'; ?>>农历</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-input-wrap" style="width: 120px; margin-right: 4px; ">
                            <input type="text" class="layui-input" name="birthday" id="birthday" placeholder="yyyy-MM-dd HH">
                        </div>
                        <div class="layui-input-inline layui-input-wrap" style="padding: 0; margin-right: 4px; border: none; width: 130px;">
                            <select name="hour" lay-filter="hour">
                                <? for ($i=0; $i < 24; $i++) { 
                                    echo "<option value='{$i}' ".($hour == $i ? 'selected':'')." >{$i}:00 - {$i}:59</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-input-wrap" style="padding: 0; border: none; width: 60px;">
                            <select name="gender" lay-filter="gender">
                                <option value="1" <?php if ($gender) echo 'selected'; ?>>男</option>
                                <option value="0" <?php if (!$gender) echo 'selected'; ?>>女</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label" style="width: 60px; padding: 9px 5px;">大限</label>
                        <div class="layui-input-inline layui-input-wrap" style="padding: 0; margin-right: 4px; border: none; width: 120px;">
                            <select name="da_xian_pos" lay-filter="da-xian-pos">
                                <? foreach ($profile['daXianRange'] as $pos => $range) {
                                    echo "<option value='{$pos}' ".($daXianPos == $pos ? 'selected':'')." >{$range['begin']} - {$range['end']}</option>";
                                } ?>
                            </select>
                        </div>
                        <label class="layui-form-label" style="width: 60px; padding: 9px 5px;">流年</label>
                        <div class="layui-input-inline layui-input-wrap" style="width: 120px; margin-right: 4px; ">
                            <input type="text" class="layui-input" name="liu_year" id="liu-year" placeholder="yyyy">
                        </div>
                        <div class="layui-form-mid" style="padding: 0!important;"> 
                            <input type="submit"  class="layui-btn layui-btn-primary" lay-submit lay-filter="search" value="查询" >
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <? for ($r=0; $r < 4; $r++) { ?>
            <div class="layui-row">
                <? for ($c=0; $c < 4; $c++) { ?>
                    <? if (($r == 1 || $r == 2) && $c == 1) { ?>
                    <div class="grid-item layui-padding-1 layui-font-14" style="width: 285px; <? echo $r == 2 ? 'border-top:none;':''; ?>">
                        <? 
                            if ($r == 1) {
                                echo '<p class="">'.($profile['yinYang'] ? '阳':'阴').($profile['gender'] ? '男':'女').'</p>'; 
                                echo '<p class="">新历：'.$profile['solar'].' '.$profile['xingZuo'].'</p>'; 
                                echo '<p class="">农历：'.$profile['lunar'].'</p>'; 
                                echo '<p class="">四柱：'.implode(' ', $profile['bazi']).'</p>'; 
                                echo '<p class="">命局：'.$profile['wuXing']['naYin'].'</p>'; 
                                echo '<p class="">命主：'.$profile['mingZhu'].'</p>'; 
                                echo '<p class="">身主：'.$profile['shenZhu'].'</p>'; 
                            } elseif ($r == 2) {
                                echo '<p class="">本命：';
                                foreach ($mingPan['siHua'] as $key => $sihua) {
                                    echo $sihua['star'].$sihua['title'] . ' ';
                                }
                                echo '</p>'; 
                                echo '<p class="">大限：';
                                foreach ($daXianPan['siHua'] as $key => $sihua) {
                                    echo $sihua['star'].$sihua['title'] . ' ';
                                }
                                echo '</p>'; 
                                echo '<p class="">流年：';
                                foreach ($liuNianPan['siHua'] as $key => $sihua) {
                                    echo $sihua['star'].$sihua['title'] . ' ';
                                }
                                echo '</p>'; 
                            }
                        ?>
                    </div>
                    <? } elseif (($r == 1 || $r == 2) && $c == 2) { 
                        echo ''; 
                        } else { ?>
                    <div class=" grid-item layui-padding-1">
                        <table style="width: 100%; height: 100%;">
                        <? 
                            if (isset($places[$r][$c])) {
                                $place = $places[$r][$c];

                                echo '<tr style="vertical-align:top;">';
                                echo '<td style="text-align: right; height: 112px;" colspan="3">';
                                if (is_array($place['stars']['masterStars'])) {
                                    foreach ($place['stars']['masterStars'] as $star) {
                                        echo "<span class='master-star star'>{$star['name']} <small class='layui-font-12 layui-font-gray'>{$star['brightSubTitle']}</small>";
                                        if ($mingPan['siHua']['lu']['star'] == $star['name']) echo "<small class='layui-badge'>禄</small>";
                                        if ($mingPan['siHua']['quan']['star'] == $star['name']) echo "<small class='layui-badge'>权</small>";
                                        if ($mingPan['siHua']['ke']['star'] == $star['name']) echo "<small class='layui-badge'>科</small>";
                                        if ($mingPan['siHua']['ji']['star'] == $star['name']) echo "<small class='layui-badge'>忌</small>";
                                        if ($daXianPan['siHua']['lu']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>禄</small>";
                                        if ($daXianPan['siHua']['quan']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>权</small>";
                                        if ($daXianPan['siHua']['ke']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>科</small>";
                                        if ($daXianPan['siHua']['ji']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>忌</small>";
                                        if ($liuNianPan['siHua']['lu']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>禄</small>";
                                        if ($liuNianPan['siHua']['quan']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>权</small>";
                                        if ($liuNianPan['siHua']['ke']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>科</small>";
                                        if ($liuNianPan['siHua']['ji']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>忌</small>";
                                        echo "</span>";
                                    }
                                }

                                if (is_array($place['stars']['luckyStars'])) {
                                    foreach ($place['stars']['luckyStars'] as $star) {
                                        echo "<span class='lucky-star star'>{$star['name']} <small class='layui-font-12 layui-font-gray'>{$star['brightSubTitle']}</small>";
                                        if ($mingPan['siHua']['lu']['star'] == $star['name']) echo "<small class='layui-badge'>禄</small>";
                                        if ($mingPan['siHua']['quan']['star'] == $star['name']) echo "<small class='layui-badge'>权</small>";
                                        if ($mingPan['siHua']['ke']['star'] == $star['name']) echo "<small class='layui-badge'>科</small>";
                                        if ($mingPan['siHua']['ji']['star'] == $star['name']) echo "<small class='layui-badge'>忌</small>";
                                        if ($daXianPan['siHua']['lu']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>禄</small>";
                                        if ($daXianPan['siHua']['quan']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>权</small>";
                                        if ($daXianPan['siHua']['ke']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>科</small>";
                                        if ($daXianPan['siHua']['ji']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-green'>忌</small>";
                                        if ($liuNianPan['siHua']['lu']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>禄</small>";
                                        if ($liuNianPan['siHua']['quan']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>权</small>";
                                        if ($liuNianPan['siHua']['ke']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>科</small>";
                                        if ($liuNianPan['siHua']['ji']['star'] == $star['name']) echo "<small class='layui-badge layui-bg-blue'>忌</small>";
                                        echo "</span>";
                                    }
                                }
                                if (is_array($place['stars']['unluckyStars'])) {
                                    foreach ($place['stars']['unluckyStars'] as $star) {
                                        echo "<span class='unlucky-star star'>{$star['name']} <small class='layui-font-12 layui-font-gray'>{$star['brightSubTitle']}</small></span>";
                                    }
                                }
                                if (is_array($place['stars']['otherStars'])) {
                                    foreach ($place['stars']['otherStars'] as $star) {
                                        echo "<span class='other-star star'>{$star['name']}</span>";
                                    }
                                }
                                echo '</td>'; 
                                echo '</tr><tr style="vertical-align:top;">';
                                echo '<td>';
                                echo ($mingPan['laiYin']['zhi'] == $place['zhi'] ? '<span class="layui-font-14 layui-border">来因</span>':'');
                                echo ($mingPan['shen']['zhi'] == $place['zhi'] ? '<span class="layui-font-14 layui-border-red">身宫</span>':'');
                                echo '</td>';
                                echo '<td style="text-align: right;" colspan="2">';
                                echo "<span class='layui-font-12 grid-item-desc'>{$place['daXian']['begin']}-{$place['daXian']['end']}</span><br/>";
                                echo '</td>';
                                echo '</tr><tr style="vertical-align:bottom;">';
                                echo '<td style="text-align:left;">';
                                if (is_array($place['stars']['boShiStars'])) {
                                    foreach ($place['stars']['boShiStars'] as $star) {
                                        echo "<sp`an class='layui-font-12' style='color:#82a6a3;'>{$star['name']}</span><br/>";
                                    }
                                }
                                if (is_array($place['stars']['jiangQianStars'])) {
                                    foreach ($place['stars']['jiangQianStars'] as $star) {
                                        echo "<span class='layui-font-12' style='color:#aaa;'>{$star['name']}</span><br/>";
                                    }
                                }
                                if (is_array($place['stars']['suiQianStars'])) {
                                    foreach ($place['stars']['suiQianStars'] as $star) {
                                        echo "<span class='layui-font-12' style='color:#aaa;'>{$star['name']}</span><br/>";
                                    }
                                }
                                echo '</td><td style="text-align:right;">';
                                echo "<span class='layui-font-14 layui-font-blue'>{$place['yearPlace']}</span><br/>";
                                echo "<span class='layui-font-14 layui-font-green'>{$place['dxPlace']}</span><br/>";

                                echo '<b class="layui-font-14 '.($mingPan['selfPalace']['zhi'] == $place['diZhi'] ? ' layui-badge':' layui-bg-gray').' ">' . $place['name'].'</b>';
                                echo '</td>';
                                echo '<td style="text-align:right;">';
                                if (is_array($place['stars']['changShengStars'])) {
                                    foreach ($place['stars']['changShengStars'] as $star) {
                                        echo "<span class='layui-font-12' style='color: #aaa;'>{$star['name']}</span><br/>";
                                    }
                                }
                                echo '<span class="layui-font-16" style="color: #777;">'.$place['gan'].'<br/>'.$place['zhi'].'</span> <td>';
                                echo '</tr>';

                            }
                        ?>
                        </table>
                        
                    </div>
                    <? } ?>
                <? } ?>
            </div>
        <? } ?>
    </div>
</body>
<!-- 请勿在项目正式环境中引用该 layui.js 地址 -->
<script src="//unpkg.com/layui@2.9.20/dist/layui.js"></script>

<script>
    var actionUrl = window.location.protocol + "//"+ window.location.hostname + ":" + window.location.port;

    layui.use(['form', 'laydate', 'util'], function(){
        var form = layui.form, layer = layui.layer, laydate = layui.laydate;
        var $ = layui.jquery;

        laydate.render({
            elem: '#birthday',
            format: 'yyyy-MM-dd',
            type: 'date',
            max: '<? echo date('Y-m-d'); ?>',
            value: '<? echo $birthday; ?>'
        });

        laydate.render({
            elem: '#liu-year',
            type: 'year',
            btns: ['confirm'],
            value: '<? echo $liuNian; ?>',
            min: '<? echo $year; ?>-1-1',
        });


        form.on('submit(search)', function(data){
            console.log(data.field);
            // return false;
        });
    });
</script>
</html>