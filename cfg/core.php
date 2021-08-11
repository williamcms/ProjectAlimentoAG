<?php 
header('Content-type: text/html; charset=utf-8');
//Login Messages
define('ERROR_LOGIN_BLANK', 'Os campos do formulário não podem ficar em branco.');
define('ERROR_LOGIN_DENIED', 'Você não tem permissão para fazer login.<br>Se acha que isto é um erro, contate o administrador do site.');
define('ERROR_LOGIN_USERNAME', 'Erro! Usuário incorreto.');
define('ERROR_LOGIN_PASSWORD', 'Erro! Senha incorreta.');
define('ERROR_ACCESS_DENIED', 'Você não possui permissão para acessar esta área,<br>permissão elevada necessária.');
define('LOGIN_SUCCESS', 'Sucesso! Você será redirecionado em alguns instantes.');
define('LOGIN_SUCCESS_SESSION', 'Parece que você já esta logado, vamos te redirecionar em alguns instantes.');
//Registration Messages
define('INVALID_USERNAME', 'Erro! Seu nome de usuário não é válido.');
define('INVALID_PASSWORD', 'Erro! Sua senha não é válida.');
define('USERNAME_EXISTS', 'Erro! O nome de usuário que você escolheu já existe.');
//Database Messages
define('ERROR_QUERY_NORESULT', 'Parece que não há nada por aqui!');
define('ERROR_QUERY_NORESULT_PARAMS', 'Parece que não há nada por aqui, tente mudar os termos da busca!');
define('ERROR_DATABASE_ACCESS_DENIED', 'Erro! Você não possui permissões para realizar este tipo de operação.');

if(ini_set('session.name', 'LOGIN_ALIMENTOAG') === false || !session_name('LOGIN_ALIMENTOAG'))
{
    die('Unable to set sesssion scope');
}

session_start();

function url(){
	$server_name = $_SERVER['SERVER_NAME'];
	//$port = ':'.$_SERVER['SERVER_PORT'];
	$port = ''; //Change if necessary

	$n = $_SERVER['SCRIPT_NAME'];
	$arr = explode('/', $n);
	if(count($arr)>2){
		array_splice($arr,0,1);
		array_splice($arr,count($arr)-1);

	    $n = '/'.implode('/', $arr);
	}else{
	    $n = '';
	}

	if (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) {
		$scheme = 'https';
	} else {
		$scheme = 'http';
	}
	return $scheme.'://'.$server_name.$port.$n;
}

class _conn{
	private $host = 'localhost';
	private $username = 'INSERT_MISSING_INFO_HERE';
	private $password = 'INSERT_MISSING_INFO_HERE';
	private $database = 'INSERT_MISSING_INFO_HERE';
	public $link;

	public function getter($var){
		return $this->$var;
	}
	public function connect(){
		error_reporting(E_ERROR | E_PARSE);
		$conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);
		mysqli_set_charset($conn, 'utf8'); //Remove Bug de acentos :3
		// Check connection
		if(mysqli_connect_errno()){
			echo '<div class="popup popup-red">Desculpe, Ocorreu algum erro!<br>';
			echo 'Falha de conexão com a Base de Dados (MySQL): ' . mysqli_connect_error();
			echo '</div>';
		}
		return $conn;
	}
	public function disconnect($link){
		mysqli_close($link);
	}
}

$conn = new _conn;

(function() {
	global $conn;
	$conn->link = $conn->connect();

	function get_result($statement){
	    $RESULT = array();
	    $statement->store_result();
	    for ($i=0; $i<$statement->num_rows; $i++) {
	        $Metadata = $statement->result_metadata();
	        $PARAMS = array();
	        while ($field = $Metadata->fetch_field()){
	            $PARAMS[] = &$RESULT[$i][$field->name];
	        }
	        call_user_func_array( array( $statement, 'bind_result' ), $PARAMS );
	        $statement->fetch();
	    }
	    return $RESULT;
	}
	if($stmt = $conn->link->prepare("SELECT config_name, config_value, config_active FROM website_configs")){
		try{
			$stmt->execute();
			$result = get_result($stmt);

		    foreach($result as $i => $v){
		    	if(empty($v['config_value']) OR $v['config_active'] == 0){
	            	//$websiteContent[$v['config_name']] = '0';
	            	define(strtoupper($v['config_name']), '');
		    	} else{
	            	//$websiteContent[$v['config_name']] = $v['config_value'];
	            	define(strtoupper($v['config_name']), $v['config_value'], true);
		    	}
		    }
		}
		catch(Exception $e){
			throw new Exception('Erro ao conectar com a base de dados: '. $e);
		}
	}
})();

