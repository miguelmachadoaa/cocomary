<?php 

/********************************************************************************/	
/*
PHP SEO is a tool coded by Juanma Rodríguez AKA Netgrows.
Copyright 2020, Juanma Rodríguez, All rights reserved.	
You can find me at:
	https://twitter.com/netgrows
	https://www.youtube.com/c/NetgrowsES
*/
/********************************************************************************/		
	
	/*
	debugSearchDisplay can be true or false. If true, it will allow you to see search results in your screen (not recommended, only for debugging).
	debugSearchDisplay puede ser true o false. En true, te mostrará los resultados de Google directamente en pantalla (no recomendado, solo para debug).
	*/	
	$debugSearchDisplay=false;

/********************************************************************************/
/*

WARNING - AVISO

ENGLISH: DO NOT TOUCH ANYTHING BELOW THIS POINT UNLESS YOU KNOW WHAT YOU ARE DOING!

SPANISH: ¡NO TOQUES NADA DEBAJO DE ESTA ZONA A MENOS QUE SEPAS LO QUE ESTÁS HACIENDO!

*/
/************************************************************************************/
$phpseoVersion="1.0BETA";

if (isset($_POST['query'])) $query = $_POST['query'];
else $query ="";
if (isset($_POST['pagina'])) $pagina = $_POST['pagina'];
else $pagina ="1";
if (isset($_POST['optionz'])) $optionz = $_POST['optionz'];
else $optionz ="";
if (isset($_POST['googleRegion'])) $googleRegion = $_POST['googleRegion'];
else $googleRegion ="";
if (isset($_POST['webproxy'])) $webproxy = $_POST['webproxy'];
else $webproxy ="noproxy";
if (isset($_GET['module'])) $module = $_GET['module'];
else $module ="";
if (isset($_POST['lang'])) $lang = $_POST['lang'];
else $lang ="";
if (isset($_POST['mainkwsuggest'])) $mainkwsuggest = $_POST['mainkwsuggest'];
else $mainkwsuggest ="";
if (isset($_POST['kwsugPlatform'])) $kwsugPlatform = $_POST['kwsugPlatform'];
else $kwsugPlatform ="";
if (isset($_POST['imagequery'])) $imagequery = $_POST['imagequery'];
else $imagequery ="";
if (isset($_POST['imagesToDownload'])) $imagesToDownload = $_POST['imagesToDownload'];
else $imagesToDownload ="";
if (isset($_POST['plagichecktext'])) $plagichecktext = $_POST['plagichecktext'];
else $plagichecktext ="";
if (isset($_POST['indexedcheckURLs'])) $indexedcheckURLs = $_POST['indexedcheckURLs'];
else $indexedcheckURLs ="";

if ($module=="lastnews"){
	$totalNotificaciones = file_get_contents('https://netgrows.com/notifications/total.php', true);	
	setcookie( "seenNotifications", $totalNotificaciones, time() + (10 * 365 * 24 * 60 * 60) );
}

/*Initialize*/
if (!file_exists("content-extractor-templates.txt")) file_put_contents('content-extractor-templates.txt', 'Template Woo Product@@@product_title###<h[1-3] .*?class=".*?product_title entry-title.*?">###<\/h[1-3]>@@@product_description###<div .*?class=".*?woocommerce-product-details__short-description.*?">###<\/div>'."\n".'Template Yahoo News@@@news_title###<h1 data-test-locator="headline">###<\/h1>@@@news_body###<div class="caas-body">###<\/div>@@@news_date###<time.*?>###<\/time>'."\n".'Template Reddit@@@post_title###<title>###<\/title>@@@post_description###RichTextJSON-root">###<\/div>'."\n".'Template Amazon.com@@@product_title###<span id="productTitle.*?>###<\/span>@@@product_description###<!-- show up to 2 reviews by default -->###<\/div>');
if (!file_exists("web-proxy-list.txt")) file_put_contents('web-proxy-list.txt', 'Sample 1@@@https://mydomain2.com/test/phpseo-proxy.php?password=YOURPASSWORDHERE'."\n".'Sample 2@@@https://mydomain3.com/phpseo-proxy.php?password=YOURPASSWORDHERE');
if (!file_exists("footprints.txt")) file_put_contents('footprints.txt', 'Site@@@site:yourdomain.com'."\n".'Intext@@@intext:yourkeyword'."\n".'Allintext@@@allintext:yourkeyword'."\n".'Allintitle@@@allintitle:yourkeyword'."\n".'Inurl@@@inurl:yourkeyword'."\n".'Allinurl@@@allinurl:yourkeyword'."\n".'Filetype@@@filetype:pdf'."\n".'OR@@@OR keyword'."\n".'AND@@@AND keyword'."\n".'AROUND(X)@@@AROUND(12) youkeyword'."\n".'FB pages@@@site:facebook.com+inurl:about'."\n".'FB groups@@@site:facebook.com/groups'."\n".'FB photos@@@site:facebook.com+inurl:photos'."\n".'TW tweets@@@site:twitter.com+inurl:status'."\n".'IG posts@@@site:instagram.com/p'."\n".'YT videos@@@site:youtube.com+inurl:watch'."\n".'Yahoo news@@@site:news.yahoo.com inurl:html'."\n".'Amazon products@@@site:amazon.com inurl:/dp/'."\n".'Web forums@@@inurl:forums+OR+intitle:forums+OR+intitle:foro'."\n".'Disqus comments@@@%22Powered+by+Disqus%22'."\n".'Facebook comments@@@%22facebook+comments+plugin%22'."\n".'Blogspot comments@@@site:blogspot.com+inurl:html'."\n".'Wordpress comments@@@%22powered+by+wordpress%22+AND+%22leave+a+reply%22'."\n".'Wordpress.com comments@@@site:wordpress.com+%22introduce+tu+comentario%22+OR+%22enter+your+comment%22');

if (!file_exists('data-extractor')) {
    mkdir('data-extractor', 0777, true);
}
if (!file_exists("config.txt")) {
	$langConfig = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	if ($langConfig=="es") $langToSave="es";
	else $langToSave="en";		
	$howManyURLPerStep=3;
	$batchDelaySeconds=20;
	$searchDelaySeconds=15;
	$customUserAgent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36";
	file_put_contents('config.txt', "lang=$langToSave"."\n"."howManyURLPerStep=$howManyURLPerStep"."\n"."batchDelaySeconds=$batchDelaySeconds"."\n"."searchDelaySeconds=$searchDelaySeconds"."\n"."customUserAgent=$customUserAgent");
	$langSelected=$langConfig;	
} else {
	/*Read config*/
	$configVars = file_get_contents('config.txt', true);
	$arrayVars = explode("\n",$configVars);
	foreach ($arrayVars as $varconfig){
		$partesVar = explode("=",$varconfig);		
		if (trim($partesVar[0])=="lang") $langSelected=trim($partesVar[1]);
		if (trim($partesVar[0])=="howManyURLPerStep") $howManyURLPerStep=trim($partesVar[1]);
		if (trim($partesVar[0])=="batchDelaySeconds") $batchDelaySeconds=trim($partesVar[1]);
		if (trim($partesVar[0])=="searchDelaySeconds") $searchDelaySeconds=trim($partesVar[1]);
		if (trim($partesVar[0])=="customUserAgent") $customUserAgent=trim($partesVar[1]);		
	}
	
}
if (!file_exists("search.json")) file_put_contents('search.json', "");

