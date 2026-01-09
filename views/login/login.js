const response = document.addEventListener("submit", (event) => {
  event.preventDefault()
  const form = event.target
  const formData = new FormData(form)

  const usuario = formData.get("usuario")
  const password = formData.get("password")

  console.log("Usuario:", usuario)
  console.log("Password:", password)
  fetch("http://localhost/notas-app/backend/api/index.php/login", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      username: usuario,
      password: password,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Respuesta:", data)
      if (data.success) {
        console.log("Login exitoso")
        // Redirigir a dashboard o home
        window.location.href = "http://localhost/notas-app/"
      } else {
        console.error("Error:", data.message)
        alert("Usuario o contraseña incorrectos")
      }
    })
    .catch((error) => {
      console.error("Error en la solicitud:", error)
      alert("Error al conectar con el servidor")
    })
})
