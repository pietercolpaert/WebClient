<?
if(isset($_GET['from'])){
     header_remove();
     if(isset($_GET["to"]) && $_GET["to"] != ""){
		header('Location: /board/'.$_GET['from'].'/'.$_GET['to'].'/');			
	}else{
		header('Location: /board/'.$_GET['from'].'/');					
	}
}
?>
<!DOCTYPE html>
<html lang="en" appcache="/appcache.mf">    
    <head>
	<meta name="apple-mobile-web-app-capable"  content="yes" />
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.6, user-scalable=no" />
        <meta name="keywords" content="nmbs, sncb, iphone, mobile, irail, irail.be, route planner"/>
        <meta name="description" content="NMBS/SNCB mobile iPhone train route planner."/>
	<!-- as not every OS supports HTML-less icon detection, provide this in details, and link to imgage dir instead of root -->
	<!-- 1. iPhone 4/retina --> 
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="./templates/iRail/images/apple-touch-icon-114x114-precomposed.png">
	<!-- iPad G1 -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="./templates/iRail/images/apple-touch-icon-72x72-precomposed.png">
	<!-- non-retina iPhone, iPod Touch, Android 2.1+ -->
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="./templates/iRail/images/apple-touch-57x57-icon-precomposed.png">
	<!-- everything else, provider higher resolution img -->
	<link rel="apple-touch-icon-precomposed" href="./templates/iRail/images/apple-touch-icon-precomposed.png">
        <title>iRail.be</title>
        <link rel="shortcut icon" href="/favicon.ico"/>
        <link rel="stylesheet" type="text/css" href="/templates/iRail/css/main.css" />
        
		        <script src="/templates/iRail/js/main.js"></script>
		<script>
		window.addEventListener('load', function(e) {
			  window.applicationCache.update();
		  window.applicationCache.addEventListener('updateready', function(e) {
			if (window.applicationCache.status == window.applicationCache.UPDATEREADY) {
			  window.applicationCache.swapCache();
			window.location.reload();	  
			}
		  }, false);

		}, false);

      var stations= [<? foreach($content["station"] as $station){
	   echo "\"" . $station["name"] . "\",";
      } ?>];
	  
		var _arrElements = [ "from", "to", "search" ];

		document.onkeyup = KeyCheck;       
		function KeyCheck(evt) {
			if (typeof evt == "undefined" || !evt)
				evt = window.event; //IE...
		   
			var KeyID = evt.which || evt.keyCode;
			var flag = 0;
			switch(KeyID) {
				 case 13:
					 flag = 1; //Forward
					 break;
			}
			if (flag == 0)
				return; //key is not relevant
			
			var sender = evt.target || evt.srcElement;
			if (!sender)
				return; //key up did not come from valid element
			
			var nIndex = -1;
			for (var i = 0; i < _arrElements.length; i++) {
				if (sender.id == _arrElements[i]) {
					nIndex = i;
					break;
				}
			}
			if (nIndex < 0)
				return; //key up did not come from valid textbox
			
			var newIndex = nIndex + flag;
			if (newIndex >= _arrElements.length)
				newIndex = 0;
			if (newIndex < 0)
				newIndex = _arrElements.length - 1;
			document.getElementById(_arrElements[newIndex]).focus();
		}
      </script>
    </head>
    <body onclick="removeAllHolders()" class="bckgroundDarkGrey">
        <div class="MainContainer">
		<form method="get" action="" id="board" name="board">
            <div class="bannerContainer">
                <div class="bannerCubeContainerFixedLogo gradient" style="cursor: pointer;" onclick="window.location='/'">
                    <div class="Top">iRail</div>
                    <div class="Bot">
                        <div class="blackFlagColor"></div>
                        <div class="yelFlagColor"></div>
                        <div class="redFlagColor"></div>
                    </div>
                </div>
                <a href="/route/"><div class="bannerCubeContainerFixed gradientBanner"><?=$i18n["route"] ?></div></a>
                <a href="/board/"><div class="bannerCubeContainerFixed bannerLinkActive removeBorderLeft"><?=$i18n["board"] ?></div></a>
                <a href="/settings/"><div class="bannerCubeContainerFixedSettings gradientBanner"><img style="margin-top: 15px;" src="/templates/iRail/images/settings.png" alt="set" height="18" width="14"/></div></a>
                <div class="bannerCubeContainerScaleFill gradientBanner"></div>
            </div>
            <div class="searchContainer">
                <div class="containerFrom">
                    <div class="fillDotLeft"></div>
                    <div class="fillDotRight"></div>
                    <div class="listButton">
                        <div class="buttonFav"><a href="/stations/"><img src="/templates/iRail/images/fav.png" alt="favorite" width="40" height="25" class="floatRight"/></a></div>
                    </div>
                    <div class="fromHeader"><label for="from"><?=$i18n["of"] ?></label></div>
                </div>
<?
$last = $this->user->getLastUsedBoard();
$lastof = $last["of"];
$lastto = $last["to"];
?>
                <div class="inputFrom">
                    <input autocomplete="off" placeholder="<?=$i18n["ofStation"] ?>" onKeyPress="return disableEnterKey(event);" onkeyup="autoComplete('from', event); changeActiveAutoCompletion('from', event)" class="inputStyle" type="text" id="from" name="from" value="<?=$last["of"]?>"/>
					<div id="autoCmpletefrom" class="autoCmpletefrom">
                    </div>
				</div>
                <div class="inputChange"><img class="pointer" src="/templates/iRail/images/change.png" onclick="swap_From_To()" alt="change" width="25" height="30"/></div>
                <div class="inputMid"></div>
                <div class="toHeader"><label for="to"><?=$i18n["to_optional"] ?></label></div>
                <div class="inputTo">
                    <input autocomplete="off" placeholder="<?=$i18n["to_optionalStation"] ?>" onKeyPress="return disableEnterKey(event);" onkeyup="autoComplete('to', event); changeActiveAutoCompletion('to', event)" class="inputStyle" type="text" id="to" name="to" value="<?=$last["to"]?>"/>
                    <div id="autoCmpleteto" class="autoCmpleteto">
                    </div>               
			   </div>

            </div>
            <div class="subMenuContainer">
                <div class="containerSubMenuBtn">
                    <div class="centerDivBtn">
                        <input class="gradientBtnSearch Btn" type="submit" name="search" id="search" value="<?=$i18n["show_live_board"] ?>"/>
                    </div>
<?
     if(isset($_GET["search"]) && !$_GET["from"]){
	  print "<p style=\"padding: 10px; color: #FFFFFF;\">". $i18n["errSubmitBoard"] . "</p>";
     }
?>
                </div>
            </div>
		</form>
        </div>
		<? include_once("templates/iRail/footer.php"); ?>
    </body>
</html>