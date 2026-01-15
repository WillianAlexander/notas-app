let nota = {};

document.addEventListener("DOMContentLoaded", () => {
  console.log("Dashboard loaded");
  nota = new NotesService();
  nota.addNote({
    titulo: "Nota 1",
    contenido: "Contenido de la nota 1",
    fechaCreacion: new Date(),
  });
  nota.addNote({
    titulo: "Nota 2",
    contenido: "Contenido de la nota 2",
    fechaCreacion: new Date(),
  });
  const notesList = nota.getNotes();
  console.log("Notes:", notesList);
  /*   nota.updateNote({
    titulo: "Nota 1",
    contenido: "Nota 1 actualizada con nuevo contenido",
    fechaModificacion: new Date(),
  }); */

  console.log("Notes:", nota.getNotes());
  console.log("section: ", document.getElementById("notes-list"));
  const section = document.getElementById("notes-list");
  for (const note of nota.getNotes()) {
    const noteElement = document.createElement("div");
    noteElement.classList.add("note");
    noteElement.innerHTML = `<h2>${note.titulo}</h2>
        <p>${note.contenido}</p>
        <div>
          <h3>${"⏰" + note.fechaCreacion.toLocaleDateString()}</h3>
          <h3>${
            note.fechaModificacion
              ? "⏰" + note.fechaModificacion.toLocaleDateString()
              : ""
          }</h3>
        </div>`;
    section.appendChild(noteElement);
  }
});

class NotesService {
  notas = [];

  constructor() {}

  getNotes() {
    return this.notas;
  }

  addNote(data = { titulo, contenido, fechaCreacion }) {
    this.notas.push({
      ...data,
    });
  }

  deleteNote(id) {
    this.notas = this.notas.filter((nota) => nota.id !== id);
  }

  updateNote(newData = {}) {
    const nota = this.notas.find((nota) => nota.titulo === newData.titulo);
    console.log("Nota encontrada: ", nota);
    if (nota) {
      this.notas = this.notas.map((n) =>
        n.titulo === newData.titulo ? { ...n, ...newData } : n
      );
    }
  }
}

export { nota };
