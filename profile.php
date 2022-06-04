<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/template/essentials.tpl.php');
    require_once(__DIR__ . '/utils/session.php');
    require_once(__DIR__ . '/database/user.class.php');
    require_once(__DIR__ . '/database/owner.class.php');
    require_once(__DIR__ . '/database/restaurant.class.php');

    $session = new Session();

    function show_favorites() {

        global $session;

        $id = $session->getUserId();

        $db = getDatabaseConnection();

        if ($session->isLogged()) { ?>
            <ul>
            <h2>Restaurants</h2>
            <?php 
            $favRestaurants = User::getFavRestaurants($db, $id);
            $favDishes = User::getFavDishes($db, $id);
            foreach($favRestaurants as $favRestaurant) { ?>
            <li>

                <img id="restaurant_img" alt="Imagem do Restaurante" src="<?php echo ($favRestaurant->getPhoto($db, $favRestaurant->id)); ?>">
            
                <h3>
                    <?php echo($favRestaurant->getName()); ?>
                </h3>
                <p id="classificao"><?php echo ($favRestaurant->calcRating($db, $favRestaurant->id));?>
                <?php
                    $categories = $favRestaurant->getCategory($db, $favRestaurant->id);
                    foreach($categories as $category){
                        echo (" • ");
                        echo ($category);
                    }
                ?>
                </p>

            </li>
            <?php
            }
            ?>
            </ul>
            <ul>
            <h2>Dishes</h2>
            <?php
            foreach($favDishes as $favDish) { ?>
            <li>
                <img id="dish_img" alt="Imagem do Prato" src="<?php echo ($favDish->getPhoto($db, $favDish->id)); ?>">
                <h3>
                    <?php echo($favDish->name); ?>
                </h3>
                <p>
                <?php
                    echo ($favDish->price . "€");
                    echo (" • ");
                    echo($favDish->getType($db));
                ?>
                </p>
            </li>

            <?php

            }

            ?>
            </ul>
    <?php
        }
    }

    function show_restaurants() {

        global $session;

        $id = $session->getUserId();

        $db = getDatabaseConnection();

        if($session->isLogged()) {
            if (!User::isCustomer($db, $session->getUsername())) {
    ?>
        <script type="text/javascript" src="js/priceImageChange.js" defer></script>

        <section id="restaurant_list">

            <?php show_restaurant_list(); ?>

            <form id="add-restaurant">
                <input class="input" type="text" placeholder="Restaurant name" name="n" id="restaurant_input" required="required">
                <input class="input" type="text" placeholder="Address" name="a" id="address_input" required="required">
                <input class="input" type="text" placeholder="Categories separated by comma" name="c" id="categories_input" required="required">
                <input type="radio" id="€" name="pr" value="€"> 
                <label for="euro" > <img clickable src="images/euro.png" id="euro" width="47px" height="40px" alt="euro" ></label>
                <input type="radio" id="€€" name="pr" value="€€"> 
                <label for="doiseuro" > <img clickable src="images/2euro.png" id="doiseuro" width="50px" height="40px" alt="2euro"></label>
                <input type="radio" id="€€€" name="pr" value="€€€"> 
                <label for="treseuro" > <img clickable src="images/3euro.png" id="treseuro" width="55px" height="40px" alt="3euro"></label>
                <input type="hidden" name="id" value="<?php echo($id); ?>">
                
                <input formaction="/action/action_add_restaurant.php" formmethod="post" type="submit" class="white_button" value="Insert">
            </form>
        </section>

    <?php
            }
        }
    }

    function show_restaurant_list(){

        global $session;

        $db = getDatabaseConnection();

        $id = $session->getUserId();
        ?>

        <ul>

        <?php $restaurants = Owner::getRestaurants($db, $id);
        foreach($restaurants as $restaurant) {?>

            <li>

            <img id="restaurant_img" alt="Imagem do Restaurante" src="<?php echo ($restaurant->getPhoto($db, $id)); ?>">
            
                <h3>
                    <?php echo($restaurant->getName()); ?>
                </h3>
                <p id="classificao"><?php echo ($restaurant->calcRating($db, $id));?>
                <?php
                    $categories = Restaurant::getCategory($db, $id);
                    foreach($categories as $category){
                        echo (" • ");
                        echo ($category);
                    }
                ?>
                </p>

                <form>
                    <input class="input" type="text" placeholder="Dish name" name="n" id="dish_input" required="required">
                    <input class="input" type="number" step="0.01" placeholder="Price" name="p" id="price_input" required="required">
                    <input class="input" type="text" placeholder="Dish type" name="t" id="type_input" required="required">
                    <input type="hidden" name="id" value="<?php echo($restaurant->id); ?>"> 
                    
                    <input formaction="/action/action_add_dish.php" formmethod="post" type="submit" class="white_button" value="Insert">
                </form>

            </li>

        <?php
        }
        ?>
        </ul>

    <?php
    }

    function show_profile() {

    
        global $session;
        $db = getDatabaseConnection();
        $user_photo =  User::getUser($db, $session->getUsername())->getPhoto($db);

        ?>

        <section id="profile">
        <h1>Personal Information</h1>
            <section id="account">
                <section id="fields">
                    <form action="/action/action_profile.php" method="post" class="profile_form">
                        <label>Name</label>
                        <input name="n" class="attr" type="text" placeholder="Name" value="<?php echo $session->getName(); ?>" required="required">
                        <label>Username</label>
                        <input name="u" class="attr" type="text" placeholder="Username" value="<?php echo $session->getUsername(); ?>" required="required">
                        <label>Email</label>
                        <input name="m" class="attr" type="text" placeholder="Email" value="<?php echo $session->getEmail(); ?>" required="required">
                        <label>Phone</label>
                        <input name="ph" class="attr" type="text" placeholder="Phone Number" value="<?php echo $session->getPhone(); ?>">
                        <label>Address</label>
                        <input name="a" class="attr" type="text" placeholder="Address" value="<?php echo $session->getAddress(); ?>">
                        <label>Password</label>
                        <input name="p" class="attr" type="password" placeholder="Password" value="<?php echo $session->getPassword(); ?>" required="required">
                        <input type="submit" name="Submit" value="Update">
                    </form>
                    <form>
                        <input formaction="/action/action_logout.php" type="submit" value="Logout">
                    </form>
                    <form>
                        <input formaction="/action/action_delete_account.php" type="submit" value="Delete Account">
                    </form>
                </section>
                <div id="photo_field">
                    <form action="/action/action_profile.php" method="post" enctype="multipart/form-data">
                        <label>Photo</label>
                        <img id="photo" src="<?php echo $user_photo; ?>" alt="Profile Picture">
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <input type="submit" name="Submit" value="Upload">
                    </form>
                </div>
            </section>
        </section>
    <?php

    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,600,1,0"/>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/position.css">
        <title>Du'Campu</title>
    </head>
    <body>

    <?php show_header_menu(); ?>

    <main>

    <article id="profile_page">
        <nav id="aside">
            <a class="menu_option" href="index.php">
            <span class="material-symbols-outlined">home</span>
                <p>Home</p>
            </a>
            <a class="menu_option" href="profile.php?page=account">
            <span class="material-symbols-outlined">account_circle</span>
                <p>Account</p>
            </a>
            <a class="menu_option" href="profile.php?page=favorites">
            <span class="material-symbols-outlined">favorite</span>
                <p>Favorites</p>
            </a>
            <?php $db = getDatabaseConnection();
            
            if(!User::isCustomer($db, $session->getUsername())) { ?>
                <a class="menu_option" href="profile.php?page=myRestaurants">
                <span class="material-symbols-outlined">restaurant</span>
                    <p>My Restaurants</p>
                </a>
            <?php
            }
            ?>
        </nav>
        
        <?php 
        
        $page = $_GET['page'];
        if ( !isset($_GET['page']) || $page === 'account') {
            show_profile();
        }
        else if ($page === 'myRestaurants' && !User::isCustomer($db, $session->getUsername())) {
            show_restaurants();
        }
        else if ($page === 'favorites') {
            show_favorites();
        }
        ?>

    </article>

    </main>

    <?php show_footer(); ?>

    </body>
</html>