/******************/
/*PROXY GENERATOR*/
/****************/
if ($module=="proxygenerator") {
	
if (isset($_GET['passNewProxy'])) $passNewProxy = $_GET['passNewProxy'];
else echo "ERROR, NO PASSWORD";
	
$output='
<?php
/*BEGIN PROXY CODE*/
	$password="'.$passNewProxy.'";				
	if(isset($_GET["password"])) $passwordGet=@$_GET["password"];
	else $passwordGet="";	
	if ($passwordGet!=$password) {
		die("Wrong password");
	}
	if(isset($_GET["tbm"])) $tbm=@$_GET["tbm"];
	else $tbm="";
	if(isset($_GET["getPage"])) $getPage=$_GET["getPage"];
	else $getPage="";
	if(isset($_GET["lang"])) $lang=@$_GET["lang"];
	else $lang="";	
	if(isset($_GET["querysug"])) $querysug=$_GET["querysug"];
	if(isset($_GET["querysugYT"])) $querysugYT=$_GET["querysugYT"];
	if(isset($_GET["querysugAZ"])) $querysugAZ=$_GET["querysugAZ"];
	
	function getPageGoogle2($proxy, $url, $referer, $agent, $header, $timeout) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");
		curl_setopt($ch, CURLOPT_REFERER, "https://twitter.com/");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$result["EXE"] = curl_exec($ch);
		$result["INF"] = curl_getinfo($ch);
		$result["ERR"] = curl_error($ch);
		curl_close($ch);
		return $result;
	}
	function googleSuggestKeywords2($k, $lang) {
			if (!function_exists("curl_init")) die("googleSuggestKeywords needs CURL module, please install CURL on your php.");	
			$k=urlencode($k);
			$w = "http://suggestqueries.google.com/complete/search?output=toolbar&hl=$lang&lr=lang_$lang&pws=0&gl=us&gws_rd=cr&q=".$k;	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $w);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
			//echo curl_exec($ch);	
			$aux=utf8_encode(curl_exec($ch));
			$xml = simplexml_load_string($aux);	
			curl_close($ch);
			// Parse the keywords 
			$result = $xml->xpath("//@data");
			$ar = array();
			foreach($result as $key => $value) $ar[] = (string)$value;
			return $ar;
	}	
	function youtubeSuggestKeywords2($k, $lang) {
			if (!function_exists("curl_init")) die("googleSuggestKeywords needs CURL module, please install CURL on your php.");	
			$k=urlencode($k);			
			$w = "https://clients1.google.com/complete/search?client=youtube&output=toolbar&gs_ri=youtube&ds=yt&hl=$lang&lr=lang_$lang&q=".$k;			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $w);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
			$content=utf8_encode(curl_exec($ch));		
			$contentArray=explode(",",$content);				
			//delete last and first array elements (trash)
			unset($contentArray[count($contentArray)-1]);
			unset($contentArray[0]);		
			foreach ($contentArray as $element){		
				$elementClean = str_replace(array(\']\',\'[\',\'}\',\'{\',\'"\',\':\'), "", $element);
				if ((!empty($elementClean))&&(strlen($elementClean)>=4)) echo $elementClean."\n";
			}
			curl_close($ch);
	}
	function amzSuggestKeywords2($k, $lang) {
			if (!function_exists("curl_init")) die("amzSuggestKeywords needs CURL module, please install CURL on your php.");	
			$k=urlencode($k);
			$w = "https://completion.amazon.com/search/complete?search-alias=aps&client=amazon-search-ui&mkt=1&q=".$k;	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $w);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
			$content=utf8_encode(curl_exec($ch));		
			$contentArray=explode(",",$content);				
			//delete last and first array elements (trash)
			unset($contentArray[count($contentArray)-1]);
			unset($contentArray[0]);		
			foreach ($contentArray as $element){		
				$elementClean = str_replace(array(\']\',\'[\',\'}\',\'{\',\'"\',\':\'), "", $element);
				if ((!empty($elementClean))&&(strlen($elementClean)>=4)) echo utf8_decode($elementClean)."\n";
			}
			curl_close($ch);
	}	
	$query=$searchPageStarts=$googleRegion="";
	if (isset($_GET["query"])) $query=urlencode($_GET["query"]);
	if (isset($_GET["searchPageStarts"])) $searchPageStarts=$_GET["searchPageStarts"];
	if (isset($_GET["googleRegion"])) $googleRegion=$_GET["googleRegion"];
	if ((isset($querysug))&&($querysug!="")) {
		foreach(range("a","z") as $i){
			$arraysug = googleSuggestKeywords2("$querysug $i", $lang);
			if (is_array($arraysug) || is_object($arraysug)) {			
				foreach ($arraysug as &$value) {
					if (!empty($value)) {
						echo $value;	
						if(end($arraysug)!=$value) echo "\n";					
					}				
				}
			}
		}
	}
	if ((isset($querysugYT))&&($querysugYT!="")) {
		foreach(range("a","z") as $i){
			$arraysug = youtubeSuggestKeywords2("$querysugYT $i", $lang);
			if (is_array($arraysug) || is_object($arraysug)) {			
				foreach ($arraysug as &$value) {
					if (!empty($value)) {
						echo $value;	
						if(end($arraysug)!=$value) echo "\n";					
					}				
				}
			}
		}
	}	
	if ((isset($querysugAZ))&&($querysugAZ!="")) {
		foreach(range("a","z") as $i){
			$arraysug = amzSuggestKeywords2("$querysugAZ $i", $lang);
			if (is_array($arraysug) || is_object($arraysug)) {			
				foreach ($arraysug as &$value) {
					if (!empty($value)) {
						echo $value;	
						if(end($arraysug)!=$value) echo "\n";					
					}				
				}
			}
		}
	}
	if  ((($query!="")&&($searchPageStarts!="")&&($googleRegion!=""))||($tbm!="")) {
		if ($tbm=="isch") { //image search
			$getcontents="https://$googleRegion/search?q=$query&tbm=isch";	
		} else $getcontents="https://$googleRegion/search?q=$query&ion=0&num=100&start=$searchPageStarts";	
		$results = getPageGoogle2("",$getcontents, "", "'.$customUserAgent.'", 1, 25);	
		echo $arraydatos=$results["EXE"];
	}
	if ($getPage!="") {
		$results = getPageGoogle2("",$getPage, "", "'.$customUserAgent.'", 1, 25);	
		echo $arraydatos=$results["EXE"];
	}
/*END PROXY CODE*/	
?>
			';
	$file = "phpseo-proxy.php";
	$txt = fopen($file, "w") or die("Unable to open file!");
	fwrite($txt, $output);
	fclose($txt);
	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename='.basename($file));
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	header("Content-Type: text/plain");
	readfile($file);		
	exit;
}
session_start();
function getPageGoogle($proxy, $url, $referer, $agent, $header, $timeout) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");
	curl_setopt($ch, CURLOPT_REFERER, "https://twitter.com/");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_ENCODING, "");
    $result['EXE'] = curl_exec($ch);
    $result['INF'] = curl_getinfo($ch);
    $result['ERR'] = curl_error($ch);
    curl_close($ch);
    return $result;
}
function googleSuggestKeywords($k, $lang) {
		if (!function_exists("curl_init")) die("googleSuggestKeywords needs CURL module, please install CURL on your php.");	
		$k=urlencode($k);
		$w = "http://suggestqueries.google.com/complete/search?output=toolbar&hl=$lang&lr=lang_$lang&pws=0&gl=us&gws_rd=cr&q=".$k;	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $w);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		$aux=utf8_encode(curl_exec($ch));
		$xml = simplexml_load_string($aux);	
		curl_close($ch);
		// Parse the keywords 
		$result = $xml->xpath('//@data');
		$ar = array();
		foreach($result as $key => $value) $ar[] = (string)$value;
		return $ar;
}
function youtubeSuggestKeywords($k, $lang) {
		if (!function_exists("curl_init")) die("googleSuggestKeywords needs CURL module, please install CURL on your php.");	
		$k=urlencode($k);			
		$w = "https://clients1.google.com/complete/search?client=youtube&output=toolbar&gs_ri=youtube&ds=yt&hl=$lang&lr=lang_$lang&q=".$k;			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $w);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		$content=utf8_encode(curl_exec($ch));		
		$contentArray=explode(",",$content);				
		//delete last and first array elements (trash)
		unset($contentArray[count($contentArray)-1]);
		unset($contentArray[0]);		
		foreach ($contentArray as $element){		
			$elementClean = str_replace(array(']','[','}','{','"',':'), "", $element);
			if ((!empty($elementClean))&&(strlen($elementClean)>=4)) echo $elementClean."\n";
		}
		curl_close($ch);
}
function amzSuggestKeywords($k, $lang) {
		if (!function_exists("curl_init")) die("amzSuggestKeywords needs CURL module, please install CURL on your php.");	
		$k=urlencode($k);
		$w = "https://completion.amazon.com/search/complete?search-alias=aps&client=amazon-search-ui&mkt=1&q=".$k;	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $w);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		$content=utf8_encode(curl_exec($ch));		
		$contentArray=explode(",",$content);				
		//delete last and first array elements (trash)
		unset($contentArray[count($contentArray)-1]);
		unset($contentArray[0]);		
		foreach ($contentArray as $element){		
			$elementClean = str_replace(array(']','[','}','{','"',':'), "", $element);
			if ((!empty($elementClean))&&(strlen($elementClean)>=4)) echo utf8_decode($elementClean)."\n";
		}
		curl_close($ch);
}
function get_subscriber($channel,$use = "user") {
    (int) $subs = 0;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://www.youtube.com/".$use."/".$channel."/about?disable_polymer=1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_POST,           0 ); 
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.youtube.com/');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0');
    $result = curl_exec($ch);
    $R = curl_getinfo($ch);
    if($R["http_code"] == 200) {
        $pattern = '/"subscriberCountText":{"simpleText":"(.*?)}/';
        preg_match($pattern, $result, $matches, PREG_OFFSET_CAPTURE);
        $subs = intval(str_replace(',','',$matches[1][0]));
    }
    if($subs == 0 && $use == "user") return get_subscriber($channel,"channel");
    return $subs;
}
function strip_HTML_tags($text)
{ 	
	//Delete all between curly braces or parenthesis
	$pattern = '~(?:(\()|(\[)|(\{))(?(1)(?>[^()]++|(?R))*\))(?(2)(?>[^][]++|(?R))*\])(?(3)(?>[^{}]++|(?R))*\})~';
	$text= preg_replace($pattern , '', $text);
	
	//Strips HTML 4.01 start and end tags. Preserves contents.
    $text= preg_replace('%
        # Match an opening or closing HTML 4.01 tag.
        </?                  # Tag opening "<" delimiter.
        (?:                  # Group for HTML 4.01 tags.
          ABBR|ACRONYM|ADDRESS|APPLET|AREA|ARTICLE|A|BASE|BASEFONT|BDO|BIG|
          BLOCKQUOTE|BODY|BR|BUTTON|B|CAPTION|CENTER|CITE|CODE|COL|
          COLGROUP|DD|DEL|DFN|DIR|DIV|DL|DT|EM|FIELDSET|FIGURE|FIGCAPTION|FONT|FORM|
          FRAME|FRAMESET|H\d|HEAD|HR|HTML|IFRAME|IMG|INPUT|INS|
          ISINDEX|I|KBD|LABEL|LEGEND|LI|LINK|MAP|MENU|META|NOFRAMES|
          NOSCRIPT|OBJECT|OL|OPTGROUP|OPTION|PARAM|PRE|PATH|P|Q|SAMP|
          SCRIPT|SELECT|SMALL|SPAN|STRIKE|STRONG|STYLE|SUB|SUP|SVG|S|
          TABLE|TD|TBODY|TEXTAREA|TFOOT|TH|THEAD|TITLE|TR|TT|U|UL|VAR
        )\b                  # End group of tag name alternative.
        (?:                  # Non-capture group for optional attribute(s).
          \s+                # Attributes must be separated by whitespace.
          [\w\-.:]+          # Attribute name is required for attr=value pair.
          (?:                # Non-capture group for optional attribute value.
            \s*=\s*          # Name and value separated by "=" and optional ws.
            (?:              # Non-capture group for attrib value alternatives.
              "[^"]*"        # Double quoted string.
            | \'[^\']*\'     # Single quoted string.
            | [\w\-.:]+      # Non-quoted attrib value can be A-Z0-9-._:
            )                # End of attribute value alternatives.
          )?                 # Attribute value is optional.
        )*                   # Allow zero or more attribute=value pairs
        \s*                  # Whitespace is allowed before closing delimiter.
        /?                   # Tag may be empty (with self-closing "/>" sequence.
        >                    # Opening tag closing ">" delimiter.
        | <!--.*?-->         # Or a (non-SGML compliant) HTML comment.
        | <!DOCTYPE[^>]*>    # Or a DOCTYPE.
        %six', '', $text);
	
	return $text;	
}
function validate_email($email) {
    // Check email syntax
    if(preg_match('/^([a-zA-Z0-9\._\+-]+)\@((\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,7}|[0-9]{1,3})(\]?))$/', $email, $matches)) {
        $user = $matches[1];
        $domain = $matches[2];

        // Check availability of DNS MX records
        if(getmxrr($domain, $mxhosts, $mxweight)) {
            for($i=0;$i<count($mxhosts);$i++){
                $mxs[$mxhosts[$i]] = $mxweight[$i];
            }

            // Sort the records
            asort($mxs);
            $mailers = array_keys($mxs);
        } elseif(checkdnsrr($domain, 'A')) {
            $mailers[0] = gethostbyname($domain);
        } else {
            $mailers = array();
        }
        $total = count($mailers);

        // Added to still catch domains with no MX records
        if($total == 0 || !$total) {
            $error = "No MX record found for the domain.";
        }
    } else {
        $error = "Address syntax not correct.";
    }	
	if (empty($error)) return "ok";
	else return $error;
}
function getInbetweenStrings($start, $end, $str, $txtorhtml, $isgreedyornot){
    $matches = array();	
	if ($txtorhtml=="text") {	
		if ($isgreedyornot=="nongreedy") $regex = "/$start([\s\S]*?)$end/s";		 //QUESTION MARK DEFINES GREEDY OR NON GREEDY
		else $regex = "/$start([\s\S]*)$end/s";	
		preg_match_all($regex, $str, $matches);
		/*if (!is_array($matches[1])) return strip_tags($matches[1]);*/
		/*return strip_HTML_tags($matches[1]);*/

		return $newArray = array_map(function($v){
			return trim(strip_tags(html_entity_decode($v)));
		}, $matches[1]);


		
	}elseif ($txtorhtml=="html") {
		if ($isgreedyornot=="nongreedy") $regex = "/$start([\s\S]*?)$end/s";		 //QUESTION MARK DEFINES GREEDY OR NON GREEDY
		else $regex = "/$start([\s\S]*)$end/s";	
		preg_match_all($regex, $str, $matches);
		return $matches[1];	
	}	
}
function clean($string) {
   $string = preg_replace("/[\r\n]+/", " ", $string);
   //return $string = preg_replace('/[^\w\s]+/u','' ,$string);
   return $string = preg_replace('/[^A-Za-z0-9 !@#$%^ñáéíóú&*\'¿?().]/u','', strip_tags($string));
   //return preg_replace('/[^A-Za-z0-9\-\s\']/', '', $string);
}
function restructure_array(array $images)
{
	$result = array();

	foreach ($images as $key => $value) {
		foreach ($value as $k => $val) {
			for ($i = 0; $i < count($val); $i++) {
				$result[$i][$k] = $val[$i];
			}
		}
	}

	return $result;
}
function hexColorAllocate($im,$hex){
    $hex = ltrim($hex,'#');
    $a = hexdec(substr($hex,0,2));
    $b = hexdec(substr($hex,2,2));
    $c = hexdec(substr($hex,4,2));
    return imagecolorallocate($im, $a, $b, $c); 
}
function replace_extension($filename, $new_extension) {
    $info = pathinfo($filename);
    return $info['filename'] . '.' . $new_extension;
}		
function transform_image($src, $dist, $dis_width = 100,$quality,$flipImageSelector, $textImageSelector, $addTextoImage, $coordinateX, $coordinateY, $coordinateXwatermark, $coordinateYwatermark,$imageFontSize, $imageFontColor, $textWatermarkSelector,$convertFormat){
	$img = '';
	$extension = strtolower(strrchr($src, '.'));
	switch($extension)
	{
		case '.jpg':
		case '.jpeg':
			$img = @imagecreatefromjpeg($src);
			break;
		case '.gif':
			$img = @imagecreatefromgif($src);
			break;
		case '.png':
			$img = @imagecreatefrompng($src);
			break;
	}
	if ($img != ''){
		$width = imagesx($img);
		$height = imagesy($img);		
	} else return false;
	
	if (($width>=1)&&($height>=1)){
		$dis_height = $dis_width * ($height / $width);
		$new_image = imagecreatetruecolor($dis_width, $dis_height);	
		//Download initial font, watermark and imagetext.txt creation
		if (!file_exists("font.ttf")) {
			$font = file_get_contents("http://themes.googleusercontent.com/static/fonts/abel/v3/RpUKfqNxoyNe_ka23bzQ2A.ttf");
			file_put_contents("font.ttf", $font);
		}
		if (!file_exists("watermark.png")) {
			$font = file_get_contents("https://netgrows.com/phpseo/minilogo.png");
			file_put_contents("watermark.png", $font);
		}	
		if (!file_exists("imagetext.txt")) {
			$content="Line 1 from imagetext.txt\nLine 2 from imagetext.txt\nLine 3 from imagetext.txt\nLine 4 from imagetext.txt\nLine 5 from imagetext.txt";
			file_put_contents("imagetext.txt", $content);
		}		 
		imagecopyresampled($new_image, $img, 0, 0, 0, 0, $dis_width, $dis_height, $width, $height);
		if ($flipImageSelector=="fliphorizontal") imageflip($new_image, IMG_FLIP_HORIZONTAL);
		if ($flipImageSelector=="flipvertical") imageflip($new_image, IMG_FLIP_VERTICAL);
		if ($flipImageSelector=="flipboth") imageflip($new_image, IMG_FLIP_BOTH);
		$hexColorAllocate = hexColorAllocate($new_image,$imageFontColor);	
		if ($textImageSelector=="addaphrase") imagettftext($new_image, $imageFontSize, $angle, $coordinateX, $coordinateY, $hexColorAllocate, "font.ttf", $addTextoImage);
		elseif ($textImageSelector=="addrandomlines") { 
			$f_contents = file("imagetext.txt"); 
			$line = $f_contents[rand(0, count($f_contents) - 1)];
			imagettftext($new_image, $imageFontSize, $angle, $coordinateX, $coordinateY, $hexColorAllocate, "font.ttf", $line); 
		}
		elseif ($textImageSelector=="addsecuenciallines") {
			$contents = file("imagetextaux.txt", FILE_IGNORE_NEW_LINES);
			$line = array_shift($contents);
			file_put_contents("imagetextaux.txt", implode("\r\n", $contents));
			imagettftext($new_image, $imageFontSize, $angle, $coordinateX, $coordinateY, $hexColorAllocate, "font.ttf", $line); 
		}		
		if ($textWatermarkSelector=="addwatermark")	{ 	
			$stamp = imagecreatefrompng('watermark.png');
			$marge_right = $coordinateXwatermark;
			$marge_bottom = $coordinateYwatermark;		
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);
			imagecopy($new_image, $stamp, imagesx($new_image) - $sx - $marge_right, imagesy($new_image) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
		}		
		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
				if (imagetypes() & IMG_JPG) {
					imagejpeg($new_image, $dist, $quality);
				}
				break;

			case '.gif':
				if (imagetypes() & IMG_GIF) {
					imagegif($new_image, $dist);
				}
				break;

			case '.png':
				$scaleQuality = round(($quality/100) * 9);
				$invertScaleQuality = 9 - $scaleQuality;

				if (imagetypes() & IMG_PNG) {
					imagepng($new_image, $dist, $invertScaleQuality);
				}
				break;
		}
		if ($convertFormat=="converttoWEBP")	{ 
			$newReplacedFileName=replace_extension($dist,"webp");
			imagewebp($new_image, "images/".$newReplacedFileName, $quality);						
		}
		if ($convertFormat=="converttoJPG")	{ 
			$newReplacedFileName=replace_extension($dist,"jpg");
			imagejpeg($new_image, "images/".$newReplacedFileName, $quality);						
		}
		if ($convertFormat=="converttoPNG")	{ 
			$scaleQuality = round(($quality/100) * 9);
			$invertScaleQuality = 9 - $scaleQuality;
			$newReplacedFileName=replace_extension($dist,"png");
			imagepng($new_image, "images/".$newReplacedFileName, $invertScaleQuality);						
		}
		if ($convertFormat=="converttoGIF")	{ 
			$newReplacedFileName=replace_extension($dist,"gif");
			imagegif($new_image, "images/".$newReplacedFileName);						
		}		
		imagedestroy($new_image);				
	} else return false;	
}
if ($module=="imagedownload") {	
	if (file_exists("phpseoimages.zip")) unlink("phpseoimages.zip");			
	$files=explode("|",$_POST['data']);
	$tmpFile = tempnam('/tmp', '');
	$zip = new ZipArchive;
	$zip->open($tmpFile, ZipArchive::CREATE);
	foreach ($files as $file) {
		$file=str_replace("\"","",$file);
		if ((strlen($file))>=5){
			$fileContent = file_get_contents($file);		
			$zip->addFromString(basename($file), $fileContent);				
		}
	}
	$zip->close();
	header('Content-Type: application/zip');
	header('Content-disposition: attachment; filename=file.zip');
	header('Content-Length: ' . filesize($tmpFile));
	readfile($tmpFile);
	rename($tmpFile,"phpseoimages.zip");
exit;	
}
if ($module=="checkindexedsubmit") {
	$webproxy=$_POST['webproxy'];		
	$allurls=explode("|",$_POST['data']);
	$allurls=array_filter($allurls);//cleans empty lines
	foreach ($allurls as $url){
		$url=str_replace('"','',$url);
		$urlencoded=urlencode($url);
		if (!empty($url)){
			$urltoCheck="https://google.com/search?q=$urlencoded";
			if ($webproxy=="noproxy") {
				$results = getPageGoogle('',$urltoCheck, '', $customUserAgent, 1, 25);	
			} else {
				$urlDefinitiva=$webproxy."&getPage=".$urltoCheck;
				$results = getPageGoogle('',$urlDefinitiva, '', $customUserAgent, 1, 25);
			}
			$arraydatos=$results['EXE'];			
			//Check if banned
			if (preg_match("/unusual traffic/i", $arraydatos)) {
				$bannedProxy=true;
			} else {
				if (preg_match("/href/i", $arraydatos)) {
					$bannedProxy=false;
				} else $bannedProxy=true;
			}			
					
			if ($bannedProxy==true){
				echo "Your current IP address -".$_SERVER['SERVER_ADDR']."- has been banned by Google. Stop during some hours or use a web proxy.\n";	
			} else {				
				$url=strtolower($url);
				$url=trim( $url, "/" );
				$arraydatos=strtolower($arraydatos);				
				if (strpos($arraydatos,"href=\"$url") !== false) {	//se ha encontrado la URL  // \"
					echo "YES	$url\n";				
				} else {
					echo "NO	$url\n";
				}				
			}
		}		
	}
	sleep ($batchDelaySeconds);	
exit;	
}
if ($module=="getnotifications") {
	$totalNotificaciones = file_get_contents('https://netgrows.com/notifications/total.php', true);	
	$data = array(
		'unseen_notification'  => $totalNotificaciones
	);
	echo json_encode($data);
	exit;
}
class SimpleXLSXGen {
	public $curSheet;
	protected $sheets;
	protected $template;
	protected $SI, $SI_KEYS;
	public function __construct() {
		$this->curSheet = -1;
		$this->sheets = [ ['name' => 'Sheet1', 'rows' => [] ] ];
		$this->SI = [];		// sharedStrings index
		$this->SI_KEYS = []; //  & keys
		$this->template = [
			'[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Override PartName="/_rels/.rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
<Override PartName="/xl/_rels/workbook.xml.rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
{SHEETS}
<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
</Types>',
			'_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>',
			'docProps/app.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties">
<TotalTime>0</TotalTime>
<Application>'.__CLASS__.'</Application></Properties>',
			'docProps/core.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<dcterms:created xsi:type="dcterms:W3CDTF">{DATE}</dcterms:created>
<dc:language>en-US</dc:language>
<dcterms:modified xsi:type="dcterms:W3CDTF">{DATE}</dcterms:modified>
<cp:revision>1</cp:revision>
</cp:coreProperties>',
			'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
{SHEETS}',
			'xl/worksheets/sheet1.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><dimension ref="{REF}"/><cols>{COLS}</cols><sheetData>{ROWS}</sheetData></worksheet>',
			'xl/sharedStrings.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="{CNT}" uniqueCount="{CNT}">{STRINGS}</sst>',
			'xl/styles.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<fonts count="2"><font><name val="Calibri"/><family val="2"/></font><font><name val="Calibri"/><family val="2"/><b/></font></fonts>
<fills count="1"><fill><patternFill patternType="none"/></fill></fills>
<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>
<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" /></cellStyleXfs>
<cellXfs count="6">
	<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
	<xf numFmtId="1" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>
	<xf numFmtId="9" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>	
	<xf numFmtId="10" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>
	<xf numFmtId="14" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>
	<xf numFmtId="20" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>
	<xf numFmtId="22" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>
	<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1" applyAlignment="1"><alignment horizontal="right"/></xf>	
</cellXfs>
<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>
</styleSheet>',
			'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
<fileVersion appName="'.__CLASS__.'"/><sheets>
{SHEETS}
</sheets></workbook>'
		];
	}
	public static function fromArray( array $rows, $sheetName = null ) {
		$xlsx = new static();
		return $xlsx->addSheet( $rows, $sheetName );
	}
	public function addSheet( array $rows, $name = null ) {
		$this->curSheet++;
		$this->sheets[$this->curSheet] = ['name' => $name ?: 'Sheet'.($this->curSheet+1)];
		if ( is_array( $rows ) && isset( $rows[0] ) && is_array($rows[0]) ) {
			$this->sheets[$this->curSheet]['rows'] = $rows;
		} else {
			$this->sheets[$this->curSheet]['rows'] = [];
		}
		return $this;
	}
	public function __toString() {
		$fh = fopen( 'php://memory', 'wb' );
		if ( ! $fh ) {
			return '';
		}
		if ( ! $this->_write( $fh ) ) {
			fclose( $fh );
			return '';
		}
		$size = ftell( $fh );
		fseek( $fh, 0);
		return (string) fread( $fh, $size );
	}
	public function saveAs( $filename ) {
		$fh = fopen( $filename, 'wb' );
		if (!$fh) {
			return false;
		}
		if ( !$this->_write($fh) ) {
			fclose($fh);
			return false;
		}
		fclose($fh);
		return true;
	}
	public function download() {
		return $this->downloadAs( gmdate('YmdHi') . '.xlsx' );
	}
	public function downloadAs( $filename ) {
		$fh = fopen('php://memory','wb');
		if (!$fh) {
			return false;
		}
		if ( !$this->_write( $fh )) {
			fclose( $fh );
			return false;
		}
		$size = ftell($fh);
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T' , time() ));
		header('Content-Length: '.$size);
		while( ob_get_level() ) {
			ob_end_clean();
		}
		fseek($fh,0);
		fpassthru( $fh );
		fclose($fh);
		return true;
	}
	protected function _write( $fh ) {
		$dirSignatureE= "\x50\x4b\x05\x06"; // end of central dir signature
		$zipComments = 'Generated by '.__CLASS__.' PHP class, thanks sergey.shuchkin@gmail.com';
		if (!$fh) {
			return false;
		}
		$cdrec = '';	// central directory content
		$entries= 0;	// number of zipped files
		$cnt_sheets = count( $this->sheets );
		foreach ($this->template as $cfilename => $template ) {
			if ( $cfilename === '[Content_Types].xml' ) {
				$s = '';
				for ( $i = 0; $i < $cnt_sheets; $i++) {
					$s .= '<Override PartName="/xl/worksheets/sheet'.($i+1).
							'.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
				}
				$template = str_replace('{SHEETS}', $s, $template);
				$this->_writeEntry($fh, $cdrec, $cfilename, $template);
				$entries++;
			}
			elseif ( $cfilename === 'xl/_rels/workbook.xml.rels' ) {
				$s = '';
				for ( $i = 0; $i < $cnt_sheets; $i++) {
					$s .= '<Relationship Id="rId'.($i+2).'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet"'.
						 ' Target="worksheets/sheet'.($i+1).".xml\"/>\n";
				}
				$s .= '<Relationship Id="rId'.($i+2).'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/></Relationships>';
				$template = str_replace('{SHEETS}', $s, $template);
				$this->_writeEntry($fh, $cdrec, $cfilename, $template);
				$entries++;
			}
			elseif ( $cfilename === 'xl/workbook.xml' ) {
				$s = '';
				foreach ( $this->sheets as $k => $v ) {
					$s .= '<sheet name="' . $v['name'] . '" sheetId="' . ( $k + 1) . '" state="visible" r:id="rId' . ( $k + 2) . '"/>';
				}
				$template = str_replace('{SHEETS}', $s, $template);
				$this->_writeEntry($fh, $cdrec, $cfilename, $template);
				$entries++;
			}
			elseif ( $cfilename === 'docProps/core.xml' ) {
				$template = str_replace('{DATE}', gmdate('Y-m-d\TH:i:s\Z'), $template);
				$this->_writeEntry($fh, $cdrec, $cfilename, $template);
				$entries++;
			} elseif ( $cfilename === 'xl/sharedStrings.xml' ) {
				if (!count($this->SI)) {
					$this->SI[] = 'No Data';
				}
				$si_cnt = count($this->SI);
				$this->SI = '<si><t>'.implode("</t></si>\r\n<si><t>", $this->SI).'</t></si>';
				$template = str_replace(['{CNT}', '{STRINGS}'], [ $si_cnt, $this->SI ], $template );
				$this->_writeEntry($fh, $cdrec, $cfilename, $template);
				$entries++;
			} elseif ( $cfilename === 'xl/worksheets/sheet1.xml' ) {
				foreach ( $this->sheets as $k => $v ) {
					$filename = 'xl/worksheets/sheet'.($k+1).'.xml';
					$xml = $this->_sheetToXML($this->sheets[$k], $template);
					$this->_writeEntry($fh, $cdrec, $filename, $xml );
					$entries++;
				}
				$xml = null;
			}
			else {
				$this->_writeEntry($fh, $cdrec, $cfilename, $template);
				$entries++;
			}
		}
		$before_cd = ftell($fh);
		fwrite($fh, $cdrec);
		// end of central dir
		fwrite($fh, $dirSignatureE);
		fwrite($fh, pack('v', 0)); // number of this disk
		fwrite($fh, pack('v', 0)); // number of the disk with the start of the central directory
		fwrite($fh, pack('v', $entries)); // total # of entries "on this disk"
		fwrite($fh, pack('v', $entries)); // total # of entries overall
		fwrite($fh, pack('V', mb_strlen($cdrec,'8bit')));     // size of central dir
		fwrite($fh, pack('V', $before_cd));         // offset to start of central dir
		fwrite($fh, pack('v', mb_strlen($zipComments,'8bit'))); // .zip file comment length
		fwrite($fh, $zipComments);
		return true;
	}
	protected function _writeEntry($fh, &$cdrec, $cfilename, $data) {
		$zipSignature = "\x50\x4b\x03\x04"; // local file header signature
		$dirSignature = "\x50\x4b\x01\x02"; // central dir header signature
		$e = [];
		$e['uncsize'] = mb_strlen($data, '8bit');
		// if data to compress is too small, just store it
		if($e['uncsize'] < 256){
			$e['comsize'] = $e['uncsize'];
			$e['vneeded'] = 10;
			$e['cmethod'] = 0;
			$zdata = $data;
		} else{ // otherwise, compress it
			$zdata = gzcompress($data);
			$zdata = substr(substr($zdata, 0, - 4 ), 2); // fix crc bug (thanks to Eric Mueller)
			$e['comsize'] = mb_strlen($zdata, '8bit');
			$e['vneeded'] = 10;
			$e['cmethod'] = 8;
		}
		$e['bitflag'] = 0;
		$e['crc_32']  = crc32($data);
		// Convert date and time to DOS Format, and set then
		$lastmod_timeS  = str_pad(decbin(date('s')>=32?date('s')-32:date('s')), 5, '0', STR_PAD_LEFT);
		$lastmod_timeM  = str_pad(decbin(date('i')), 6, '0', STR_PAD_LEFT);
		$lastmod_timeH  = str_pad(decbin(date('H')), 5, '0', STR_PAD_LEFT);
		$lastmod_dateD  = str_pad(decbin(date('d')), 5, '0', STR_PAD_LEFT);
		$lastmod_dateM  = str_pad(decbin(date('m')), 4, '0', STR_PAD_LEFT);
		$lastmod_dateY  = str_pad(decbin(date('Y')-1980), 7, '0', STR_PAD_LEFT);
		# echo "ModTime: $lastmod_timeS-$lastmod_timeM-$lastmod_timeH (".date("s H H").")\n";
		# echo "ModDate: $lastmod_dateD-$lastmod_dateM-$lastmod_dateY (".date("d m Y").")\n";
		$e['modtime'] = bindec("$lastmod_timeH$lastmod_timeM$lastmod_timeS");
		$e['moddate'] = bindec("$lastmod_dateY$lastmod_dateM$lastmod_dateD");
		$e['offset'] = ftell($fh);
		fwrite($fh, $zipSignature);
		fwrite($fh, pack('s', $e['vneeded'])); // version_needed
		fwrite($fh, pack('s', $e['bitflag'])); // general_bit_flag
		fwrite($fh, pack('s', $e['cmethod'])); // compression_method
		fwrite($fh, pack('s', $e['modtime'])); // lastmod_time
		fwrite($fh, pack('s', $e['moddate'])); // lastmod_date
		fwrite($fh, pack('V', $e['crc_32']));  // crc-32
		fwrite($fh, pack('I', $e['comsize'])); // compressed_size
		fwrite($fh, pack('I', $e['uncsize'])); // uncompressed_size
		fwrite($fh, pack('s', mb_strlen($cfilename, '8bit')));   // file_name_length
		fwrite($fh, pack('s', 0));  // extra_field_length
		fwrite($fh, $cfilename);    // file_name
		// ignoring extra_field
		fwrite($fh, $zdata);
		// Append it to central dir
		$e['external_attributes']  = (substr($cfilename, -1) === '/'&&!$zdata)?16:32; // Directory or file name
		$e['comments']             = '';
		$cdrec .= $dirSignature;
		$cdrec .= "\x0\x0";                  // version made by
		$cdrec .= pack('v', $e['vneeded']); // version needed to extract
		$cdrec .= "\x0\x0";                  // general bit flag
		$cdrec .= pack('v', $e['cmethod']); // compression method
		$cdrec .= pack('v', $e['modtime']); // lastmod time
		$cdrec .= pack('v', $e['moddate']); // lastmod date
		$cdrec .= pack('V', $e['crc_32']);  // crc32
		$cdrec .= pack('V', $e['comsize']); // compressed filesize
		$cdrec .= pack('V', $e['uncsize']); // uncompressed filesize
		$cdrec .= pack('v', mb_strlen($cfilename,'8bit')); // file name length
		$cdrec .= pack('v', 0);                // extra field length
		$cdrec .= pack('v', mb_strlen($e['comments'],'8bit')); // file comment length
		$cdrec .= pack('v', 0); // disk number start
		$cdrec .= pack('v', 0); // internal file attributes
		$cdrec .= pack('V', $e['external_attributes']); // internal file attributes
		$cdrec .= pack('V', $e['offset']); // relative offset of local header
		$cdrec .= $cfilename;
		$cdrec .= $e['comments'];
	}
	protected function _sheetToXML(&$sheet, $template) {
		$COLS = [];
		$ROWS = [];
		if ( count($sheet['rows']) ) {
			$CUR_ROW = 0;
			$COL = [];
			foreach( $sheet['rows'] as $r ) {
				$CUR_ROW++;
				$row = '<row r="'.$CUR_ROW.'">';
				$CUR_COL = 0;
				foreach( $r as $v ) {
					$CUR_COL++;
					if ( !isset($COL[ $CUR_COL ])) {
						$COL[ $CUR_COL ] = 0;
					}
					if ( $v === null || $v === '' ) {
						continue;
					}
					$vl = mb_strlen( (string) $v );
					$COL[ $CUR_COL ] = max( $vl, $COL[ $CUR_COL ] );
					$cname = $this->num2name($CUR_COL) . $CUR_ROW;
					$ct = $cs = null;
					if ( is_string($v) ) {
						if ( $v === '0' || preg_match( '/^[-+]?[1-9]\d{0,14}$/', $v ) ) { // Integer as General
							$cv = ltrim( $v, '+' );
							if ( $vl > 10 ) {
								$cs = 1; // [1] 0
							}
						} elseif ( preg_match('/^[-+]?(0|[1-9]\d*)\.\d+$/', $v ) ) {
							$cv = ltrim($v,'+');
						} elseif ( preg_match('/^([-+]?\d+)%$/', $v, $m) ) {
							$cv = round( $m[1] / 100, 2);
							$cs = 2; // [9] 0%
						} elseif ( preg_match('/^([-+]\d+\.\d+)%$/', $v, $m) ) {
							$cv = round( $m[1] / 100, 4 );
							$cs = 3; // [10] 0.00%
						} elseif ( preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d)$/', $v, $m ) ){
							$cv = $this->date2excel($m[1],$m[2],$m[3]);
							$cs = 4; // [14] mm-dd-yy
						} elseif ( preg_match('/^(\d\d)\/(\d\d)\/(\d\d\d\d)$/', $v, $m ) ){
							$cv = $this->date2excel($m[3],$m[2],$m[1]);
							$cs = 4; // [14] mm-dd-yy
						} elseif ( preg_match('/^(\d\d):(\d\d):(\d\d)$/', $v, $m ) ){
							$cv = $this->date2excel(0,0,0,$m[1],$m[2],$m[3]);
							$cs = 5; // [14] mm-dd-yy
						} elseif ( preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d) (\d\d):(\d\d):(\d\d)$/', $v, $m ) ) {
							$cv = $this->date2excel( $m[1], $m[2], $m[3], $m[4], $m[5], $m[6] );
							$cs = 6; // [22] m/d/yy h:mm
						} elseif ( preg_match('/^(\d\d)\/(\d\d)\/(\d\d\d\d) (\d\d):(\d\d):(\d\d)$/', $v, $m ) ) {
							$cv = $this->date2excel( $m[3], $m[2], $m[1], $m[4], $m[5], $m[6] );
							$cs = 6; // [22] m/d/yy h:mm
						} elseif ( mb_strlen( $v ) > 160 ) {
							$ct = 'inlineStr';
							$cv = str_replace(['&','<','>',"\x03"],['&amp;','&lt;','&gt;',''], $v);
						} else {
							if ( preg_match('/^[0-9+-.]+$/', $v ) ) { // Long ?
								$cs = 7; // Align Right
							}
							$v = ltrim($v,"\0"); // disabled type detection
							$ct = 's'; // shared string
							$v = str_replace(['&','<','>',"\x03"],['&amp;','&lt;','&gt;',''], $v);
							$cv = false;
							$skey = '~'.$v;
							if ( isset($this->SI_KEYS[ $skey ]) ) {
								$cv = $this->SI_KEYS[ $skey ];
							}

							if ( $cv === false ) {
								$this->SI[] = $v;
								$cv  = count( $this->SI ) - 1;
								$this->SI_KEYS[$skey] = $cv;
							}
						}
					} elseif ( is_int( $v ) || is_float( $v ) ) {
						$cv = $v;
					} else {
						continue;
					}
					$row .= '<c r="' . $cname . '"'.($ct ? ' t="'.$ct.'"' : '').($cs ? ' s="'.$cs.'"' : '').'>'
					        .($ct === 'inlineStr' ? '<is><t>'.$cv.'</t></is>' : '<v>' . $cv . '</v>')."</c>\r\n";
				}
				$ROWS[] = $row . "</row>\r\n";
			}
			foreach ( $COL as $k => $max ) {
				$COLS[] = '<col min="'.$k.'" max="'.$k.'" width="'.min( $max+1, 60).'" />';
			}
			$REF = 'A1:'.$this->num2name(count($COLS)) . $CUR_ROW;
		} else {
			$COLS[] = '<col min="1" max="1" bestFit="1" />';
			$ROWS[] = '<row r="1"><c r="A1" t="s"><v>0</v></c></row>';
			$REF = 'A1:A1';
		}
		return str_replace(['{REF}','{COLS}','{ROWS}'],
			[ $REF, implode("\r\n", $COLS), implode("\r\n",$ROWS) ],
			$template );
	}
	public function num2name($num) {
		$numeric = ($num - 1) % 26;
		$letter  = chr( 65 + $numeric );
		$num2    = (int) ( ($num-1) / 26 );
		if ( $num2 > 0 ) {
			return $this->num2name( $num2 ) . $letter;
		}
		return $letter;
	}
	public function date2excel($year, $month, $day, $hours=0, $minutes=0, $seconds=0) {
	    $excelTime = (($hours * 3600) + ($minutes * 60) + $seconds) / 86400;
	    if ( $year === 0 ) {
	        return $excelTime;
        }
		// self::CALENDAR_WINDOWS_1900
		$excel1900isLeapYear = True;
		if (((int)$year === 1900) && ($month <= 2)) { $excel1900isLeapYear = False; }
		$myExcelBaseDate = 2415020;
		//    Julian base date Adjustment
		if ($month > 2) {
			$month -= 3;
		} else {
			$month += 9;
			--$year;
		}
		//    Calculate the Julian Date, then subtract the Excel base date (JD 2415020 = 31-Dec-1899 Giving Excel Date of 0)
		$century = substr($year,0,2);
		$decade = substr($year,2,2);
		$excelDate = floor((146097 * $century) / 4) + floor((1461 * $decade) / 4) + floor((153 * $month + 2) / 5) + $day + 1721119 - $myExcelBaseDate + $excel1900isLeapYear;
		return (float) $excelDate + $excelTime;
	}
}
if ($module=="dataextractorsubmit") {	
	$allurls=explode("|",$_POST['data']);
	$tipolink=$_POST['tipolink'];
	$whattoget=$_POST['whattoget'];
	$isgreedyornot=$_POST['isgreedyornot'];	
	$typeofexport=$_POST['typeofexport'];
	$index=$_POST['index'];
	$lengthacumulaURLs=$_POST['lengthacumulaURLs'];
	$webproxy=$_POST['webproxy'];	
	$customTemplateParts="";
	if ($tipolink=="default") return "nolinktype";	
	$cuentaLoops=0;
	$titleFieldsArray=array();	
	//We get custom template outside the loop
	if (strpos($tipolink, 'template-') !== false) {	
		$customTemplateParts=getData_ExtractorTemplates($tipolink);
		$customTemplateParts=str_replace("@@@","###",$customTemplateParts);
	}	
	$allurls=array_filter($allurls);//cleans empty lines
	if (($typeofexport=="csvfile")||($typeofexport=="xlsfile")) {
		$filenameCSV=$_SESSION['filenameCSVsession'];
		$fp = fopen(dirname(__FILE__)."/data-extractor/".$filenameCSV, 'a');
		$partesSueltasAux=explode('###',$customTemplateParts);
		$notNeededTemplateName=array_shift($partesSueltasAux);	
		$cuentauxiliar=0;
		for( $z = 0; $z<=((count($partesSueltasAux)/3)-1); $z++ ) {
				$titlefield= str_replace("\n","",$partesSueltasAux[$cuentauxiliar]);
				$cuentauxiliar++;
				$beginBloque= str_replace("\n","",$partesSueltasAux[$cuentauxiliar]);
				$cuentauxiliar++;
				$endBloque= str_replace("\n","",$partesSueltasAux[$cuentauxiliar]);
				$cuentauxiliar++;	
			if ($debugSearchDisplay){
				echo "\n-------titlefield-----\n";			
				print_r($titlefield);
				echo "\n------------\n";
			}
			$titleFieldsArray[]=$titlefield;							
		}				
		if ((!isset($_SESSION['cabecerasCSV']))||(($_SESSION['cabecerasCSV'])==false)) {
			$_SESSION['cabecerasCSV']=true;
			$cabecerasCSV=$titleFieldsArray;
			fputcsv($fp, $cabecerasCSV);	
		}
	}
	$cuentaurls=0;
	foreach ($allurls as $url){
		$contenidoCSV=array();
		$url=str_replace('"','',$url);

		if ($webproxy=="noproxy") {
			$results = getPageGoogle('',$url, '', $customUserAgent, 1, 25);	
		} else {
			$urlDefinitiva=$webproxy."&getPage=".$url;
			$results = getPageGoogle('',$urlDefinitiva, '', $customUserAgent, 1, 25);
		}		
		$results = getPageGoogle('',$url, '', $customUserAgent, 1, 25);
		$arraydatos=$results['EXE']	;
		$dom = new DOMDocument();
		if (!empty($arraydatos)) @$dom->loadHTML($arraydatos);
		else echo "\nERROR getting the URL $url \n";
		$xpath = new DOMXPath($dom);
		//Custom template
		if (strpos($tipolink, 'template-') !== false) {		
			$partesSueltas=explode('###',$customTemplateParts);
			$notNeededTemplateName=array_shift($partesSueltas);	
			$maincounter = 0;
			if ($debugSearchDisplay){			
				echo "\n-------partesSueltas-----\n";			
				print_r($partesSueltas);
				echo "\n------------\n";			
			}
			$partPosition=1;		
			$titlefield=$beginBloque=$endBloque="";		
			$totalPartes=count($partesSueltas);
			$totalLoops=$totalPartes/3;
			$cuentaindex=0;
			$contenidotxt="";			
			if ($debugSearchDisplay){
				echo "\n------totalLoops------\n";
				echo $totalLoops;
				echo "\n------------\n";				
			}			
			for( $i = 0; $i<=$totalLoops-1; $i++ ) {
				$titlefield= str_replace("\n","",$partesSueltas[$cuentaindex]);   //if (array_key_exists($cuentaindex,$partesSueltas)) 
				$cuentaindex++;
				$beginBloque= str_replace("\n","",$partesSueltas[$cuentaindex]);
				$cuentaindex++;
				$endBloque= str_replace("\n","",$partesSueltas[$cuentaindex]);
				$cuentaindex++;			
				if ($debugSearchDisplay){
					echo "\n-------beginBloque-----\n";			 
					echo $beginBloque;
					echo "\n-------endBloque-----\n";	
					echo $endBloque;
					echo "\n------------\n";
				}				
				if ($beginBloque!=""){					
					$str_arr = getInbetweenStrings($beginBloque, $endBloque, $arraydatos, $whattoget, $isgreedyornot);
						echo "\n";
						echo "Extracting content from: ".$url." | Regex: /$beginBloque([\s\S]*?)$endBloque/s";
						echo "\n";
								if (array_key_exists(0,$str_arr)) $extraido=$str_arr[0];								
								else $extraido="NULL";							
								$extraido = preg_replace("/[\r\n]+/", "\n", trim($extraido));								
								if ($typeofexport=="txtfile") { $contenidotxt.=$extraido."\n";}
								elseif (($typeofexport=="csvfile")||($typeofexport=="xlsfile")) { $contenidoCSV[]=$extraido;}						
				}							
			 }
				//We save individual txt files
				if ($typeofexport=="txtfile") {
					$filename=rand(99999, 999999999999).".txt";	 
					file_put_contents(dirname(__FILE__)."/data-extractor/".$filename, $contenidotxt);					
				}
				if (($typeofexport=="csvfile")||($typeofexport=="xlsfile")) {
					//We save each csv row
					/*$contenidoCSV = array_map("utf8_decode", $contenidoCSV);*/					 
   		   
				   $contenidoCSV = array_map(
						function($str) {
							return mb_convert_encoding($str, 'UTF-8', 'UTF-8'); //, 'HTML-ENTITIES' will remove Spanish special chars - still not perfect
						},
						$contenidoCSV
					);					
					fputcsv($fp, $contenidoCSV);					
				}							
		}
		if($tipolink=="imgurlsHARD"){
			$totalImages=0;
			preg_match_all('/([-a-z0-9_\/:.]+\.(jpg|jpeg|png))/i', $arraydatos, $matches);							
			if (sizeof($matches[0]) > 0) {
				foreach ($matches[0] as $imageURL) {
					if (sanitizeURL($imageURL)) {
						echo sanitizeURL($imageURL)."\n";
						$totalImages++;					
					}
				}
			}
			if ($totalImages==0) {
				echo "0 images found at $url\n";
			}	
		}
		elseif($tipolink=="emailaddress"){
			$totalEmails=0;
			preg_match_all('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i', $arraydatos, $matches);	
			if (sizeof($matches[0]) > 0) {
				foreach ($matches[0] as $emailAdress) {
					//echo $emailAdress."\n";
					if (validate_email($emailAdress)=="ok") {
						echo $emailAdress."\n";	
						$totalEmails++;						
					}	
				}
			}
			if ($totalEmails==0) {
				echo "0 emails found at $url\n";
			}					
		}
		elseif (strpos($tipolink, 'template-') === false) {	
			if($tipolink=="imgurlsSOFT") $hrefs = $xpath->evaluate("/html/body//img");
			else $hrefs = $xpath->evaluate("/html/body//a");				
			if (!empty($url))	{
				for ($i = 0; $i < $hrefs->length; $i++) {
					$href = $hrefs->item($i);				
					if($tipolink=="imgurlsSOFT") $urlExtraida = $href->getAttribute('src');
					else $urlExtraida = $href->getAttribute('href');					
					$resultURL = parse_url($url);
					$baseDomainz=$resultURL['scheme']."://".$resultURL['host'];								
					if((strpos($urlExtraida, "http://") !== false)||(strpos($urlExtraida, "https://") !== false)){
						if ((($tipolink=="alllinks")||($tipolink=="external")||($tipolink=="imgurlsSOFT"))&& (strpos($urlExtraida, $resultURL['host']) === false)) if (sanitizeURL($urlExtraida)) echo trim($urlExtraida)."\n";		//All URLS con http (internal y external)
						elseif (((($tipolink=="alllinks")||($tipolink=="internal")||($tipolink=="imgurlsSOFT"))&& (strpos($urlExtraida, $resultURL['host']) !== false))) echo trim($urlExtraida)."\n";	//Internal with https
					} else if (($tipolink=="alllinks")||($tipolink=="internal")||($tipolink=="imgurlsSOFT")) echo trim($baseDomainz.$urlExtraida)."\n";	//Internal relative URL, we add domain prefix
				} if ($hrefs->length==0) echo "No URLs found for $url. They may be blocking you.\n";					
			}
		}
		$cuentaurls++;
	}	
	if (($typeofexport=="csvfile")||($typeofexport=="xlsfile")) fclose($fp);	
	if (($typeofexport=="xlsfile")&&($index==$lengthacumulaURLs-1))	{
		sleep(5);
		$csvFilePath=dirname(__FILE__)."/data-extractor/".$filenameCSV;
		$xlsFilePath=str_replace(".csv",".xlsx",$csvFilePath);	
		$arrayofArrays=array();
		$file = fopen($csvFilePath, 'r');
		while (($line = fgetcsv($file)) !== FALSE) {		   		   
		   $line = array_map(
				function($str) {
					return clean($str);
				},
				$line
			);
		   $arrayofArrays[]= $line;
		}	  
		$xlsx = SimpleXLSXGen::fromArray( $arrayofArrays );
		$xlsx->saveAs($xlsFilePath);	
		fclose($file);							
	}
//End dataextractorsubmit	
exit;	
}
if ($module=="imgdltDirectory"){
	//Delete all stored images
	$images = glob("images/*.*");
	foreach($images as $image){
		 @unlink($image);
	}	
exit;
}
if ($module=="imgupld"){ //Begin image submit
	$data = array();
	if( isset( $_POST['image_upload'] ) && !empty( $_FILES['images'] )){		
		//flip
		if(isset($_POST['flipImageSelector'])) $flipImageSelector=$_POST['flipImageSelector'];
		//resize
		if(isset($_POST['resizeImageSelector'])) $resizeImageSelector=$_POST['resizeImageSelector'];
		if(isset($_POST['newImageWidth'])) $newImageWidth=$_POST['newImageWidth'];	
		if(isset($_POST['conversionQuality'])) $conversionQuality=$_POST['conversionQuality'];			
		//text1
		if(isset($_POST['text1ImageSelector'])) $text1ImageSelector=$_POST['text1ImageSelector'];
		else $text1ImageSelector="";		
		$addTextoImage=$_POST['addTextoImage'];		
		$coordinateX=$_POST['coordinateX'];
		$coordinateY=$_POST['coordinateY'];
		$coordinateXwatermark=$_POST['coordinateXwatermark'];
		$coordinateYwatermark=$_POST['coordinateYwatermark'];			
		if(isset($_POST['imageFontSize'])) $imageFontSize=$_POST['imageFontSize'];
		if(isset($_POST['imageFontColor'])) $imageFontColor=$_POST['imageFontColor'];			
		if(isset($_POST['textColor'])) $textColor=$_POST['textColor'];
		//text2
		if(isset($_POST['text2ImageSelector'])) $text2ImageSelector=$_POST['text2ImageSelector'];
		else $text2ImageSelector="";
		if ($text2ImageSelector=="addsecuenciallines") {
			$file_contents = file("imagetext.txt"); 
			file_put_contents("imagetextaux.txt", $file_contents);
		}			
		//watermark
		$textWatermarkSelector=$_POST['textWatermarkSelector'];
		//conversion		
		$convertFormat=$_POST['convertFormat'];		
		//get the structured array
		$images = restructure_array(  $_FILES );
		$allowedExts = array("gif", "jpeg", "jpg", "png");		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}		
		foreach ( $images as $key => $value){
			$i = $key+1;
			//create directory if not exists
			if (!file_exists('images')) {
				mkdir('images', 0777, true);
			}
			$image_name = $value['name'];
			//get image extension
			$ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
			//assign unique name to image
			$nameOnly=strtolower(pathinfo($image_name, PATHINFO_FILENAME));;
			$name = $nameOnly."-".$i*time().'.'.$ext;
			//image size calcuation in KB
			$image_size = $value["size"] / 1024;
			$image_flag = true;			
			//max upload image size 512MB
			$max_size = 512000;
			if( in_array($ext, $allowedExts) && $image_size < $max_size ){
				$image_flag = true;
			} else {
				$image_flag = false;
				$data[$i]['error'] = 'Maybe '.$image_name. ' exceeds max '.$max_size.' KB size or incorrect file extension';
			} 			
			if( $value["error"] > 0 ){
				$image_flag = false;
				$data[$i]['error'] = '';
				$data[$i]['error'].= '<br/> '.$image_name.' Image contains error - Error Code : '.$value["error"];
			}			
			if($image_flag){
				move_uploaded_file($value["tmp_name"], "images/".$name);
				$src = "images/".$name;				
				$dataImg = getimagesize($src);
				$width = $dataImg[0];
				$randSufix=rand(1,99999);
				$dist = "images/".$nameOnly."-".$randSufix.'.'.$ext;
				$data[$i]['success'] = $thumbnail = ''.$nameOnly."-".$randSufix.'.'.$ext;			
				if ($resizeImageSelector!="reducesize"){
					$newImageWidth=$width;
				}								
				//text from input
				if ($text1ImageSelector=="addaphrase"){
					$textImageSelector="addaphrase";
				} 												
				//text from file
				if ($text2ImageSelector=="addrandomlines"){
					$textImageSelector="addrandomlines";
				} elseif ($text2ImageSelector=="addsecuenciallines"){
					$textImageSelector="addsecuenciallines";
				} else $textImageSelector="";
				transform_image($src, $dist, $newImageWidth, $conversionQuality, $flipImageSelector, $textImageSelector, $addTextoImage, $coordinateX, $coordinateY, $coordinateXwatermark, $coordinateYwatermark, $imageFontSize, $imageFontColor, $textWatermarkSelector, $convertFormat);				
				if ($convertFormat=="converttoWEBP")	{ 
					$newReplacedFileName=replace_extension($dist,"webp");
					$data[$i]['success'] = $newReplacedFileName;							
				}
				if ($convertFormat=="converttoJPG")	{ 
					$newReplacedFileName=replace_extension($dist,"jpg");
					$data[$i]['success'] = $newReplacedFileName;						
				}
				if ($convertFormat=="converttoPNG")	{ 
					$newReplacedFileName=replace_extension($dist,"png");
					$data[$i]['success'] = $newReplacedFileName;						
				}
				if ($convertFormat=="converttoGIF")	{ 
					$newReplacedFileName=replace_extension($dist,"gif");
					$data[$i]['success'] = $newReplacedFileName;						
				}						
			}			
			//delete original
			unlink("./images/".$name);
		}		
		echo json_encode($data);		
	} else {
		$data[] = 'No Image Selected..';
	}
