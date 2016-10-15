<?php
require_once 'dbconfig.php';
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="profil">
                        Mon Profil
                    </a>
                </li>

                <?php
                if ($user->is_loggedin()) {
                    echo "<li><a href=\"profile\">Mon Profil</a></li>";
                    echo "<li><a href=\"addchild\">Balance: ". $user->getBalance() ." € </a></li>";
                    echo "<li><a href=\"../logout\">Logout</a></li>";
                    
                } else {
                    echo "<li><a href=\"authentification\">S'enregistrer</a></li>
                      <li><a href=\"signup\">S'inscrire</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
