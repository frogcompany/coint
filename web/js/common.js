var agent = navigator.userAgent;
if(agent.search(/iPad/) != -1){
	$("meta[name=viewport]").attr("content", "width=1060");
}

$('#btnregist').click(function () {
    $('#contactcol').addClass("active");
    $('html').addClass("active");
    $('.btnclose').addClass("active");
});

$('.btn1').click(function () {
    $('#contactcol').addClass("active");
    $('html').addClass("active");
    $('.btnclose').addClass("active");
});

$('.btnprivacy').click(function () {
    $('#privacycol').addClass("active");
    $('html').addClass("active");
    $('.btnclose').addClass("active");
});

$('.btnuse').click(function () {
    $('#usecol').addClass("active");
    $('html').addClass("active");
    $('.btnclose').addClass("active");
});

$('.btnlaw').click(function () {
    $('#lawcol').addClass("active");
    $('html').addClass("active");
    $('.btnclose').addClass("active");
});

$('.btnclose').click(function () {
    $('#contactcol').removeClass("active");
    $('#privacycol').removeClass("active");
    $('#lawcol').removeClass("active");
    $('#usecol').removeClass("active");
    $('html').removeClass("active");
    $('.btnclose').removeClass("active");
});

$(document).ready(function(){
    $("#topBtn").hide();
    $(window).on("scroll", function() {
        if ($(this).scrollTop() > 120) {
            $("#topBtn").fadeIn("normal");
        } else {
            $("#topBtn").fadeOut("normal");
        }
        scrollHeight = $(document).height(); //ドキュメントの高さ 
        scrollPosition = $(window).height() + $(window).scrollTop(); //現在地 
        footHeight = $("footer").innerHeight(); //footerの高さ（＝止めたい位置）
        if ( scrollHeight - scrollPosition  <= footHeight ) { //ドキュメントの高さと現在地の差がfooterの高さ以下になったら
            $("#topBtn").css({
                "position":"fixed", //pisitionをabsolute（親：wrapperからの絶対値）に変更
                "bottom": footHeight + 10 //下からfooterの高さ + 20px上げた位置に配置
            });
        } else { //それ以外の場合は
            $("#topBtn").css({
                "position":"fixed", //固定表示
                "bottom": "0" //下から20px上げた位置に
            });
        }
    });
    $('#topBtn').click(function () {
        $('body,html').animate({
        scrollTop: 0
        }, 400);
        return false;
    });
});


$(function(){
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
          if (target.length) {
            $('html,body').animate({
              scrollTop: target.offset().top - 50
            }, 600);
        	$body.removeClass('open');
            return false;
          }
        }
    });
});

$(function(){
    var delaySpeed = 300;
    var fadeSpeed = 1000;
    $(window).scroll(function(){
        var obj_t_pos = $('#maincol').offset().top - 200;
        var scr_count = $(document).scrollTop();
        if(scr_count > obj_t_pos){ // スクロール量が、指定した要素の位置を超えたら発火
            $('#btnregist').addClass('active'); 
            $('#maincol article').each(function(i){
                $(this).delay(i*(delaySpeed)).animate({opacity:'1',marginTop:'0px'},fadeSpeed);
            });
        }else{
            $('#btnregist').removeClass();
        }
    })
})