exit;
}
//End image submit

/*************************/
/*Filename for csv & xls*/
/***********************/
if ((!isset($_SESSION['filenameCSVsession']))||($module=="dataextractor")||($module=="indexedcheck")) {
	$_SESSION['filenameCSVsession']=rand(99999, 999999999999).".csv";
	$_SESSION['cabecerasCSV']=false;
}

/****************************/
/*Begin string translations*/
/**************************/
if ($langSelected=="es") {	
	//Spanish	
	$_welcometo="¡Bienvenid@ a PHPSEO! Busca una palabra clave para empezar a usar la herramienta.";
	$_keyword="palabra clave";
	$_optional="opcional";
	$_search="BUSCAR";
	$_titleURLscrapper="URL scraper";	
	$_subtitleURLscrapper="Busca URLs por palabra clave. Elige footprint (opcional), nº de resultados y región de Google";
	$_titleKeywordTool="Generador de keywords";	
	$_subtitleKeywordTool="Obtén sugerencias de palabras clave de Google, YouTube y Amazon derivadas de una palabra clave principal";
	$_titleImageDownloaderBoth="Descargar y editar imágenes (bulk)";
	$_titleImageDownloader="Descargar imágenes (bulk)";
	$_titleImageEditor="Editor de imágenes (bulk)";	
	$_subtitleImageDownloader="Busca imágenes y descárgalas en un fichero .zip";
	$_subtitleImageEditor="Selecciona múltiples imágenes y modifícalas a la vez";
	$_titleDataExtractor="Extractor de datos";
	$_subtitleDataExtractor="Extrae URLs, enlaces, imágenes, emails y cualquier otro contenido de múltiples URLs";
	$_titlePlagiCheck="Detector de plagio";	
	$_subtitlePlagiCheck="Pega tu texto y comprueba si está plagiado en Google";
	$_morewordreqString="100 o más palabras requeridas";
	$_titleIndexedUrlCheck="Comprobar URLs indexadas";	
	$_subtitleIndexedUrlCheck="Comprueba si URLs están indexadas en Google de forma masiva";
	$_titleProxyManager="Gestor de proxies";
	$_subtitleProxyManager="Gestiona y crea proxies web";
	$_createaProxyServer="CREAR UN NUEVO FICHERO PROXY";
	$_typeaPassText="Escribe un password y pulsa CREAR";
	$_createText="CREAR";
	$_newPassText="nuevo password";
	$_downloadProxyFileText="DESCARGAR FICHERO PROXY";		
	$_proxyListText="LISTADO DE PROXIES";	
	$_proxyMiniHelp="
	<p style='margin-left:15px'>Aquí defines los distintos proxies web que hayas creado en diferentes dominios (archivo <em>web-proxy-list.txt</em>). </p>
	<ul>
		<li>Ten en cuenta que cada dominio debería tener una IP diferente, y que si tu IP es compartida, puede estar ya bloqueada o mostrando captcha.</li>
		<li><strong>Para crear un nuevo proxy</strong>, solo necesitas subir el archivo <em>phpseo-proxy.php</em> usando FTP a cualquier dominio, y obtener la URL completa del fichero subido.</li>
		<ul>
			<li>Ejemplo: si subo <em>phpseo-proxy.php</em> a la subcarpeta <em>-prueba-</em> de <em>midominiochulo.com</em>, la URL del proxy será <em>http://midominiochulo.com/prueba/phpseo-proxy.php?password=TUPASSWORD</em></li>		
			<li>Por seguridad, es recomendable cambiar el nombre del fichero <em>phpseo-proxy.php</em> antes de subirlo.</li>
		</ul>
		<li><strong>Después de subir el fichero</strong>, guarda el nuevo proxy web en la lista inferior (en una nueva línea), siguiendo el formato: <em>NOMBREPROXY<strong>@@@</strong>URLPROXY?password=PASS</em>.</li>
	</ul>
	";
	$_templateListText="PLANTILLAS PERSONALIZADAS";
	$_templateListFormatText="Formato de las plantillas";
	$_templateListHelp="Una plantilla por cada línea. Después de salvar una nueva plantilla, necesitarás refrescar la página para verla en el desplegable.";	
	$_titleDocumentation="Documentación";		
	$_subtitleDocumentation="PHPSEO ayuda y documentación";	
	$_titleInfo="Info";
	$_titleMainSettings="Ajustes generales";
	$_subtitleMainSettings="Controla los ajustes generales";		
	$_extractedUrls="URLS EXTRAIDAS";
	$_extractedKeywords="PALABRAS CLAVE EXTRAÍDAS";
	$_extractedImages="IMÁGENES EXTRAÍDAS";
	$_urlList="LISTA DE URLS";
	$_results="RESULTADOS";
	$_text="TEXTO";	
	$_removeDuplicate="BORRA URLS DUPE";
	$_removeDuplicateDomains="BORRA DOMINIOS DUPE";	
	$_containing="que contengan";
	$_notContaining="que no contengan";
	$_records="registros";
	$_showingCache="Mostrando caché de una búsqueda previa";
	$_badProxy="El proxy falla";
	$_ucAll="El proxy falla";	
	$_ucAll="MAYUSC PRIMERAS";
	$_ucFirst="MAYUSC PRIMERA";	
	$_upperCase="MAYÚSCULAS";
	$_lowerCase="MINÚSCULAS";
	$_extractSelection="Extraer selección";
	$_allLinks="Todos los links";
	$_internalLinks="Links internos";
	$_externalLinks="Links externos";
	$_imageURLShard="URLs de imágenes (todas)";
	$_imageURLSoft="URLs de imágenes (tags)";
	$_emailAddress="Direcciones email";	
	$_urlPerBatch="URLs/bloque";
	$_batchDelay="Pausa bloques";	
	$_searchDelay="Pausa";
	$_sec="seg";
	$_remove="Borrar";
	$mainSettingsIntroText="Aquí defines las opciones principales de PHPSEO (archivo <em>config.txt</em>).";
	$langInfo="<strong>lang</strong> define el idioma de la herramienta. Idiomas disponibles actualmente: <strong>en/es</strong>. Recarga la página después de cambiar el idioma para ver los cambios.";
	$howManyURLPerStepInfo="<strong>howManyURLPerStep</strong> define cuántas URLs se consultarán a la vez en cada bloque.";
	$batchDelaySecondsInfo="<strong>batchDelaySeconds</strong> define los segundos de pausa al buscar entre cada bloque.";
	$searchDelaySecondsInfo="<strong>searchDelaySeconds</strong> define los segundos de pausa entre diferentes búsquedas.";		
	$customUserAgentInfo="<strong>customUserAgent</strong> define el user agent del navegador que utilizan los robots de PHPSEO.";	
	$_chooseImg="Elige una o varias imágenes";	
	$_applyoperImg="Aplica una o varias operaciones";
	$_flipString="Reflejar";	
	$_noflipString="no reflejar";	
	$_fliphorString="reflejar horizontal";	
	$_flipvertString="reflejar vertical";
	$_flipbothString="reflejar ambas";		
	$_resizeString="Redimensionar";	
	$_widthString="Ancho";
	$_qualityString="Calidad JPG";		
	$_noresizeString="no redimensionar";
	$_reducesizeString="reducir tamaño a";
	$_addtextString="Añadir texto";
	$_textString="Texto";	
	$_texttoaddString="texto a agregar";			
	$_marleftString="Margen Izquierda";
	$_martopString="Margen Superior";
	$_fosizeString="Tamaño de Fuente";
	$_focolorString="Color de Fuente";
	$_notextString="sin texto";
	$_addPhraseString="añadir una frase";
	$_customFontString="Puedes usar una fuente personalizada sobreescribiendo el archivo <a href='font.ttf' target='_blank'>font.ttf</a> localizado en la carpeta principal";
	$_addTextfromFileString="Añadir textos desde un archivo";
	$_randomOrderString="orden aleatorio";
	$_secuencialOrderString="orden secuencial";
	$_eachlineWillBeString="Cada línea del archivo <a href='imagetext.txt' target='_blank'>imagetext.txt</a> se usará como una frase. Puedes elegir entre orden aleatorio o secuencial a la hora de agregar frases a las imágenes.";
	$_addWatermarkString="Añadir marca de agua (imagen)";
	$_noWatermarkString="sin marca de agua";
	$_addaWatermarkString="añadir marca de agua";
	$_theFileWatermarkString="El  archivo <a href='watermark.png' target='_blank'>watermark.png</a> en la carpeta principal se usará en esta operación. Puedes usar una nueva marca de agua sobreescribiendo el archivo.";
	$_marrightString="Margen Derecha";
	$_marbottomString="Margen Inferior";
	$_convertFormatString="Convertir formato";
	$_noConversionString="sin conversion";
	$_convertToString="convertir a";
	$_uploadEditString="SUBIR Y EDITAR";	
	$_uploadWaitString="Procesando las imágenes...";	
	$_last100procString="Últimas 100 imágenes procesadas";
	$_deleteImagesString="BORRAR IMÁGENES";	
	$_downloadAllString="Descargar todo";		
	$_procString="Imágenes procesadas";
	$notificationsTitle="ULTIMAS ACTUALIZACIONES";
	$notificationsSubtitle="Notificaciones de PHPSEO";
	$autoUpdateTitle="PHPSEO ACTUALIZÁNDOSE...";
	$autoUpdateAlreadyString="Ya tienes la última versión de PHPSEO";
	$autoUpdateFailedDownloadString="Ha fallado la descarga desde";	
	$autoUpdateOkString="PHPSEO actualizado con éxito, <a style='color: #49ae5a; text-decoration: none;' href='".basename(__FILE__)."'>haz click aquí.</a>";	
	$proxyAlertString='Se generará un nuevo archivo de servidor proxy para PHPSEO. \n\n1.Descarga el archivo generado phpseo-proxy.php (puedes renombrarlo para más seguridad). \n\n2.Sube el archivo a una carpeta dentro de un sitio web. Necesitarás la URL pública completa de ese archivo (incluyendo http/s). \n\n3. Añade la URL del archivo en la lista inferior en el formato adecuado. Puedes ver un ejemplo del formato más abajo. No te olvides de agregar el password a la URL como parámetro final.';		
	$_titleFootprints='Footprints';
	$_descFootprints='Guarda nuevas footprints con el formato: <strong>Nombre Footprint@@@comando de google</strong>';
	$_manualCheckString='CHECK MANUAL';
	$_automaticCheckString='CHECK AUTOMÁTICO';
	$_selectPasteExcelString='también puedes seleccionar todo y pegar en excel';
	$_downloadCSVString='DESCARGAR CSV';
	$_setUpYourProxy='Por favor, configura tu propio web proxy, no puedes usar los proxies de ejemplo.';
	$_textUnique='El texto parece único';
	$_textNotUnique='El texto NO parece único';
	$_noGoogleResults='Google no ha devuelto ningún resultado, probablemente han bloqueado tu dirección IP. Espera unas horas o prueba con un proxy.';
	$_bannedProxyString="El proxy:\n\n$webproxy\n\nparece estar baneado en Google. \n\nPor favor, visita el pie de págna y pega la URL de debug -Search URL- en una pestaña nueva.\n\nSi estás baneado, necesitarás esperas unas horas o probar un servidor y/o dirección IP diferentes.";	
} else {	
	//English
	$_welcometo="Welcome to PHPSEO! Search a new keyword to start using the tool.";
	$_keyword="keyword";
	$_optional="optional";
	$_search="SEARCH";
	$_titleURLscrapper="URL scraper";
	$_subtitleURLscrapper="Search URLs by keyword, footprint, number of results & Google TLD";
	$_titleKeywordTool="Keyword generator";	
	$_subtitleKeywordTool="Get keyword suggestions from Google, YouTube or Amazon based in any main keyword";
	$_titleImageDownloaderBoth="Bulk image downloader & editor";
	$_titleImageDownloader="Bulk image downloader";
	$_titleImageEditor="Bulk image editor";
	$_subtitleImageDownloader="Search images & download them";
	$_subtitleImageEditor="Select multiple images and modify them";
	$_titleDataExtractor="Data extractor";
	$_subtitleDataExtractor="Extract URLs, links, images,emails & any other content from multiple URLs";
	$_titlePlagiCheck="Plagiarism checker";	
	$_subtitlePlagiCheck="Paste your text and check plagiarism using Google";
	$_morewordreqString="100 or more words required";			
	$_titleIndexedUrlCheck="Indexed URL checker";
	$_subtitleIndexedUrlCheck="Check if URLs are Google indexed in bulk";
	$_titleProxyManager="Proxy manager";
	$_subtitleProxyManager="Manage and create web proxies";
	$_createaProxyServer="CREATE A PROXY SERVER FILE";	
	$_typeaPassText="Type a password & press CREATE";
	$_createText="CREATE";
	$_newPassText="new password";
	$_downloadProxyFileText="DOWNLOAD PROXY FILE";	
	$_proxyListText="PROXY LIST";	
	$_proxyMiniHelp="
	<p style='margin-left:15px'>Here you define all the web proxies that you will create in different domains (<em>web-proxy-list.txt</em> file). </p>
	<ul>
		<li>Keep in mind that you will need different IP addresses per each web proxy. If they are shared IP's, they may be already blocked or showing captcha.</li>
		<li><strong>To create a new proxy</strong>, you only need to upload the file <em>phpseo-proxy.php</em> to any domain and then get the full URL address to that file.</li>
		<ul>
			<li>Example: if I drop <em>phpseo-proxy.php</em> under the folder <em>-testing-</em> at <em>mycooldomain.com</em>, the proxy URL will be <em>http://mycooldomain.com/testing/phpseo-proxy.php?password=YOURPASSWORD</em></li>	
			<li>For extra security, you may want to rename the file <em>phpseo-proxy.php</em> before uploading it.</li>
		</ul>
		<li><strong>After uploading the file</strong>, save the new web proxy to the list below (in a new line) with the format: <em>PROXYNAME<strong>@@@</strong>PROXYURL?password=PASS</em>.</li>
	</ul>
	";
	$_templateListText="CUSTOM TEMPLATES";
	$_templateListFormatText="Template list format";
	$_templateListHelp="One template per line. After saving a new template, you will need to refresh the page to see it in the dropdown.";	
	$_titleDocumentation="Documentation";
	$_subtitleDocumentation="PHPSEO help & documentation";
	$_titleInfo="Info";	
	$_titleMainSettings="Main settings";
	$_subtitleMainSettings="Edit the main program settings";	
	$_extractedUrls="EXTRACTED URLS";
	$_extractedKeywords="EXTRACTED KEYWORDS";
	$_extractedImages="EXTRACTED IMAGES";
	$_urlList="URL LIST";
	$_results="RESULTS";
	$_text="TEXT";
	$_removeDuplicate="REMOVE DUPLICATE";	
	$_removeDuplicateDomains="REMOVE DUPE DOMAIN";
	$_containing="containing";
	$_notContaining="not containing";
	$_records="records";	
	$_showingCache="Showing cached results from previous search";
	$_badProxy="Bad proxy";	
	$_ucAll="UC ALL";
	$_ucFirst="UC FIRST";	
	$_upperCase="UPPERCASE";
	$_lowerCase="LOWERCASE";	
	$_extractSelection="Extract selection";	
	$_allLinks="All links";
	$_internalLinks="Internal links";
	$_externalLinks="External links";
	$_imageURLShard="Image URLs (hard)";
	$_imageURLSoft="Image URLs (soft)";
	$_emailAddress="Email address";
	$_urlPerBatch="URLs/batch";
	$_batchDelay="Batch delay";	
	$_searchDelay="Search delay";
	$_sec="sec";
	$_remove="Remove";	
	$mainSettingsIntroText="Here you define the main PHPSEO settings (<em>config.txt</em> file).";
	$langInfo="<strong>lang</strong> defines the tool language. Currenly available: <strong>en/es</strong>. Reload the page after saving the changes.";
	$howManyURLPerStepInfo="<strong>howManyURLPerStep</strong> sets how many URLs the tool will check at once in each batch (each step).";
	$batchDelaySecondsInfo="<strong>batchDelaySeconds</strong> controls the seconds paused between each URL batch.";
	$searchDelaySecondsInfo="<strong>searchDelaySeconds</strong> controls the seconds paused between each different search query.";		
	$customUserAgentInfo="<strong>customUserAgent</strong> defines the browser user agent used by all PHPSEO robots.";	
	$_chooseImg="Choose one or more images";	
	$_applyoperImg="Apply one or more operations";
	$_flipString="Flip";	
	$_noflipString="no flip";	
	$_fliphorString="flip horizontal";	
	$_flipvertString="flip vertical";
	$_flipbothString="flip both";
	$_resizeString="Resize";
	$_widthString="Width";
	$_qualityString="JPG Quality";
	$_noresizeString="no resize";
	$_reducesizeString="reduce size to";
	$_addtextString="Add text";
	$_textString="Text";
	$_texttoaddString="text to add";		
	$_marleftString="Margin Left";
	$_martopString="Margin Top";
	$_fosizeString="Font Size";
	$_focolorString="Font Color";
	$_notextString="no text";
	$_addPhraseString="add a phrase";
	$_customFontString="You can use a custom font by overwriting the file <a href='font.ttf' target='_blank'>font.ttf</a> located in the root folder";
	$_addTextfromFileString="Add text from file";
	$_randomOrderString="random order";
	$_secuencialOrderString="secuencial order";
	$_eachlineWillBeString="Each line from the file <a href='imagetext.txt' target='_blank'>imagetext.txt</a> will be used as a phrase. You can choose between adding sentences to the images in random or secuencial order.";
	$_addWatermarkString="Add watermark (image)";
	$_noWatermarkString="no watermark";
	$_addaWatermarkString="add a watermark";
	$_theFileWatermarkString="The file <a href='watermark.png' target='_blank'>watermark.png</a> in the main root will be used in this operation. You can use a new watermark by overwriting the file.";
	$_marrightString="Margin Right";
	$_marbottomString="Margin Bottom";
	$_convertFormatString="Convert format";
	$_noConversionString="no conversion";
	$_convertToString="convert to";
	$_uploadEditString="UPLOAD & EDIT";
	$_uploadWaitString="Processing the images...";	
	$_last100procString="Last 100 processed images";
	$_deleteImagesString="DELETE ALL IMAGES";
	$_downloadAllString="Download all";	
	$_procString="Processed images";
	$notificationsTitle="LATEST UPDATES";
	$notificationsSubtitle="PHPSEO notifications";	
	$autoUpdateTitle="PHPSEO AUTO UPDATING...";
	$autoUpdateAlreadyString="You already have the last version of PHPSEO";
	$autoUpdateFailedDownloadString="Failed to download the update from";	
	$autoUpdateOkString="PHPSEO updated successfully, <a style='color: #49ae5a; text-decoration: none;' href='".basename(__FILE__)."'>click here.</a>";	
	$proxyAlertString='A new PHPSEO proxy server will be generated. \n\n1.Download the generated phpseo-proxy.php file (you can rename it for more security). \n\n2.Upload the file into any website folder. You will need the complete public URL of that file (including http/s). \n\n3. Add the file URL in the list below in the right format. You can see a sample of the format below. Do not forget to add your password as the final parameter.';	
	$_titleFootprints='Footprints';
	$_descFootprints='Save new footprints in the format: <strong>Footprint Name@@@google command</strong>';
	$_manualCheckString='MANUAL CHECK';
	$_automaticCheckString='AUTOMATIC CHECK';
	$_selectPasteExcelString='you can also select all & paste to excel';
	$_downloadCSVString='DOWNLOAD CSV';	
	$_setUpYourProxy='Please, set up your own web proxy, you cannot use sample proxies.';
	$_textUnique='The text seems unique';
	$_textNotUnique='The text seems NOT unique';
	$_noGoogleResults='Google did not return any result, they probably blocked your IP address. Try using a proxy.';		
	$_bannedProxyString="The proxy:\n\n$webproxy\n\nseems to be banned by Google search. \n\nPlease, visit the bottom of this page and try to copy & paste the debug -Search URL- in a new browser tab.\n\nIf you are banned, you may need to wait some hours or try a different server/IP address.";	
}
/**************************/
/*End string translations*/
/************************/
if ($module=="plagichecksubmit") {	
	$textContent=$_POST['data'];
	$webproxy=$_POST['webproxy'];	
	if (!empty($textContent)){
		$words = explode(' ', $textContent);
		$numWords=count($words);
		$substringRandom=join(' ', array_slice($words, mt_rand(0, $numWords - 1), mt_rand(1, $numWords)));
		$substringRandom=trim(substrwords($substringRandom,120));
		$substringRandom=urlencode($substringRandom);	
		$urltoCheck="https://google.com/search?q=%22$substringRandom%22&hl=en";		
		if ($webproxy=="noproxy") {
			$results = getPageGoogle('',$urltoCheck, '', $customUserAgent, 1, 25);	
		} else {
			$urlDefinitiva=$webproxy."&getPage=".$urltoCheck;
			$results = getPageGoogle('',$urlDefinitiva, '', $customUserAgent, 1, 25);
		}
		$arraydatos=$results['EXE'];	
		if (strpos($arraydatos,"No results found for") !== false) {	
			echo "<div class='uniqueok'>";
			echo "<p> <a style='color: grey;' target='_blank' href='$urltoCheck'> <i class='fa fa-external-link' aria-hidden='true'></i> $_manualCheckString</a></p> $_textUnique :) </div>";
		} elseif ((strpos($arraydatos,"About") !== false)||(strpos($arraydatos,"result") !== false)) {
			echo "<div class='uniqueko'>";
			echo "<p> <a style='color: grey;' target='_blank' href='$urltoCheck'> <i class='fa fa-external-link' aria-hidden='true'></i> $_manualCheckString</a></p> $_textNotUnique :( </div>";
		}
 		else	{
			echo "<div class='uniqueko'><p>ERROR</p> $_noGoogleResults</div>";
		}
	}
exit;	
}
/**************/
/*Auto update*/
/************/
if ($module=="autoupdate"){	
//Delete cookies in future for welcome and changelog	
?>
<!DOCTYPE html>
<html>
<head>
<title>PHPSEO autoupdate</title>
<link href="https://fonts.googleapis.com/css2?family=Slabo+27px&amp;display=swap" rel="stylesheet">
</head>
<body style="background-color: #3f3f3f; color:white;text-align:center;font-family: 'Slabo 27px', serif;">
	<a href='<?php echo basename(__FILE__);?>'><img src='https://netgrows.com/phpseo/PHPSEO.png'></a>
	<h2><?php echo $autoUpdateTitle;?></h2>	
	<?php
	$file = "https://netgrows.com/phpseo/".$phpseoVersion."-next/phpseo.zip";
	if (false === @file_get_contents($file,0,null,0,1)) {
		echo "<p>$autoUpdateAlreadyString (<strong>$phpseoVersion</strong>).</p>";
	} else {		
		$newfile = 'phpseoLast.zip';		
				if (!copy($file, $newfile)) {
					echo "<p>$autoUpdateFailedDownloadString <strong>$file</strong></p>";
				}else {	
					$zip = new ZipArchive();
					if ($zip->open($newfile, ZIPARCHIVE::CREATE)!==TRUE) {
						echo "<p>Cannot open <strong>$filename></strong></p>";
						unlink($newfile);
					} else {
						echo "<p>$autoUpdateOkString</p>";
						$zip->extractTo('.');
						$zip->close();
						unlink($newfile);
						if (isset($_COOKIE['dialogShown'])) {
							unset($_COOKIE['dialogShown']);
							setcookie('dialogShown', '', time() - 3600, '/'); // empty value and old timestamp
						}					
					}						
				}
			?>
		</body>
		</html>
		<?php		
	}
	exit;
}

/***************/
/*Begin Output*/
/*************/
header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js" integrity="sha512-y3o0Z5TJF1UsKjs/jS2CDkeHN538bWsftxO9nctODL5W40nyXIbs0Pgyu7//icrQY9m6475gLaVr39i/uh/nLA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip-utils/0.1.0/jszip-utils.min.js" integrity="sha512-3WaCYjK/lQuL0dVIRt1thLXr84Z/4Yppka6u40yEJT1QulYm9pCxguF6r8V84ndP5K03koI9hV1+zo/bUbgMtA==" crossorigin="anonymous"></script>
<meta name="msapplication-TileImage" content="https://netgrows.com/wp-content/uploads/2018/11/cropped-netgrows-favicon-270x270.png" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="http://netgrows.com/phpseo/favicon.png"/>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Slabo+27px&display=swap" rel="stylesheet">
<style>
body{
	background-color:#3f3f3f;
	padding: 0px;	
	margin: 0px;
	font-family: 'Slabo 27px', serif;
}
a  {
  -o-transition:.2s;
  -ms-transition:.2s;
  -moz-transition:.2s;
  -webkit-transition:.2s;
  transition:.2s;
}
#topbar{
    width: 100%;
    background: grey;
    position: fixed;
    top: 0;
    text-align: center;
    color: white;
    font-size: 12px;
    padding: 4px 0px 1px 0px;	
}
#topbar a{
	color: #464646;
}
#topbar .icon{
    color: #17ea58;
}
#topbar a:hover{
	color: #17ea58;
}
#topbar .fa{
    font-size: 20px;
}
#topbarleft .fa{
    font-size: 14px;
}
#topbarcontent{
	max-width:1400px;
	margin: 0 auto;
}
#topbarright{
	float:right;
	margin-top: 7px;
    width: 40%;
    text-align: right;	
}
#topbarleft{
	float:left;
    width: 40%;
    text-align: left;	
}
#containlogo{
	width:100%;
	text-align:center;
}
#containlogo img{
	max-width: 320px;
    margin-top: 50px;
}
#menucenter2{
	width:19%;
	text-align:center;
	float: left;	
}
#menucenter2 img{
    max-width: 65px;	
}
#menucenter{
    margin-top: 3px;
    display: inline-block;
	width: auto;
	text-align:center;
	margin-left:15px;
	background: #ececec;
    padding: 4px 0px;
    margin-top: 0px;	
}
#menucenter .fa{
    font-size: 14px;
}
#menucenter a{
	padding:5px 10px;
}
#menucenter a:hover{
	background:#464646;
}
#menucenter a.active{
	background:#464646;
}
#topbar a.active{
	color: #17ea58;
}
#webproxy{
	padding:2px;
	font-size: 11px;	
}
.separator{
	color:#cacaca;
}
.iconseparator {
    color: #cacaca;
    width: 2px;
    display: inline-block;
    border-right: solid 1px #d6d6d6;
    height: 10px;
    margin-right: 2px;
}
#downloadResult {
    color: #464646;
    margin: 0px 10px;
    font-size: 12px;
    display: block;
    margin: 25px;	
}
.modal-content #downloadResult {
    font-size: 16px;
}
#downloadResult a{
	color: #059030;
	text-decoration: none;	
}
.buttonz {
	color: #fff;
    width: 135px;
    height: 29px;
    border: none;
    margin: 5px;
    background: #49ae5a;
    cursor: pointer;
    box-shadow: 1px 1px 2px #464646;
    border: solid 1px #b5b5b5;
    border-radius: 8px;	
	
}
.buttonz:hover{
	color: #49ae5a;
    background: #000000;
}

