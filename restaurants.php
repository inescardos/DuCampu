<?php

declare(strict_types = 1);

require_once('template/essentials.tpl.php');
require_once('database/category.class.php');
require_once('database/connection.db.php');


function show_restaurant_category(){ ?>

        <section class= "categories" >
        <p><h3>Category</h3></p>
        <?php 
            $types = array();
            $db = getDatabaseConnection();

            foreach(Category::getCategories($db) as $category ){
                ?>
                <input type="checkbox" id="<?php echo ($category->id);?>"  
                name="<?php echo ($category->name);?>" > 
                <label for="<?php echo ($category->name);?>" >
                <?php echo ($category->name);?></label> 
                <br>
                <?php 
            }
        ?>
        </section>

    <?php 
}?>





<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/position.css">
    <script type="text/javascript" src="js/range.js" defer></script>
    <script type="text/javascript" src="js/restaurants.js" defer></script>


    <title>Du'Campu</title>
</head>

<body>

    <?php show_header_menu(); ?>

    <main class = "restaurantsListPage">   

        <h1>Explore the restaurants</h1>   
         <br>    

        <section class = "restaurantsList">                                                                              
            <section class = "filters">

                <?php show_restaurant_category(); ?>

            <br>

            <section class = "price-range">
                <p><h3>Price range</h3></p> 
                    <input type="checkbox" id="€" name="classification" value="€"> 
                    <label for="euro" > <img clickable src="images/euro.png" id=  "euro" width="47px" height="40px" alt="euro"></label>
                    <input type="checkbox" id="€€" name="classification" value="€€"> 
                    <label for="2euro" > <img clickable src="images/2euro.png" id=  "€€" width="50px" height="40px" alt="2euro"></label>
                    <input type="checkbox" id="€€€" name="classification" value="€€€"> 
                    <label for="3euro" > <img clickable src="images/3euro.png" id=  "3euro" width="55px" height="40px" alt="3euro"></label>
            </section>
            
            <br>
            <br>
            <br>

            <section class = "classification">
                    <div class="min-value numberVal">
                        <input type="number" min="0" max="5" value="0" disabled>
                    </div>   
           
                    <div class="range-slider">
                        <div class="progress"></div>
                        <input type="range" class="range-min" min="0" max="5" value="0" step="0.1">
                        <input type="range" class="range-max" min="0" max="5" value="5" step="0.1">
                    </div>
                    <div class="max-value numberVal">
                        <input type="number" min="0" max="5" value="5" disabled>
                    </div>
            </section>

            </section>

            <section class = "orderBy">
                <h5>ORDER BY:</h5>
                <select id="sorter">
                    <option value="rating"> Rating</option>
                    <option value="price"> Price</option>
                </select>
                <input type="checkbox" id="asc"> <label for="asc"></label> <br>
                
            </section>

            <section class = "restaurants">
                

            </section>
        </section>
        
    </main>

    <?php show_footer(); ?>
</body>
</html>
