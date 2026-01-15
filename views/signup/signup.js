document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("signupForm")

  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault()

      const formData = new FormData(form)
      const nombre = formData.get("nombre")
      const email = formData.get("email")
      const username = formData.get("username")
      const password = formData.get("password")

      try {
        const response = await fetch(
          "http://localhost/notas-app/backend/api/index.php/register",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nombre, email, username, password }),
          }
        )

        const data = await response.json()
        console.log("Response data:", data)

        if (data.success) {
          console.log(data.message)
          /* sessionStorage.setItem("user_id", data.user.id_usuario) */
          window.location.href = "/notas-app/views/login/login.html"
        } else {
          console.log("Error: ", data.message)
          /* alert("Error: " + data.message); */
        }
      } catch (error) {
        console.error("Fetch error:", error)
        /* alert("Error de conexión"); */
      }
    })
  }
})