.subtopContainer{
	font-size: 14px;	
    overflow: auto;	
}
.extractedLabel{
    float: left;
    margin: 5px 0px 10px 0px;
    font-size: 10px;
    background: #ececec;
    padding: 4px 10px;
	color: #464646;
    font-weight: bold;	
}
.currentprogress{
	color: #464646;
    margin: 4px 10px;
    float: left;
    font-size: 11px;
    background: #17ea58;
    padding: 4px 10px;	
}
.totalbatches{
	color: #ff0000;
	font-size: 14px;
	font-weight:bold;	
}
#goodproxy{
	color:#059030;
	text-align: center;
	float:right;
    margin: 5px 0px 10px 10px;
    font-size: 12px;
    background: #ececec;
    padding: 4px 10px;	
}
#badproxy{
	color:#ea6e6e;
	text-align: center;
	float:right;
    margin: 5px 0px 10px 10px;
    font-size: 12px;
    background: #ececec;
    padding: 4px 10px;
}
.aprincipal {
	background: #cccccc;
	padding: 10px 20px 0px 20px;
	overflow: auto;	
}
.aprincipal .buttonz {
    font-size: 10px;
	padding:9px;	
}
.contadorLineas, #contadorLineas2{
	color:#059030;
	font-size:12px;
	float:right;
    margin: 5px 0px 10px 10px;
    font-size: 12px;
    background: #ececec;
    padding: 4px 10px;
    font-weight: bold;		
}
.aprincipal h4{
	color: #464646;
    font-size: 14px;
    padding: 0px 10px 15px 0px;
}
h1{
	text-align: center;
    color: #d0cece;
	margin-bottom: 0px;
    margin-top: 60px;	
}
h2{
    text-align: center;
    color: #64d678;
    margin-bottom: 0px;
    padding-bottom: 0px;
    margin-top: 0px;	
}
.subtitle{
	text-align: center;
    color: #ececec;
    font-style: italic;
    font-size: 14px;
    margin: 3px 10px 25px 10px;
}
a{
	color: aliceblue;
}
.configvar{
	color:#17ea58;
}
h3{
    text-align: center;
    color: #eaeaea;
    font-weight: normal;
    font-size: 12px;
    margin: 5px;
	padding-top: 0px;
}
h4{
	text-align: center;
    color: #eaeaea;
    font-weight: normal;
    padding: 5px;
    font-size: 12px;
    margin: 5px;
    color: #b5b5b5;	
}
#documentation h4{
	font-size: 32px;
    color: #247710;
    text-align: left;
	margin:0px;
	padding:0px;
}
#documentation h5{
	font-size: 22px;
    color: #464646;
    text-align: left;
	margin-bottom:25px;
}
#documentation ul, #documentation ol{
    font-size: 16px;
	line-height: 24px;
}
#tocList a{
	color: #6fa5db;
}
#documentation p{
    font-size: 16px;
    margin-left: 20px;	
}
#documentation a, #imageform a{
    color:#0a7710;
	text-decoration: none;	
}
.miniurl {
	text-transform:lowercase;
	color:#a9a9a9;
}
hr {
	border: solid 0.5px #e8e8e8;
}
.atitle{
	text-align:center;
	font-size:18px;
	color:#8c8c8c;
	font-weight:bold;
	padding:10px;
    background-color: #f7f7f7;
    padding: 10px 20px;		
}
.abody{
	text-align:left;
	font-size:14px;	
	color:#888888;	
    background-color: #f7f7f7;
    border: solid 1px #d6d6d6;
    padding: 30px 20px;	
	height: 120px;
    display: block;
    overflow-y: scroll;	
}
.original{
	height: 120px;
	display: block;
	overflow-y: scroll;
	background: #ececec;
	padding: 20px 40px;
}
.translatedbody{
	background: #ececec;
	padding: 20px 40px;	
}
.highlight {
    background-color: yellow;
}
#loading {
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   position: fixed;
   display: block;
   opacity: 0.9;
   background-color:rgba(105 104 104 / 0.9);   
   z-index: 99;
   text-align: center;
}
select {
    padding: 5px 10px;
	background: #464646;
    color: white;
	font-size: 12px;
}
.maincontent {
	margin: 10px 20px; 
}
.maincontent .titleblock{
	text-transform: uppercase; 
}
.uppercase{
	text-transform: uppercase; 
}
.error {
	color: wheat;
	margin: 20px;	
}
.error a{
	color: #10bfe6;
}
#resetformcontainer{
	text-align:center;
}
#resetform{
	color:white;
	font-size:12px;
}
textarea{
	background-attachment: local;
	background-repeat: no-repeat;
	padding-left: 15px;
	padding-top: 10px;
	border-color:#ccc;
    background-color: #ececec;	
	line-height: 16.5px;
	font-family: sans-serif;
	width:calc(100% - 85px)!important;	
}
.debugArea{
	font-size:10px;
	background-color:#f7f7f7;
	border: solid 1px #ea6e6e;
	padding: 10px 20px;
	margin: 30px 40px 5px 40px;
}
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 40%; /* Could be more or less, depending on screen size */
  text-align: center;

}
/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}
.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
#documentation{
  background-color: #f7f7f7;
  margin: 10px auto;
  padding: 20px 50px;
  max-width: 1400px; 
}
#documentation #tocList li {
  font-size: 24px;
  line-height: 40px;
  list-style: none;  
}
#documentation #tocList li li {
  font-size: 16px;
  font-weight: normal;
  list-style: disc;
  line-height: 26px;
}
#docsubmenu{
	text-align:center;	
}
#docsubmenu a{
    color: #464646;
    text-decoration: none;
    padding: 5px 10px;
    text-decoration: none;
    font-size: 13px;
    background: #ececec;
    border: solid 1px #ababab;
    box-shadow: 1px 1px 2px #ababab;	
}
.buttonsright{
	float:right;
    font-size: 24px;
    margin-right: 30px;	
}
.buttonsright a{
    color: #464646;
	padding: 5px 10px;
    text-decoration: none;
    font-size: 13px;
    background: #ececec;
    border: solid 1px #ababab;
    box-shadow: 1px 1px 2px #ababab;
	white-space: nowrap;	
}
.buttonsright a:hover{
	color:#4fea58;
	background:#464646;
}
.stop_button{
	color:white;
	background-color:red;
}
#moreoptionscontainer{display:none;}
notification-container {
    position: relative;
    width: 16px;
    height: 16px;
    top: 15px;
    left: 15px;
    
    i {
        color: #fff;
    }
}
.notification-counter {   
    position: absolute;
    top: -5px;
    left: 12px;   
    background-color: rgba(212, 19, 13, 1);
    color: #fff;
    border-radius: 3px;
    padding: 1px 3px;
    font: 10px Verdana;
}
#notifications {
	position: relative;
	float:right;
	color: #ececec;
	font-weight: bold;
	margin-left: 20px;
	margin-top: -5px;
}
#notifications ul {
    background: #000;
    width: 100%;
}
#notifications ul, #notifications li {
    list-style: none;
    margin: 0;
    padding: 0;
}
#linkbell{
	color: #ececec!important;;
}
#linkbell:hover{
	color: #10ef5b!important;
}
#minilogo{
	margin-top: -2px;
}
#numversion, #minilogo{
	display:inline-block;
}
#numversion{
	font-size:9px;
	vertical-align: bottom;
    display: block;
    margin-top: -10px;
    margin-left: 50px;	
}
#resetfile{
	color: #464646;
    float: right;
    margin: 5px 0px 10px 10px;
    font-size: 12px;
    background: #ececec;
    padding: 4px 10px;
    font-weight: bold;
    text-decoration: none;
	font-size: 10px;
}
#resetfile:hover{
    background: #464646;
    color: #17ea58;	
}
#extractlinks:hover{
	color:#4fea58;
	background:#464646;
}
.notifi_title{
	font-size: 26px;
    padding: 10px 0px;	
}
.notifi_desc{
	font-size: 18px;
    padding: 10px 0px 10px 0px;	
}
.notifi_fecha{
	font-size: 14px;
    font-style: italic;
    color: #84b5eb;
    float: right;
    background: #f7f7f7;
    padding: 3px;
    border-radius: 20px;
    padding: 2px 10px;
	margin-top:-10px;
}
.notifi_desc a{
	color: red;	
}
.notifi_desc a:hover{
	color: #50eb5c;	
}
.bloque_notifi{
	color: #464646;
    padding: 40px;
    text-decoration: none;
    font-size: 13px;
    background: #ececec;
    border: solid 1px #ababab;
    box-shadow: 1px 1px 2px #ababab;
    margin: 40px 0px;
}
#downloadnewproxyContainer{
	color:#f91212;	
    margin: 5px 20px 10px 20px;
    font-size: 14px;
    background: #ececec;
    padding: 4px 10px;
    font-weight: bold;
}
#downloadnewproxyContainer a{
    color: #189a1f;	
}
.plagiresults{
	text-align: left;margin:20px;
	font-size:14px;
	background-color:#f7f7f7;
	border: solid 1px #d6d6d6;
	padding: 10px 20px;
	color: #888888;
}
.plagiresults a{
	text-decoration: none;
	padding: 5px 10px;
    text-decoration: none;
    font-size: 13px;
    background: #ececec;
    border: solid 1px #ababab;
    box-shadow: 1px 1px 2px #ababab;
}
.uniqueok, .uniqueko{
    margin: 5px;
    padding: 0px 20px 15px 20px;
    box-shadow: 1px 1px 2px #464646;
    border: solid 1px #ababab;
    font-size: 20px;
    background: #ececec;	
}
.uniqueko {color:#ea6e6e;}
.uniqueok {color:#059030;}
.under {
  position: absolute;
  left: 0px;
  top: 0px;
  z-index: -1;
}
.over {
  position: absolute;
  left: 40px;
  top: 10px;
  z-index: -1;
}
#loader {
  padding: 10px; 
  color: #22774f;	
}
.containloader{
    width: 325px;
    margin: 0 auto;
    display: block;
    overflow: hidden;
    position: relative;
    z-index: 999999;
    height: 280px;
    position: relative;
    top: 50%;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
	margin-top: -50px;
}
.card {
  background-color: #ececec;
  color: #808080;
  padding: 5px;
  text-align: center;
  background-size: 50px;
  background-repeat: no-repeat;
  background: radial-gradient(circle, rgba(79,82,82,1) 0%, rgba(214,214,214,1) 100%);
}
.cards {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  margin-top: 20px;
}
.cards .fa{
  font-size: 44px;
  padding: 13px;	
}
.cards a{
  color: #3f3f3f;
  text-decoration:none; 
  display: block;
  background-color: #ececec;
  padding: 3px 10px;
  height: 160px;
}
.cards a:hover{
  background-color: #3f3f3f;
  color:#ececec;
}
.cards p{
  margin: 10px;
  font-size: 14px;
}    
@media (min-width: 600px) {
  .cards { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 900px) {
  .cards { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width:1200px)  { 
	textarea{ width:calc(100% - 65px)!important;}
	.aprincipal{ padding: 10px 20px 10px 20px;}
	#topbarleft{
		float: none;
		width: 100%;
		text-align: center;				
	}
	#topbarright{display:none;}
	#menucenter2{display:none;}	
	#menucenter, #topbarright{margin:10px;}	
	#notifications{float:none;margin: 5px;}	
	#containlogo{margin-top: 25px;}
	.notification-counter{position: relative;}
	.notification-container{background: grey;}	
	#notifications ul, #notifications li {margin-top: -15px;}
	.containerControls input, .containerControls select{width:100%;}
	.operationLabel{margin: 20px; display: block;}
}
.titleblock{
    color: #46a557;
    font-weight: bold;
	font-size: 18px;
}
.rownr {border:none;font-size:12px;resize: none; margin:0px; padding: 10px 5px 10px 0px; width:auto!important; overflow-y: hidden; background-color: rgb(204 204 204); color: #a59d9d; text-align: right; vertical-align:top; z-index: 0; height: 281px;}
.txt {margin:0px; padding:0px; width: 90%!important; overflow-x: scroll; background: transparent; z-index: 0; height:250px;}
textarea{font-size:12px;}
#containImageFolder{
	max-height: 550px;
    overflow-y: auto;
    border-top: solid 1px #a5a5a5;
    margin-top: 25px;
}
#containImageFolder img{
    height: 100px;
    width: 100px;
    object-fit: contain;
    object-position: center;
    float: left;
    display: inline-block;
    margin: 5px;
    background: #aba9a9;
    border: solid 1px #cccccc;
    box-shadow: 1px 1px 2px #6d6a6a;
    padding: 5px;
}
#success_div{
    width: auto;
    background: #444444;
    padding: 25px;
    border: solid 2px #9e9e9e;
	color: #ececec;
    margin: 30px 0px;	
}
.containImageSmall{
    display: inline-block;
    background: grey;
    margin: 5px;
    color: #ececec;
    padding: 0px 0px 6px 0px;
    border: solid 1px #b7b7b7;
    box-shadow: 2px 2px 3px #6d6a6a;
}
.imageSizeTextBelow, .imageSizeTextBelow2{
	font-size:12px;
	display: block;
    text-align: center;	
}
.operationLabel{
	margin-left: 20px;
    font-size: 16px;
    margin-right: 20px;	
	font-weight: bold;
}
.containImgOperation{
    padding: 50px 15px 40px 15px;
}
.containImgOperation p{
    margin-left: 40px;
    padding: 15px 0px 0px 0px;
}
.containImgOperation:nth-child(odd) {
	background:#a5a5a5;
}
.containImgOperation:nth-child(even) {
	background:#b1b1b1;	
}
.subinput{
	font-size: 14px;
    max-width: 500px;
    margin: 0 auto;
    line-height: 20px;
    margin-top: 5px;	
}
.lastnewshome li{
	list-style: none;
    padding: 10px;	
}
.lastnewshome .titleblock{
	padding:20px 0px;
	background: #ececec;
}

.lastnewshome .contentblock{
    overflow-y: auto;
    height: 192px;
    padding: 10px 5px;
	background: #ececec;
}
.lastnewshome ul{
	margin: 0px;
	padding: 0px;
}
.lastnewshome .contentblock a{
    height: auto;
    border-radius: 10px;
    padding: 6px 10px;
    background-color: #46a557;
    color: #ececec;
}
.lastnewshome .contentblock a:hover{
    background-color: #3f3f3f;
    color: #ececec;
}

</style>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js" integrity="sha512-Meww2sXqNHxI1+5Dyh/9KAtvI9RZSA4c1K2k5iL02oiPO/RH3Q30L3M1albtqMg50u4gRTYdV4EXOQqXEI336A==" crossorigin="anonymous"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
</head>
<body>
<div id="loading">
<div class="containloader">
<img class="under" style="opacity: 0.5;"src='https://netgrows.com/phpseo/spider.png'>
<img class="over" style="padding:70px;padding-bottom:5px;" src="https://netgrows.com/phpseo/loader.gif"><br>
</div>
</div>
<?php
$debug="<hr>";
?>
<form method='post' id="busca1" action="<?php echo basename($_SERVER['REQUEST_URI']);?>">
<div id="topbar">
	<div id="topbarcontent">
		<div id="topbarleft">
			<i class="fa fa-plug icon" aria-hidden="true"></i>
			Web proxy: 
			<select name="webproxy" id="webproxy">
			<option value='noproxy'>No proxy</option>
			<?php showProxies($webproxy); ?>
			</select>
			<?php
			if (!isset($_GET['module'])) $_GET['module']="home";	
			?>
			<div id="menucenter">
				<a title='Home' href="<?php echo basename(__FILE__);?>" <?=(($_GET['module'] == "home") ? 'class="active"' : '')?>><i class="fa fa-home"></i></a><span class='iconseparator'></span><a title='URL Scraper' href="<?php echo basename(__FILE__);?>?module=urlscraper" <?=(($_GET['module'] == "urlscraper") ? 'class="active"' : '')?>><i class="fa fa-search"></i></a><span class='iconseparator'></span><a title='Data extractor' href="<?php echo basename(__FILE__);?>?module=dataextractor" <?=(($_GET['module'] == "dataextractor") ? 'class="active"' : '')?>><i class="fa fa-database" aria-hidden="true"></i></a><span class='iconseparator'></span><a title='Keyword Scraper' href="<?php echo basename(__FILE__);?>?module=keywords" <?=(($_GET['module'] == "keywords") ? 'class="active"' : '')?>><i class="fa fa-book" aria-hidden="true"></i></a><span class='iconseparator'></span><a title='Image Scraper' href="<?php echo basename(__FILE__);?>?module=images" <?=(($_GET['module'] == "images") ? 'class="active"' : '')?>><i class="fa fa-picture-o" aria-hidden="true"></i></a><span class='iconseparator'></span><a title='<?php echo $_titlePlagiCheck;?>' href="<?php echo basename(__FILE__);?>?module=plagicheck" <?=(($_GET['module'] == "plagicheck") ? 'class="active"' : '')?>><i class="fa fa-files-o" aria-hidden="true"></i></a><span class='iconseparator'></span><a title='<?php echo $_titleIndexedUrlCheck;?>' href="<?php echo basename(__FILE__);?>?module=indexedcheck" <?=(($_GET['module'] == "indexedcheck") ? 'class="active"' : '')?>><i class="fa fa-check" aria-hidden="true"></i></a><span class='iconseparator'></span><a title='<?php echo $_subtitleDocumentation;?>' href="<?php echo basename(__FILE__);?>?module=docs" <?=(($_GET['module'] == "docs") ? 'class="active"' : '')?>><i class="fa fa-question" aria-hidden="true"></i></a> 		
			</div>
		</div>		
		<div id="menucenter2">
			<div id="minilogo"><a title='URL Scraper' href="<?php echo basename(__FILE__);?>"><img style="vertical-align: middle;" src='https://netgrows.com/phpseo/minilogo.png'></a></div>
			<div id="numversion"><?php echo $phpseoVersion;?></div>
		</div>
		<div id="topbarright">
			 <?php echo $_urlPerBatch;?>: <span class='configvar'> <?php echo $howManyURLPerStep;?></span> <span class='separator'>|</span> <?php echo $_batchDelay;?>: <span class='configvar'> <?php echo $batchDelaySeconds;?> <?php echo $_sec;?></span> <span class='separator'>|</span> <?php echo $_searchDelay;?>: <span class='configvar'> <?php echo $searchDelaySeconds;?> <?php echo $_sec;?></span> <span class='separator'>|</span> Debug: <span class='configvar'><?php if ($debugSearchDisplay) echo "ON"; else echo "OFF";?></span> <span class='separator'>|</span> Exec time: <span class='configvar'><?php echo ini_get('max_execution_time');?> <?php echo $_sec;?></span> 


			<div id="notifications">
			<a id='linkbell' href='<?php echo basename(__FILE__);?>?module=lastnews'><i class="fa fa-bell" aria-hidden="true"></i></a>
				<ul>
					<li class="notification-container">
						<i class="icon-globe"></i>
						<span class="notification-counter"></span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div style="height:10px;clear:both;"></div>
