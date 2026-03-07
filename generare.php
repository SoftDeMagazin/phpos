<?php

$bookList = array(
    'http://www.amazon.com/Making-Software-Really-Works-Believe/dp/0596808321' => 'http://ecx.images-amazon.com/images/I/512eTxcGW6L._SX258_BO1,204,203,200_.jpg',
    'http://www.amazon.com/Code-Complete-Practical-Handbook-Construction/dp/0735619670' => 'http://ecx.images-amazon.com/images/I/515iO%2B-PRUL._SX258_BO1,204,203,200_.jpg',
    'http://www.amazon.com/Clean-Code-Handbook-Software-Craftsmanship/dp/0132350882' => 'http://www-fp.pearsonhighered.com/assets/hip/images/bigcovers/0132350882.jpg',
    'http://www.amazon.com/The-Art-Lean-Software-Development/dp/0596517319' => 'http://akamaicovers.oreilly.com/images/9780596517311/cat.gif',
    'http://www.amazon.com/Patterns-Enterprise-Application-Architecture-Martin/dp/0321127420' => 'http://martinfowler.com/books/eaa.jpg',
    'http://www.amazon.com/Head-First-Design-Patterns-Freeman/dp/0596007124' => 'http://www.headfirstlabs.com/Images/hfdp_cover.gif',
    'http://www.amazon.com/Refactoring-Improving-Design-Existing-Code/dp/0201485672' => 'http://ecx.images-amazon.com/images/I/512-aYxS4ML._SX258_BO1,204,203,200_.jpg',
    'http://www.amazon.com/The-Clean-Coder-Professional-Programmers/dp/0137081073' => 'http://ecx.images-amazon.com/images/I/81AZxqehh-L.jpg',
'http://www.amazon.com/Design-Patterns-Object-Oriented-Professional-Computing/dp/0201634988' => 'http://ecx.images-amazon.com/images/I/81gtKoapHFL.jpg'
    );
$books = 0;
?>
<h1>Code</h1>
<table style="text-align: center">
    <?php
    foreach ($bookList as $amazonLink => $coverLink) :
        if ($books % 3 == 0) : ?>
                <tr>
        <?php endif; ?>
            
            <td style="padding-left: 40px; width: 200px; padding-top: 20px;">
                <img style=" width: 150px; height: 200px;" src="<?php echo $coverLink; ?>">
                <img src="https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=<?php echo $amazonLink; ?>">
                <?php $books++ ?>
            </td>
            
            <?php if ($books - 1 % 3 == 2) : ?>
                </tr>
            <?php endif;
    endforeach;
    ?>
    </tr>
</table>

<?php
$bookList = array_combine(
    array(
    'http://www.amazon.com/Agile-Estimating-Planning-Mike-Cohn/dp/0131479415',
    'http://www.amazon.com/User-Stories-Applied-Software-Development/dp/0321205685',
    'http://www.amazon.com/Succeeding-Agile-Software-Development-Using/dp/0321579364',
    'http://www.amazon.com/Coaching-Agile-Teams-ScrumMasters-Addison-Wesley/dp/0321637704',
    'http://www.amazon.com/Agile-Product-Management-Scrum-Addison-Wesley/dp/0321605780',
    'http://www.amazon.com/Practices-Agile-Developer-Pragmatic-Bookshelf/dp/097451408X/',
    'http://www.amazon.com/Agile-Testing-Practical-Guide-Testers/dp/0321534468',
    ),
    array(
    'http://ecx.images-amazon.com/images/I/514-PYvdNrL._SY344_BO1,204,203,200_.jpg',
    'http://www.mountaingoatsoftware.com/uploads/reviews/user-stories-applied-cover.jpg',
    'http://ecx.images-amazon.com/images/I/51joNPqgfeL._SX258_BO1,204,203,200_.jpg',
    'http://ecx.images-amazon.com/images/I/51loQrUFfVL._SX258_BO1,204,203,200_.jpg',
    'http://www.romanpichler.com/wp-content/uploads/2013/05/Agile-Product-Management-With-Scrum.jpg',
    'http://ecx.images-amazon.com/images/I/516JgnUK9yL._SX258_BO1,204,203,200_.jpg',
    'http://ecx.images-amazon.com/images/I/51D%2BrM0acxL._SX258_BO1,204,203,200_.jpg',
    )
);
$books = 0;
?>
<h1 style="page-break-before: always;">Agile</h1>
<table style="text-align: center">
    <?php
    foreach ($bookList as $amazonLink => $coverLink) :
        if ($books % 3 == 0) : ?>
                <tr>
        <?php endif; ?>
            
            <td style="padding-left: 40px; width: 200px; padding-top: 20px;">
                <img style=" width: 150px; height: 200px;" src="<?php echo $coverLink; ?>">
                <img src="https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=<?php echo $amazonLink; ?>">
                <?php $books++ ?>
            </td>
            
            <?php if ($books - 1 % 3 == 2) : ?>
                </tr>
            <?php endif;
    endforeach;
    ?>
    </tr>
