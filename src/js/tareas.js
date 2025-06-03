(function(){

    obtenerTareas();

    let tareas = [];
    let tareasFiltradas = [];

    //boton para mostrar el modal de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function(){
        mostrarFormulario(false);
    });

    //Filtros de Busqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio =>{
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e) {
        const filtro = e.target.value;

        if(filtro !== ''){
            tareasFiltradas = tareas.filter(tarea => tarea.estado === filtro);
        }else{
            tareasFiltradas = [...tareas];
        }

        mostrarTareas();
    }

    async function obtenerTareas(){
        try{
            const id = obtenerProyecto();
            const url = `api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;

            mostrarTareas();

        }catch(error){
            console.log(error);
        }
    }

    function mostrarTareas(){
        limpiarTareas();

        totalPendientes();
        totalCompletas();

        const arrayTareas = tareasFiltradas.length ? tareasFiltradas : tareas;
        const contenedorTareas = document.querySelector('#listado-tareas');
        contenedorTareas.innerHTML = ''; // Limpiar el contenedor

        if(arrayTareas.length === 0){
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        arrayTareas.forEach(tarea => {
            const nuevoTarea = document.createElement('LI');
            nuevoTarea.dataset.tareaId = tarea.id;
            nuevoTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function() {
                mostrarFormulario(true, {...tarea});
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(estados[tarea.estado].toLowerCase());
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.tareaId = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function() {
                //console.log('Eliminando');
                confirmarEliminarTarea(tarea);  // Corrección: pasar tarea directamente
            }

            // Agregar elementos al contenedor de opciones
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            // Agregar elementos a la tarea
            nuevoTarea.appendChild(nombreTarea);
            nuevoTarea.appendChild(opcionesDiv);

            // Agregar tarea al contenedor principal
            contenedorTareas.appendChild(nuevoTarea);
        });
    }

    function totalPendientes(){
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0').length;
        const pendientesRadio = document.querySelector('#pendientes');

        if(totalPendientes === 0){
            pendientesRadio.disabled = true;
            pendientesRadio.checked = false;
        }else{
            pendientesRadio.disabled = false;
        }
    }

    function totalCompletas(){
        const totalCompletas = tareas.filter(tarea => tarea.estado === '1').length;
        const completasRadio = document.querySelector('#completadas');

        if(totalCompletas === 0){
            completasRadio.disabled = true;
            completasRadio.checked = false;
        }else{
            completasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        console.log(editar);
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = 
        `
        <form class = "formulario nueva-tarea">
            <legend>${editar ? 'Edita el nombre de la tarea' : 'Agrega una nueva tarea'}</legend>
            <div class="campo">
                <label>Tarea</label>
                <input 
                type="text" 
                name="tarea" 
                placeholder="${tarea.nombre ? 'Editar el nombre de la tarea': 'Añade una nueva tarea al proyecto'}"
                id="tarea"
                value="${tarea.nombre ? tarea.nombre : ''}"

                />
            </div>
            <div class="opciones">
                <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Confirmar Cambio': 'Añadir Tarea'}"/>
                <button type="button" class="cerrar-modal">Cancelar</button>
            </div>
        </form>       
        `;

        setTimeout(()=>{
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        },30);

        modal.addEventListener('click', function(e){
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(()=>{
                    modal.remove();
                },300);
            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                const nombreTarea = document.querySelector('#tarea').value.trim();

                if (nombreTarea === '') {
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', 
                    document.querySelector('.formulario legend'));
                    return;
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }

                
            }

        });
            
        document.querySelector('.dashboard').appendChild(modal);
       
    }


    //Muestra la tarea en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {
        //Previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        }
        //Crea la alerta
        const alerta = document.createElement('DIV');
        alerta.textContent = mensaje;
        alerta.classList.add('alerta', tipo);
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

    //Consultar al servidor para agregar una nueva tarea al proyecto actual
    async function agregarTarea(tarea){
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try{
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body:datos

            });
            const resultado = await respuesta.json();
            console.log(resultado);

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.
            querySelector('.formulario legend'));

            if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1000);
                //Agregar el objeto de tareas al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj];

                mostrarTareas();
            }

        }catch(error){
            console.log(error);
        }
    }

    function cambiarEstadoTarea(tarea){
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea){
        const { estado, id, nombre, proyectoId } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try{
            const url = 'http://localhost:3000/api/tarea/actualizar';
            const respuesta = await fetch(
                url, {
                    method: 'POST',
                    body: datos
                });
                const resultado = await respuesta.json();
                
                if(resultado.respuesta.tipo === 'exito'){
                    Swal.fire(
                        resultado.respuesta.mensaje, // Corregido de 'esultado' a 'resultado'
                        resultado.respuesta.mensaje,
                        'success'
                    );

                    const modal = document.querySelector('.modal');
                    if(modal){  
                        modal.remove();
                    }

                    tareas = tareas.map(tareaActual => {
                        if(tareaActual.id === id){
                            tareaActual.estado = estado;
                            tareaActual.nombre = nombre;
                        }
                        return tareaActual;
                    });
                    mostrarTareas();
                }
            }catch(error){
                console.log(error);
            }
    }

    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: "Eliminar Tarea?",
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
            }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
    }
    async function eliminarTarea(tarea){
        const { id, nombre, estado } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            if (!respuesta.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const resultado = await respuesta.json();
            
            if(resultado.resultado){
                Swal.fire('Eliminada!', resultado.mensaje, 'success');
                tareas = tareas.filter(tareaActual => tareaActual.id !== tarea.id);
                mostrarTareas();
            } else {
                Swal.fire('Error!', 'Hubo un error al eliminar la tarea', 'error');
            }

        } catch(error) {
            console.error('Error al eliminar la tarea:', error);
            Swal.fire('Error!', 'Hubo un error al eliminar la tarea', 'error');
        }
    }

    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');
        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

})();