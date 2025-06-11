// Aplica capitalización tipo "Jonathan Marini" en inputs con clase .capitalizar
document.addEventListener("DOMContentLoaded", () => {
  const capitalizables = document.querySelectorAll("input.capitalizar");

  capitalizables.forEach(input => {
    input.addEventListener("input", () => {
      const valor = input.value;
      const capitalizado = valor
        .toLowerCase()
        .split(" ")
        .filter(Boolean)
        .map(palabra => palabra.charAt(0).toUpperCase() + palabra.slice(1))
        .join(" ");

      // Solo actualizar si cambia, para no mover el cursor innecesariamente
      if (valor !== capitalizado) {
        const pos = input.selectionStart;
        input.value = capitalizado;
        input.setSelectionRange(pos, pos); // Mantener el cursor en su lugar
      }
    });
  });
});