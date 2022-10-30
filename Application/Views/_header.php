<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');

array_push($this->jsFiles, 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js');

/**
 * On le cale ici, car les Structure et partenaire ne possède pas le fichier Application.js
 */
$this->jsText .= '     

(() => {
  \'use strict\'
  document.querySelector(\'#navbarSideCollapse\').addEventListener(\'click\', () => {
    document.querySelector(\'.offcanvas-collapse\').classList.toggle(\'open\')
  })
})()
';
?>


<nav class="navbar navbar-expand-lg fixed-top navbar-dark  bg-primary" aria-label="Main navigation">
    <div class="container-fluid col-lg-8  mx-auto">
        <a class="navbar-brand" href="#"><img src="<?php echo URI_ROOT;?>/Assets/Images/logo.svg" alt="" width="200" ></a>
        <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if($current_user->getRoleID() == ROLE_ADMIN) : ?>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo URI_ROOT;?>/partner/list">Dashboard</a></li>
                <?php endif;?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Mon Compte</a>
                  <ul class="dropdown-menu">
                      <!--<li><a class="dropdown-item" href="<?php echo URI_ROOT;?>/user/profil">Mon Profil</a></li>
                      <li><a class="dropdown-item" href="<?php echo URI_ROOT;?>/user/logs">Voir mes logs </a></li>-->
                      <li><a class="dropdown-item" href="<?php echo URI_ROOT;?>/user/logout">Deconnexion</a></li>
                  </ul>
                </li>
            </ul>
            <?php if($current_user->getRoleID() == ROLE_ADMIN) : ?>
            <div class="search-box"><input class="form-control me-2" type="search" data-bs-toggle="modal" data-bs-target="#SearchModal" placeholder="Search" aria-label="Search"><div class="result"></div></div>
            <?php endif;?>
        </div>
    </div>
</nav>

<div class="nav-scroller bg-body shadow-sm">
    <nav class="nav col-lg-8  mx-auto" aria-label="Secondary navigation">
        <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
        <span class="nav-link" ><?php echo $this->cleanHTML($current_user->getUserFirstname()). ' '.$this->cleanHTML($current_user->getUserLastName());?> </span>
    </nav>
</div>