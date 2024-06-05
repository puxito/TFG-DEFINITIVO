// RECARGAR
const reload = document.getElementById("reload");
reload.addEventListener("click", (_) => {
    location.reload();
});

// EDICION DE DATOS
function editardatos() {
    let $formcontrol = document.getElementsByClassName("form-control editable-field");
    for (let i = 0; i < $formcontrol.length; i++) {
        $formcontrol[i].removeAttribute("readonly");
    }
    document.getElementById("save-btn").style.display = "inline-block";
}


document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        editable: true,
        selectable: true,
        selectMirror: true,
        allDaySlot: false,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {
            url: 'dietas/cargarDietas.php',
            method: 'POST',
            extraParams: {
                correoElectronicoUsuario: '<?php echo obtenerCorreoElectronicoUsuario(); ?>'
            }
        },
        dateClick: function(info) {
            $('#modalAgregarEvento').modal('show');
            $('#start').val(info.dateStr);
        },
        eventClick: function(info) {
            var eventId = info.event.id; // Obtiene el ID del evento
            if (confirm('¿Seguro que quieres eliminar este evento?')) {
                $.ajax({
                    url: '../dietas/quitarDietas.php',
                    type: 'POST',
                    data: { id: eventId },
                    success: function(response) {
                        if (response === 'success') {
                            info.event.remove(); // Elimina el evento del calendario
                            alert('Evento eliminado correctamente.');
                        } else {
                            alert('No se ha podido eliminar el evento.');
                        }
                    }
                });
            }
        }
    });
    calendar.render();
});

// Función para editar datos
function editardatos() {
    document.querySelectorAll('.editable-field').forEach(field => {
        field.removeAttribute('readonly');
    });
    document.getElementById('save-btn').style.display = 'inline-block';
    document.getElementById('edit-btn').style.display = 'none';
}

