<?php 
    
    if(isset($_COOKIE['language']))
    {
        $lang = $_COOKIE['language'];
        
        if($lang == 'pl')
        {
            $xml = simplexml_load_file("xml/stronaglowna.xml");  
            $xml_errors = simplexml_load_file("xml/bledy.xml"); 
            $support_location = "img/support-btn-pl.png";
        }
        else if($lang == 'en')
        {
            $xml = simplexml_load_file("xml/stronaglowna_en.xml");
            $xml_errors = simplexml_load_file("xml/bledy_en.xml"); 
            $support_location = "img/support-btn-en.png";
        }
    }
    else
    {
        $xml = simplexml_load_file("xml/stronaglowna.xml");
        $xml_errors = simplexml_load_file("xml/bledy.xml"); 
        $support_location = "img/support-btn-pl.png";
    }

    require 'connect.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Kulnemj</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        
        <link href="css/style.css" rel="stylesheet">
        
        <!-- FAVICON LINKING -->
        <link rel="icon" type="image/png" sizes="32x32" href="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/J_Church_logo.svg/2000px-J_Church_logo.svg.png">
    
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
      
    </head>
    <body data-version="0.1">
        <img id="logo-saver" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/J_Church_logo.svg/2000px-J_Church_logo.svg.png" style="display: none;"/>
        
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" id="toggle_menu_btn">
                        <span class="sr-only"><?php echo $xml->naglowek3; ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    
                    <a class="navbar-brand" href="./">
                        <img alt="Brand" class="logo_img" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/J_Church_logo.svg/2000px-J_Church_logo.svg.png">
                    </a>
                    <p class="navbar-text" style="font-weight: bold; font-style: italic;">Kulnemj</p>
                    
                </div>
                
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        
                        <?php
                            if(isset($_GET['id']) || isset($_GET['category']))
                            {
                                echo '<li>';
                            }
                            else
                            {
                                echo '<li class="active">';
                            }
                        ?>
                        
                        <a href="./"><?php echo $xml->naglowek1; ?></a></li>
                        <li><a href="#"><?php echo $xml->naglowek2; ?></a></li>
                    </ul>
                    
                    <button type="button" id="nav_sign_in_btn" class="btn btn-primary navbar-btn pull-right" data-toggle="modal" data-target="#loginModal"><?php echo $xml->zaloguj; ?></button>
                    
                    <div class="btn-group btn-group-xs pull-right lang_btns" id="lang_btns" role="group">
                        <button type="button" id="lang_btn_pl" data-language="PL" class="btn btn-default">PL</button>
                        <button type="button" id="lang_btn_en" data-language="EN" class="btn btn-default">EN</button>
                    </div>
                    
                </div>
              
            </div>
        </nav>
        
        <?php
            
            if(isset($_GET['id']) || isset($_GET['category']))
            {
                if(isset($_GET['id']))
                {
                    $id = $_GET['id'];
                    
                    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                    @mysqli_set_charset($polaczenie,"utf8");

                    if($polaczenie->connect_errno == 0)
                    {  
                        if($result = $polaczenie->query("SELECT category FROM item_category WHERE id='$id'")) 
                        {
                            if($result->num_rows > 0) 
                            {
                                $row = mysqli_fetch_assoc($result);
                                
                                $category = $row['category'];

                                if($result = $polaczenie->query("SELECT * FROM $category WHERE id='$id'")) 
                                {
                                    $row = mysqli_fetch_assoc($result);
                                    
                                    switch($category)
                                    {
                                        case 'devices':
                                            include 'php/kategorie/devices.php';
                                            break;
                                    }
                                }
                            }
                            else
                            {
                                echo $xml->brakwyniku;
                            }
                        }
                        else 
                        {
                            echo $xml_errors->blad6;
                        }

                        $polaczenie->close();
                    }
                    else
                    {
                        echo $xml_errors->blad6;
                    }
                }
                else if(isset($_GET['category']))
                {
                    $category = $_GET['category'];
                    
                    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                    @mysqli_set_charset($polaczenie,"utf8");

                    if($polaczenie->connect_errno == 0)
                    {  
                        if($result = $polaczenie->query("SELECT * FROM $category ORDER BY id ASC")) 
                        {
                            if($result->num_rows > 0) 
                            {
                                switch($category)
                                {
                                    case 'devices':
                                        include 'php/kategorie/devices.php';
                                        break;
                                }
                            }
                            else
                            {
                                echo $xml->brakwkategorii;
                            }
                        }
                        else 
                        {
                            echo $xml_errors->blad6;
                        }

                        $polaczenie->close();
                    }
                    else
                    {
                        echo $xml_errors->blad6;
                    }
                }
            }
            else
            {
                echo '
                
                <div class="col-md-6">
                    <div class="jumbotron">

                        <h2 style="text-align: center; margin-bottom: 15px;">'; echo $xml->content1; echo '</h2>

                        <form method="get">
                            <div class="input-group" style="width: 450px; margin: 0 auto;"> 
                                <input id="id_number_input" class="form-control" name="id" type="number" min="1" value="1" aria-label="Text input with multiple buttons"> 
                                <div class="input-group-btn"> 
                                    <button id="id_help_btn" type="button" class="btn btn-default" aria-label="Help" data-toggle="modal" data-target="#infoModal1"><span class="glyphicon glyphicon-question-sign"></span></button> 
                                    <button id="search_by_id" type="submit" class="btn btn-default">'; echo $xml->szukaj; echo '</button> 
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="col-md-6">  
                    <div class="jumbotron">  

                        <h2 style="text-align: center; margin-bottom: 15px;">'; echo $xml->content2; echo '</h2>

                        <form method="get">
                            <div class="input-group input-group-md" style="width: 450px; margin: 0 auto; float: none;">
                                <select class="form-control" name="category" id="categories_select">
                                    <option selected disabled>'; echo $xml->wybierz; echo '</option>';

                                        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                                        @mysqli_set_charset($polaczenie,"utf8");

                                        if($polaczenie->connect_errno == 0)
                                        {  
                                            if($result = $polaczenie->query("SELECT * FROM categories ORDER BY name ASC")) 
                                            {
                                                if($result->num_rows > 0) 
                                                {
                                                    while($row = mysqli_fetch_array($result))
                                                    {
                                                        $name = $row['name'];

                                                        echo '<option value="'. $name .'">'. $xml->$name .'</option>';
                                                    }
                                                }
                                                else
                                                {
                                                    echo $xml->brakkategorii;
                                                }
                                            }
                                            else 
                                            {
                                                echo $xml_errors->blad6;
                                            }

                                            $polaczenie->close();
                                        }
                                        else
                                        {
                                            echo $xml_errors->blad6;
                                        }
                    
                                    
                                echo '</select>
                                <span class="input-group-btn">
                                    <button id="search_by_category" type="submit" class="btn btn-default">'; echo $xml->szukaj; echo '</button>
                                </span>
                            </div>
                        </form>

                    </div>
                </div>

                <div id="infoModal1" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">'; echo $xml->modal_tytul1; echo '</h4>
                        </div>
                    <div class="modal-body">
                        <p>'; echo $xml->modal_tekst1; echo '</p>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">'; echo $xml->zamknij; echo '</button>
                        </div>
                    </div>
                    </div>
                </div>
                
                ';
            }
        
        ?>
        
        <div id="loginModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $xml->modal_tytul2; ?></h4>
                </div>
            <div class="modal-body">
                
                <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
                <input id="login_input" type="text" class="form-control" name="login_input" placeholder="<?php echo $xml->placeholder1 ?>" />
                </div>
                <br/>
                <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
                    <input id="password_input" type="password" class="form-control" name="password_input" placeholder="<?php echo $xml->placeholder2 ?>" />
                </div>
                <br/>

                <button type="button" id="sign_in_btn" class="btn btn-primary"><?php echo $xml->zaloguj; ?></button>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $xml->zamknij; ?></button>
                </div>
            </div>
            </div>
        </div>
        
        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <p class="text-muted">Bartosz Kropidłowski &nbsp;</p>
                <a href="#" target="_blank"><img src="<?php echo $support_location ?>" style="height: 25px; width: auto; margin-top: 7.5px;"/></a>
                <p class="text-muted">&nbsp;&nbsp; | &nbsp; Oversee Systems &copy; 2017</p>
            </div>
        </footer>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/menu.js"></script>
    </body>
</html>