<?php
$this->titre = "Prime-Fitness - Connexion";
?>
<main class="form-signin text-center">
    <form action="<?php echo URI_ROOT;?>/user/login" method="post" autocomplete="off">
        <img class="mb-4" src="<?php echo URI_ROOT;?>/Assets/Images/logo.svg" alt="" width="300" >
        <div class="shadow p-2 mb-5 rounded bg-warning">
            <div class="form-floating">
                <input type="email"
                       name="mail-prime"
                       class="form-control" id="floatingInput"
                       pattern="<?php echo REGEX_MAIL;?>" title="<?php echo REGEX_MAIL_TEXT;?>" placeholder="<?php echo REGEX_MAIL_PLACEHOLDER;?>"
                       autocomplete="off" required>
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password-prime" class="form-control" id="floatingPassword" placeholder="Password" autocomplete="off" required>
                <label for="floatingPassword">Password</label>
            </div>
        <?php if (isset($msgErreur)): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <div><i class="bi bi-exclamation-triangle-fill"></i> <?php echo $msgErreur ?></div>
            </div>
        <?php endif; ?>
            <input class="w-100 btn btn-lg btn-primary" type="submit" value="Se connecter">
        </div>
        <p class="mt-5 mb-1 text co">&copy; 2022 Prime-Fitness</p>

    </form>
</main>