<div id="containlogo"><a title='URL Scraper'  href="<?php echo basename(__FILE__);?>"><img src='https://netgrows.com/phpseo/PHPSEO.png'></a></div>
<?php 
/*Begin scraper module*/
if ($module=="urlscraper") {
?>
<h2>URL SCRAPER</h2>
<p class="subtitle"><?php echo $_subtitleURLscrapper;?></p>
  <center>
  <input style="padding:5px" type="search" name="query" value="<?php echo $query;?>" placeholder="<?php echo $_keyword;?>" required>    
  <?php 		
  $selected1=$selected2=$selected3=$selected4=$selected5=$selected6=$selected7="";
  if (!empty($pagina)) {
	  if ($pagina=="1") $selected1="selected"; if ($pagina=="2") $selected2="selected"; if ($pagina=="3") $selected3="selected"; if ($pagina=="4") $selected4="selected"; if ($pagina=="5") $selected5="selected";
	  if ($pagina=="6") $selected6="selected"; if ($pagina=="7") $selected7="selected"; if ($pagina=="8") $selected8="selected"; if ($pagina=="9") $selected9="selected"; if ($pagina=="10") $selected10="selected";	  
  }
  ?>
  <input style="padding:5px" type="search" list="footprints" name="optionz" value="<?php echo $optionz;?>" placeholder="footprint (<?php echo $_optional;?>)"> 
  <datalist id="footprints"> <?php showData_Footprints(); ?> </datalist>
  <br><br>
  <select name="pagina">
  	<option value="1" <?php echo $selected1;?>>100</option>	<option value="2" <?php echo $selected2;?>>200</option>	<option value="3" <?php echo $selected3;?>>300</option>	<option value="4" <?php echo $selected4;?>>400</option>	
  </select> 
<select name="googleRegion">
	<option value="google.com" <?php if ($googleRegion=='google.com') echo 'selected';?>>Global / US (google.com)</option>
	<option value="google.es" <?php if ($googleRegion=='google.es') echo 'selected';?>>Spain (google.es)</option>	
	<option value="google.com.af">Afghanistan (google.com.af)</option>
	<option value="google.al">Albania (google.al)</option>
	<option value="google.dz">Algeria (google.dz)</option>
	<option value="google.as">American Samoa (google.as)</option>
	<option value="google.ad">Andorra (google.ad)</option>
	<option value="google.co.ao">Angola (google.co.ao)</option>
	<option value="google.com.ai">Anguilla (google.com.ai)</option>
	<option value="google.com.ag">Antigua and Barbuda (google.com.ag)</option>
	<option value="google.com.ar">Argentina (google.com.ar)</option>
	<option value="google.am">Armenia (google.am)</option>
	<option value="google.ac">Ascension Island (google.ac)</option>
	<option value="google.com.au">Australia (google.com.au)</option>
	<option value="google.at">Austria (google.at)</option>
	<option value="google.az">Azerbaijan (google.az)</option>
	<option value="google.bs">Bahamas (google.bs)</option>
	<option value="google.com.bh">Bahrain (google.com.bh)</option>
	<option value="google.com.bd">Bangladesh (google.com.bd)</option>
	<option value="google.by">Belarus (google.by)</option>
	<option value="google.be">Belgium (google.be)</option>
	<option value="google.com.bz">Belize (google.com.bz)</option>
	<option value="google.bj">Benin (google.bj)</option>
	<option value="google.bt">Bhutan (google.bt)</option>
	<option value="google.com.bo">Bolivia (google.com.bo)</option>
	<option value="google.ba">Bosnia &amp; Herzegovina (google.ba)</option>
	<option value="google.co.bw">Botswana (google.co.bw)</option>
	<option value="google.com.br">Brasil (google.com.br)</option>
	<option value="google.vg">British Virgin Islands (google.vg)</option>
	<option value="google.com.bn">Brunei (google.com.bn)</option>
	<option value="google.bg">Bulgaria (google.bg)</option>
	<option value="google.bf">Burkina Faso (google.bf)</option>
	<option value="google.com.mm">Burma (google.com.mm)</option>
	<option value="google.bi">Burundi (google.bi)</option>
	<option value="google.com.kh">Cambodia (google.com.kh)</option>
	<option value="google.cm">Cameroon (google.cm)</option>
	<option value="google.ca">Canada (google.ca)</option>
	<option value="google.cv">Cape Verde (google.cv)</option>
	<option value="google.cat">Catalan Countries (google.cat)</option>
	<option value="google.cf">Central African Republic (google.cf)</option>
	<option value="google.td">Chad (google.td)</option>
	<option value="google.cl">Chile (google.cl)</option>
	<option value="google.com.co">Colombia (google.com.co)</option>
	<option value="google.co.ck">Cook Islands (google.co.ck)</option>
	<option value="google.co.cr">Costa Rica (google.co.cr)</option>
	<option value="google.hr">Croatia (google.hr)</option>
	<option value="google.com.cu">Cuba (google.com.cu)</option>
	<option value="google.com.cy">Cyprus (google.com.cy)</option>
	<option value="google.cz">Czech (google.cz)</option>
	<option value="google.cd">DR Congo (google.cd)</option>
	<option value="google.dk">Denmark (google.dk)</option>
	<option value="google.dj">Djibouti (google.dj)</option>
	<option value="google.dm">Dominica (google.dm)</option>
	<option value="google.com.do">Dominican Republic (google.com.do)</option>
	<option value="google.com.ec">Ecuador (google.com.ec)</option>
	<option value="google.com.eg">Egypt (google.com.eg)</option>
	<option value="google.com.sv">El Salvador (google.com.sv)</option>
	<option value="google.ee">Estonia (google.ee)</option>
	<option value="google.com.et">Ethiopia (google.com.et)</option>
	<option value="google.fm">FS Micronesia (google.fm)</option>
	<option value="google.com.fj">Fiji (google.com.fj)</option>
	<option value="google.fi">Finland (google.fi)</option>
	<option value="google.fr">France (google.fr)</option>
	<option value="google.ga">Gabon (google.ga)</option>
	<option value="google.gm">Gambia (google.gm)</option>
	<option value="google.ge">Georgia (google.ge)</option>
	<option value="google.de">Germany (google.de)</option>
	<option value="google.com.gh">Ghana (google.com.gh)</option>
	<option value="google.com.gi">Gibraltar (google.com.gi)</option>
	<option value="google.gr">Greece (google.gr)</option>
	<option value="google.gl">Greenland (google.gl)</option>
	<option value="google.gp">Guadeloupe (google.gp)</option>
	<option value="google.com.gt">Guatemala (google.com.gt)</option>
	<option value="google.gg">Guernsey (google.gg)</option>
	<option value="google.gy">Guyana (google.gy)</option>
	<option value="google.ht">Haiti (google.ht)</option>
	<option value="google.hn">Honduras (google.hn)</option>
	<option value="google.com.hk">Hong Kong (google.com.hk)</option>
	<option value="google.hu">Hungary (google.hu)</option>
	<option value="google.is">Iceland (google.is)</option>
	<option value="google.co.in">India (google.co.in)</option>
	<option value="google.co.id">Indonesia (google.co.id)</option>
	<option value="google.iq">Iraq (google.iq)</option>
	<option value="google.ie">Ireland (google.ie)</option>
	<option value="google.im">Isle of Man (google.im)</option>
	<option value="google.co.il">Israel (google.co.il)</option>
	<option value="google.it">Italia (google.it)</option>
	<option value="google.ci">Ivory Coast (google.ci)</option>
	<option value="google.com.jm">Jamaica (google.com.jm)</option>
	<option value="google.co.jp">Japan (google.co.jp)</option>
	<option value="google.je">Jersey (google.je)</option>
	<option value="google.jo">Jordan (google.jo)</option>
	<option value="google.kz">Kazakhstan (google.kz)</option>
	<option value="google.co.ke">Kenya (google.co.ke)</option>
	<option value="google.ki">Kiribati (google.ki)</option>
	<option value="google.com.kw">Kuwait (google.com.kw)</option>
	<option value="google.kg">Kyrgyzstan (google.kg)</option>
	<option value="google.la">Laos (google.la)</option>
	<option value="google.lv">Latvia (google.lv)</option>
	<option value="google.com.lb">Lebanon (google.com.lb)</option>
	<option value="google.co.ls">Lesotho (google.co.ls)</option>
	<option value="google.com.ly">Libya (google.com.ly)</option>
	<option value="google.li">Liechtenstein (google.li)</option>
	<option value="google.lt">Lithuania (google.lt)</option>
	<option value="google.lu">Luxembourg (google.lu)</option>
	<option value="google.mk">Macedonia (google.mk)</option>
	<option value="google.mg">Madagascar (google.mg)</option>
	<option value="google.mw">Malawi (google.mw)</option>
	<option value="google.com.my">Malaysia (google.com.my)</option>
	<option value="google.mv">Maldives (google.mv)</option>
	<option value="google.ml">Mali (google.ml)</option>
	<option value="google.com.mt">Malta (google.com.mt)</option>
	<option value="google.mu">Mauritius (google.mu)</option>
	<option value="google.com.mx">Mexico (google.com.mx)</option>
	<option value="google.md">Moldova (google.md)</option>
	<option value="google.mn">Mongolia (google.mn)</option>
	<option value="google.me">Montenegro (google.me)</option>
	<option value="google.ms">Montserrat (google.ms)</option>
	<option value="google.co.ma">Morocco (google.co.ma)</option>
	<option value="google.co.mz">Mozambique (google.co.mz)</option>
	<option value="google.com.na">Namibia (google.com.na)</option>
	<option value="google.nr">Nauru (google.nr)</option>
	<option value="google.com.np">Nepal (google.com.np)</option>
	<option value="google.nl">Netherlands (google.nl)</option>
	<option value="google.co.nz">New Zealand (google.co.nz)</option>
	<option value="google.com.ni">Nicaragua (google.com.ni)</option>
	<option value="google.ne">Niger (google.ne)</option>
	<option value="google.com.ng">Nigeria (google.com.ng)</option>
	<option value="google.nu">Niue (google.nu)</option>
	<option value="google.com.nf">Norfolk Island (google.com.nf)</option>
	<option value="google.no">Norway (google.no)</option>
	<option value="google.com.om">Oman (google.com.om)</option>
	<option value="google.com.pk">Pakistan (google.com.pk)</option>
	<option value="google.ps">Palestine (google.ps)</option>
	<option value="google.com.pa">Panama (google.com.pa)</option>
	<option value="google.com.pg">Papua New Guinea (google.com.pg)</option>
	<option value="google.com.py">Paraguay (google.com.py)</option>
	<option value="google.com.pe">Peru (google.com.pe)</option>
	<option value="google.com.ph">Philippines (google.com.ph)</option>
	<option value="google.pn">Pitcairn Islands (google.pn)</option>
	<option value="google.pl">Poland (google.pl)</option>
	<option value="google.pt">Portugal (google.pt)</option>
	<option value="google.com.pr">Puerto Rico (google.com.pr)</option>
	<option value="google.com.qa">Qatar (google.com.qa)</option>
	<option value="google.cg">Republic of the Congo (google.cg)</option>
	<option value="google.ro">Romania (google.ro)</option>
	<option value="google.ru">Russia (google.ru)</option>
	<option value="google.rw">Rwanda (google.rw)</option>
	<option value="google.sh">Saint Helena (google.sh)</option>
	<option value="google.com.vc">Saint Vincent (google.com.vc)</option>
	<option value="google.ws">Samoa (google.ws)</option>
	<option value="google.sm">San Marino (google.sm)</option>
	<option value="google.com.sa">Saudi Arabia (google.com.sa)</option>
	<option value="google.sn">Senegal (google.sn)</option>
	<option value="google.rs">Serbia (google.rs)</option>
	<option value="google.sc">Seychelles (google.sc)</option>
	<option value="google.com.sl">Sierra Leone (google.com.sl)</option>
	<option value="google.com.sg">Singapore (google.com.sg)</option>
	<option value="google.sk">Slovak (google.sk)</option>
	<option value="google.si">Slovenia (google.si)</option>
	<option value="google.com.sb">Solomon Islands (google.com.sb)</option>
	<option value="google.so">Somalia (google.so)</option>
	<option value="google.co.za">South Africa (google.co.za)</option>
	<option value="google.co.kr">South Korea (google.co.kr)</option>
	<option value="google.lk">Sri Lanka (google.lk)</option>
	<option value="google.se">Sweden (google.se)</option>
	<option value="google.ch">Switzerland (google.ch)</option>
	<option value="google.st">São Tomé and Príncipe (google.st)</option>
	<option value="google.com.tw">Taiwan (google.com.tw)</option>
	<option value="google.com.tj">Tajikistan (google.com.tj)</option>
	<option value="google.co.tz">Tanzania (google.co.tz)</option>
	<option value="google.co.th">Thailand (google.co.th)</option>
	<option value="google.tl">Timor-Leste (google.tl)</option>
	<option value="google.tg">Togo (google.tg)</option>
	<option value="google.tk">Tokelau (google.tk)</option>
	<option value="google.to">Tonga (google.to)</option>
	<option value="google.tt">Trinidad and Tobago (google.tt)</option>
	<option value="google.tn">Tunisia (google.tn)</option>
	<option value="google.com.tr">Turkey (google.com.tr)</option>
	<option value="google.tm">Turkmenistan (google.tm)</option>
	<option value="google.co.ug">Uganda (google.co.ug)</option>
	<option value="google.com.ua">Ukraine (google.com.ua)</option>
	<option value="google.ae">UAE (google.ae)</option>
	<option value="google.co.uk">United Kingdom (google.co.uk)</option>
	<option value="google.com.uy">Uruguay (google.com.uy)</option>
	<option value="google.co.uz">Uzbekistan (google.co.uz)</option>
	<option value="google.vu">Vanuatu (google.vu)</option>
	<option value="google.co.ve">Venezuela (google.co.ve)</option>
	<option value="google.com.vn">VietNam (google.com.vn)</option>
	<option value="google.co.vi">Virgin Islands (google.co.vi)</option>
	<option value="google.co.zm">Zambia (google.co.zm)</option>
	<option value="google.co.zw">Zimbabwe (google.co.zw)</option>
    </select> 
  <br><br>
  <input type="submit" id="submit" class="buttonz" value="<?php echo $_search;?>">
  </center>
</form>

<?php } /*End scraper module*/ 
elseif ($module=="keywords"){
/*Begin keywords module*/ 
	?>
	<h2 class="uppercase"><?php echo strtoupper($_titleKeywordTool);?></h2>
	<p class="subtitle"><?php echo $_subtitleKeywordTool;?></p>
	  <center>
	  <input style="padding:5px" type="search" name="mainkwsuggest" value="<?php echo $mainkwsuggest;?>" placeholder="<?php echo $_keyword;?>" required>    
	  <select name="kwsugPlatform" id="kwsugPlatform">
		<option value="googleplatform" <?php if ($kwsugPlatform=="googleplatform") echo "selected";?>>Google</option>
		<option value="youtubeplatform" <?php  if ($kwsugPlatform=="youtubeplatform") echo "selected";?>>YouTube</option>
		<option value="amazonplatform" <?php  if ($kwsugPlatform=="amazonplatform") echo "selected";?>>Amazon</option>
	  </select>
	  <select name="lang" id="idioma">
			<option value="en" <?php if ($lang=="en") echo "selected";?>>English</option>
			<option value="es" <?php  if ($lang=="es") echo "selected";?>>Spanish</option>
			<option value="af" <?php  if ($lang=="af") echo "selected";?>>Afrikaans</option>
			<option value="bg" <?php  if ($lang=="bg") echo "selected";?>>Bulgarian</option>
			<option value="ca" <?php  if ($lang=="ca") echo "selected";?>>Catalan</option>
			<option value="hr" <?php  if ($lang=="hr") echo "selected";?>>Croatian</option>
			<option value="cs" <?php  if ($lang=="cs") echo "selected";?>>Czech</option>
			<option value="da" <?php  if ($lang=="da") echo "selected";?>>Danish</option>
			<option value="nl" <?php  if ($lang=="nl") echo "selected";?>>Dutch</option>
			<option value="et" <?php  if ($lang=="et") echo "selected";?>>Estonian</option>
			<option value="tl" <?php  if ($lang=="tl") echo "selected";?>>Filipino</option>
			<option value="fi" <?php  if ($lang=="fi") echo "selected";?>>Finnish</option>
			<option value="fr" <?php  if ($lang=="fr") echo "selected";?>>French</option>
			<option value="de" <?php  if ($lang=="de") echo "selected";?>>German</option>
			<option value="el" <?php  if ($lang=="el") echo "selected";?>>Greek</option>
			<option value="hi" <?php  if ($lang=="hi") echo "selected";?>>Hindi</option>
			<option value="hu" <?php  if ($lang=="hu") echo "selected";?>>Hungarian</option>
			<option value="id" <?php  if ($lang=="id") echo "selected";?>>Indonesian</option>
			<option value="it" <?php  if ($lang=="it") echo "selected";?>>Italian</option>
			<option value="lv" <?php  if ($lang=="lv") echo "selected";?>>Latvian</option>
			<option value="lt" <?php  if ($lang=="lt") echo "selected";?>>Lithuanian</option>
			<option value="no" <?php  if ($lang=="no") echo "selected";?>>Norwegian</option>
			<option value="pt" <?php  if ($lang=="pt") echo "selected";?>>Portuguese</option>
			<option value="ro" <?php  if ($lang=="ro") echo "selected";?>>Romanian</option>
			<option value="sr" <?php  if ($lang=="sr") echo "selected";?>>Serbian</option>
			<option value="sk" <?php  if ($lang=="sk") echo "selected";?>>Slovak</option>
			<option value="sl" <?php  if ($lang=="sl") echo "selected";?>>Slovenian</option>			
			<option value="sv" <?php  if ($lang=="sv") echo "selected";?>>Swedish</option>
			<option value="tr" <?php  if ($lang=="tr") echo "selected";?>>Turkish</option>  
	  </select>
	  <br><br>
	  <input type="submit" id="submit" class="buttonz" value="<?php echo $_search;?>">
	  </center>
	</form>
	<?php	
/*End keywords module*/	
}
elseif ($module=="images"){
	?>
	<h2 class="uppercase"><?php echo $_titleImageDownloader;?></h2>
	<p class="subtitle"><?php echo $_subtitleImageDownloader;?></p>
	  <center>
	  <input  style="padding:5px" type="search" name="imagequery" value="<?php echo $imagequery;?>" placeholder="<?php echo $_keyword;?>" required>    
	  <br><br>
	  <input type="submit" id="submit" class="buttonz" value="<?php echo $_search;?>">
	  </center>
	</form>
	<?php	 	
}
elseif ($module=="dataextractor"){
	?>
	<h2 class="uppercase"><?php echo strtoupper($_titleDataExtractor);?></h2>
	<p class="subtitle"><?php echo $_subtitleDataExtractor;?></p>
	<?php	 		
}
elseif ($module=="plagicheck"){
	?>
	<h2 class="uppercase"><?php echo strtoupper($_titlePlagiCheck);?></h2>
	<p class="subtitle"><?php echo $_subtitlePlagiCheck;?></p>
	<?php	 	
}
elseif ($module=="indexedcheck"){
	?>
	<h2 class="uppercase"><?php echo strtoupper($_titleIndexedUrlCheck);?></h2>
	<p class="subtitle"><?php echo $_subtitleIndexedUrlCheck;?></p>
	<?php	 	
}
elseif ($module=="proxymanager"){
	?>
	<h2 class="uppercase"><?php echo strtoupper($_titleProxyManager);?></h2>
	<p class="subtitle"><?php echo $_subtitleProxyManager;?></p>
	<?php	 	
}
elseif ($module=="mainsettings"){
	?>
	<h2 class="uppercase"><?php echo strtoupper($_titleMainSettings);?></h2>
	<p class="subtitle"><?php echo $_subtitleMainSettings;?></p>
	<?php	 	
}
elseif ($module=="docs"){	
	?>
	<h2 class="uppercase"><?php echo $_titleDocumentation;?></h2>
	<p class="subtitle"><?php echo $_subtitleDocumentation;?></p>
	  <div id="documentation">
		<div id="tocDiv">
		   <ul id="tocList">
			</ul>
		</div>	 	    
<?php
if ($langSelected=="es") {	
?>
		<div class="bloque_notifi">
			<h4>¡Bienvenid@ a PHPSEO!</h4>	 
			<p>¡Bienvenid@ a PHPSEO! una herramienta SEO gratuita creada con mucho amor :)</p>
			<p>Soy Juanma, un SEO español que también programa un poquito de PHP y con un par de décadas de experiencia haciendo estas cosas. Con PHPSEO pretendo crear un reemplazo chulo de muchas otras herramientas y scripts personalizados que pueden ser útiles para SEO.</p>
			<p>Recuerda que si me sigues en <a href="https://twitter.com/netgrows" target="_blank">Twitter</a> o <a href="https://www.youtube.com/c/NetgrowsES" target="_blank">YouTube</a>, publicaré contenidos chulos como footprints, plantillas para el extractor de contenidos o encuestas para ver qué queréis en próximos updates :)</p>		
			<h4>Características principales</h4>
			<ul>
				<li>100% gratis: <strong>URL scraper, extractor de datos en bulk, generador de keywords, descargar y editar imágenes en bulk, detector de plagio y detector de URLs indexadas en bulk.</strong>.</li>
				<li>Puedes <strong>usar IPs de sitios web en lugar de proxies</strong> (proxies web).</li>
				<li>Crea <strong>tu propio proxy</strong> en cualquier hosting web.</li>
				<li>Si tienes acceso a muchos hostings o PBNS, con PHPSEO puedes usarlos como <strong>una red de proxies gratis</strong>.</li>
				<li><strong>No necesita instalación, no necesita base de datos</strong>.</li>
				<li>Todo en <strong>un único fichero PHP portable</strong>. Toda la configuración se almacena en ficheros .txt y .json autogenerados.</li>
				<li><strong>Un servidor local (por ejemplo, Xampp) es muy recomendable, pero no obligatorio</strong>. Solo ubica el fichero <strong>phpseo.php</strong> en cualquier servidor FTP y empieza a trabajar.</li>
			</ul><p></p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-plug icon" aria-hidden="true"></i> Crea y usa proxies web</h4>	
			<h5>Usa proxies web desde tu hosting</h5>	
			<p>PHPSEO te permite crear y usar tus propios proxies alojándolos en tu proveedor de hosting web. </p>
			<p>Solo necesitas escribir un password, generar el fichero <em><strong>phpseo-proxy.php</strong></em> y ubicarlo en alguna carpeta de cualquier sitio web.</p>			
			<p>Ten en cuenta que necesitarás una dirección IP diferente para cada hosting. Si son IPs compartidas, pueden estar ya bloqueadas o solicitando un captcha..</p>
			<h5>¿Cómo crear un nuevo proxy?</h5>	
			<ol>
			<li>Visita el Gestor de Proxies <a href='?module=proxymanager'>aquí</a>.</li>
			<li>Escribe un nuevo password y pulsa CREAR y después DESCARGAR FICHERO PROXY.</li>
			<li>Sube el fichero <em>phpseo-proxy.php</em> a un sitio web y obtén la URL completa del archivo, incluyendo el parámetro con el password.</li>
			<li>Añade tu nuevo proxy a la lista (en una nueva línea) y pulsa el botón SALVAR.</li>			
			</ol>
			<hr>
			<p><strong>Formato de ficheros proxy</strong>: <em style="word-break: break-all;">Proxy name <strong>@@@</strong> https://yourdomain.com/anyfolder/phpseo-proxy.php?password=YOURPASSWORDHERE</em></p>
			<hr>			
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-search"></i><?php echo $_titleURLscrapper;?></h4>
			<p><strong><?php echo $_subtitleURLscrapper;?></strong></p>
			<p>Busca palabras clave en Google, obtén hasta 400 resultados. Es recomendable elegir un máximo de 300 (la mayoría de búsquedas no devolverán 400).</p>
			<p>Puedes usar una footprint de forma opcional para filtrar los resultados de búsqueda.</p>
			<p>PHPSEO te permite elegir entre diferentes footprints por defecto. También puedes agregar footprints personalizadas desde la sección -ajustes generales-.</p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-database" aria-hidden="true"></i> <?php echo $_titleDataExtractor;?></h4>
			<p><strong><?php echo $_subtitleDataExtractor;?></strong></p>			
			<p>Usando esta herramienta puedes extraer de forma masiva (múltiples URLs) todos los links, links internos, links externos, URLs de imágenes (hard y soft mode), direcciones de email y casi cualquier otro tipo de contenido utilizando plantillas personalizadas.</p>			
			<h5>¿Cómo crear plantillas personalizadas?</h5>
			<p>Las plantillas personalizadas te permiten extraer cualquier contenido de muchas URLs a la vez. Debes definir los marcadores del inicio -begin- y del final -end- para cada campo que quieras scrapear.</p>
			<p>Puedes crear y salvar nuevas plantillas utilizando el fichero <em><strong>content-extractor-templates.txt</strong></em></p>
			<p>Agrega nuevas plantillas, una por cada línea, siguiendo el formato a continuación. Puedes crear tantas plantillas como quieras con tantos campos cada una como quieras.</p>			
			<hr>
			<p><strong>Formato de las plantillas</strong>: <em>Template name <strong>@@@</strong> field1 <strong>###</strong> beginfield1 <strong>###</strong> endfield1 <strong>@@@</strong> field2 <strong>###</strong> beginfield2 <strong>###</strong> endfield2 <strong>@@@</strong> field3 <strong>###</strong> beginfield3 <strong>###</strong> endfield3</em></p>
			<hr>			
			<p>Fijándonos en el formato anterior, utilizarás dos clases de separadores, "@@@" para separar el nombre de la plantilla y cada nuevo campo, y "###" para separar el nombre del campo de sus marcadores inicio -begin- y final -end-.</p>
			<p>PHPSEO usa expresiones regulares para encontrar el contenido en el extractor de datos. Si no puedes obtener un contenido, es recomendable probar la regex en regex101.com hasta que funcione.</p>
			<p>Ejemplo de regex: <em>begin([\s\S]*?)end/s</em></p>
			<h5>Exportar los datos scrapeados</h5>
			<p>Si estás extrayendo URLS, emails u otro contenido tipo lista, verás los resultados como lo haces en otros módulos.</p>
			<p>Si estás extrayendo contenido usando una plantilla personalizada, puedes guardar el contenido como txt, csv o xlsx.</p>
			<ul>
			<li><strong>txt</strong>: exporta múltiples ficheros txt (uno por cada URL scrapeada).</li>
			<li><strong>csv</strong>: exporta un fichero CSV que contiene todo el contenido extraído.</li>
			<li><strong>xlsx</strong>: export one XLSX (excel) que contiene todo el contenido extraído.</li>
			</ul>
		</div>					
		<div class="bloque_notifi">
			<h4><i class="fa fa-book" aria-hidden="true"></i> <?php echo $_titleKeywordTool;?></h4>	 
			<p><strong><?php echo $_subtitleKeywordTool;?></strong></p>
			<p>Introduce una palabra clave para obtener sugerencias relacionadas de Google, Amazon o YouTube.</p> 
			<p>Esta herramienta mostrará las subconsultas de la A a la Z de Google suggest, usando como semilla tu palabra clave principal al inicio de cada sugerencia. Puedes usar el símbolo * como comodín en Google. </p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-picture-o" aria-hidden="true"></i> <?php echo $_titleImageDownloaderBoth;?></h4>
			<h5><?php echo $_subtitleImageDownloader;?></h5>			
			<p>Introduce una palabra clave para extraer las URLs de las imágenes mostradas en Google imágenes. También puedes pegar URLs de imágenes obtenidas del módulo extractor de datos o de cualquier otra fuente. </p>	
			<p>Usa el botón -DOWNLOAD IMGs- para descargar un fichero .zip que contenga todas las imágenes (puede tardar si usas muchas URLs).</p>
			<h5><?php echo $_subtitleImageEditor;?></h5>	
			<p>Selecciona múltiples imágenes y elige una o varias operaciones de edición para aplicar de forma masiva. Puedes añadir textos diferentes usando el fichero <a href="imagetext.txt" target="_blank">imagetext.txt</a>, y modificar fuentes, tamaños, marcas de agua, etc.</p>	
			<p>También puedes convertir cualquier formato de imágenes a JPG, PNG, GIF o WEBP.</p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-files-o" aria-hidden="true"></i> <?php echo $_titlePlagiCheck;?></h4>	 
			<p><strong><?php echo $_subtitlePlagiCheck;?></strong></p>
			<p>Pega un texto de al menos 100 palabras y comprueba si un fragmento aleatorio aparece como texto duplicado en Google.</p> 
			<p>La comprobación automática comprueba un fragmento aleatorio. La comprobación manual te muestra 3 botones para comprobar 3 fragmentos aleatorios de forma manual. </p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-check" aria-hidden="true"></i> <?php echo $_titleIndexedUrlCheck;?></h4>	 
			<p><strong><?php echo $_subtitleIndexedUrlCheck;?></strong></p>
			<p>Pega una lista de URLs (una por línea) y comprueba si están indexadas en Google.</p> 
			<p>Puedes descargar los resultados de indexación en formato CSV o copiar los resultados del textarea y pegarlos en una hoja de cálculo tipo excel. </p>
		</div>		
		<div class="bloque_notifi">
			<h4><i class="fa fa-desktop" aria-hidden="true"></i> Requisitos del sistema</h4>	 
			  <ul>
				<li>PHPSEO version: <strong><?php echo $phpseoVersion;?></strong>.</li>
				<li>Probado: <strong>PHP 5.6.402.0 y superiores</strong>.</li>
				<li>Variable max execution time mínima recomendada: <strong>240 segundos</strong>, (tu max exec time actual es <?php if (ini_get('max_execution_time')<=240) echo "<span style='color:red;'>".ini_get('max_execution_time')." segundos</span>";?> ).</li>
			  </ul>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-life-ring" aria-hidden="true"></i> Soporte y ayuda</h4>
			<p>Si tienes dudas o problemas con PHPSEO, te recomiendo <a href="https://www.facebook.com/groups/323627338348916/" target="_blank">unirte al grupo de ayuda en Facebook</a> y publicar allí tu pregunta.</p>
			<p>Para preguntas cortas o rápidas, puedes encontrarme en  <a href="https://twitter.com/netgrows" target="_blank">Twitter</a> y <a href="https://www.youtube.com/c/NetgrowsES" target="_blank">YouTube</a>.</p>
			<p>PHPSEO, por el momento es 100% gratis y BETA, así que me ayudarás mucho si reportas cualquier error.</p> 
			<p>No me odies si tienes problemas, recuerda no abusar demasiado de los recursos de tu hosting y todo debería de ir bien :)</p>
		</div>		
		<div class="bloque_notifi">
			<h4><i class="fa fa-question-circle" aria-hidden="true"></i> Preguntas frecuentes</h4>	
			<h5>¿Por qué la herramienta no termina un proceso? (se cuelga o congela)</h5>	
			<p><strong>Problema</strong>: si el ciclo de avisos "Processing URL batch" parece no terminar nunca, probablemente tu servidor está sobrecargado y no responde a las peticiones.</p>
			<p><strong>Solución</strong>: si estás haciendo operaciones con muchas URLs, instala Xampp u otro servidor local y utiliza PHPSEO en local. Recuerda <a target="_blank" href="https://stackoverflow.com/questions/29552583/maximum-execution-time-of-300-seconds-in-xampp-on-windows">incrementar el max execution timeout y/o otras settings que puedan ser demasiado bajas en tu servidor local.</a></p>
			<p>También puedes incrementar la -pausa entre bloques- y disminuir las -URL/s por bloque- en los ajustes generales para mitigar este problema.</p>			
			<h5>¿Puedo usar proxies normales (no web) con PHPSEO?</h5>
			<p>A pesar de que ni los he probado ni es la intención de esta herramienta, deberías poder usarlos fácilmente modificando los ficheros proxy <a target='_blank' href='https://stackoverflow.com/questions/5211887/how-to-use-curl-via-a-proxy'>siguiendo estas indicaciones</a>.</p>		
			<h5>¿Puedo usar los web proxies con la IP de un dispositivo móvil?</h5>
			<p>Existen varias aplicaciones para montar servidores PHP en dispositivos móviles. No he llegado a probarlas con PHPSEO, pero si conseguimos obtener una URL pública mediante esas aplicaciones, y estas incorporan las extensiones básicas como cURL, debería ser posible usar la IP de datos móvil como proxy web.</p>		
			<p>Si alguien prueba y consigue usar el móvil como web proxy, estaré encantado de escuchar su feedback :).</p>	
			<h5>¿Necesito usar un servidor local con PHPSEO?</h5>	
			<p>No es imprescindible (pero sí recomendable) usar PHPSEO con un servidor PHP local. Para ello, lo mejor es instalar XAMPP, lo que nos permitirá incrementar las variables Execution Time, Upload Size y similares tanto como deseemos. </p>			
		</div> 
<?php
}else {
?>	
		<div class="bloque_notifi">
			<h4>Welcome to PHPSEO</h4>	 
			<p>Welcome to PHPSEO! a free SEO tool made with a lot of love :)</p>
			<p>I'm Juanma, an Spanish SEO with some coding knowledge and 20+ years experience doing this kind of things. By creating PHPSEO I'm trying to provide a cool replacement for a lot other tools and custom scripts that may be useful for SEO purposes.</p>
			<p>You can follow me in <a href="https://twitter.com/netgrowsEN" target="_blank">Twitter</a> or <a href="https://www.youtube.com/c/NetgrowsEnglish" target="_blank">YouTube</a> to get cool contents like footprints, content extractor templates or vote for the next updates :)</p>
			<h4>Main features</h4>
			<ul>
				<li>Free <strong>URL scraper, bulk URL data extractor, keyword generator, bulk image downloader & editor, plagiarism checker & indexed URL checker</strong>.</li>
				<li>Use <strong>website IPs instead of proxies</strong> (web proxies).</li>
				<li>Create <strong>your own proxy</strong> in any hosting provider.</li>
				<li>Do you own multiple hostings or PBNs? You can use them as a <strong>free proxy network</strong> with PHPSEO!</li>
				<li><strong>No installation needed, no database</strong>.</li>
				<li>Everything is contained in a <strong>single PHP portable file</strong>. All config is stored in auto generated .txt and .json files.</li>
				<li><strong>Local server (e.g. Xampp) is recommended but not required</strong>. Just paste/upload <strong>phpseo.php</strong> file to any PHP server and start working.</li>
			</ul><p></p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-plug icon" aria-hidden="true"></i> Create & use web proxies</h4>	
			<h5>Use free web proxies!</h5>	
			<p>PHPSEO allows you to create and use your own free proxies by hosting them in any web hosting provider. </p>
			<p> You just need to set a password and create a new copy of the file <em><strong>phpseo-proxy.php</strong></em>. Upload it so any website folder.</p>			
			<p>Keep in mind that you will need different IP addresses per hosting. If they are shared IP address hostings, they may be already banned from the search engines (or asking a captcha).</p>
			<h5>How to create a new web proxy?</h5>	
			<ol>
			<li>Visit the Proxy Manager <a href='?module=proxymanager'>here</a>.</li>
			<li>Set up a new password and press CREATE and DOWNLOAD PROXY FILE.</li>
			<li>Upload the <em>phpseo-proxy.php</em> file to any website and get the full URL of that file including the password parameter.</li>
			<li>Save your new proxy to the list using SAVE button.</li>			
			</ol>
			<hr>
			<p><strong>Proxy file format</strong>:  <em style="word-break: break-all;">Proxy name <strong>@@@</strong> https://yourdomain.com/anyfolder/phpseo-proxy.php?password=YOURPASSWORDHERE</em></p>
			<hr>			
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-search"></i><?php echo $_titleURLscrapper;?></h4>
			<p><strong><?php echo $_subtitleURLscrapper;?></strong></p>			
			<p>Search keywords in Google, get up to 400 results. It is better so set a max of 300 results (most keywords will not return 400).</p>
			<p>You can use a footprint to filter the search results.</p>
			<p>PHPSEO allows you to choose between different default footprints. You can also add custom footprints in the -main settings-.</p>			
			
			
		</div>		
		<div class="bloque_notifi">
			<h4><i class="fa fa-database" aria-hidden="true"></i> <?php echo $_titleDataExtractor;?></h4>
			<p><strong><?php echo $_subtitleDataExtractor;?></strong></p>
			<p>By using this tool you can extract: all links, internal links, external links, image URLs (hard and soft mode), email address and almost any other content using custom templates.</p>			
			<h5>How to create custom templates?</h5>
			<p>Custom templates allow you to extract any content from multiple URLs, by setting up -begin- and -end- markers for every field that you want to scrape.</p>
			<p>You can create and save new templates using the file <em><strong>content-extractor-templates.txt</strong></em></p>
			<p>Add new templates, one per line, following the format below. You can create as many templates as you want with as many fields as you need.</p>			
			<hr>
			<p><strong>Template format</strong>: <em>Template name <strong>@@@</strong> field1 <strong>###</strong> beginfield1 <strong>###</strong> endfield1 <strong>@@@</strong> field2 <strong>###</strong> beginfield2 <strong>###</strong> endfield2 <strong>@@@</strong> field3 <strong>###</strong> beginfield3 <strong>###</strong> endfield3</em></p>
			<hr>			
			<p>So you will use two kind of separators, "@@@" to separate the template name and every new field, and  "###" to separate the field name from the -begin- and -end- markers.</p>
			<p>PHPSEO uses regular expressions to find content in the content extractor module. If you cannot get a content, we recommend you to try the regex at regex101.com until it works.</p>
			<p>Regex sample: <em>begin([\s\S]*?)end/s</em></p>
			<h5>Exporting the scraped data</h5>
			<p>If you are extracting URLs, you will see and manage them as in any other module.</p>
			<p>If you are extracting content using a custom template, you will be able to save your content as txt, csv or xlsx formats.</p>
			<ul>
			<li><strong>txt</strong>: export multiple txt files (one per URL)</li>
			<li><strong>csv</strong>: export one CSV file containing all the extracted content</li>
			<li><strong>xlsx</strong>: export one XLSX (excel) file containing all the extracted content.</li>
			</ul>
		</div>			
		<div class="bloque_notifi">
			<h4><i class="fa fa-book" aria-hidden="true"></i> <?php echo $_titleKeywordTool;?></h4>	 
			<p><strong><?php echo $_subtitleKeywordTool;?></strong></p>
			<p>Enter a keyword to get all the Google related suggestions.</p>
			<p>This tool will loop from A to Z in Google suggestions using your main keyword as the beginning of every suggestion. You can use * symbol as a normal Google wildcard. </p>			
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-picture-o" aria-hidden="true"></i> <?php echo $_titleImageDownloaderBoth;?></h4>
			<h5><?php echo $_subtitleImageDownloader;?></h5>	
			<p>Enter a keyword to extract related Google Images URLs. Optionally, clean the list or remove duplicate URLs. You can also paste image URLs extracted from data extractor module or any other sources.</p>	
			<p>Use the -DOWNLOAD IMGs- button to download a .zip file containing all the images.</p>
			<h5><?php echo $_subtitleImageEditor;?></h5>	
			<p>Select multiple images and apply edition effects over them. You can add different texts using the file <a href="imagetext.txt" target="_blank">imagetext.txt</a> and use different fonts, watermarks, sizes...</p>	
			<p>You can also convert any image to JPG, PNG, GIF or WEBP.</p>			
		</div>	
		<div class="bloque_notifi">
			<h4><i class="fa fa-files-o" aria-hidden="true"></i> <?php echo $_titlePlagiCheck;?></h4>	 
			<p><strong><?php echo $_subtitlePlagiCheck;?></strong></p>
			<p>Paste a text of 100 words or more and check plagiarism against Google results using random sentences.</p> 
			<p>The automatic check will use one random text fragment. The manual check will show you three buttons to manually check three different random fragments. </p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-check" aria-hidden="true"></i> <?php echo $_titleIndexedUrlCheck;?></h4>	 
			<p><strong><?php echo $_subtitleIndexedUrlCheck;?></strong></p>
			<p>Paste a list of URLs (one per line) and check if they are Google indexed.</p> 
			<p>You can download the results in CSV format and/or copy the results from the textarea and paste them to a spreadsheet. </p>
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-desktop" aria-hidden="true"></i> System requirements</h4>	 
			  <ul>
				<li>PHPSEO version: <strong><?php echo $phpseoVersion;?></strong>.</li>
				<li>Tested: <strong>PHP 5.6.402.0 and up</strong>.</li>
				<li>Server max execution time recommended: <strong>240 seconds</strong>, (current timeout <?php if (ini_get('max_execution_time')<=240) echo "<span style='color:red;'>".ini_get('max_execution_time')." seconds</span>";?> ).</li>
			  </ul>
		</div>		
		<div class="bloque_notifi">
			<h4><i class="fa fa-life-ring" aria-hidden="true"></i> Get support</h4>					
			<p>If you have questions or problems when using PHPSEO, I recommend you to join the <a href="https://www.facebook.com/groups/1054029494632405/" target="_blank">Facebook support group</a> and publish your question there.</p>
			<p>For fast questions, you can find me at <a href="https://twitter.com/netgrowsEN" target="_blank">Twitter</a> & <a href="https://www.youtube.com/c/NetgrowsEnglish" target="_blank">YouTube</a>.</p>
			<p>Currently, PHPSEO is 100% free and BETA, so you will help me reporting any problem.</p> 
			<p>Do not hate me if you have problems, do not abuse your hosting provider resources and everything should be fine :)</p>			
		</div>
		<div class="bloque_notifi">
			<h4><i class="fa fa-question-circle" aria-hidden="true"></i> F.A.Q.</h4>	
			<h5>Why the tool is not finishing the batch process? (freezes)</h5>	
			<p><strong>Problem</strong>: if the "Processing URL batch" cycle seems to never end, probably your server is overloaded and is not responding to requests.</p>
			<p><strong>Solution</strong>: if you are making heavy operations with a lot of URLs, install Xampp or other local web server and use PHPSEO in your local computer. Remember to <a target="_blank" href="https://stackoverflow.com/questions/29552583/maximum-execution-time-of-300-seconds-in-xampp-on-windows">increase the local sever max execution timeout and other default settings if needed.</a></p>
			<p>You can also increase the -batch delay- setting & decrease -blocks per batch- setting to mitigate this problem.</p>			
			<h5>Can I use normal proxies (no web) with PHPSEO?</h5>
			<p>It is not tested, anyway, you should be able to do it by editing the PHP proxy files <a target='_blank' href='https://stackoverflow.com/questions/5211887/how-to-use-curl-via-a-proxy'>following these instructions</a>.</p>		
			<h5>Can I use my phone IP as a web proxy?</h5>
			<p>There are multiple apps that allow you to run a PHP web server in your mobile devices. I have not tested it, but if you can obtain a public URL, and this apps have the basic PHP extensions like cURL, it should work.</p>		
			<p>If you are skilled enough to make it work, I will be happy to hear your feeedback :).</p>
			<h5>Do I need a local server to use PHPSEO?</h5>	
			<p>It is not mandatory, but I recommend it. Using a local server like XAMPP will allow you to easily increase Execution Time or Upload Size settings to higher values. </p>						
		</div> 	
