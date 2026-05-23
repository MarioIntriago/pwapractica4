function confirmarEliminacion() {
    return confirm("¿Seguro que deseas eliminar este registro? Se hará una eliminación lógica.");
}

document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(".alert");

    alerts.forEach(function (alerta) {
        setTimeout(function () {
            alerta.style.opacity = "0";
            alerta.style.transform = "translateY(-8px)";
            alerta.style.transition = "0.4s";
        }, 3500);
    });
});
