<?php 
include('cfg/core.php');

include('cfg/BrowserDetection.php');
$browser = new Wolfcast\BrowserDetection();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title><?php echo $website->title; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="index, follow">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!-- Styles -->
	<link rel="stylesheet" href="<?php echo url(); ?>/css/common.css?v=1.6.2"> <!-- mudar --->
	<!-- Scripts -->	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?php echo url(); ?>/js/jquery.hoverIntent.min.js"></script>
    <script src="<?php echo url(); ?>/js/extra.js?v=1.3"></script>
	<script src="<?php echo url(); ?>/js/common.js?v=1.5.1"></script>  <!-- mudar --->
    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo url(); ?>/apple-touch-icon.png<?php echo $website->iconVersion; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo url(); ?>/favicon-32x32.png<?php echo $website->iconVersion; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo url(); ?>/favicon-16x16.png<?php echo $website->iconVersion; ?>">
    <link rel="manifest" href="<?php echo url(); ?>/site.webmanifest<?php echo $website->iconVersion; ?>">
    <link rel="mask-icon" href="<?php echo url(); ?>/safari-pinned-tab.svg<?php echo $website->iconVersion; ?>" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
	<!-- Open Graph-->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $website->title; ?>">
    <meta property="og:description" content="<?php echo $website->description; ?>">
    <meta property="og:url" content="<?php echo url() ?>">
    <meta property="og:site_name" content="<?php echo $website->title; ?>">
    <meta property="og:image" content="<?php echo $website->image; ?>">
    <meta property="og:image:width" content="1654">
    <meta property="og:image:height" content="612">
    <!-- Facebook -->
    <?php
        if($fb->page){
            print '<meta property="fb:page_id" content="'.$fb->page.'" />';
        }
        if($fb->app){
            print '<meta property="fb:app_id" content="'.$fb->app.'" />';
        }
    ?>

    <!-- Management -->
    <?php
        if($mkt->gSearchConsole){
            print $mkt->gSearchConsole;
        }
        if($mkt->bWebmaster){
            print $mkt->bWebmaster;
        }
    ?>
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $website->title; ?>">
    <meta name="twitter:description" content="<?php echo $website->description; ?>">
    <meta name="twitter:url" content="<?php echo url(); ?>">
    <!-- Other tags -->
    <meta name="description" content="<?php echo $website->description; ?>">
    <meta name="author" content="<?php echo $website->author; ?>">
    <meta name="keywords" content="<?php echo $website->keywords; ?>"/>
    <!-- Location -->
    <?php
    if($location->stAddress){
        echo '<meta name="og:street-address" content="'.$location->stAddress.'"/>';
    }
    if($location->locality){
        echo '<meta name="og:locality" content="'.$location->locality.'"/>';
    }
    if($location->region){
        echo '<meta name="og:region" content="'.$location->region.'"/>';
    }
    if($location->postalCode){
        echo '<meta name="og:postal-code" content="'.$location->postalCode.'"/>';
    }
    if($location->country){
        echo '<meta name="og:country-name" content="'.$location->country.'"/>';
    }
    ?>

    <!-- Info -->
    <?php
    if($email->address){
        echo '<meta name="og:email" content="'.$email->address.'"/>';
    }
    if($location->phoneNumber){
        echo '<meta name="og:phone_number" content="'.$location->phoneNumber.'"/>';
    }
    if($mkt->fPixel){
        echo $mkt->fPixel;
    }
    ?>

    <noscript><p class="center popup popup-red">Oh não! Um erro ocorreu, para viualizar melhor está página é necessário ativar o javascript. Saiba mais <a href="https://www.enable-javascript.com/pt/" target="_blank">clicando aqui</a>.</p></noscript>
</head>
<body>
<main>
<div class="hm_menu" id="hm_menu" aria-controls="hm_menu_items" aria-expanded="false" onclick="toggleMenu(this);">
    <div class="bar1"></div>
    <div class="bar2"></div>
    <div class="bar3"></div>
</div>

<nav name="menu" id="hm_menu_items" aria-expanded="false">
    <ul>
        <a href="#intro"><li>HOME</li></a>
        <a href="#oquefazemos"><li>O QUE FAZEMOS</li></a>
        <a href="#oquesomos"><li>O QUE SOMOS</li></a>
        <a href="#comofazemos"><li>COMO FAZEMOS</li></a>
        <a href="#clientes"><li>NOSSOS CLIENTES</li></a>
        <a href="#contato"><li>CONTATO</li></a>
    </ul>

    <div class="social-icons">
        <!-- Font Awesome Icons -->
        <?php
            echo $social_link_instagram;
            echo $social_link_facebook;
            echo $social_link_twitter;
            echo $social_link_linkedin;
        ?>
    </div>
