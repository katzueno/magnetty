<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE?>">
  <head>
  <?php Loader::element('header_required'); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap core CSS -->
    <!--<link href="<?php echo $this->getThemePath();
?>/assets/css/bootstrap.css" rel="stylesheet">-->!
    <!-- <link href="<?php echo $this->getThemePath();
?>/assets/css/font-awesome.min.css" rel="stylesheet">-->
    <!-- Custom styles for this template -->
    <link href="<?php echo $this->getThemePath();
?>/assets/css/main.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>


<body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Magnetee</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="../index.html">HOME</a></li>
            <li><a href="../about.html">ABOUT</a></li>
            <li><a href="../services.html">SERVICES</a></li>
            <li><a href="../works.html">WORKS</a></li>
            <li><a data-toggle="modal" data-target="#myModal" href="#myModal"><i class="fa fa-envelope-o"></i></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>