class _website{
	public $title = WEBSITE_TITLE; //og:title
	public $iconVersion = ''; //If you need to update an old version of your icon in browsers that may have cached it ex: ?v=E6myLpg8vn
	public $image = WEBSITE_IMAGE; //og:image
	public $description = WEBSITE_DESCRIPTION; //og:description
	public $keywords = WEBSITE_KEYWORDS; //Site's keywords
	public $author = WEBSITE_AUTHOR; //Site's author
}
class _email{
	public $name = EMAIL_NAME; //Your name or your Site Name
	public $replyTo = EMAIL_REPLYTO; //Email address to reply to your emails
	public $address = EMAIL_ADDRESS; //Email address that will receive the emails
	public $host = 'INSERT_MISSING_INFO_HERE'; //Your SMTP servers (smtpout.secureserver.net) (use localhost on GoDaddy)
	public $username = 'INSERT_MISSING_INFO_HERE';
	public $password = 'INSERT_MISSING_INFO_HERE';
	public $port = '465'; //deafult = 465 (use 25 on GoDaddy)
	public $encryption = 'ssl';
}
class _social{ //Your Social Medias
	public $facebook = SOCIAL_FACEBOOK;
	public $twitter = SOCIAL_TWITTER;
	public $linkedin = SOCIAL_LINKEDIN;
	public $instagram = SOCIAL_INSTAGRAM;
}
class _mkt{ //Keys from your tools
	public $gAnalytics = MKT_GANALYTICS;
	public $gAds = MKT_GADS;
	public $fPixel = MKT_FPIXEL;
	public $gSearchConsole = MKT_GSEARCHCONSOLE;
	public $bWebmaster = MKT_BWEBMASTER;
}

class _fb{ //Keys from your facebook page and/or app
	public $page = FB_PAGE;
	public $app = FB_APP;
}
class _locationDetails{ //Location of your bussines
	public $stAddress = LOCATION_STADDRESS;		//1601 S California Ave
	public $locality = LOCATION_LOCALITY;		//Palo Alto
	public $region = LOCATION_REGION;			//CA
	public $postalCode = LOCATION_POSTALCODE;	//94304
	public $country = LOCATION_COUNTRY;			//USA
	public $phoneNumber = LOCATION_PHONENUMBER;	//+1-650-123-4567
}

$website = new _website;
$social = new _social;
$email = new _email;
$mkt = new _mkt;
$fb = new _fb;
$location = new _locationDetails;

//Social media icons SVG
if(!$social->instagram == ''){
	$social_instagram = '<a href="'.$social->instagram.'" target="_blank"><img src="'.url().'/images/instagram-brands.svg" alt="Instagram" aria-hidden="true"/></a>';
}else{
	$social_instagram = '';
}
if(!$social->facebook == ''){
	$social_facebook = '<a href="'.$social->facebook.'" target="_blank"><img src="'.url().'/images/facebook-square-brands.svg" alt="Facebook" aria-hidden="true"/></a>';
}else{
	$social_facebook = '';
}
if(!$social->twitter == ''){
	$social_twitter = '<a href="'.$social->twitter.'" target="_blank"><img src="'.url().'/images/twitter-brands.svg" alt="Twitter" aria-hidden="true"/></a>';
}else{
	$social_twitter = '';
}
if(!$social->linkedin == ''){
	$social_linkedin = '<a href="'.$social->linkedin.'" target="_blank"><img src="'.url().'/images/linkedin-brands.svg" alt="Linkedin" aria-hidden="true"/></a>';
}else{
	$social_linkedin = '';
}

//Social media icons CSS
if(!$social->instagram == ''){
	$social_link_instagram = '<a href="'.$social->instagram.'" target="_blank" class="fab fa-instagram" aria-hidden="true"></a>';
}else{
	$social_link_instagram = '';
}
if(!$social->facebook == ''){
	$social_link_facebook = '<a href="'.$social->facebook.'" target="_blank" class="fab fa-facebook" aria-hidden="true"></a>';
}else{
	$social_link_facebook = '';
}
if(!$social->twitter == ''){
	$social_link_twitter = '<a href="'.$social->twitter.'" target="_blank" class="fab fa-twitter" aria-hidden="true"></a>';
}else{
	$social_link_twitter = '';
}
if(!$social->linkedin == ''){
	$social_link_linkedin = '<a href="'.$social->linkedin.'" target="_blank" class="fab fa-linkedin" aria-hidden="true"></a>';
}else{
	$social_link_linkedin = '';
}

/*
<?php include("cfg/core.php"); ?>

$conn->link = $conn->connect();

$stmt->close();
$conn->disconnect($conn->link);

$conn->getter(var);

<?php
	echo $social_link_instagram;
	echo $social_link_facebook;
	echo $social_link_twitter;
	echo $social_link_linkedin;
?>

*/

?>