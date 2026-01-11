document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");

  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      const username = formData.get("username");
      const password = formData.get("password");

      try {
        const response = await fetch(
          "http://localhost/notas-app/backend/api/index.php/login",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, password }),
          }
        );

        const data = await response.json();
        console.log("Response data:", data);

        if (data.success) {
          console.log("Login exitoso: " + data.user.username);
          sessionStorage.setItem("user_id", data.user.id_usuario);
          window.location.href = "/notas-app/index.html";
        } else {
          console.log("Error: ", data.message);
          /* alert("Error: " + data.message); */
        }
      } catch (error) {
        console.error("Fetch error:", error);
        /* alert("Error de conexión"); */
      }
    });
  }
});
