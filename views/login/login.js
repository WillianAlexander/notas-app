const response = document.addEventListener("submit", (event) => {
  event.preventDefault();
  const form = event.target;
  const formData = new FormData(form);

  const usuario = formData.get("usuario");
  const password = formData.get("password");

  console.log("Usuario:", usuario);
  console.log("Password:", password);
});
