<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/validation.js" defer></script>
</head>
<body>
<form id="registerForm" method="post" action="register.php" novalidate>

  <div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" id="nom" name="nom" class="form-control" required minlength="2">
    <div class="invalid-feedback" id="nomError"></div>
  </div>

  <div class="mb-3">
    <label for="prenom" class="form-label">Prénom</label>
    <input type="text" id="prenom" name="prenom" class="form-control" required minlength="2">
    <div class="invalid-feedback" id="prenomError"></div>
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" id="email" name="email" class="form-control" required>
    <div class="invalid-feedback" id="emailError"></div>
  </div>

  <div class="mb-3">
    <label for="password" class="form-label">Mot de passe</label>
    <input type="password" id="password" name="password" class="form-control" required minlength="8">
    <div class="invalid-feedback" id="passwordError"></div>
  </div>

  <div class="mb-3">
    <label for="confirm_password" class="form-label">Confirmation mot de passe</label>
    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">
    <div class="invalid-feedback" id="confirmPasswordError"></div>
  </div>

  <div class="mb-3">
    <label for="telephone" class="form-label">Téléphone</label>
    <input type="tel" id="telephone" name="telephone" class="form-control" required minlength="8" maxlength="15">
    <div class="invalid-feedback" id="telephoneError"></div>
  </div>

  <div class="d-grid">
    <button class="btn btn-primary" type="submit">S'inscrire</button>
  </div>

</form>

</body>
</html>