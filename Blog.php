<?php require_once ("Includes/DB.php") ?>
<?php require_once ("Includes/Functions.php") ?>
<?php require_once ("Includes/Sessions.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/e3893d6151.js" crossorigin="anonymous"></script>

    <title>Blog Page</title>
    <link rel="stylesheet" href="Css/bootstrap.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <link rel="stylesheet" href="Css/Style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">



</head>
<body>
<div style="height: 10px; background: #27aae1;"></div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" >
    <div class="container">
        <a href="#" class="navbar-brand">BLOGPOST.com</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarcollapseCMS">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item">
                    <a href="Blog.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="Blog.php" class="nav-link">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Features</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto mt-2 mb-2">
                <form class="form-inline d-none d-sm-block" action="Blog.php" >
                    <div class="form-group">
                        <input class="form-control mr-2" type="text" name="Search" placeholder="Type here" value="">
                        <button class="btn btn-primary" name="SearchButton">Go</button>
                    </div>
                </form>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 10px; background: #27aae1;"></div>
<!--NAVBAR ENDS HERE -->

<div class="container">
    <div class="row mt-4">
        <div class="col-sm-8">
            <h1>The Complete Responsive CMS Blog </h1>
            <h1 class="lead">DESIGNED BY BLOGPOST.</h1>
            <?php
            echo ErrorMessage();
            echo SuccessMessage();
            ?>
            <?php
            global $ConnectingDB;
            if(isset($_GET["SearchButton"]))
            {
                $Search = $_GET["Search"];
                $sql = "SELECT * FROM posts
                WHERE datetime LIKE :search
                OR title LIKE :search
                OR category LIKE :search
                OR post LIKE :search";
                $stmt = $ConnectingDB->prepare($sql);
                $stmt->bindValue(':search','%'.$Search.'%');
                $stmt->execute();
            }
            elseif (isset($_GET["page"])){
                $Page =$_GET["page"];
                if($Page==0||$Page<1){
                    $ShowPostFrom = 0;
                }
                else {
                    $ShowPostFrom = ($Page*5)-5;
                }
                $sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,5";
                $stmt=$ConnectingDB->query($sql);
            }
            elseif (isset($_GET["category"])){
                $Category = $_GET["category"];
                $sql = "SELECT * FROM posts WHERE category='$Category' ORDER BY id desc";
                $stmt=$ConnectingDB->query($sql);
            }
            else{
                $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT 0,3";
                $stmt = $ConnectingDB->query($sql);
            }
            while($DataRows=$stmt->fetch()){
                $PostId = $DataRows["id"];
                $DataTime = $DataRows["datetime"];
                $PostTitle = $DataRows["title"];
                $Category = $DataRows["category"];
                $Admin = $DataRows["author"];
                $Image = $DataRows["image"];
                $PostDescription = $DataRows["post"];
            ?>
            <div class="card">
                <img src="Uploads/<?php echo htmlentities($Image);?>" style="max-height: 450px;" class="img-fluid card-img-top">
                <div class="card-body">
                    <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                    <small class="text-muted">Category: <span class="text-dark"><a href="Blog.php?category=<?php echo htmlentities($Category); ?>"><?php echo htmlentities($Category); ?></a></span> & Written By <span class="text-dark"><a href="Profile.php?username=<?php echo htmlentities($Admin);?>" ><?php echo htmlentities($Admin);?></a></span> On <?php echo htmlentities($DataTime); ?></small>
                    <span style="float: right;" class="badge badge-dark text-light">
                        Comments <?php echo ApproveCommentsAccordingToPost($PostId); ?>
                    </span>

                    <hr>
                    <p class="card-text"></p>
                    <a href="FullPost.php?id=<?php echo $PostId; ?>" style="float: right;">
                        <span class="btn btn-info">Read More>></span>

                    </a>
                </div>
            </div>
                <br>
            <?php } ?>
            <!-- Pagination -->
            <nav>
                <ul class="pagination pagination-lg">
                    <?php if(isset($Page)){
                        if($Page>1){  ?>
                            <li class="page-item">
                                <a href="Blog.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
                            </li>
                        <?php    }
                    } ?>
                    <?php
                        global $ConnectingDB;
                        $sql = "SELECT COUNT(*) FROM posts";
                        $stmt=$ConnectingDB->query($sql);
                        $RowPagination=$stmt->fetch();
                        $TotalPosts=array_shift($RowPagination);
                        $PostPagination=$TotalPosts/4;
                        $PostPagination=ceil($PostPagination);
                        for ($i=1;$i<=$PostPagination;$i++){
                            if(isset($Page)){
                                if($i == $Page) { ?>
                                <li class="page-item active">
                                    <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                </li>
                                <?php
                                } else{
                                        ?>
                                <li class="page-item">
                                    <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                </li>
                                    <?php
                                } } } ?>

                    <?php if(isset($Page)&&!empty($Page)){
                        if($Page+1<=$PostPagination){  ?>
                    <li class="page-item">
                        <a href="Blog.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
                    </li>
                    <?php    }
                    } ?>
                </ul>
            </nav>
        </div>


        <div class="col-sm-4">
            <div class="card mt-4">
                <div class="card-body">
                    <img src="Images/Blog-intro.jpg" class="d-block img-fluid mb-3" alt="">
                    <div class="text-center">
                        The BlogPost is a website where a user can post there ideas. They can use this webiste for sharing facts and
                        information regarding there language.
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header bg-dark text-light">
                    <h2 class="lead">Sign Up</h2>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-success btn-block text-center text-white mb-4" name="button">Join the Forum</button>
                    <button type="button" class="btn btn-danger btn-block text-center text-white mb-4" name="button">Login</button>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="" placeholder="Enter your Email" value="">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary btn-sm text-center text-white" name="button">Subscribe Now</button>
                        </div>
                    </div>
                </div>
            </div>

    <br>
    <div class="card">
        <div class="card-header bg-primary text-light">
            <h2 class="lead">Categories</h2>
        </div>
            <div class="card-body">
                <?php
                        global $ConnectingDB;
                        $sql = "SELECT * FROM category ORDER BY id desc";
                        $stmt = $ConnectingDB->query($sql);
                        while ($DataRows=$stmt->fetch()){
                            $CategoryId = $DataRows["id"];
                            $CategoryName = $DataRows["title"];
                            ?>
                <a href="Blog.php?category=<?php echo $CategoryName; ?>"> <span class="heading"><?php echo $CategoryName; ?></span></a><br>
                   <?php     }
                ?>
            </div>
        </div>
            <br>
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h2 class="lead">Recent Posts</h2>
                </div>
                <div class="card-body">
                    <?php
                        global $ConnectingDB;
                        $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                        $stmt = $ConnectingDB->query($sql);
                        while ($DataRows=$stmt->fetch()){
                            $Id = $DataRows["id"];
                            $Title = $DataRows["title"];
                            $DateTime = $DataRows["datetime"];
                            $Image = $DataRows['image'];
                            ?>
                    <div class="media">
                        <img src="Uploads/<?php echo  htmlentities($Image); ?>" class="d-block img-fluid align-self-start" width="90px" height="94px" alt="">
                        <div class="media-body ml-2">
                            <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank" ><h6 class="lead"><?php echo htmlentities($Title); ?></h6></a>
                           <p class="small"><?php echo  htmlentities($DateTime); ?></p>
                        </div>
                    </div>
                            <hr>
                    <?php } ?>
                </div>
            </div>
    </div>
    </div>
</div>

<br>


<footer class="bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p class="lead text-center m-3">THEME BY | BLOGPOST | <span id="year"></span> &copy; ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </div>
</footer>
<div style="height: 10px; background: #27aae1;"></div>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>
    $('#year').text(new Date().getFullYear());
</script>

</body>
</html>