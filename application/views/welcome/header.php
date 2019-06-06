<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Baza! - Search Results</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>assets/public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="<?php echo base_url(); ?>assets/public/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/public/vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>assets/public/css/landing-page.min.css" rel="stylesheet">

    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-light bg-light static-top">
            <div class="container">
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img height="40" src="<?php echo base_url(); ?>assets/img/header_logo.png" /></a>
                <div>
                    <a class="btn btn-primary" href="<?php echo site_url('login/') ?>">Sign In</a>&nbsp; Or &nbsp;
                    <a class="btn btn-success" href="<?php echo site_url('users/create_account') ?>">Create account</a> 
                </div>
            </div>
        </nav>
        <!-- Masthead -->
        <header class="masthead text-white text-center" style="padding-top: 2rem; padding-bottom: 2rem;">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <h1 class="mb-5">Baza! Umutekano w'ibyawe ni zo nyungu zacu!</h1>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-12 mx-auto" style="text-align: center;">

                        <div>
                            <div >
                                <input type="text" class="form-control-lg required" autocomplete="off" id="search_text" name="search" placeholder="Enter SN, Item Label ...">
                                <button type="submit" class="form-control-lg btn btn-primary" style="padding: .5rem 1rem; font-size: 1.25rem;" onclick="search_registered();">Shakisha icyandikishijwe</button>
                                <button type="submit" class="form-control-lg btn btn-primary" style="padding: .5rem 1rem; font-size: 1.25rem;" onclick="search_found();">Shakisha icyatakaye</button>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </header>

