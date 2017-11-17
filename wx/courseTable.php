<?php
require_once "signature.php";
$jssdk = new JSSDK();
$signPackage = $jssdk->getSignPackage();
//var_dump($signPackage["ticket"]);
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">

<head>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来。
            appId: '<?php echo $signPackage["appId"];?>', 
            timestamp: <?php echo $signPackage["timestamp"];?> , // 必填，生成签名的时间戳
            nonceStr: '<?php echo $signPackage["nonceStr"];?>', // 必填，生成签名的随机串
            signature: '<?php echo $signPackage["signature"];?>',// 必填，签名，见附录1
            jsApiList: [
            "onMenuShareTimeline",
            "onMenuShareAppMessage"
            ] 
        });
        
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: '课程表', // 分享标题
                link: "http://lwl.bingyan.net/courseTable.php", //享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '', // 分享图标
                success: function () { 
        // 用户确认分享后执行的回调函数
                    alert('success');
                },
                cancel: function () { 
        // 用户取消分享后执行的回调函数
                    alert('fail');
                }
            });
        });
        
        wx.error(function(res){
            alert(res.errMsg);
        }); 
    </script>
</head>

<body>
    <table border="1">
        <caption>秋季课程表</caption>
        <thead>
            <tr>
                <th>科目</th>
                <th>星期一</th>
                <th>星期二</th>
                <th>星期三</th>
                <th>星期四</th>
                <th>星期五</th>
                <th>星期六</th>
                <th>星期日</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <th rowspan="2">上午</th>
            <td>微积分</td>
            <td>管理经济学</td>
            <td>&nbsp休息</td>
            <td>思想修养与品德</td>
            <td>管理经济学</td>
            <td rowspan="6">&nbsp休息&nbsp</td>
            <td rowspan="6">&nbsp休息&nbsp</td>
        </tr>
        <tr>
            <td>&nbspC++</td>
            <td>&nbsp篮球</td>
            <td>大学英语</td>
            <td>&nbsp&nbspC++</td>
            <td>&nbsp微积分</td>

        </tr>
        <tr>
            <th rowspan="2">下午</th>
            <td>大学英语</td>
            <td>会计学原理</td>
            <td>&nbsp微积分</td>
            <td>&nbsp休息</td>
            <td>会计学原理</td>
        </tr>
        <tr>
            <td>&nbsp休息</td>
            <td>&nbsp休息</td>
            <td>&nbsp休息</td>
            <td>&nbsp休息</td>
            <td>&nbsp休息</td>

        </tr>
        <tr>
            <th rowspan="2">晚上</th>
            <td rowspan="2">VB程序设计</td>
            <td rowspan="2">C++上机实验</td>
            <td rowspan="2">VB程序设计</td>
            <td rowspan="2">&nbsp休息</td>
            <td >&nbsp自习</td>
        </tr>
        <br/>
        <tr>
            <td>&nbsp班会</td>
        </tr>
</tbody>
</body>
</html>