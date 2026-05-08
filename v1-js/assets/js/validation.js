// validation.js
// Validation UX côté client (Bootstrap invalid-feedback)
// ⚠️ Ne remplace pas la validation PHP côté serveur !

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#registerForm");
  if (!form) return;

  // Champs
  const nom = document.querySelector("#nom");
  const prenom = document.querySelector("#prenom");
  const email = document.querySelector("#email");
  const password = document.querySelector("#password");
  const confirmPassword = document.querySelector("#confirm_password");
  const telephone = document.querySelector("#telephone");

  // Zones d'erreur (Bootstrap invalid-feedback)
  const nomError = document.querySelector("#nomError");
  const prenomError = document.querySelector("#prenomError");
  const emailError = document.querySelector("#emailError");
  const passwordError = document.querySelector("#passwordError");
  const confirmPasswordError = document.querySelector("#confirmPasswordError");
  const telephoneError = document.querySelector("#telephoneError");

  // Regex email simple
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Utilitaires
  function setFieldError(field, errorBox, message) {
    // Affiche une erreur sur un champ
    field.classList.add("is-invalid");
    field.classList.remove("is-valid");
    if (errorBox) errorBox.textContent = message;
  }

  function setFieldValid(field, errorBox) {
    // Marque comme valide (optionnel, mais agréable)
    field.classList.remove("is-invalid");
    field.classList.add("is-valid");
    if (errorBox) errorBox.textContent = "";
  }

  function clearAll() {
    const fields = [
      [nom, nomError],
      [prenom, prenomError],
      [email, emailError],
      [password, passwordError],
      [confirmPassword, confirmPasswordError],
      [telephone, telephoneError],
    ];

    fields.forEach(([f, box]) => {
      f.classList.remove("is-invalid", "is-valid");
      if (box) box.textContent = "";
    });
  }

  // Validation d'un champ (retourne true/false)
  function validateNom() {
    const v = nom.value.trim();
    if (v.length < 2) {
      setFieldError(nom, nomError, "Le nom doit contenir au moins 2 caractères.");
      return false;
    }
    setFieldValid(nom, nomError);
    return true;
  }

  function validatePrenom() {
    const v = prenom.value.trim();
    if (v.length < 2) {
      setFieldError(prenom, prenomError, "Le prénom doit contenir au moins 2 caractères.");
      return false;
    }
    setFieldValid(prenom, prenomError);
    return true;
  }

  function validateEmail() {
    const v = email.value.trim();
    if (v === "") {
      setFieldError(email, emailError, "L'email est obligatoire.");
      return false;
    }
    if (!emailRegex.test(v)) {
      setFieldError(email, emailError, "L'email n'est pas valide (ex: nom@domaine.com).");
      return false;
    }
    setFieldValid(email, emailError);
    return true;
  }

  function validatePassword() {
    const v = password.value;
    if (v.length < 8) {
      setFieldError(password, passwordError, "Le mot de passe doit contenir au moins 8 caractères.");
      return false;
    }
    setFieldValid(password, passwordError);
    return true;
  }

  function validateConfirmPassword() {
    const p = password.value;
    const c = confirmPassword.value;

    if (c.length < 8) {
      setFieldError(confirmPassword, confirmPasswordError, "Veuillez confirmer le mot de passe (min 8 caractères).");
      return false;
    }
    if (p !== c) {
      setFieldError(confirmPassword, confirmPasswordError, "Les mots de passe ne correspondent pas.");
      // On peut aussi marquer password invalide pour indiquer le couple
      password.classList.add("is-invalid");
      if (passwordError && passwordError.textContent === "") {
        passwordError.textContent = "Vérifiez le mot de passe et sa confirmation.";
      }
      return false;
    }
    setFieldValid(confirmPassword, confirmPasswordError);
    // si le password était marqué invalid juste à cause de la comparaison, on peut le remettre ok si sa règle est ok
    if (password.value.length >= 8) setFieldValid(password, passwordError);
    return true;
  }

  function validateTelephone() {
    const v = telephone.value.trim().replace(/\s+/g, ""); // retire espaces
    const digitsRegex = /^[0-9]+$/;

    if (v.length < 8 || v.length > 15) {
      setFieldError(telephone, telephoneError, "Le téléphone doit contenir entre 8 et 15 chiffres.");
      return false;
    }
    if (!digitsRegex.test(v)) {
      setFieldError(telephone, telephoneError, "Le téléphone ne doit contenir que des chiffres.");
      return false;
    }
    setFieldValid(telephone, telephoneError);
    return true;
  }

  // (Optionnel) validation en live à la sortie du champ (blur)
  nom.addEventListener("blur", validateNom);
  prenom.addEventListener("blur", validatePrenom);
  email.addEventListener("blur", validateEmail);
  password.addEventListener("blur", validatePassword);
  confirmPassword.addEventListener("blur", validateConfirmPassword);
  telephone.addEventListener("blur", validateTelephone);

  // Au submit : valider tout
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    clearAll();

    const ok =
      validateNom() &
      validatePrenom() &
      validateEmail() &
      validatePassword() &
      validateConfirmPassword() &
      validateTelephone();

    // Note: on utilise & (bitwise) pour forcer l'exécution de toutes les validations
    // plutôt que && qui s'arrête au premier false.

    if (ok) {
      form.submit(); // envoi réel si tout est valide
    }
  });
});