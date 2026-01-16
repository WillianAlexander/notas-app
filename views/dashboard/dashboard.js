import NotesService from "../../services/notas/notas_service.js"

document.addEventListener("DOMContentLoaded", () => {
  let nota = new NotesService()
  nota.addNote({
    titulo: "Nota 1",
    contenido: "Contenido de la nota 1",
    fechaCreacion: new Date(),
  })
  nota.addNote({
    titulo: "Nota 2",
    contenido: "Contenido de la nota 2",
    fechaCreacion: new Date(),
  })

  /*   nota.updateNote({
    titulo: "Nota 1",
    contenido: "Nota 1 actualizada con nuevo contenido",
    fechaModificacion: new Date(),
  }); */

  console.log("Notes:", nota.getNotes())
  const section = document.getElementById("notes-list")
  for (const note of nota.getNotes()) {
    const noteElement = document.createElement("div")
    noteElement.classList.add("note")
    noteElement.innerHTML = `<h2>${note.titulo}</h2>
        <p>${note.contenido}</p>
        <div>
          <h3>${"⏰" + note.fechaCreacion.toLocaleDateString()}</h3>
          <h3>${
            note.fechaModificacion
              ? "⏰" + note.fechaModificacion.toLocaleDateString()
              : ""
          }</h3>
        </div>`
    section.appendChild(noteElement)
  }
})

/* export { nota }; */
