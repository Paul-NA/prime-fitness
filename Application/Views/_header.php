<?php
array_push($this->jsFiles,
    'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js'
);


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
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo URI_ROOT;?>/partner/list">Dashboard</a></li>
                <!--<li class="nav-item"><a class="nav-link" href="<?php echo URI_ROOT;?>/partner/list">Partenaires</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo URI_ROOT;?>/dashboard/profil">Mes Structure</a></li>-->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Mon Compte</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo URI_ROOT;?>/user/profil">Mon Profil</a></li>
                    <!--<li><a class="dropdown-item" href="<?php echo URI_ROOT;?>/user/logs">Voir mes logs </a></li>-->
                    <li><a class="dropdown-item" href="<?php echo URI_ROOT;?>/user/logout">Deconnexion</a></li>
                  </ul>
                </li>
            </ul>
            <?php //if($user_info->getRoleID() == ROLE_ADMIN) : ?>
            <div class="search-box"><input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"><div class="result"></div></div>
            <?php //endif;?>
        </div>
    </div>
</nav>

<div class="nav-scroller bg-body shadow-sm">
  <nav class="nav col-lg-8  mx-auto" aria-label="Secondary navigation">
    <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
    <a class="nav-link" href="#">Users <span class="badge text-bg-light rounded-pill align-text-bottom">29</span></a>
    <a class="nav-link" href="#">Partner <span class="badge text-bg-light rounded-pill align-text-bottom">12</span></a>
    <a class="nav-link" href="#">Structures <span class="badge text-bg-light rounded-pill align-text-bottom">17</span></a>
  </nav>
</div>