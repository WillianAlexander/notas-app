class NotesService {
  constructor() {
    this.notas = []
  }

  getNotes() {
    return this.notas
  }

  addNote(data = { titulo, contenido, fechaCreacion }) {
    this.notas.push({
      ...data,
    })
  }

  deleteNote(id) {
    this.notas = this.notas.filter((nota) => nota.id !== id)
  }

  updateNote(newData = {}) {
    const nota = this.notas.find((nota) => nota.titulo === newData.titulo)
    console.log("Nota encontrada: ", nota)
    if (nota) {
      this.notas = this.notas.map((n) =>
        n.titulo === newData.titulo ? { ...n, ...newData } : n
      )
    }
  }
}

export default NotesService

//Named export --> export { NotesService }
//Default export --> export default NotesService