<?php	
}
?>  
	  </div>
	</form>
<script>
$(document).ready(function() {                                                                          
    $(tocList).empty();                                                               
    var prevH2Item = null;                                                            
    var prevH2List = "";                                                               
    var index = 0;                                                                    
    $("h4, h5").each(function() {                                                                 
        var anchor = "<a name='" + index + "'></a>";                 
        $(this).before(anchor);                                           
        var li     = "<li><a href='#" + index + "'>" + $(this).text() + "</a></li>";         
        if( $(this).is("h4") ){                                     
            prevH2List = $("<ul></ul>");                
            prevH2Item = $(li);                                     
            prevH2Item.append(prevH2List);                          
            prevH2Item.appendTo("#tocList");                        
        } else {                                                    
            prevH2List.append(li);                                  
        }                                                           
        index++;                                                    
    }); 	
});                                                                                                     
</script>
	<?php	 	
}
elseif ($module=="lastnews"){
	?>
	<h2><?php echo $notificationsTitle;?></h2>
	<p class="subtitle"><?php echo $notificationsSubtitle;?></p>
	  <div id="documentation">	  
		<?php
		
		if ($langSelected=="es") $cache_file_not="cache-notifi-es.txt";
		else $cache_file_not="cache-notifi.txt";
		if ($langSelected=="es") $urlNoti="https://netgrows.com/notifications/notificaciones.txt";
		else $urlNoti="https://netgrows.com/notifications/notifications.txt";	
		if (file_exists($cache_file_not) && (filemtime($cache_file_not) > (time() - 60 * 1440 ))) {
		   $lastNews=file_get_contents($cache_file_not);
		} else {
		   $lastNews=file_get_contents($urlNoti);
		   file_put_contents($cache_file_not, $lastNews, LOCK_EX);
		}				
		$arrayNews = explode("\n",$lastNews);
		foreach ($arrayNews as $noticia){
			$elementosNoticia = explode("|||",$noticia);
					$cuentaElementos=1;
					foreach ($elementosNoticia as $elemento){
						if ($cuentaElementos==1) echo "<div class='bloque_notifi'><div class='notifi_fecha'>".$elemento."</div>";
						if ($cuentaElementos==2) echo "<div class='notifi_title'>".$elemento."</div>";
						if ($cuentaElementos==3) echo "<div class='notifi_desc'>".$elemento."</div></div>";
						if ($cuentaElementos%3==0) $cuentaElementos=1;
						$cuentaElementos++;						
					}
		}
		?>
	  </div>
	</form>	
<?php	
}?>

<?php
/*TEMPLATES TO REUSE*/
$buttonSRightTextarea1="
<div class='buttonsright'>
<a href='#' title='$_downloadAllString' onclick='doDL(document.getElementById(\"txtArea\").value)'><i class='fa fa-download' aria-hidden='true'></i></a>
<a href='#' title='Select all' onclick='selectTextarea(\"txtArea\")'><i class='fa fa-clipboard' aria-hidden='true'></i></a>
<a href='#' title='$_remove $_containing=' onclick='removecontaining(\"txtArea\")'><i class='fa fa-trash' aria-hidden='true'></i> $_containing</a>
<a href='#' title='$_remove $_notContaining' onclick='removenotcontaining(\"txtArea\")'><i class='fa fa-trash' aria-hidden='true'></i> $_notContaining</a>
<a href='#' title='Clear all' onclick='clearTextarea(\"txtArea\")'><i class='fa fa-trash' aria-hidden='true'></i></a>
</div>";
$buttonSRightTextarea1mini="
<div class='buttonsright'>
<a href='#' title='$_downloadAllString' onclick='doDL(document.getElementById(\"txtArea\").value)'><i class='fa fa-download' aria-hidden='true'></i></a>
<a href='#' title='Select all' onclick='selectTextarea(\"txtArea\")'><i class='fa fa-clipboard' aria-hidden='true'></i></a>
<a href='#' title='Clear all' onclick='clearTextarea(\"txtArea\")'><i class='fa fa-trash' aria-hidden='true'></i></a>
</div>";
?>
<div class='maincontent'>
<?php
//require_once('vendor/autoload.php');
/*Carga googletranslate composer*/
/*use Stichoza\GoogleTranslate\GoogleTranslate;
$tr = new GoogleTranslate('es'); */
function substrwords($text, $maxchar, $end='') {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);      
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            } 
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    } 
    else {
        $output = $text;
    }
    return $output;
}
function sanitizeURL($url){
	if ((substr($url, 0, 7) == "http://")||(substr($url, 0, 8) == "https://")){						
		if ((strpos($url, '.google.') !== false)||(strpos($url, '.googleusercontent.') !== false)||(strpos($url, '.gstatic.') !== false)) {
			return false;
		} else {
			if ((strpos($url, '.jpg') !== false)||(strpos($url, '.png') !== false)||(strpos($url, '.jpeg') !== false)) { 
				if ((strlen($url)) >= 15) {
					return $url;
				}
			}
			else return $url;
			
		}
	} else return false;
}
function showData_ExtractorTemplatesOptions(){
	$allTemplates = file_get_contents('content-extractor-templates.txt');				
	$cuentaIteration=1;				
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $allTemplates) as $line){
		echo "<option value='template-$cuentaIteration'>";
		$partesTemplate=explode("@@@",$line);
		echo $tituloTemplate=$partesTemplate[0];
		echo "</option>";
		$cuentaIteration++;
		}	
}
function showData_Footprints(){
	$allFootprints = file_get_contents('footprints.txt');				
	$cuentaIteration=1;				
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $allFootprints) as $line){	
		$partesFootprint=explode("@@@",$line);
		$tituloFootprint=$partesFootprint[0];
		$footPrint=$partesFootprint[1];		
		echo "<option value='$footPrint'>$tituloFootprint</option>";
		$cuentaIteration++;
		}	
}  				
function getData_ExtractorTemplates($templateid){ //id is the number of line in the txt file
	$partesString="";
	$allTemplates = file_get_contents('content-extractor-templates.txt');				
	$cuentaIteration=1;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $allTemplates) as $line){
		if ("template-$cuentaIteration"==$templateid) {
			$partesString=$line;
		}
		$cuentaIteration++;
	}
	return $partesString;
} 
function showProxies($webproxy){
	$allProxies = file_get_contents('web-proxy-list.txt');				
	$cuentaIteration=1;				
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $allProxies) as $line){
		$partesProxy=explode("@@@",$line);
		$nombreProxy=$partesProxy[0];
		$urlProxy=$partesProxy[1];
		if ($webproxy==$urlProxy) echo "<option value='$urlProxy' selected>";
		else echo "<option value='$urlProxy'>";		
		echo "$nombreProxy</option>";
		$cuentaIteration++;
		}	
} 

				
/**************/
/*MAIN THREAD*/
/**************/
$bannedProxy=false;
$cacheJson=false;
/*if (!empty($query)) {*/	

		if ((!isset($_SESSION['optionz']))&&(!empty($query)))$_SESSION['optionz']="first-call";
		if ((isset($_SESSION['optionz']))&&($_SESSION["optionz"]==$optionz)) {
			//Misma opción que antes	
			$_SESSION["optionz"]=$optionz;
		} elseif (!empty($query)) {
			//Distinta opción, reseteamos json
			$_SESSION["optionz"]=$optionz;	
			file_put_contents("search.json", "");			
		}
		
		
		if ((!isset($_SESSION['keyword']))&&(!empty($query))) $_SESSION['keyword']="first-call";
		if ((isset($_SESSION['keyword']))&&($_SESSION["keyword"]==$query)) {
			//Misma opción que antes
			$_SESSION["keyword"]=$query;
		} elseif (!empty($query)) {
			//Distinta keyword, reseteamos json
			$_SESSION["keyword"]=$query;	
			file_put_contents("search.json", "");			
		}		
			
/***********/
/*HOMEPAGE*/
/**********/	
if ($module==""){
?>	
</form>
<div class="cards">
  <div class="card">
	<a title='<?php echo $_titleURLscrapper;?>' href="<?php echo basename(__FILE__);?>?module=urlscraper" <?=(($_GET['module'] == "urlscraper") ? 'class="active"' : '')?>><i class="fa fa-search"></i>
		<div class="titleblock"><?php echo strtoupper($_titleURLscrapper);?></div>	
	<p><?php echo $_subtitleURLscrapper;?>.</p>
	</a>
  </div> 
  <div class="card">
	<a title='<?php echo $_titleDataExtractor;?>' href="<?php echo basename(__FILE__);?>?module=dataextractor" <?=(($_GET['module'] == "dataextractor") ? 'class="active"' : '')?>><i class="fa fa-database" aria-hidden="true"></i>
		<div class="titleblock"><?php echo strtoupper($_titleDataExtractor);?></div>
	<p><?php echo $_subtitleDataExtractor;?>.</p>
	</a>
  </div>
  <div class="card">
	<a title='<?php echo $_titleKeywordTool;?>' href="<?php echo basename(__FILE__);?>?module=keywords" <?=(($_GET['module'] == "keywords") ? 'class="active"' : '')?>><i class="fa fa-book" aria-hidden="true"></i>
		<div class="titleblock"><?php echo strtoupper($_titleKeywordTool);?></div>
	<p><?php echo $_subtitleKeywordTool;?>.</p>
	</a>	
  </div>
  <div class="card">
	<a title='<?php echo $_titleImageDownloaderBoth;?>' href="<?php echo basename(__FILE__);?>?module=images" <?=(($_GET['module'] == "images") ? 'class="active"' : '')?>><i class="fa fa-picture-o" aria-hidden="true"></i>
		<div class="titleblock"><?php echo $_titleImageDownloaderBoth;?></div>
	<p><?php echo $_subtitleImageDownloader;?>. <?php echo $_subtitleImageEditor;?>.</p>
	</a>  
  </div>
  <div class="card">
	  <a title='<?php echo $_titlePlagiCheck;?>' href="<?php echo basename(__FILE__);?>?module=plagicheck" <?=(($_GET['module'] == "plagicheck") ? 'class="active"' : '')?>><i class="fa fa-files-o" aria-hidden="true"></i>
		<div class="titleblock"><?php echo strtoupper($_titlePlagiCheck);?></div>
	  <p><?php echo $_subtitlePlagiCheck;?>.</p>
	  </a>
  </div>
  <div class="card">
	  <a title='<?php echo $_titleIndexedUrlCheck;?>' href="<?php echo basename(__FILE__);?>?module=indexedcheck" <?=(($_GET['module'] == "indexedcheck") ? 'class="active"' : '')?>><i class="fa fa-check" aria-hidden="true"></i>
		<div class="titleblock"><?php echo strtoupper($_titleIndexedUrlCheck);?></div>  
	  <p><?php echo $_subtitleIndexedUrlCheck;?>.</p>
	  </a>
  </div>
  <div class="card">
	  <a title='<?php echo $_titleProxyManager;?>' href="<?php echo basename(__FILE__);?>?module=proxymanager" <?=(($_GET['module'] == "proxymanager") ? 'class="active"' : '')?>><i class="fa fa-plug icon" aria-hidden="true"></i>
		<div class="titleblock"><?php echo strtoupper($_titleProxyManager);?></div>  
	  <p><?php echo $_subtitleProxyManager;?>.</p>
	  </a>
  </div>
  <div class="card">
	  <a title='<?php echo $_titleMainSettings;?>' href="<?php echo basename(__FILE__);?>?module=mainsettings" <?=(($_GET['module'] == "proxymanager") ? 'class="active"' : '')?>><i class="fa fa-sliders" aria-hidden="true"></i>
		<div class="titleblock"><?php echo strtoupper($_titleMainSettings);?></div>	  
	  <p><?php echo $_subtitleMainSettings;?>.</p>
	  </a>
  </div>  
  <div class="card">
	  <a title='<?php echo $_titleDocumentation;?>' href="<?php echo basename(__FILE__);?>?module=docs" <?=(($_GET['module'] == "docs") ? 'class="active"' : '')?>><i class="fa fa-question" aria-hidden="true"></i>
		<div class="titleblock"><?php echo $_titleDocumentation;?></div>	 
		<p><?php echo $_subtitleDocumentation;?>.</p>
	   </a>
  </div>  
  
  
  
  <div class="card lastnewshome" style="max-height: 300px; overflow: hidden; background: #cbcbcb;"> 
  <?php 
	
	if ($langSelected=="es")$cache_file="cache-lastnews-home-es.txt";
	else $cache_file="cache-lastnews-home.txt";	
	if ($langSelected=="es") $url="http://netgrows.com/notifications/ultimasnoticiashome.txt";
	else $url="http://netgrows.com/notifications/lastnewshome.txt";	
	if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * 1440 ))) {
	   echo $fileCache=file_get_contents($cache_file);
	} else {
	   echo $fileCache=file_get_contents($url);
	   file_put_contents($cache_file, $fileCache, LOCK_EX);
	}
  ?>
  </div> 
  
  
  <?php if ($langSelected=="es") {?>
  <div class="card" style="max-height: 300px; overflow: hidden; background: #cbcbcb;"> 
    <div class="titleblock" style="padding:20px 0px;background: #ececec;">ÚLTIMOS TWEETS</div>
	<div style="overflow-y: auto; max-height: 213px;">
		<a data-tweet-limit=6 class="twitter-timeline" href="https://twitter.com/netgrows?ref_src=twsrc%5Etfw">Tweets by netgrows</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div>
  </div>
  <div class="card" style="max-height: 300px; overflow: hidden; overflow-y: auto; background: #cbcbcb;"> 
	<div class="titleblock" style="padding:20px 0px;background: #ececec;">ÚLTIMOS VÍDEOS</div>	
	<iframe width="380" height="213" src="https://www.youtube.com/embed/videoseries?list=PLv4y6os0JBLKOUdbp5GMDWyjDxpNkPlfb" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>   
  
  
  <?php } else {?>
  <div class="card" style="max-height: 300px; overflow: hidden; background: #cbcbcb;"> 
    <div class="titleblock" style="padding:20px 0px;background: #ececec;">LATEST TWEETS</div>
	<div style="overflow-y: auto; max-height: 213px;">
		<a data-tweet-limit=6 class="twitter-timeline" href="https://twitter.com/netgrowsEN?ref_src=twsrc%5Etfw">Tweets by Netgrows EN</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div>
  </div>
  <div class="card" style="max-height: 300px; overflow: hidden; overflow-y: auto; background: #cbcbcb;"> 
	<div class="titleblock" style="padding:20px 0px;background: #ececec;">LATEST VIDEOS</div>	
	<iframe width="380" height="213" src="https://www.youtube.com/embed/videoseries?list=PLER4IYznJukcxT-SJ0GAyJaQkOqfVPMHl" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>   
  <?php } ?>
  
  
 
 
</div>

<div style="clear:both; height:50px;"></div>
<?php
}
		
/********************/
/*URLSCRAPER MODULE*/
/******************/				
	if ($module=="urlscraper"){	
		if (!empty($optionz)) $keybusqueda=$query." ".$optionz;
		else $keybusqueda=$query;
		$keybusqueda=str_replace(" ","+",$keybusqueda);				
		if (( 0 == filesize( "search.json" ) )&&($pagina<=1)&&(!empty($query)))
		{
		//hace nueva búsqueda y guarda json						
			if ($webproxy!="noproxy") {
				$getcontents=$webproxy."&query=$keybusqueda&searchPageStarts=1&googleRegion=$googleRegion";	
			} else $getcontents="https://$googleRegion/search?q=$keybusqueda&ion=0&num=100";
			
				$debug.="Search URL: <hr>$getcontents<hr>"; 		
				$results = getPageGoogle('',$getcontents, '', $customUserAgent, 1, 25);	
				$arraydatos=$results['EXE'];								
				//Check if banned
				if (preg_match("/unusual traffic/i", $arraydatos)) {
					$bannedProxy=true;
				} else {
					if (preg_match("/href/i", $arraydatos)) {
						$bannedProxy=false;
					} else $bannedProxy=true;
				}							
				if ($debugSearchDisplay) echo $arraydatos;				
				$dom = new DOMDocument();
				@$dom->loadHTML($arraydatos);
				$xpath = new DOMXPath($dom);					
				echo "<br>";				
				$hrefs = $xpath->evaluate("/html/body//a");
				for ($i = 0; $i < $hrefs->length; $i++) {
					$href = $hrefs->item($i);
					$url = $href->getAttribute('href');
					//Solo guarda URLs válidas
					if (sanitizeURL($url)){
						$arrayurl[$i]=$url;						 
						$arrayjson[$i] = array(
							'url' => $arrayurl[$i]
						);
					}
				}	
				//guarda json de la búsqueda (cache)
				if (isset($arrayjson)) $json_data = json_encode($arrayjson);
				if (isset($json_data)){ if ($json_data!="null") file_put_contents('search.json', $json_data);}
				else file_put_contents("search.json", "");						
		} 		
		elseif (($pagina>1)&&(!empty($query)))
		{
		//hace búsqueda en bucle con delay	
			$urlsAcumuladas="";
			for ($i = 1; $i <= $pagina; $i++) {
				if (isset($pagina)) {
					$searchPageStarts=($i-1)*100;
					if ($webproxy!="noproxy") {
						$getcontents=$webproxy."&query=$keybusqueda&searchPageStarts=$searchPageStarts&googleRegion=$googleRegion";
					} else $getcontents="https://$googleRegion/search?q=$keybusqueda&ion=0&num=100&start=$searchPageStarts";										
				} else echo "ERROR CHUNGO";								
				//echo "<br>Loop $i, total $pagina<br>";				
				$results = getPageGoogle('',$getcontents, '', $customUserAgent, 1, 25);	
				$arraydatos=$results['EXE'];
				//Check if banned
				if (preg_match("/unusual traffic/i", $arraydatos)) {
					$bannedProxy=true;
				} else {
					if (preg_match("/href/i", $arraydatos)) {
						$bannedProxy=false;
					} else $bannedProxy=true;
				}				
				if ($debugSearchDisplay) echo $arraydatos;				
				$dom = new DOMDocument();
				@$dom->loadHTML($arraydatos);
				$xpath = new DOMXPath($dom);					
				$hrefs = $xpath->evaluate("/html/body//a");
				for ($j = 0; $j < $hrefs->length; $j++) {
					$href = $hrefs->item($j);
					$url = $href->getAttribute('href');
					if (sanitizeURL($url)) $urlsAcumuladas.=sanitizeURL($url)."\n";
				}
				$debug.="Search made: <hr>$getcontents<hr>"; 
				sleep ($searchDelaySeconds);
			}		
				$arrayurl=explode("\n",$urlsAcumuladas);
				if (isset($urlsAcumuladas)) $json_data = json_encode($arrayurl);
		}
		elseif ($module=="urlscraper") {
		//Saca datos del json	
				$debug.="Search extracted from previous json cache<hr>";
				$json = json_decode(file_get_contents('search.json'), true);
				//print_r($json);
				$cuentaurl=1;
				$cacheJson=true;
				if ($json) {
					foreach ($json as $arraysearch){
						$arrayurl[$cuentaurl]=$arraysearch['url'];
						$cuentaurl++;
					}
				} else {
					echo '<center style="color: #ececec; background: #333333; max-width: 800px; margin: 0 auto; padding: 10px; border: solid 1px #000000;">'.$_welcometo.'</center>';	
				}				
		}
	}	
	if ($module=="urlscraper"){
		echo "
		<div style='padding: 2px; background: #ffffff; margin: 20px;'>
		<div class='aprincipal'>
		<div class='subtopContainer'>
		<span class='extractedLabel'>$_extractedUrls</span>
		";		
		if ((!$bannedProxy)&&(!$cacheJson)){
			echo "<span id='goodproxy''>Good proxy <i class='fa fa-smile-o' aria-hidden='true'></i></span>";
		} else{
			if ($cacheJson) echo "<span id='goodproxy''>$_showingCache <i class='fa fa-database' aria-hidden='true'></i></span>";
			else echo "<span id='badproxy'>$_badProxy <i class='fa fa-frown-o' aria-hidden='true'></i></span>";					
		}
		echo "</div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id='txtArea' style='width:100%;height:288px;' name='linksbusqueda'>";
			if (!$bannedProxy){			
				if (isset($arrayurl)){
					foreach ($arrayurl as $dato){
						echo $dato;
						if(end($arrayurl)!=$dato) echo "\n";
					}									
				} else {
					if ( filesize( "search.json" ) > 0 ) {
						foreach ($json as $dato){
							echo $dato;
							if(end($json)!=$dato) echo "\n";
						}
					}					
				}						
			} else  {
				echo $_bannedProxyString;	
				
			}		
		echo "</textarea>
			<input type='submit' class='buttonz' id='borradupes' value='$_removeDuplicate'>
			<span class='contadorLineas'></span>
			
			$buttonSRightTextarea1								
		</div>
		</div>
		";				
		echo "</form>";
		echo "<div style='height:25px;'></div>";	
	}		

/******************/
/*KEYWORDS MODULE*/
/****************/
if ($module=="keywords") {
	//Llamamos a la función de extracción en un bucle que recorre todas las letras del abecedario	
	$keyword=$mainkwsuggest;
	echo "
	<div style='padding: 2px; background: #ffffff; margin: 20px;'>
	<div class='aprincipal'>
	<div class='subtopContainer'>
	<span class='extractedLabel'>$_extractedKeywords</span>
	";	
	echo "</div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id='txtArea' style='width:100%;height:288px;' name='keywordssuggest'>";
	if (!empty($mainkwsuggest)) {
			if ($webproxy!="noproxy") {				
				if ($kwsugPlatform=="youtubeplatform") $arraysug=$webproxy."&querysugYT=$keyword&lang=$lang";
				elseif ($kwsugPlatform=="googleplatform") $arraysug=$webproxy."&querysug=$keyword&lang=$lang";
				elseif ($kwsugPlatform=="amazonplatform") $arraysug=$webproxy."&querysugAZ=$keyword&lang=$lang";							
				$debug.="Suggest got from: <hr>$arraysug<hr>";
				$results = getPageGoogle('',$arraysug, '', $customUserAgent, 1, 25);	
				echo $arraysug=$results['EXE'];
			} else {
				foreach(range('a','z') as $i){
					if ($kwsugPlatform=="youtubeplatform") $arraysug = youtubeSuggestKeywords("$keyword $i", $lang);
					elseif ($kwsugPlatform=="googleplatform") $arraysug = googleSuggestKeywords("$keyword $i", $lang);
					elseif ($kwsugPlatform=="amazonplatform") $arraysug = amzSuggestKeywords("$keyword $i", $lang);
					if (is_array($arraysug) || is_object($arraysug)) {
						foreach ($arraysug as &$value) {
							if (!empty($value)&&($value!=" ")) {
								echo $value."\n";					
							}				
						}
					}
				}
				$debug.="Suggest got from: <hr>localhost<hr>";
			}
	}
	echo "</textarea>
		&nbsp;<input type='submit' class='buttonz' id='toupperall' value='$_ucAll'>		
		&nbsp;<input type='submit' class='buttonz' id='toupperfirst' value='$_ucFirst'>
		&nbsp;<input type='submit' class='buttonz' id='touppercase' value='$_upperCase'>
		&nbsp;<input type='submit' class='buttonz' id='tolowercase' value='$_lowerCase'>
		<span class='contadorLineas'></span>	

		$buttonSRightTextarea1						
	</div>
	</div>
	";	
	echo "<div style='height:25px;'></div>";
}

