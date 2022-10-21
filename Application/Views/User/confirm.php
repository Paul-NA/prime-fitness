<?php
$this->titre = "Prime-Fitness - Confirmez votre compte en changeant votre mot de passe";
?>
<main class="form-signin text-center">
    <form method="post">
        <img class="mb-4" src="<?php echo URI_ROOT;?>/Assets/Images/logo.svg" alt="" width="300" >
        <div class="form-floating">
            <input type="password" name="password-prime" class="form-control" id="floatingInput" placeholder="Password" required>
            <label for="floatingInput">Password</label>
        </div>
        <div class="form-floating">
            <input type="password"  name="password-prime-confirm" class="form-control" id="floatingPassword" placeholder="Password confirmation" required>
            <label for="floatingPassword">Répéter le Password</label>
        </div>
        
        <input type="hidden" name="csrf" value="<?php echo $csrf;?>">
    <?php if (isset($msgErreur)): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <div><i class="bi bi-exclamation-triangle-fill"></i> <?php echo$msgErreur ?></div>
        </div>
    <?php endif; ?>
        <input class="w-100 btn btn-lg btn-warning" type="submit" value="Créer mon mot de passe">
        <p class="mt-5 mb-1 text co">&copy; 2022 Prime-Fitness</p>
    </form>
</main>