</nav>

<?php
    $introT = array("<p>Satisfazer os clientes não é mais o suficiente:</p><p>é preciso encantá-los</p>", "<p>Existimos para comunicar, desenvolver conexões</p>
         <p>e fomentar negócios.</p>");
    shuffle($introT);

    if(isset($_GET['success'])){
        echo '<div class="popup popup-green pill-notification" id="successEmailNotification">Sucesso! O seu formulário foi enviado :) <span id="closeEmailNotification">&times;</span></div>';
    }
?>
<section id="intro">
    <img src="images/logo.png" class="logo_a" id="logo">
    <i class="subir fas fa-angle-double-up" id="backTop" title="Voltar ao topo"></i>
    <div class="intro-text"><?php echo $introT[0]; ?></div>
</section>
<section id="oquefazemos">
    <h1>O QUE FAZEMOS?</h1>
    <div class="row">
        <div class="col">
            <i class="fas fa-briefcase"></i>
            <p>Consultoria de redes sociais.</p>
        </div>
        <div class="col">
            <i class="fas fa-thumbs-up"></i>
            <p>Inserção no mercado digital, com gestão de mídias.</p>
        </div>
        <div class="col">
            <i class="fas fa-lightbulb"></i>
            <p>Criação de identidade visual e comunicação online/offline.</p>
        </div>
        <div class="col">
            <i class="fas fa-comments"></i>
            <p>SAC 2.0</p>
            <p style="padding: 0;">Estrutura de atendimento ao cliente via redes sociais.</p>
        </div>
        <div class="col">
            <i class="fas fa-hand-holding-usd"></i>
            <p>Anúncios em mídia online</p>
        </div>
    </div>
</section>

<section id="oquesomos">
    <h1>O QUE SOMOS?</h1>
    <p>Nascemos com o objetivo de gerar <span class="destaque">lembranças</span>, <span class="destaque">emoções</span> e <span class="destaque">conversões</span>. Apaixonados por marcas e gastronomia, a <span class="destaque">Agência Alimento</span> entrou de cabeça no marketing gastronômico, com uma visão atualizada e inovadora no mercado, de forma que nossos clientes possam contar com as <span class="destaque">melhores estratégias</span> para alcançar seu melhor público.</p>
    <br>
    <p>Acreditamos em um mundo em que alimentação é tratada como prioridade, e nossa missão é levar à sério esse tema. <span class="destaque">Marketing gastronômico é a oportunidadede informar, educar e mostrar o melhor aos consumidores</span>. Valorizamos nossa terra, as riquezas e grandezas da gastronomia brasileira, estamos aqui para ajudar a enaltecê-la.</p>
</section>

<section id="comofazemos">
    <h1>COMO FAZEMOS?</h1>
    <div class="row">
        <div class="col"  aria-hidden="false" id="comofazemosCol0">
            <h3>BRIEFING</h3>
            <p>Reunião inicial para entender a necessidadedo cliente.</p>
        </div>
        <div class="col arrow-right pulse" onclick="nextArrow($(this));"  aria-hidden="false" aria-controls="comofazemosCol1" title="Clique em mim"><i class="fas fa-arrow-right"></i></div>
        <div class="col" aria-hidden="true" id="comofazemosCol1">
            <h3>CONTRATO</h3>
            <p>Após o desenvolvimento do plano estratégico e cronograma deações, reunião para apresentar o projeto e assinatura do contrato de prestação de serviços.</p>
        </div>
        <div class="col arrow-right" onclick="nextArrow($(this));" aria-hidden="true" aria-controls="comofazemosCol2"><i class="fas fa-arrow-right"></i></div>
        <div class="col" aria-hidden="true" id="comofazemosCol2">
            <h3>PESQUISA</h3>
            <p>Pesquisa do segmento específico, concorrência e estratégias atuais.</p>
        </div>
        <div class="col arrow-down" onclick="nextArrow($(this));" aria-hidden="true" aria-controls="comofazemosCol3"><i class="fas fa-arrow-down"></i></div>
        <div class="col" aria-hidden="true" id="comofazemosCol3">
            <h3>PLANEJAMENTO</h3>
            <p>Com base no briefing e pesquisa, desenvolvemos um plano para atender seu negócio.</p>
        </div>
        <div class="col arrow-right" onclick="nextArrow($(this));" aria-hidden="true" aria-controls="comofazemosCol4"><i class="fas fa-arrow-right"></i></div>
        <div class="col" aria-hidden="true" id="comofazemosCol4">
            <h3>EXECUÇÃO DOPROJETO</h3>
            <p>A melhor parte do projeto: O momento de colocar as ideias para funcionar.</p>
        </div>
        <div class="col arrow-right" onclick="nextArrow($(this));" aria-hidden="true" aria-controls="comofazemosCol5"><i class="fas fa-arrow-right"></i></div>
        <div class="col" aria-hidden="true" id="comofazemosCol5">
            <h3>MENSURAÇÃO DE RESULTADOS</h3>
            <p>Hora de colocar na mesaos resultados do projeto em andamento, com feedbacks semanais, ou quinzenais.</p>
        </div>
</div>
</section>

<section id="clientes">
    <h1>NOSSOS CLIENTES</h1>
    <?php
        if($browser->isMobile()){

            $sql = "SELECT * FROM clientes WHERE cliente_active = 1";
            if($result = mysqli_query($conn->link, $sql)){
                while($row = mysqli_fetch_array($result)){
                    $clientesList[] = $row;
                }
                $clientesQtd = mysqli_num_rows($result);

                if($clientesQtd){
                    echo '<div class="row">';
                    for($i=0;$i<$clientesQtd;$i++){
                        if($clientesList[$i][4]){
                            echo '<a href="'.$clientesList[$i][4].'" target="_blank"><div class="col" title="'.$clientesList[$i][1].'"><div style="background-image:url(\'./uploads/logo/'.$clientesList[$i][2].'\');" class="logo-cover"> </div></div></a>';
                        }else{
                            echo '<div class="col" title="'.$clientesList[$i][1].'"><div style="background-image:url(\'./uploads/logo/'.$clientesList[$i][2].'\');" class="logo-cover"> </div></div>';
                        }
                    }
                    echo '</div>';
                } else{
                    echo 'Não há parceiros cadastrados no momento.';
                }
            }

        } else{
            $conn->link = $conn->connect();
            /* Destaque */

            $sql = "SELECT * FROM clientes WHERE cliente_active = 1 AND cliente_id = 6";
            if($result = mysqli_query($conn->link, $sql)){
                $clienteDestaque = mysqli_fetch_array($result);
                $destaqueExists = mysqli_num_rows($result);

                if($destaqueExists){
                    echo '<div class="row destaque">';
                            echo '
                            <a href="'.$clienteDestaque[4].'" target="_blank">
                                <div class="col" title="'.$clienteDestaque[1].'">
                                    <div class="content">
                                    <table>
                                    <tr>
                                        <td><div style="background-image:url(\'./uploads/logo/'.$clienteDestaque[2].'\');" class="logo-cover"> </div></td>
                                        <td><div class="destaqueText">
                                            <div class="headerText">NOVAS EXPERIÊNCIAS EM CONSUMIR PÃES, CONHEÇA A NATURAL BAKERY BRASIL</div>
                                            <div class="subText">
                                                '.$clienteDestaque[3].'
                                            </div>
                                            <div class="subLink">'.$clienteDestaque[4].'</div>
                                        </div></td>
                                        </tr>
                                    </table>
                                    </div>
                                </div></a>
                            ';
                    echo '</div>';
                } else{
                    echo 'Não há parceiros cadastrados no momento.';
                }
            }

            /* Outros Parceiros */

            $sql = "SELECT * FROM clientes WHERE cliente_active = 1 AND cliente_id != 6";
            if($result = mysqli_query($conn->link, $sql)){
                while($row = mysqli_fetch_array($result)){
                    $clientesList[] = $row;
                }
                $clientesQtd = mysqli_num_rows($result);

                if($clientesQtd){
                    echo '<div class="row">';
                    for($i=0;$i<$clientesQtd;$i++){
                            echo '<div class="col" title="'.$clientesList[$i][1].'" data-title="'.$clientesList[$i][1].'" data-subtext="'.$clientesList[$i][3].'" data-showlink="" data-link="'.$clientesList[$i][4].'" onclick="exchangeDestaque(this);"><div style="background-image:url(\'./uploads/logo/'.$clientesList[$i][2].'\');" class="logo-cover"> </div></div>';
                    }
                    echo '</div>';
                } else{
                    echo 'Não há parceiros cadastrados no momento.';
                }
            }
        }
    ?>
</section>

<section id="contato">
    <h1>CONTATO</h1>
    <div class="description">Entre em contato com a gente! Você pode preencher o formulário abaixo, nos mandar um <a href="mailto:<?php echo $email->address; ?>" target="_blank" title="contato@alimentoag.com.br" style="font-weight: bold;">email</a> ou chamar no <a href="https://wa.me/<?php echo preg_replace('/[^a-zA-Z0-9\']/', '', $location->phoneNumber); ?>" target="_blank" title="<?php echo $location->phoneNumber; ?>" style="font-weight: bold;">Whatsapp</a>.
        <p>Email: <a href="mailto:<?php echo $email->address; ?>" target="_blank" title="<?php echo $email->address; ?>"><?php echo $email->address; ?></a></p>
        <p>Whatsapp: <a href="https://wa.me/<?php echo preg_replace('/[^a-zA-Z0-9\']/', '', $location->phoneNumber); ?>" target="_blank" title="<?php echo $location->phoneNumber; ?>"><?php echo $location->phoneNumber; ?></a></p></div>
    <form action="#" method="POST" id="formContato">
        <div class="form-group">
            <div class="row">
                <div class="col">Nome Completo: <input type="text" name="Fname" required></div>
                <div class="col">Email: <input type="email" name="email" required></div>
            </div>
        </div>
        <div class="form-group">
            Precisa acrescentar alguma informação? Digite abaixo <span class="text-muted">(opcional)</span>
            <textarea class="fontb" name="message" placeholder="Escreva aqui..."></textarea>
        </div>
        <button class="button" name="SubmitButton"><span>Enviar</span></button>
    </form>
</section>
<?php
if(isset($_POST['SubmitButton'])){
    $name = ($_POST['Fname']);
    $from = ($_POST['email']);
    $messageform = ($_POST['message']);

    $messagePlainText = $name. 'entrou em contato:';
    $messagePlainText2 = 'Nome: '.$name.' | Email: '.$from.' | Mensagem: '.$messageform;

    //Email para o website
    $formText = "Hello ".$email->name.",\nThis is a text email, the text/plain version, because your device does not support the original HTML version..\\n\n ".$messagePlainText." \n ".$messagePlainText2;

    $formHTML = '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
     <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>'.$website->title.': Formulário de Contato</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body style="margin: 0;padding: 0;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc;">
                        <tr>
                            <td align="center" style="padding: 0 0 0 0">
                                <img src="https://alimentoag.com.br/images/AAG_cover.png" width="600" height="230" alt="Cover Image" style="object-fit:cover;"/>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#FFFFFF" style="padding: 40px 30px 40px 30px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td>
                                            <b>Nome:</b> '.$name.'
                                        </td>
                                        <td>
                                            <b>Email:</b> '.$from.'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Mensagem:</b> '.$messageform.'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#202125" style="padding: 15px 15px 15px 15px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="75%" style="color:#FFFFFF;text-align:center;">
                                            alimentoag.com.br &reg; 2020.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>    
    </body>
    </html>';

    $mail = new PHPMailer(true);                                // Passing `true` enables exceptions

    try {
        //Server settings
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;                                   // Enable verbose debug output (2)
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = $email->host;                             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                // Enable SMTP authentication (false on GoDaddy)
        //$mail->SMTPAutoTLS = false;                             // (false on GoDaddy)
        $mail->Username = $email->username;                     // SMTP username
        $mail->Password = $email->password;                     // SMTP password
        $mail->SMTPSecure = $email->encryption;              // Enable TLS encryption, `ssl` also accepted (disabled on GoDaddy)
        $mail->Port = $email->port;                             // TCP port to connect to

        //Recipients
        $mail->setFrom($email->replyTo);            //Use ALWAYS the email that is sending
        $mail->addAddress($email->address);                     // Name is optional
        $mail->addReplyTo($from);

        //Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $website->title.' - Formulário de Contato: '.$name;
        $mail->Body    = $formHTML;
        $mail->AltBody = $formText;

        if($mail->send()){
            echo '<p>Caso não encontre o email, verifique sua caixa de Spam!</p>';
            //echo '<script type="text/javascript">window.location.href="?success"</script>';
        }
    } catch (Exception $e) {
        echo '<p>Pode haver um problema com o servidor de email, tente novamente em um momento..</p><br />';
        echo '<span class="text-muted">Message could not be sent. Mailer Error: '. $mail->ErrorInfo .'</span>';
    }

}
?>
</main>
<?php include('footer.php'); ?>
</body>
</html>