<?php

// if(!isset($_SESSION['status']))
// {
//     header ('Location: login.php');
// }

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Open Course</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
                
        <link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <link href="css/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>

        <script src="js/jquery.min.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

     <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.php" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Open Course
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-left">

                <form action="search.php" method="post">
                     <div class="input-group margin " style="margin-left:70px;width:350px;">
                            <div class="input-group-btn">

                                <select class="form-control" name="category" style="width:100px">
                                <option>Users</option>

                                  <option name="fac">Faculty</option>
                                  <option  name="stud">Students</option>
                                    <option >Courses</option>

                                  <option> Assignments</option>
                                  <option >Lectures</option>
                               </select>

                                    </div>
                         <input type="text" class="form-control" name="query" style="border-radius:5px" placeholder="Search...">
                       <span class="input-group-btn">
                                <button type="submit" name="seach" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                        </div> 

                </form>
                </div>
      
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <?php
                            if(isset($_SESSION['status']) )
                                {

                                    include_once '../models/messages.php';
                                    include_once '../models/notifications.php';

                                    $msg = New messages();
                                    $ntfc = New notifications();

                                    $cnts = $ntfc->getUnreadCounts($msg->getUserid($_SESSION['username']));
                                    $cnts['assignmentcount'];
                                    $cnts['lecturecount'];
                                    $cnts['threadcount'];

                                    $totalcount = $cnts['lecturecount'] + $cnts['threadcount'];
                                    $totalcount = $totalcount + $cnts['assignmentcount'];


                                    echo '
                        <li class="messages-menu">
                            <a href="inbox.php" >
                                <i class="fa fa-envelope"></i>
                                <span class="label label-success">'.$msg->getUnreadCount($msg->getUserid($_SESSION['username'])).'</span>
                            </a>
                        </li>';


                        
                        
                        echo '<li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell"></i>
                                <span class="label label-warning">'.$totalcount.'</span>
                            </a>';

                            

                            echo '<ul class="dropdown-menu">
                                <li class="header">You have '.$totalcount.' notifications</li>
                                <li>
                                    
                                    <ul class="menu">';

                                    if(($_SESSION['usertype'])=="Faculty"  || $_SESSION['usertype']=="HOD" ||$_SESSION['usertype']=="admin")
                                          {
                                        echo '<li>
                                            <a href="notificationsfac.php">
                                                <i class="fa fa-users warning"></i> '.$cnts['threadcount'].' new threads have started in your forums                                            </a>
                                        </li>';


                                      echo '  </ul>
                                </li>
                                <li class="footer"><a href="notificationsfac.php">View all</a></li>
                            </ul> ';
                       


                                           } 
                                        if(($_SESSION['usertype'])=="Student")
                                          {
                                            echo '
                                            <li>
                                            <a href="notifications.php">
                                                <i class="fa fa-users warning"></i> '.$cnts['threadcount'].' new threads have started in your forums                                            </a>
                                        </li>

                                            <li>
                                            <a href="notifications.php">
                                                <i class="fa fa-edit success"></i>'.$cnts['assignmentcount'].' new assignments
                                            </a>
                                        </li>
                                        <li>
                                            <a href="notifications.php">
                                                <i class="fa fa-folder-o aqua"></i> '.$cnts['lecturecount'].' new lectures have been uploaded
                                            </a>
                                        </li> ';

                                         
                                        
                                  echo '  </ul>
                                </li>
                                <li class="footer"><a href="notifications.php">View all</a></li>
                            </ul> ';
                       
                                }
                       echo '</li>
                        
                        <!--  User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>

                                <span>'.$_SESSION["username"].'<i class="caret"></i></span></a>
                                                 <ul class="dropdown-menu">
                                
                                <li class="user-header bg-light-blue">
                                    
                                   <p>Hello, '.$_SESSION["username"].'<br/>
                                   '.$_SESSION["usertype"].'
                                   </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="../controller/logout.php" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>';
                                            }
                                    else
                                    {
                                         echo '<ul style="margin-top:7px" class="list-inline">
                                           <li><a href="login.php"><button class="btn btn-info"> Sign In</button></a></li>
                                           <li><a href="signup.php"><button class="btn btn-warning">Sign Up</button></a></li>
                                         </ul>';
                                    }
                            ?>
                            </ul>
                        </li>
                    </ul>
                </div>
    </nav>
</header>