</table>


<?php
$bookList = array(
    'http://www.amazon.com/How-Win-Friends-Influence-People/dp/0671027034' => 'http://ecx.images-amazon.com/images/I/810IASGMUYL.jpg',
    'http://www.elefant.ro/carti/carti-de-specialitate/stiinte-umaniste/relatii-publice-comunicare/arta-persuasiunii-cum-sa-influentezi-oamenii-si-sa-obtii-ceea-ce-vrei-18460.html' => 'http://static.elefant.ro/images/60/18460/arta-persuasiunii-cum-sa-influentezi-oamenii-si-sa-obtii-ceea-ce-vrei_1_produs.jpg',
'http://www.elefant.ro/carti/carti-de-specialitate/stiinte-umaniste/relatii-publice-comunicare/cum-sa-vorbim-in-public-17757.html' => 'http://static.elefant.ro/images/57/17757/cum-sa-vorbim-in-public_1_produs.jpg',
'http://www.elefant.ro/carti/arta-design/design-arte-decorative/pe-spatele-servetelului-14850.html' => 'http://static.elefant.ro/images/50/14850/pe-spatele-servetelului_1_produs.jpg',
'http://www.edituratrei.ro/product.php/Peter_Collett_Cartea_gesturilor_Cum_putem_citi_g%C3%A2ndurile_oamenilor_din_ac%C5%A3iunile_lor/2372/' => 'http://www.edituratrei.ro/images/productimage/2372.jpg'
    );
$books = 0;
?>



<h1 style="page-break-before: always;">Comunicare</h1>
<table style="text-align: center">
    <?php
    foreach ($bookList as $amazonLink => $coverLink) :
        if ($books % 3 == 0) : ?>
                <tr>
        <?php endif; ?>
            
            <td style="padding-left: 40px; width: 200px; padding-top: 20px;">
                <img style=" width: 150px; height: 200px;" src="<?php echo $coverLink; ?>">
                <img src="https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=<?php echo $amazonLink; ?>">
                <?php $books++ ?>
            </td>
            
            <?php if ($books - 1 % 3 == 2) : ?>
                </tr>
            <?php endif;
    endforeach;
    ?>
    </tr>
</table>
<?php
$bookList = array_combine(
    array(
    'http://www.amazon.com/Minute-Manager-Kenneth-Blanchard-Ph-D/dp/074350917X',
    'http://www.amazon.com/Primal-Leadership-Realizing-Emotional-Intelligence/dp/1559277440',
    'http://www.amazon.com/Developing-Leader-Within-John-Maxwell/dp/0785281126',
    'http://www.amazon.com/The-Power-Now-Spiritual-Enlightenment/dp/1577314808',
    'http://www.amazon.com/Getting-Things-Done-Stress-Free-Productivity/dp/0142000280',
    'http://www.elefant.ro/carti/lecturi-motivationale/dezvoltare-personala/cele-vii-deprinderi-ale-persoanelor-eficace-lectii-importante-pentru-schimbarea-personala-editia-iiiiv-116104.html'
    ),
    array(
    'http://upload.wikimedia.org/wikipedia/en/3/3b/The_One_Minute_Manager.jpg',
    'http://d.gr-assets.com/books/1353574359l/163106.jpg',
    'http://ecx.images-amazon.com/images/I/51qJRF3z-KL.jpg',
    'http://ecx.images-amazon.com/images/I/41H398NYsbL.jpg',
    'http://upload.wikimedia.org/wikipedia/en/e/e1/Getting_Things_Done.jpg',
    'http://static.elefant.ro/images/04/116104/cele-7-deprinderi-ale-persoanelor-eficace-lectii-importante-pentru-schimbarea-personala-editia-2015_1_produs.jpg',
    )
);
$books = 0;
?>
<h1 style="page-break-before: always;">Dezvoltare personala</h1>
<table style="text-align: center">
    <?php
    foreach ($bookList as $amazonLink => $coverLink) :
        if ($books % 3 == 0) : ?>
                <tr>
        <?php endif; ?>
            
            <td style="padding-left: 40px; width: 200px; padding-top: 20px;">
                <img style=" width: 150px; height: 200px;" src="<?php echo $coverLink; ?>">
                <img src="https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=<?php echo $amazonLink; ?>">
                <?php $books++ ?>
            </td>
            
            <?php if ($books - 1 % 3 == 2) : ?>
                </tr>
            <?php endif;
    endforeach;
    ?>
    </tr>
</table>


