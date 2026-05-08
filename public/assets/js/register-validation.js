document.addEventListener("DOMContentLoaded", () => {
  const step1 = document.querySelector("#registerStep1");
  if (step1) {
    const nom = document.querySelector("#nom");
    const prenom = document.querySelector("#prenom");
    const genre = document.querySelector("#genre");
    const email = document.querySelector("#email");
    const telephone = document.querySelector("#telephone");
    const password = document.querySelector("#password");

    const nomError = document.querySelector("#nomError");
    const prenomError = document.querySelector("#prenomError");
    const genreError = document.querySelector("#genreError");
    const emailError = document.querySelector("#emailError");
    const telephoneError = document.querySelector("#telephoneError");
    const passwordError = document.querySelector("#passwordError");

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const digitsRegex = /^[0-9]+$/;

    function setFieldError(field, errorBox, message) {
      field.style.borderColor = "#dc3545";
      if (errorBox) errorBox.textContent = message;
    }

    function setFieldValid(field, errorBox) {
      field.style.borderColor = "#198754";
      if (errorBox) errorBox.textContent = "";
    }

    function clearAll() {
      const fields = [
        [nom, nomError],
        [prenom, prenomError],
        [genre, genreError],
        [email, emailError],
        [telephone, telephoneError],
        [password, passwordError],
      ];

      fields.forEach(([f, box]) => {
        f.style.borderColor = "";
        if (box) box.textContent = "";
      });
    }

    function validateNom() {
      const v = nom.value.trim();
      if (v.length < 2) {
        setFieldError(nom, nomError, "Le nom doit contenir au moins 2 caracteres.");
        return false;
      }
      setFieldValid(nom, nomError);
      return true;
    }

    function validatePrenom() {
      const v = prenom.value.trim();
      if (v.length < 2) {
        setFieldError(prenom, prenomError, "Le prenom doit contenir au moins 2 caracteres.");
        return false;
      }
      setFieldValid(prenom, prenomError);
      return true;
    }

    function validateGenre() {
      if (!genre.value) {
        setFieldError(genre, genreError, "Veuillez selectionner un genre.");
        return false;
      }
      setFieldValid(genre, genreError);
      return true;
    }

    function validateEmail() {
      const v = email.value.trim();
      if (v === "") {
        setFieldError(email, emailError, "L'email est obligatoire.");
        return false;
      }
      if (!emailRegex.test(v)) {
        setFieldError(email, emailError, "L'email n'est pas valide.");
        return false;
      }
      setFieldValid(email, emailError);
      return true;
    }

    function validateTelephone() {
      const v = telephone.value.trim().replace(/\s+/g, "");
      if (v.length < 8 || v.length > 15) {
        setFieldError(telephone, telephoneError, "Le telephone doit contenir entre 8 et 15 chiffres.");
        return false;
      }
      if (!digitsRegex.test(v)) {
        setFieldError(telephone, telephoneError, "Le telephone ne doit contenir que des chiffres.");
        return false;
      }
      setFieldValid(telephone, telephoneError);
      return true;
    }

    function validatePassword() {
      const v = password.value;
      if (v.length < 6) {
        setFieldError(password, passwordError, "Le mot de passe doit contenir au moins 6 caracteres.");
        return false;
      }
      setFieldValid(password, passwordError);
      return true;
    }

    step1.addEventListener("submit", (e) => {
      e.preventDefault();
      clearAll();

      const ok =
        validateNom() &
        validatePrenom() &
        validateGenre() &
        validateEmail() &
        validateTelephone() &
        validatePassword();

      if (ok) {
        step1.submit();
      }
    });
  }

  const step2 = document.querySelector("#registerStep2");
  if (step2) {
    const taille = document.querySelector("#taille");
    const poids = document.querySelector("#poids");
    const tailleError = document.querySelector("#tailleError");
    const poidsError = document.querySelector("#poidsError");

    function setFieldError(field, errorBox, message) {
      field.style.borderColor = "#dc3545";
      if (errorBox) errorBox.textContent = message;
    }

    function setFieldValid(field, errorBox) {
      field.style.borderColor = "#198754";
      if (errorBox) errorBox.textContent = "";
    }

    function clearAll() {
      const fields = [
        [taille, tailleError],
        [poids, poidsError],
      ];

      fields.forEach(([f, box]) => {
        f.style.borderColor = "";
        if (box) box.textContent = "";
      });
    }

    function validateTaille() {
      const v = Number(taille.value);
      if (!Number.isFinite(v) || v < 100 || v > 250) {
        setFieldError(taille, tailleError, "La taille doit etre entre 100 et 250 cm.");
        return false;
      }
      setFieldValid(taille, tailleError);
      return true;
    }

    function validatePoids() {
      const v = Number(poids.value);
      if (!Number.isFinite(v) || v < 30 || v > 200) {
        setFieldError(poids, poidsError, "Le poids doit etre entre 30 et 200 kg.");
        return false;
      }
      setFieldValid(poids, poidsError);
      return true;
    }

    step2.addEventListener("submit", (e) => {
      e.preventDefault();
      clearAll();

      const ok = validateTaille() & validatePoids();
      if (ok) {
        step2.submit();
      }
    });
  }
});
