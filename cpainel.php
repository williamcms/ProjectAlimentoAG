<?php 
include('cfg/core.php');
include('cfg/users_class.php');

$account->sessionLogin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title><?php echo $website->title; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!-- Styles -->
	<link rel="stylesheet" href="<?php echo url(); ?>/css/common.css?v=1.7.1"> <!-- mudar --->
	<!-- Scripts -->	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
    <!-- Facebook -->
    <?php
        if($fb->page){
            echo '<meta property="fb:page_id" content="'.$fb->page.'" />';
        }
        if($fb->app){
            echo '<meta property="fb:app_id" content="'.$fb->app.'" />';
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
    <meta name="og:email" content="<?php echo $email->replyTo; ?>"/>
    <?php
    if($location->phoneNumber){
        echo '<meta name="og:phone_number" content="'.$location->phoneNumber.'"/>';
    }
    ?>

    <noscript><p class="text-center popup popup-red">Oh não! Um erro ocorreu, para viualizar melhor está página é necessário ativar o javascript. Saiba mais <a href="https://www.enable-javascript.com/pt/" target="_blank">clicando aqui</a>.</p></noscript>
</head>
<body>
<main>
    <img src="images/logo.png" class="login-logo">
<div class="loginBox">
    <form action="#" method="POST" target="_self">
        <div class="form-group">Nome <input type="text" name="username"></div>
        <div class="form-group">Senha <input type="password" name="password"></div>
        <button class="button" name="SubmitLoginButton"><span>LOGIN</span></button>
    </form>
    <div class="text-center" style="margin-top: 20px;"><a href="#" class="text-muted">Esqueceu sua senha?</a></div>
    <?php
        if(isset($_SESSION['exceptional_error'])){
            echo '<div class="box-msg error"  style="top:105%;">'.$_SESSION['exceptional_error'].'</div>';
        }
        if($account->isAuthenticated()){
            echo '<div class="box-msg success">'.LOGIN_SUCCESS_SESSION.'</div>';
            echo '<script>setTimeout(function(){ window.location.replace("./cp-home");}, 2000)</script>';
        }

        if(isset($_POST['SubmitLoginButton'])){
            $conn->link = $conn->connect();

            $username = stripslashes($_POST['username']);
            $username = mysqli_real_escape_string($conn->link, $username);

            $password = stripslashes($_POST['password']);
            $password = mysqli_real_escape_string($conn->link, $password);

            try{
                if($newLogin = $account->login($username, $password)){
                    echo '<div class="box-msg success">'.LOGIN_SUCCESS.'</div>';
                    echo '<script>setTimeout(function(){ window.location.replace("./cp-home");}, 2000)</script>';
                }
            }
            catch (Exception $e){
                echo '<div class="box-msg error">'. $e->getMessage() .'</div>';
                die();
            }
        }
    ?>
</div>

</main>
<?php include('footer.php'); ?>
</body>
</html>