/****************/
/*IMAGES MODULE*/
/**************/
if ($module=="images") {
	$keyword=$mainkwsuggest;
	$acumulaURLs="";
	echo "
	<div style='padding: 2px; background: #ffffff; margin: 20px;'>
	<div class='aprincipal'>
	<div class='subtopContainer'>
	<span class='extractedLabel'>$_extractedImages</span>
	";		
	if ((!$bannedProxy)&&(!empty($imagequery))){
		echo "<span id='goodproxy''>Good proxy <i class='fa fa-smile-o' aria-hidden='true'></i></span>";
	} elseif (!empty($imagequery)){
		if ($cacheJson) echo "<span id='goodproxy''>$_showingCache <i class='fa fa-database' aria-hidden='true'></i></span>";
		else echo "<span id='badproxy'>$_badProxy <i class='fa fa-frown-o' aria-hidden='true'></i></span>";					
	}	
	echo "</div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id='txtArea' style='width:100%;height:288px;' name='imagedownload'>";
	if (!$bannedProxy){		
		if (!empty($imagequery)) {
			$keybusqueda=urlencode($imagequery);
			$googleRegion="google.com";
			if ($webproxy!="noproxy") {
				$getcontents=$webproxy."&query=$keybusqueda&hl=en&tbm=isch&googleRegion=$googleRegion";	
			} else $getcontents="https://$googleRegion/search?q=$keybusqueda&hl=en&tbm=isch&googleRegion=$googleRegion";			
				$debug.="Search URL: <hr>$getcontents<hr>"; 		
				$results = getPageGoogle('',$getcontents, '', $customUserAgent, 1, 25);	
				$arraydatos=$results['EXE'];		
				//Check if banned
				if (preg_match("/unusual traffic/i", $arraydatos)) {
					$bannedProxy=true;
				} else {
					if (preg_match("/href/i", $arraydatos)) {
						$bannedProxy=false;
					} else $bannedProxy=true;
				}								
				preg_match_all('/([-a-z0-9_\/:.]+\.(jpg|jpeg|png))/i', $arraydatos, $matches);				
				foreach ($matches[0] as $imageURL) {
					if (sanitizeURL($imageURL)) {
						echo sanitizeURL($imageURL)."\n";	
						$acumulaURLs.=sanitizeURL($imageURL)."|";		
					}
				}
		}				
	} else {
		echo $_bannedProxyString;		
	}
	echo "</textarea>		
		<input type='submit' class='buttonz' id='borradupes' value='$_removeDuplicate'>
		<input type='submit' id='triggerajax' class='buttonz' id='submit' value='DOWNLOAD IMGs'>
		<span class='contadorLineas'></span>		
		$buttonSRightTextarea1				
	</div>
	</div>
	";	
	echo "<div style='height:25px;'></div>";
	?>	
	<h2 class="uppercase"><?php echo $_titleImageEditor;?></h2>
	<p class="subtitle"><?php echo $_subtitleImageEditor;?></p>
<div style="padding: 2px; background: #ffffff; margin: 20px;">	
<div class="aprincipal">
	<div class="subtopContainer">
		<span class="extractedLabel"><?php echo $_titleImageEditor;?></span>
		<div style='height:50px;'></div>		
		<div class="containerControls">
			<div class="form-container" style="margin-left:15px;">
				<form enctype="multipart/form-data" name='imageform' role="form" id="imageform" method="post" action="<?php echo basename(__FILE__);?>?module=imgupld">
					<div class="form-group">
						<h5 style="font-size:20px;">1. <?php echo $_chooseImg;?>: </h5>
						<input style="margin-left:20px;" class='file' multiple="multiple" accept="image/*"  type="file" class="form-control" name="images[]" id="images" placeholder="Please choose your image" required>
						<span class="help-block"></span>
						
						
						<h5 style="font-size:20px;">2. <?php echo $_applyoperImg;?>: </h5>
						<div class="containImgOperation">
							<span class="operationLabel"><?php echo $_flipString;?>:</span><select id='flipImageSelector' name='flipImageSelector'>
								<option value=''><?php echo $_noflipString;?></option>
								<option value='fliphorizontal'><?php echo $_fliphorString;?></option>
								<option value='flipvertical'><?php echo $_flipvertString;?></option>
								<option value='flipboth'><?php echo $_flipbothString;?></option>
							</select>
						</div> 
						<div class="containImgOperation"> 
							<span class="operationLabel"><?php echo $_resizeString;?>:</span> <select id='resizeImageSelector' name='resizeImageSelector'>
								<option value=''><?php echo $_noresizeString;?></option>
								<option value='reducesize'><?php echo $_reducesizeString;?></option>
							</select>						
							<span class="operationLabel"><?php echo $_widthString;?>:</span><input type="number" class="form-control" name="newImageWidth" id="newImageWidth" min="1" max="999999" value="680" placeholder="Width (pixel)" required>
							<span class="operationLabel"><?php echo $_qualityString;?>:</span><input type="number" class="form-control" name="conversionQuality" id="conversionQuality"  min="1" max="100" value="70" required>
						</div>
						<div class="containImgOperation"> 
							<span class="operationLabel"><?php echo $_addtextString;?>:</span> <select id='text1ImageSelector' name='text1ImageSelector'>
								<option value=''><?php echo $_notextString;?></option>
								<option value='addaphrase'><?php echo $_addPhraseString;?></option>
							</select>						
							<span class="operationLabel"><?php echo $_textString;?>:</span><input type="text" class="form-control" name="addTextoImage" id="addTextoImage"  placeholder="<?php echo $_texttoaddString;?>">
							<span class="operationLabel"><?php echo $_marleftString;?>:</span><input type="number" class="form-control" name="coordinateX" id="coordinateX" min="0" max="999999" value="100" placeholder="X (pixels)">
							<span class="operationLabel"><?php echo $_martopString;?>:</span><input type="number" class="form-control" name="coordinateY" id="coordinateY"  min="0" max="999999" value="100" placeholder="Y (pixels)">
							<span class="operationLabel"><?php echo $_fosizeString;?>:</span><input type="number" class="form-control" name="imageFontSize" id="imageFontSize"  min="1" max="5000" value="24" placeholder="font size (pixels)">
							<span class="operationLabel"><?php echo $_focolorString;?>:</span><input type="color" class="form-control" name="imageFontColor" id="imageFontColor"  value="#ffffff">
							<p><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;<?php echo $_customFontString;?>.</p>							
							<div style="clear:both;height:30px;"></div>
							<span class="operationLabel"><?php echo $_addTextfromFileString;?>:</span> <select id='text2ImageSelector' name='text2ImageSelector'>
								<option value=''><?php echo $_notextString;?></option>
								<option value='addrandomlines'><?php echo $_randomOrderString;?></option>
								<option value='addsecuenciallines'><?php echo $_secuencialOrderString;?></option>
							</select>													
							<br>
							<p><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;<?php echo $_eachlineWillBeString;?></p>
						</div>
						<div class="containImgOperation"> 
							<span class="operationLabel"><?php echo $_addWatermarkString;?>:</span> <select id='textWatermarkSelector' name='textWatermarkSelector'>
								<option value=''><?php echo $_noWatermarkString;?></option>
								<option value='addwatermark'><?php echo $_addaWatermarkString;?></option>
							</select>	
							<span class="operationLabel"><?php echo $_marrightString;?>:</span><input type="number" class="form-control" name="coordinateXwatermark" id="coordinateXwatermark" min="0" max="999999" value="50" placeholder="X (pixels)">
							<span class="operationLabel"><?php echo $_marbottomString;?>:</span><input type="number" class="form-control" name="coordinateYwatermark" id="coordinateYwatermark"  min="0" max="999999" value="50" placeholder="Y (pixels)">							
							<br>
							<p><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;<?php echo $_theFileWatermarkString;?></p>
						</div>	
						<div class="containImgOperation"> 
							<span class="operationLabel"><?php echo $_convertFormatString;?>:</span> <select id='convertFormat' name='convertFormat'>
								<option value=''><?php echo $_noConversionString;?></option>
								<option value='converttoJPG'><?php echo $_convertToString;?> JPG</option>
								<option value='converttoPNG'><?php echo $_convertToString;?> PNG</option>
								<option value='converttoGIF'><?php echo $_convertToString;?> GIF</option>
								<option value='converttoWEBP'><?php echo $_convertToString;?> WEBP</option>
							</select>	
						</div>												
					</div>
					<div id="loader" style="display: none;">
						<?php echo $_uploadWaitString;?>
					</div>
					<div style='height:25px;'></div>
					<input style="margin:10px" class="buttonz" type="submit" value="<?php echo $_uploadEditString;?>" name="image_upload" id="image_upload" class="btn"/>					
				</form>
			</div>
			<div class="clearfix"></div>
			
			<?php			
			$images = glob("images/*.*");
			array_multisort(array_map('filemtime', $images), SORT_NUMERIC, SORT_DESC, $images);
			$images =array_slice($images,0,100);				 				
			/*if (!empty($images)) {*/
			?>			
			<div id='containImageFolder'>
				<div id="uploaded_images" class="uploaded-images">
					<div id="error_div">
					</div>
			<?php /*} */?>
					<div id="success_div">
					<input style="margin:10px" class="buttonz" type="submit" value="<?php echo strtoupper($_downloadAllString); ?>" name="triggerdownloadimgZip" id="triggerdownloadimgZip"/>
					<div style="clear:both;"></div>
					<h5 style="font-size:16px;margin-left: 10px;"><?php echo $_procString; ?>:</h5>										
					</div>
					<textarea style="display:none;" id='txtAreaResultsImages'></textarea>
			<?php if (!empty($images)) {?>		
					<div style='height:15px;'></div>						
				</div>											
				<?php			 				
				echo "<h5 style='font-size:16px;margin-left: 10px;'>$_last100procString:</h5> <div style='height:5px;'></div>";				
				foreach($images as $image) {
					$data = getimagesize($image);
					$width = $data[0];
					$height = $data[1];
					$file_size = filesize($image);
					$file_size = $file_size / 1024; // Get file size in KB
					$file_size=round($file_size,2);	
					echo "<div class='containImageSmall'>";					
					$ext = pathinfo($image, PATHINFO_EXTENSION);						
					echo '<a title="'.$image.'" href="'.$image.'" target="_blank"><img src="'.$image.'" /></a>';
					echo "<span style='color: #4fea58;' class='imageSizeTextBelow'>$ext </span>";
					echo "<span class='imageSizeTextBelow'>$width x $height</span>";
					echo "<span class='imageSizeTextBelow2'> $file_size KB</span>";				
					echo "</div>";
					
				}
				echo "<textarea style='display:none;' id='txtAreaArchiveImages'>";
				foreach($images as $image) {
					echo $image."\n";
				}
				echo "</textarea>";
				?>	
			</div>	
			<div style="clear:both;"></div>
			<input style="margin:20px 5px; float: right;" class="buttonz" type="submit" value="<?php echo strtoupper($_deleteImagesString); ?>" name="triggerdeletePics" id="triggerdeletePics"/>	
			<input style="margin:20px 5px; float: right;" class="buttonz" type="submit" value="<?php echo strtoupper($_downloadAllString); ?>" name="triggerdownloadimgZipAll" id="triggerdownloadimgZipAll"/>					
			<?php }?>
			<div style='clear:both;height:35px;'></div>
		</div>
	</div>
</div>
</div></div>
<?php	
}
/************************/
/*DATA EXTRACTOR MODULE*/
/**********************/
if ($module=="dataextractor") {
	$acumulaURLs="";
	echo "
	<div style='padding: 2px; background: #ffffff; margin: 20px;'>
	<div class='aprincipal'>
	<div class='subtopContainer'>
	<span class='extractedLabel'>$_urlList</span>
	";	
	echo "</div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)'  id='txtArea' style='width:100%;height:288px;' name='imagedownload'>";	
	$fileToShow=$_SESSION['filenameCSVsession'];
	$fileToShowXLS=str_replace(".csv",".xlsx",$fileToShow);
	$linkReset=basename(__FILE__)."?module=dataextractor";	
	echo "</textarea>			
		<select id='linksParaExtraer'><option  value='default'>$_extractSelection...</option><option  value='alllinks'>$_allLinks</option><option value='internal'>$_internalLinks</option><option value='external'>$_externalLinks</option><option value='imgurlsHARD'>$_imageURLShard</option><option value='imgurlsSOFT'>$_imageURLSoft</option><option value='emailaddress'>$_emailAddress</option>";
		showData_ExtractorTemplatesOptions();
		echo"</select>		
		<div id='moreoptionscontainer'>
		<select style='display:none;' id='htmlotexto' name='htmlotexto'><option value='text'>extract text</option><option value='html'>extract code</option></select>		
		<select style='display:none;' id='greedyornot' name='greedyornot'><option value='nongreedy'>ungreedy</option><option value='greedy'>greedy</option></select>				
		<select id='typeofexport' name='typeofexport'><option value='txtfile'>txt</option><option value='csvfile'>csv</option><option value='xlsfile'>xlsx</option></select>		
		<span id='showfilexlsx' style='font-size:12px;'>Folder: <em>/data-extractor/$fileToShowXLS</em></span>
		<span id='showfilecsv' style='font-size:12px;'>Folder: <em>/data-extractor/$fileToShow</em></span>
		<span id='showfiletxt' style='font-size:12px;'>Folder: <em>/data-extractor/*.txt</em></span>		
		<a id='resetfile' href='$linkReset'><i class='fa fa-trash' aria-hidden='true'></i> RESET</a>
		</div>
		";
		echo "
		<button id='extractlinks' class='buttonz'>START</button>					
		<span class='contadorLineas'></span>
		$buttonSRightTextarea1					
	</div>
	</div>
	</form>	
	<div style='padding: 2px; background: #ffffff; margin: 20px;'>
	<div class='aprincipal'>
	<div class='subtopContainer'>
	<span class='extractedLabel'>$_results</span> <span id='currentoperation'></span>";
	echo "<div style='clear:both;'></div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id='txtAreaResults' style='width:100%;height:288px;' name='imagedownload'></textarea>			
		<input type='button' class='buttonz' id='borradupes2' value='$_removeDuplicate'>
		<input type='button' class='buttonz' id='borradupeDomains' value='$_removeDuplicateDomains'>
		<span id='contadorLineas2'></span>
		<div class='buttonsright'>
		<a href='#' title='$_downloadAllString' onclick='doDL(document.getElementById(\"txtAreaResults\").value)'><i class='fa fa-download' aria-hidden='true'></i></a>
		<a href='#' title='Select all' onclick='selectTextarea(\"txtAreaResults\")'><i class='fa fa-clipboard' aria-hidden='true'></i></a>
		<a href='#' title='$_remove $_containing' onclick='removecontaining(\"txtAreaResults\")'><i class='fa fa-trash' aria-hidden='true'></i> $_containing</a>
		<a href='#' title='$_remove $_notContaining' onclick='removenotcontaining(\"txtAreaResults\")'><i class='fa fa-trash' aria-hidden='true'></i> $_notContaining</a>
		<a href='#' title='Clear all' onclick='clearTextarea(\"txtAreaResults\")'><i class='fa fa-trash' aria-hidden='true'></i></a>		
		</div>		
	</div>
	</div>		
	</div>
	";	
	$file = 'content-extractor-templates.txt';
	if (isset($_POST['templateListUpdate'])) file_put_contents($file, $_POST['templateListUpdate']);
	$text = file_get_contents($file);	
	?>	
	<div style="padding: 2px; background: #ffffff; margin: 20px;">
		<div class="aprincipal">
			<div class="subtopContainer">
			<span class="extractedLabel"><?php echo $_templateListText;?></span>						
				<div style="clear:both;"></div>
				<div style="margin:15px 0px 15px 15px;"><?php echo $_templateListHelp;?></div>			
				<form action="" method="post">	
				<div style="clear:both;"></div>				
				<textarea style="height: 190px;" class='rownr' cols='3' value='1' readonly></textarea>
				<textarea nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id="templateListUpdate"  name="templateListUpdate" style='width:100%;height:200px;'><?php echo htmlspecialchars($text) ?></textarea>
				<input class="buttonz" type="submit" value="SALVAR" /> <span><strong><?php echo $_templateListFormatText;?></strong>: <em>Template name <strong>@@@</strong> field1 <strong>###</strong> beginfield1 <strong>###</strong> endfield1 <strong>@@@</strong> field2 <strong>###</strong> beginfield2 <strong>###</strong> endfield2 <strong>@@@</strong> field3 <strong>###</strong> beginfield3 <strong>###</strong> endfield3</em></span>
				</form>
			</div>
		</div>
	</div>	
	<?php
	echo "<div style='height:25px;'></div>";	
}
/**************************/
/*PLAGIARISM CHECK MODULE*/
/************************/
if ($module=="plagicheck") {
	echo "
	<div style='padding: 2px; background: #ffffff; margin: 20px;'>
	<div class='aprincipal'>
	<div class='subtopContainer'>
	<span class='extractedLabel'>$_text</span>
	";		
	echo "</div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id='txtArea' style='width:100%;height:288px;' name='plagichecktext'>";	
	if (!empty($plagichecktext)) echo $plagichecktext;
	echo "</textarea>
	<input type='submit' class='buttonz' id='checkautomatico' value='$_manualCheckString'>
	<input type='submit' id='triggerplagiarism' class='buttonz' id='submit' value='$_automaticCheckString'>
	<span class='contadorLineas'></span>
		$buttonSRightTextarea1mini	
	</div></div></form>
	
	<div class='plagiresults'>
	";
	if (!empty($plagichecktext)){
		$words = explode(' ', $plagichecktext);
		$numWords=count($words);
		$substringRandom=join(' ', array_slice($words, mt_rand(0, $numWords - 1), mt_rand(1, $numWords)));
		$substringRandom=trim(substrwords($substringRandom,120));
		$substringRandom=urlencode($substringRandom);
		echo "<span> <a style='color: grey;' target='_blank' href='https://google.com/search?q=%22$substringRandom%22'> <i class='fa fa-external-link' aria-hidden='true'></i> RANDOM CHECK 1</a></span>";
		$substringRandom=join(' ', array_slice($words, mt_rand(0, $numWords - 1), mt_rand(1, $numWords)));
		$substringRandom=trim(substrwords($substringRandom,120));
		$substringRandom=urlencode($substringRandom);				
		echo " | <span> <a style='color: grey;' target='_blank' href='https://google.com/search?q=%22$substringRandom%22'> <i class='fa fa-external-link' aria-hidden='true'></i> RANDOM CHECK 2</a></span>";
		$substringRandom=join(' ', array_slice($words, mt_rand(0, $numWords - 1), mt_rand(1, $numWords)));
		$substringRandom=trim(substrwords($substringRandom,120));
		$substringRandom=urlencode($substringRandom);		
		echo " | <span> <a style='color: grey;' target='_blank' href='https://google.com/search?q=%22$substringRandom%22'> <i class='fa fa-external-link' aria-hidden='true'></i> RANDOM CHECK 3</a></span>";
	} 
	echo "</div>";
}
/***************************/
/*INDEXED URL CHECK MODULE*/
/*************************/
if ($module=="indexedcheck") {
	echo "
	<div style='padding: 2px; background: #ffffff; margin: 20px;'>
	<div class='aprincipal'>
	<div class='subtopContainer'>
	<span class='extractedLabel'>$_urlList</span>
	";		
	echo "</div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id='txtArea' style='width:100%;height:288px;' name='plagichecktext'>";	
	if (!empty($indexedcheckURLs)) echo $indexedcheckURLs;
	echo "</textarea>	
	<button id='triggercheckindexed' class='buttonz'>START</button>					
	<span class='contadorLineas'></span>
		$buttonSRightTextarea1	
	</div></div></form>
	<div style='padding: 2px; background: #ffffff; margin: 20px;'>
	<div class='aprincipal'>
	<div class='subtopContainer'>
	<span class='extractedLabel'>$_results</span> <span id='currentoperation'></span>";	
	echo "<div class='indexcheckresults'></div>";
	echo "<div style='clear:both;'></div><textarea class='rownr' cols='3' value='1' readonly></textarea><textarea  nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id='txtAreaResults' style='width:100%;height:288px;' name='imagedownload'></textarea>			
		<input type='button' class='buttonz' id='downloadIndexedCSV' value='$_downloadCSVString' onclick='doDLindexed(document.getElementById(\"txtAreaResults\").value)'>
		<span style='font-size: 12px;'>($_selectPasteExcelString)</span>				
		<span id='contadorLineas2'></span>
		<div class='buttonsright'>
		<a href='#' title='$_downloadAllString' onclick='doDL(document.getElementById(\"txtAreaResults\").value)'><i class='fa fa-download' aria-hidden='true'></i></a>
		<a href='#' title='Select all' onclick='selectTextarea(\"txtAreaResults\")'><i class='fa fa-clipboard' aria-hidden='true'></i></a>
		<a href='#' title='$_remove $_containing' onclick='removecontaining(\"txtAreaResults\")'><i class='fa fa-trash' aria-hidden='true'></i> $_containing</a>
		<a href='#' title='$_remove $_notContaining' onclick='removenotcontaining(\"txtAreaResults\")'><i class='fa fa-trash' aria-hidden='true'></i> $_notContaining</a>
		<a href='#' title='Clear all' onclick='clearTextarea(\"txtAreaResults\")'><i class='fa fa-trash' aria-hidden='true'></i></a>		
		</div>
	</div>		
	</div>
</div>	
	";	
	echo "<div style='height:25px;'></div>";		
}
/*************************/
/*PROXY MANAGER SETTINGS*/
/***********************/
if ($module=="proxymanager") {
	$file = 'web-proxy-list.txt';
	if (isset($_POST['proxyListUpdate'])) file_put_contents($file, $_POST['proxyListUpdate']);
	$text = file_get_contents($file);
?>
<div class="maincontent">
	<div style="padding: 2px; background: #ffffff; margin: 20px;">
		<div class="aprincipal">
			<div class="subtopContainer">			
			<span class="extractedLabel"><?php echo strtoupper($_titleInfo);?> <?php echo strtoupper($_titleProxyManager);?></span>	
			<div style='clear:both;'></div>		
			<?php echo $_proxyMiniHelp;?>
			<div style='clear:both;height:20px;'></div>				
			<span class="extractedLabel"><?php echo $_createaProxyServer;?></span>
				<div style="clear:both;"></div>
				<div style="margin-left: 15px;"> <?php echo $_typeaPassText;?>: &nbsp;&nbsp;<input id='setProxyPassword' value='<?php echo $setProxyPassword;?>' placeholder='<?php echo $_newPassText;?>'> <input class="buttonz" type="submit" id='saveProxyPassword' value="<?php echo $_createText;?>" /><div style='display:none;' id="downloadnewproxyContainer"><a  id='downloadNewProxy' target='_blank' href="<?php echo  basename(__FILE__);?>?module=proxygenerator"><?php echo $_downloadProxyFileText;?></a></div></div>			
			<div style='clear:both;height:25px;'></div>	
			</div>
		</div>
	</div>
	<div style="padding: 2px; background: #ffffff; margin: 20px;">
		<div class="aprincipal">
			<div class="subtopContainer">
			<span class="extractedLabel"><?php echo $_proxyListText;?></span>		
				<form action="" method="post">	
				<div style="clear:both;"></div>				
				<textarea style="height: 190px;" class='rownr' cols='3' value='1' readonly></textarea>
				<textarea nowrap='nowrap' wrap='off' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' onclick='selectionchanged(this)' onkeyup='keyup(this,event)' oninput='input_changed(this)' onscroll='scroll_changed(this)' id="txtArea"  name="proxyListUpdate" style='width:100%;height:200px;'><?php echo htmlspecialchars($text) ?></textarea>
				<input class="buttonz" type="submit" value="SALVAR" /> <span>Proxy file format: <strong>Proxy name @@@ https://yourdomain.com/anyfolder/phpseo-proxy.php?password=YOURPASSWORDHERE</strong></span>
				</form>
			</div>
		</div>
	</div>
</div>	
<?php
}
?>

<?php
/****************/
/*MAIN SETTINGS*/
/**************/
if ($module=="mainsettings") {
	$file = 'config.txt';
	if (isset($_POST['configUpdate'])) file_put_contents($file, $_POST['configUpdate']);
	$text = file_get_contents($file);
?>
<div class="maincontent">
	<div style="padding: 2px; background: #ffffff; margin: 20px;">
		<div class="aprincipal">
			<div class="subtopContainer">
			<span class="extractedLabel"><?php echo strtoupper($_titleInfo);?> <?php echo strtoupper($_titleMainSettings);?></span>	
			<div style='clear:both;'></div>
			<p style='margin-left: 15px;'><?php echo $mainSettingsIntroText;?></p>			
			<ul>					
				<li><?php echo $langInfo;?></li>
				<li><?php echo $howManyURLPerStepInfo;?></li>
				<li><?php echo $batchDelaySecondsInfo;?></li>
				<li><?php echo $searchDelaySecondsInfo;?></li>
				<li><?php echo $customUserAgentInfo;?></li>				
			</ul>
			<div style='clear:both;height:20px;'></div>			
			<span class="extractedLabel"><?php echo strtoupper($_titleMainSettings);?></span>	
			<div style='clear:both;'></div>						
				<form action="" method="post">
				<textarea name="configUpdate" style='margin-left: 30px;height:180px;'><?php echo htmlspecialchars($text) ?></textarea>
				<input class="buttonz" type="submit" value="SALVAR" />
				<div style="clear:both;"></div>				
				</form>
			</div>
			<div style="text-align:right;overflow: hidden;">
				<a target='_blank' href='?module=autoupdate' style='color: #49ae5a; text-decoration: none; margin: 7px; font-size: 14px; display: block;'>auto update</a>
			</div>			
			<div class="subtopContainer">
				<hr>
				<div style='clear:both;height:20px;'></div>	
				<?php
					$file = 'footprints.txt';
					if (isset($_POST['fooprintsUpdate'])) file_put_contents($file, $_POST['fooprintsUpdate']);
					$text2 = file_get_contents($file);
				?>					
				<span class="extractedLabel"><?php echo strtoupper($_titleFootprints);?></span>
				<div style='clear:both;'></div>	
				<p style='margin-left: 15px;'><?php echo $_descFootprints;?>.</p>				
				<div style='clear:both;'></div>						
				<form action="" method="post">
				<textarea name="fooprintsUpdate" style='margin-left: 30px;height:180px;'><?php echo htmlspecialchars($text2) ?></textarea>
				<input class="buttonz" type="submit" value="SALVAR" />
				<div style="clear:both;"></div>				
				</form>
			</div>	
		</div>
	</div>
<?php
}
?>
<script>
$( document ).ready(function() {
	var s = $( ".translatedbody" ).text();
	var count = !s ? 0 : (s.split(/^\s+$/).length === 2 ? 0 : 2 + s.split(/\s+/).length - s.split(/^\s+/).length - s.split(/\s+$/).length);	
	$('#cuentapalabras').text(count);
    actualizaLineas();
    $("#txtAreaResults").on('change paste keyup keydown', actualizaLineas);
    $("#txtArea").on('change paste keyup keydown', actualizaLineas);
    $("#templateListUpdate").on('change paste keyup keydown', actualizaLineas);	
	
    $('#linksParaExtraer').change(function(){
		var valoroption=$(this).val();
		if (valoroption.indexOf("template-") >= 0) {
			$('#moreoptionscontainer').css('display', 'inline-block');	
			//Initial state
			$('#showfilexlsx').css('display', 'none');	
			$('#showfilecsv').css('display', 'none');
			$('#resetfile').css('display', 'none');			
		}
		else  $('#moreoptionscontainer').css('display', 'none');
    });
    $('#kwsugPlatform').change(function(){
		var valoroption=$(this).val();
		if (valoroption.indexOf("amazonplatform") >= 0) {
			$("#idioma").val("en");
			$('#idioma').prop('disabled', true);			
		}
		else  $('#idioma').prop('disabled', false);
    });
    $('#text1ImageSelector').change(function(){
		var valoroption=$(this).val();
		if (valoroption.indexOf("addaphrase") >= 0) {
			$('#text2ImageSelector').prop('disabled', true);			
		}
		else  $('#text2ImageSelector').prop('disabled', false);
    });		
	$('#text2ImageSelector').change(function(){
		var valoroption=$(this).val();
		if ((valoroption.indexOf("addrandomlines") >= 0)||(valoroption.indexOf("addsecuenciallines") >= 0)) {
			$('#text1ImageSelector').prop('disabled', true);			
		}
		else  $('#text1ImageSelector').prop('disabled', false);
    });	
    $('#typeofexport').change(function(){
		var valoroption=$(this).val();		
		if (valoroption=="csvfile"){
			$('#showfilecsv').css('display', 'inline-block');
			$('#resetfile').css('display', 'inline-block');
			$('#showfilexlsx').css('display', 'none');
			$('#showfiletxt').css('display', 'none');			
		}
		if (valoroption=="xlsfile"){
			$('#showfilexlsx').css('display', 'inline-block');
			$('#resetfile').css('display', 'inline-block');
			$('#showfilecsv').css('display', 'none');
			$('#showfiletxt').css('display', 'none');
		}		
		if (valoroption=="txtfile"){
			$('#showfiletxt').css('display', 'inline-block');
			$('#resetfile').css('display', 'none');
			$('#showfilexlsx').css('display', 'none');
			$('#showfilecsv').css('display', 'none');
		}
    });
    $('#webproxy').change(function(){
		var valorproxy=$(this).val();
		if( valorproxy.indexOf('https://mydomain') >= 0){
		  alert("<?php echo $_setUpYourProxy;?>");
		  $("#webproxy").val("noproxy");
		}
    });
	$('#triggerajax').click(function(){
		var acumuladoImgs="";
		var split = $('#txtArea').val().split('\n');
		var lines = [];
		for (var i = 0; i < split.length; i++){
			if (split[i]) acumuladoImgs=acumuladoImgs+"|"+split[i];				
		}
		var jsonString = JSON.stringify(acumuladoImgs);
		   document.getElementById("loading").style.display = "block";
		   $.ajax({
				type: "POST",
				url: "<?php echo basename(__FILE__);?>?module=imagedownload",
				data: {data : jsonString}, 
				cache: false,
				success: function(data){
					document.getElementById("loading").style.display = "none";				
					$('#downloadResult').html('Download link: <a href="phpseoimages.zip"><i class="fa fa-download" aria-hidden="true"></i> <span>phpseoimages.zip</span></a>');
					var modal = document.getElementById("myModal");
					modal.style.display = "block";
				}
			});
	});	
	$('#triggerplagiarism').click(function(e){
		e.preventDefault();
		var textContent =$('#txtArea').val();
		var numwords=textContent.trim().split(/\s+/).length;
		if (numwords<=99) {
			alert ("<?php echo $_morewordreqString; ?>");
			return;
		}
		var webproxy=$( "#webproxy" ).val();		
		   document.getElementById("loading").style.display = "block";
		   $.ajax({
				type: "POST",
				url: "<?php echo basename(__FILE__);?>?module=plagichecksubmit",
				data: {data : textContent, webproxy:webproxy}, 
				cache: false,
				success: function(data){
					document.getElementById("loading").style.display = "none";				
					$('.plagiresults').html(data);
						if (webproxy=="noproxy"){ 										
							$('.debugArea').html("<hr>Search (no proxy):<hr>https://google.com/search?q=YourText<hr>"); 
						}else {
							$('.debugArea').html("<hr>Search (using proxy):<hr>"+webproxy+"&getPage=https://google.com/search?q=YourText<hr>"); 																									
						}
				}
			});
	});
	$('#saveProxyPassword').click(function(e){
		e.preventDefault();
		alert('<?php echo $proxyAlertString;?>');
		var proxypassContent =$('#setProxyPassword').val();
		if (proxypassContent == "") { alert("Password required"); return;}
		$("#downloadnewproxyContainer").css("display", "inline-block");
		$("#downloadNewProxy").attr("href", "<?php echo basename(__FILE__);?>?module=proxygenerator&passNewProxy="+proxypassContent);
	});
	$('#triggerdeletePics').click(function(e){
		e.preventDefault();				
		var answer=confirm('Delete ALL stored images?');
		if(answer){
		   document.getElementById("loading").style.display = "block";
		   $.ajax({
				type: "POST",
				url: "<?php echo basename(__FILE__);?>?module=imgdltDirectory",
				data: {data : "none"}, 
				cache: false,
				success: function(data){
					document.getElementById("loading").style.display = "none";
					location.reload();				
				}
			});
		}
	});
	$('#triggerdownloadimgZip').click(function(){
		var acumuladoImgs="";
		var split = $('#txtAreaResultsImages').val().split('\n');
		var lines = [];
		for (var i = 0; i < split.length; i++){
			if (split[i]) acumuladoImgs=acumuladoImgs+"|"+split[i];				
		}
		var jsonString = JSON.stringify(acumuladoImgs);
		   document.getElementById("loading").style.display = "block";
		   $.ajax({
				type: "POST",
				url: "<?php echo basename(__FILE__);?>?module=imagedownload",
				data: {data : jsonString}, 
				cache: false,
				success: function(data){
					document.getElementById("loading").style.display = "none";				
					$('#downloadResult').html('Download link: <a href="phpseoimages.zip"><i class="fa fa-download" aria-hidden="true"></i> <span>phpseoimages.zip</span></a>');
					var modal = document.getElementById("myModal");
					modal.style.display = "block";
				}
			});
	});	
	$('#triggerdownloadimgZipAll').click(function(){
		var acumuladoImgs="";
		var split = $('#txtAreaArchiveImages').val().split('\n');
		var lines = [];
		for (var i = 0; i < split.length; i++){
			if (split[i]) acumuladoImgs=acumuladoImgs+"|"+split[i];				
		}
		var jsonString = JSON.stringify(acumuladoImgs);
		   document.getElementById("loading").style.display = "block";
		   $.ajax({
				type: "POST",
				url: "<?php echo basename(__FILE__);?>?module=imagedownload",
				data: {data : jsonString}, 
				cache: false,
				success: function(data){
					document.getElementById("loading").style.display = "none";				
					$('#downloadResult').html('Download link: <a href="phpseoimages.zip"><i class="fa fa-download" aria-hidden="true"></i> <span>phpseoimages.zip</span></a>');
					var modal = document.getElementById("myModal");
					modal.style.display = "block";
				}
			});
	});			
var timeouts2 = [];
	$('#triggercheckindexed').click(function(e){
		e.preventDefault();
		var textoBoton=$(this).text();	
		if (textoBoton=="START"){
			var cancel = false;
			var acumuladoImgs="";
			var linksParaExtraer=$( "#linksParaExtraer" ).val();
			var htmlotexto=$( "#htmlotexto" ).val();
			var greedyornot=$( "#greedyornot" ).val();	
			var typeofexport=$( "#typeofexport" ).val();		
			var split = $('#txtArea').val().split('\n');
			var lines = [];
			for (var i = 0; i < split.length; i++){
				if (i==0) acumuladoImgs=split[i];
				else if (split[i]) acumuladoImgs=acumuladoImgs+"|"+split[i];				
			}
			var jsonString = JSON.stringify(acumuladoImgs);
			if ($('#txtArea').val()=="") {
				alert("Please, enter some URLs");
				return false;
			} else $('#currentoperation').html("<span class='currentprogress'>Starting first URL batch</span>");
			var arr = jsonString.split('|');
			var cuentalineas=0;
			var indexbloques=0;
			var lengthacumulaURLs=0;
			var howManyPerStep=<?php echo $howManyURLPerStep;?>;
			var webproxy=$( "#webproxy" ).val();
			var acumulaURLs = new Array();			
			$.each( arr, function( index, value ) {								
				if (index==0) acumulaURLs[indexbloques]=value+"|";
				else acumulaURLs[indexbloques]=acumulaURLs[indexbloques]+value+"|";													
				acumulaURLs[indexbloques]= acumulaURLs[indexbloques].replace(/undefined/g, '');				
				if (index!=0) if (index % howManyPerStep == 0) indexbloques++;				
			});									
			lengthacumulaURLs = acumulaURLs.length;
			$.each( acumulaURLs, function( index, value ) {
				timeouts2.push(
					setTimeout(function(){
						if (cancel==true) {
							for (var i=0; i<timeouts2.length; i++) {
							  clearTimeout(timeouts2[i]);
							}
							return false;
						}
						$.ajax({
							type: "POST",
							url: "<?php echo basename(__FILE__);?>?module=checkindexedsubmit",
							data: {data : value, tipolink : linksParaExtraer, whattoget: htmlotexto, isgreedyornot: greedyornot, typeofexport: typeofexport, index: index, lengthacumulaURLs:lengthacumulaURLs, webproxy:webproxy},					
							cache: false,
							success: function(data){
								if (data.indexOf("nolinktype") >= 0){
									alert("Please, select a link type");
								}							
								if (cancel==true) {
									for (var i=0; i<timeouts2.length; i++) {
									  clearTimeout(timeouts2[i]);
									}
									return false;
								} else {
									var indexToShow=index+1;
									$('#currentoperation').html("<span class='currentprogress'>Processing URL batch <span class='totalbatches'>"+indexToShow+"/"+lengthacumulaURLs+" </span></span>");	
									if (webproxy=="noproxy"){ 										
										var allurls = value.split("|");
										$.each( allurls, function( key, value ) {
										  if (value!="") $('.debugArea').html("<hr>Search sample (no proxy):<hr>https://google.com/search?q="+value.replace(/['"]+/g, '')+"<hr>"); 
										});
									}else {
										var allurls = value.split("|");
										$.each( allurls, function( key, value ) {
										  if (value!="") $('.debugArea').html("<hr>Search sample (using proxy):<hr>"+webproxy+"&getPage=https://google.com/search?q="+value.replace(/['"]+/g, '')+"<hr>"); 
										});																									
									}
									var $output = $("#txtAreaResults");
									var val = $output.val();
									$output.val(val + data );
									var currentText = $("#txtAreaResults").val();   
									var currentLines = currentText.split(/\r|\r\n|\n/);
									var currentCount = currentLines.length;	
									$('#contadorLineas2').text(currentCount+' <?php echo $_records;?>');
									
									if (index === (lengthacumulaURLs-1)) {
											var finishedToShow=index+1;
											$('#currentoperation').html("<span class='currentprogress'>All threads finished after batch "+finishedToShow+" </span>");
											for (var i=0; i<timeouts2.length; i++) {
											  clearTimeout(timeouts2[i]);
											}
											 $("#triggercheckindexed").text("START").toggleClass('stop_button');	
											 setTimeout(function() { $('#currentoperation').html("<span class='currentprogress'>All threads finished after batch "+finishedToShow+" </span>"); }, 5000);																						 
									}									
								}								
							}
						});
					},<?php echo ($batchDelaySeconds*1000);?>*index) 
				);												 	
			});
		}
		if (textoBoton=="STOP"){
			cancel = true;				
			for (var i=0; i<timeouts2.length; i++) {
				clearTimeout(timeouts2[i]);
			}
			setTimeout(function() { $('#currentoperation').html("<span class='currentprogress'>Stopped</span>"); }, 5000);									
		}		
		$(this).text(function (_, text) {
			return text === 'STOP' ? 'START' : 'STOP';
		}).toggleClass('stop_button');		
	});
	var timeouts = [];
	$('#extractlinks').click(function(e){
		e.preventDefault();
		var textoBoton=$(this).text();	
		if (textoBoton=="START"){
			var cancel = false;
			var acumuladoImgs="";
			var linksParaExtraer=$( "#linksParaExtraer" ).val();
			var htmlotexto=$( "#htmlotexto" ).val();
			var greedyornot=$( "#greedyornot" ).val();	
			var typeofexport=$( "#typeofexport" ).val();		
			var split = $('#txtArea').val().split('\n');
			var lines = [];
			for (var i = 0; i < split.length; i++){
				if (i==0) acumuladoImgs=split[i];
				else if (split[i]) acumuladoImgs=acumuladoImgs+"|"+split[i];				
			}
			var jsonString = JSON.stringify(acumuladoImgs);
			if (linksParaExtraer=="default") {
				alert("Please, select URL type to extract");
			return false;
			}
			if ($('#txtArea').val()=="") {
				alert("Please, enter some URLs");
				return false;
			} else $('#currentoperation').html("<span class='currentprogress'>Starting first URL batch</span>");			
			var arr = jsonString.split('|');
			var cuentalineas=0;
			var indexbloques=0;
			var lengthacumulaURLs=0;
			var howManyPerStep=<?php echo $howManyURLPerStep;?>;
			var webproxy=$( "#webproxy" ).val();
			var acumulaURLs = new Array();			
			$.each( arr, function( index, value ) {								
				if (index==0) acumulaURLs[indexbloques]=value+"|";
				else acumulaURLs[indexbloques]=acumulaURLs[indexbloques]+value+"|";													
				acumulaURLs[indexbloques]= acumulaURLs[indexbloques].replace(/undefined/g, '');				
				if (index!=0) if (index % howManyPerStep == 0) indexbloques++;				
			});									
			lengthacumulaURLs = acumulaURLs.length;
			$.each( acumulaURLs, function( index, value ) {
				timeouts.push(
					setTimeout(function(){
						if (cancel==true) {
							for (var i=0; i<timeouts.length; i++) {
							  clearTimeout(timeouts[i]);
							}
							return false;
						}
						$.ajax({
							type: "POST",
							url: "<?php echo basename(__FILE__);?>?module=dataextractorsubmit",
							data: {data : value, tipolink : linksParaExtraer, whattoget: htmlotexto, isgreedyornot: greedyornot, typeofexport: typeofexport, index: index, lengthacumulaURLs:lengthacumulaURLs, webproxy:webproxy},
							/*async: false,*/					
							cache: false,
							success: function(data){
								if (data.indexOf("nolinktype") >= 0){
									alert("Please, select a link type");
								}							
								if (cancel==true) {
									for (var i=0; i<timeouts.length; i++) {
									  clearTimeout(timeouts[i]);
									}
									return false;
								} else {
									var indexToShow=index+1;
									$('#currentoperation').html("<span class='currentprogress'>Processing URL batch <span class='totalbatches'>"+indexToShow+"/"+lengthacumulaURLs+" </span></span>");	
									if (webproxy=="noproxy"){ 										
										var allurls = value.split("|");
										$.each( allurls, function( key, value ) {
										  if (value!="") $('.debugArea').html("<hr>Search sample (no proxy):<hr>"+value.replace(/['"]+/g, '')+"<hr>"); 
										});
									}else {
										var allurls = value.split("|");
										$.each( allurls, function( key, value ) {
										  if (value!="") $('.debugArea').html("<hr>Search sample (using proxy):<hr>"+webproxy+"&getPage="+value.replace(/['"]+/g, '')+"<hr>"); 
										});																									
									}
									var $output = $("#txtAreaResults");
									var val = $output.val();
									$output.val(val + data);
									var currentText = $("#txtAreaResults").val();   
									var currentLines = currentText.split(/\r|\r\n|\n/);
									var currentCount = currentLines.length;	
									$('#contadorLineas2').text(currentCount+' <?php echo $_records;?>');									
									if (index === (lengthacumulaURLs-1)) {
											var finishedToShow=index+1;
											$('#currentoperation').html("<span class='currentprogress'>All threads finished after batch "+finishedToShow+" </span>");
											for (var i=0; i<timeouts.length; i++) {
											  clearTimeout(timeouts[i]);
											}
											$("#extractlinks").text("START").toggleClass('stop_button');	
											setTimeout(function() { $('#currentoperation').html("<span class='currentprogress'>All threads finished after batch "+finishedToShow+" </span>"); }, 5000);																						 								
											if (typeofexport=="txtfile"){
												var linktoShow="";	
												$('#downloadResult').html('Txt files saved in: <a target="_blank" href="data-extractor/'+linktoShow+'"><i class="fa fa-download" aria-hidden="true"></i> <span>open <b>/data-extractor/</b> folder</span></a>');
											}					
											if (typeofexport=="csvfile") {
												var linktoShow="<?php if (!empty($fileToShow)) echo $fileToShow;?>";
												$('#downloadResult').html('Download link: <a target="_blank" href="data-extractor/'+linktoShow+'"><i class="fa fa-download" aria-hidden="true"></i> <span>'+linktoShow+'</span></a>');
											}
											if (typeofexport=="xlsfile") {
												var linktoShow="<?php if (!empty($fileToShowXLS)) echo $fileToShowXLS;?>";
												$('#downloadResult').html('Download link: <a target="_blank" href="data-extractor/'+linktoShow+'"><i class="fa fa-download" aria-hidden="true"></i> <span>'+linktoShow+'</span></a>');
											}
											if (linksParaExtraer.indexOf("template-") >= 0) {
												var modal = document.getElementById("myModal");
												modal.style.display = "block";		
											}
									}									
								}								
							}
						});
					},<?php echo ($batchDelaySeconds*1000);?>*index)
				);												 	
			});
		}
		if (textoBoton=="STOP"){
			cancel = true;				
			for (var i=0; i<timeouts.length; i++) {
				clearTimeout(timeouts[i]);
			}
			setTimeout(function() { $('#currentoperation').html("<span class='currentprogress'>Stopped</span>"); }, 5000);									
		}		
		$(this).text(function (_, text) {
			return text === 'STOP' ? 'START' : 'STOP';
		}).toggleClass('stop_button');		
	});	
	
	
// Get the modal
var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];
span.onclick = function() {
  modal.style.display = "none";
}


// Get the modal
var modalWelcome = document.getElementById("welcomeModal");
var spanWelcome = document.getElementById("closeWelcome");
spanWelcome.onclick = function() {
  modalWelcome.style.display = "none";
}



// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if ((event.target == modal)||(event.target == modalWelcome)) {
    modal.style.display = "none";
	modalWelcome.style.display = "none";
  }
}	
});

</script>

<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
	<img src="https://netgrows.com/phpseo/PHPSEO.png">
	<span id="downloadResult">	
		<p>Download link: <a href="phpseoimages.zip"><i class="fa fa-download" aria-hidden="true"></i> <span> phpseoimages.zip</span></a></p>
	</span>
  </div>
</div>


<div id="welcomeModal" class="modal">
  <div class="modal-content" style="background: #3f3f3f; color: white;">
    <span id="closeWelcome" class="close">&times;</span>
	<img src="https://netgrows.com/phpseo/PHPSEO.png" style="max-width:100%;">
	
	

	
<?php if ($langSelected=="es") { ?>	
	<h2>BIENVENIDO A PHPSEO</h2>				
	<p>Los módulos actuales de PHPSEO 1.0BETA se pueden pagar con AMOR ❤️</p>
	<p style="font-size: 24px;">Paga con amor suscribiéndote a <a target="_blank" href="https://www.youtube.com/c/NetgrowsES">YouTube</a>, siguiéndome en <a target="_blank" href="https://twitter.com/netgrows">Twitter</a> o compartiendo PHPSEO.</p>
	<p>También te puedes suscribir a la newsletter para recibir actualizaciones y cosas chulas (soy demasiado vago para enviarte spam).</p>
	<!-- ES list-->
	<form action="https://mailing.netgrows.com/subscribe" method="POST" accept-charset="utf-8" target="_blank">
		<div class='subinput'><input type="email" required name="email" id="email" placeholder="Email"/></div>
		<div class='subinput'><input type="checkbox" required name="gdpr" value="check" id="gdpr" /> <span> Doy consentimiento a Netgrows para enviarme emails con actualizaciones, noticias o promociones y acepto la <a target='_blank' href='https://netgrows.com/es/terminos-de-uso/#privacidad'>política de privacidad</a></span> </div>
			<div style="display:none;">
			<label for="hp">HP</label><br/>
			<input type="text" name="hp" id="hp"/>
			</div>
		<input type="hidden" name="list" value="BzRV6mVSp9ObI6DHeVvvHQ"/>
		<input type="hidden" name="subform" value="yes"/>
		<div class='subinput'><input type="submit" class="buttonz" name="submit" id="submit" value="ENVIAR"/> </div>
	</form>	
<?php } else {?>
	<h2>WELCOME TO PHPSEO</h2>				
	<p>The current PHPSEO 1.0BETA modules can be paid with LOVE ❤️</p>
	<p style="font-size: 24px;">Show me some love by subscribing to <a target="_blank" href="https://www.youtube.com/c/NetgrowsEnglish">YouTube</a> or following me in <a target="_blank" href="https://twitter.com/netgrowsEN">Twitter</a>.</p>
	<p>You can also subscribe to the newsletter below to receive updates and cool things (I'm too lazy to spam you).</p>
	<!-- EN list-->
	<form action="https://mailing.netgrows.com/subscribe" method="POST" accept-charset="utf-8" target="_blank">
		<div class='subinput'><input type="email" required name="email" id="email" placeholder="Email"/></div>
		<div class='subinput'><input type="checkbox" required name="gdpr" value="check" id="gdpr" /> <span> I give my consent to Netgrows to be in touch with me via email for the purpose of news, updates and marketing. I also agree to the <a target='_blank' href='https://netgrows.com/terms-of-use/#privacidad'>privacy policy</a></span> </div>
			<div style="display:none;">
			<label for="hp">HP</label><br/>
			<input type="text" name="hp" id="hp"/>
			</div>
		<input type="hidden" name="list" value="g7638925qPFtu763RAo4VACUEJ763Q"/>
		<input type="hidden" name="subform" value="yes"/>
		<div class='subinput'><input type="submit" class="buttonz" name="submit" id="submit" value="SUBMIT"/> </div>
	</form>	
<?php }?>




	<!-- EN list-->
<!--	
	<form action="https://mailing.netgrows.com/subscribe" method="POST" accept-charset="utf-8" target="_blank">
		<div class='subinput'><input type="email" name="email" id="email" placeholder="Email"/></div>
		<div class='subinput'><input type="checkbox" required name="gdpr" value="check" id="gdpr" /> <span> I give my consent to Netgrows to be in touch with me via email for the purpose of news, updates and marketing and I agree to the <a target='_blank' href='https://netgrows.com/terms-of-use/'>privacy policy</span></a> </div>
			<div style="display:none;">
			<label for="hp">HP</label><br/>
			<input type="text" name="hp" id="hp"/>
			</div>
		<input type="hidden" name="list" value="g7638925qPFtu763RAo4VACUEJ763Q"/>
		<input type="hidden" name="subform" value="yes"/>
		<div class='subinput'><input type="submit" class="buttonz" name="submit" id="submit" value="OK, SUB ME!"/> </div>
	</form>	
-->



	<!-- EN list-->	
	<!--
	<form action="https://mailing.netgrows.com/subscribe" method="POST" accept-charset="utf-8" target="_blank">
		<div class='subinput'><input type="email" name="email" id="email" placeholder="Email"/></div>
		<div class='subinput'><input type="checkbox" required name="checkbox" value="check" id="agree" /> <span>I agree to the <a target='_blank' href='https://netgrows.com/terms-of-use/'>privacy policy</span></a></div>
			<div style="display:none;">
			<label for="hp">HP</label><br/>
			<input type="text" name="hp" id="hp"/>
			</div>
		<input type="hidden" name="list" value="BzRV6mVSp9ObI6DHeVvvHQ"/>
		<input type="hidden" name="subform" value="yes"/>
		<div class='subinput'><input type="submit" class="buttonz" name="submit" id="submit" value="OK, SUB ME!"/> </div>
	</form>	
	-->
	
  </div>
</div>

<?php
/*Fin maincontent*/
echo "</div>";
/**********/
/* Debug */
/********/
if (($module!="docs")&&($module!="lastnews")&&($module!="")&&($module!="proxymanager")&&($module!="mainsettings")){
	echo "<div class='debugArea'>";
	echo "DEBUG:<br>";
	echo $debug;
	echo "</div>";
}
/**********/
/* Debug */
/********/
?>
<script language="javascript" type="text/javascript">
  window.onload = function(){
	   var div = $('#loading');
	   div.fadeToggle(500); 
  }
  $("#busca1").on("submit", function(){
	   document.getElementById("loading").style.display = "block";
	});
  $("#busca2").on("submit", function(){
	   document.getElementById("loading").style.display = "block";
	});
$("#borradupes").click(function(){
	var data = $('#txtArea').val();
    var result = data.split(/\s/g).filter((word, i, arr) => arr.indexOf(word) === i);
    $('#txtArea').val(result.join('\n'));
	actualizaLineas();
});
$("#borradupes2").click(function(){
	var data = $('#txtAreaResults').val();
    var result = data.split(/\s/g).filter((word, i, arr) => arr.indexOf(word) === i);
    $('#txtAreaResults').val(result.join('\n'));
	actualizaLineas();
});
$("#borradupeDomains").click(function(){
	var urls = $('#txtAreaResults').val().split("\n");
	function extractDomain(data) {
	  var    a      = document.createElement('a');
			 a.href = data;
	  return a.hostname;
	}
	var domains = {};
	var uniqueUrls = urls.filter(function(url) {
	  // whatever function you're using to parse URLs
	  var domain = extractDomain(url);
	  if (domains[domain]) {
		// we have seen this domain before, so ignore the URL
		return false;
	  }
	  // mark domain, retain URL
	  domains[domain] = true;
	  return true;
	});
	$('#txtAreaResults').val(uniqueUrls.join('\n'));
	actualizaLineas();
});
$('#touppercase').click(function() {
   var upperString = $('#txtArea').val().toUpperCase();
   $('#txtArea').val(upperString);
});
$('#tolowercase').click(function() {
   var lowerString = $('#txtArea').val().toLowerCase();
   $('#txtArea').val(lowerString);
});
function ucfirst(str) {
    var firstLetter = str.substr(0, 1);
    return firstLetter.toUpperCase() + str.substr(1);
}
function ucwords(str,force){
  str=force ? str.toLowerCase() : str;  
  return str.replace(/(\b)([a-zA-Z])/g,
           function(firstLetter){
              return   firstLetter.toUpperCase();
           });
}
$('#toupperfirst').click(function() {	
	var valorAcumulado1="";
	var arrayOfLines = $('#txtArea').val().split('\n');
	var length = arrayOfLines.length;
    $.each(arrayOfLines, function(index, item) {
		if (valorAcumulado1 === ''){ valorAcumulado1=ucfirst(item.toLowerCase())+"\n"; }
		else { 
			if (index === (length - 1)) {
              //console.log('Last field, submit form here');
			  valorAcumulado1=valorAcumulado1+ucfirst(item.toLowerCase()); 
			} else valorAcumulado1=valorAcumulado1+ucfirst(item.toLowerCase())+"\n"; 
		}		
    });	
   $('#txtArea').val(valorAcumulado1);
});
$('#toupperall').click(function() {	
	var valorAcumulado2="";
	var arrayOfLines = $('#txtArea').val().split('\n');
	var length = arrayOfLines.length;	
    $.each(arrayOfLines, function(index, item) {
		if (valorAcumulado2 === ''){ valorAcumulado2=ucwords(item.toLowerCase())+"\n"; }
		else { 
			if (index === (length - 1)) {
              //console.log('Last field, submit form here');
			  valorAcumulado2=valorAcumulado2+ucwords(item.toLowerCase()); 
			} else valorAcumulado2=valorAcumulado2+ucwords(item.toLowerCase())+"\n"; 
		}			
    });	
   $('#txtArea').val(valorAcumulado2);
});
function actualizaLineas(){	
	if ($("#txtArea").length ) {
		var text = $("#txtArea").val();   
		var lines = text.split(/\r|\r\n|\n/);
		var count = lines.length;	
		$('.contadorLineas').text(count+' <?php echo $_records;?>');
	}	
	if ($("#txtAreaResults").length ) {
		var text2 = $("#txtAreaResults").val();   
		var lines2 = text2.split(/\r|\r\n|\n/);
		var count2 = lines2.length;	
		$('#contadorLineas2').text(count2+' <?php echo $_records;?>');	
	}
	if ($("#templateListUpdate").length ) {
		var text3 = $("#templateListUpdate").val();   
		var lines3 = text3.split(/\r|\r\n|\n/);
		var count3 = lines3.length;	
		$('#contadorLineas3').text(count3+' <?php echo $_records;?>');	
	}
}
</script>
<script type='text/javascript'>
function clearTextarea(id) {
	event.preventDefault();
	$("#"+id).val(""); 
	actualizaLineas();
	return false;
}
function selectTextarea(id) {
	event.preventDefault();
	$("#"+id).focus();
	$("#"+id).select();
	return false;
}
function escapeRegExp(string) {
  return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
}
function removenotcontaining(id) {
	event.preventDefault();
	input = prompt('Remove all lines NOT containing:');	
    if (input === null) {
        return;
    }	
	input=input.toLowerCase();
	var valorAcumulado="";
	var arrayOfLines =  $("#"+id).val().split('\n');
	var length = arrayOfLines.length;	
    $.each(arrayOfLines, function(index, item) {		
		 if (item.toLowerCase().match(escapeRegExp(input))) valorAcumulado=valorAcumulado+(item.toLowerCase())+"\n";		
    });	
    $("#"+id).val(valorAcumulado);
	actualizaLineas();
}
function removecontaining(id) {	
	event.preventDefault();
	input = prompt('Remove all lines containing:');
    if (input === null) {
        return;
    }
	input=input.toLowerCase();	
	var valorAcumulado="";
	var arrayOfLines =  $("#"+id).val().split('\n');
	var length = arrayOfLines.length;	
    $.each(arrayOfLines, function(index, item) {		
		 if (item.toLowerCase().match(escapeRegExp(input))) {}
		else valorAcumulado=valorAcumulado+(item.toLowerCase())+"\n";		 
    });	
    $("#"+id).val(valorAcumulado);
	actualizaLineas();
}
function doDL(str) {
	event.preventDefault();
	function dataUrl(data) {
	return "data:x-application/xml;charset=utf-8," + escape(data);
	}
	var downloadLink = document.createElement("a");
	downloadLink.href = dataUrl(str);
	downloadLink.download = "urls-phpseo.txt";
	document.body.appendChild(downloadLink);
	downloadLink.click();
	document.body.removeChild(downloadLink);
}
function doDLindexed(str) {
	event.preventDefault();
	function dataUrl(data) {
	return "data:x-application/xml;charset=utf-8," + escape(data);
	}
	var downloadLink = document.createElement("a");
	str = str.replace(/	/g, ';');
	downloadLink.href = dataUrl(str);
	downloadLink.download = "indexed-checker-phpseo.csv";
	document.body.appendChild(downloadLink);
	downloadLink.click();
	document.body.removeChild(downloadLink);
}
</script>


<SCRIPT Language="javascript">

	var cntline;
	
	function keyup(obj, e)
	{
		if(e.keyCode >= 33 && e.keyCode <= 40) // arrows ; home ; end ; page up/down
			selectionchanged(obj, e.keyCode);
	}
	
	function selectionchanged(obj)
	{
		var substr = obj.value.substring(0,obj.selectionStart).split('\n');
		var row = substr.length;
		var col = substr[substr.length-1].length;
		var tmpstr = '(' + row.toString() + ',' + col.toString() + ')';
		// if selection spans over 
		if(obj.selectionStart != obj.selectionEnd)
		{
			substr = obj.value.substring(obj.selectionStart, obj.selectionEnd).split('\n');
			row += substr.length - 1;
			col = substr[substr.length-1].length;
			tmpstr += ' - (' + row.toString() + ',' + col.toString() + ')';
		}
		//obj.parentElement.getElementsByTagName('input')[0].value = tmpstr;
	}
	
	function input_changed(obj_txt)
	{
		obj_rownr = obj_txt.parentElement.parentElement.getElementsByTagName('textarea')[0];
		cntline = count_lines(obj_txt.value);
		if(cntline == 0) cntline = 1;
		tmp_arr = obj_rownr.value.split('\n');
		cntline_old = parseInt(tmp_arr[tmp_arr.length - 1], 10);
		// if there was a change in line count
		if(cntline != cntline_old)
		{
			obj_rownr.cols = cntline.toString().length; // new width of txt_rownr
			populate_rownr(obj_rownr, cntline);
			scroll_changed(obj_txt);
		}
		selectionchanged(obj_txt);
	}
	
	function scroll_changed(obj_txt)
	{
		obj_rownr = obj_txt.parentElement.parentElement.getElementsByTagName('textarea')[0];
		scrollsync(obj_txt,obj_rownr);
	}
	
	function scrollsync(obj1, obj2)
	{
		// scroll text in object id1 the same as object id2
		obj2.scrollTop = obj1.scrollTop;
	}
	
	function populate_rownr(obj, cntline)
	{
		tmpstr = '';
		for(i = 1; i <= cntline; i++)
		{
			tmpstr = tmpstr + i.toString() + '\n';
		}
		obj.value = tmpstr;
	}
	
	function count_lines(txt)
	{
		if(txt == '')
		{
			return 1;
		}
		return txt.split('\n').length + 1;
	}
	
</SCRIPT>


<script>
$(document).ready(function(){
		var dialogShown = Cookies.get("dialogShown");
		$('#downloadResult').html('Download link: <a href="phpseoimages.zip"><i class="fa fa-download" aria-hidden="true"></i> <span>phpseoimages.zip</span></a>');
		var modalWelcome = document.getElementById("welcomeModal");
		modalWelcome.style.display = "block";					
		if (!dialogShown) {		
			$(window).on("load",function(){	
				Cookies.set('dialogShown', 1, { expires: 365 });
			});
		}
		else {
			$("#dialog1").hide();
			modalWelcome.style.display = "none";
		}			
			
// Create a new jQuery.Event object with specified event properties.
var e = jQuery.Event( "keydown", { keyCode: 16 } );
// trigger an artificial keydown event with keyCode 16
$('#txtArea').trigger('input');		
$('#txtAreaResults').trigger('input');
$('#templateListUpdate').trigger('input');
	
//Hide image processing results onload
$('#success_div').hide();
	
function load_unseen_notification(view = '') {
 $.ajax({
  url: "<?php echo basename(__FILE__);?>?module=getnotifications",
  method:"POST",
  data:{view:view},
  dataType:"json",
  success:function(data){
	  if(data.unseen_notification > 0)	{
		  $('.notification-counter').html(data.unseen_notification); 
		  var seenNotifications = Cookies.get('seenNotifications');
		  if(seenNotifications === ''){ seenNotifications = 0; }
		  if(seenNotifications === undefined){ seenNotifications = 0; }
		  if(seenNotifications === null){ seenNotifications = 0; }		  
		  var totaltoshow= (data.unseen_notification)-(seenNotifications);	  
		  if (totaltoshow==0) {
			  totaltoshow="";	
			  $('.notification-counter').css("padding","0px");			  
		  }
		  $('.notification-counter').html(totaltoshow); 
	  }
  }
 });
}
load_unseen_notification();
});
</script>

<?php	
//Fixes closing form from webproxies header
if (($module=="docs")||($module=="lastnews")){
	echo "</form>";
}
?>

<script>
(function() {
	$('#imageform').ajaxForm({
		beforeSubmit: function() {	
			count = 0;
			val = $.trim( $('#images').val() );
			console.log(val);			
			if( val == '' ){
				count= 1;
				$( "#images" ).next('span').html( "Please select your images" );
			}			
			if(count == 0){
				for (var i = 0; i < $('#images').get(0).files.length; ++i) {
			    	img = $('#images').get(0).files[i].name;
			    	var extension = img.split('.').pop().toUpperCase();
			    	if(extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
			    		count= count+ 1
			    	}
			    }
				if( count> 0) $( "#images" ).next('span').html( "Please select valid images" );
			} 
		    if( count> 0){
		    	return false;
		    } else {
		    	$( "#images" ).next('span').html( "" );
		    }	 
	    },
		
		beforeSend:function(){
		   $('#loader').show();
		   $('#image_upload').hide();
		},
	    success: function(msg) {
			/*console.log("success");*/
	    },
		complete: function(xhr) {
			$('#loader').hide();
			$('#image_upload').show();
			$('#success_div').show();					
			$('#images').val('');
			$('#error_div').html('');
			result = xhr.responseText;
			result = result.trim(); 
			result = JSON.parse(result);
			base_path = $('#base_path').val();
			$.each(result, function(index, value){
				if( value.success ){
					//name = base_path+'./images/'+value.success;
					name = './images/'+value.success;
					html = '';
					var extension = (value.success.substring(value.success.indexOf(".")+1));
					html+= '<div class="containImageSmall"><a title="'+value.success+'" href="'+name+'" target="_blank"><image src="'+name+'"></a><span style="color: #4fea58;" class="imageSizeTextBelow">'+extension+'</span></div>';
					$('#success_div').append( html );					
					var txt = $("#txtAreaResultsImages");
					$('#txtAreaResultsImages').val( txt.val() + name + "\n");
				} else if( value.error ){
					error = value.error
					html = '';
					html+='<p>'+error+'</p>';
					$('#uploaded_images #error_div').append( html );
				}				
			});
			$('#error_div').delay(5000).fadeOut('slow');
		}
	}); 	
})(); 
</script>
</body>
</html>
<?php
?>