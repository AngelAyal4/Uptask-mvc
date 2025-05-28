(function(){



    obtenerTareas();

    let tareas = [];

    //boton para mostrar el modal de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

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
        const contenedorTareas = document.querySelector('#listado-tareas');
        contenedorTareas.innerHTML = ''; // Limpiar el contenedor

        if(tareas.length === 0){
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

        tareas.forEach(tarea => {
            const nuevoTarea = document.createElement('LI');
            nuevoTarea.dataset.tareaId = tarea.id;
            nuevoTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(estados[tarea.estado].toLowerCase());
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.tareaId = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';

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

    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = 
        `
        <form class = "formulario nueva-tarea">
            <legend>Agrega una nueva tarea</legend>
            <div class="campo">
                <label>Tarea</label>
                <input 
                type="text" 
                name="tarea" 
                placeholder="Añade una nueva tarea al Proyecto"
                id="tarea"
                />
            </div>
            <div class="opciones">
                <input type="submit" class="submit-nueva-tarea" value="Agregar Tarea"/>
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
                subtmitFormualrioNuevaTarea();
            }

        });
            
        document.querySelector('.dashboard').appendChild(modal);
       
    }

    function subtmitFormualrioNuevaTarea(){
        const tarea = document.querySelector('#tarea').value.trim();
        if (tarea === '') {
            mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
            return;
        }

        agregarTarea(tarea);
        

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
                
                /*document.querySelector('.formulario').reset();
                const tarea = resultado.tarea;
                const nuevoTarea = document.createElement('LI');
                nuevoTarea.dataset.tareaId = tarea.id;
                nuevoTarea.classList.add('tarea');
                nuevoTarea.innerHTML = `
                    <p>${tarea.nombre}</p>
                    <div class="acciones">
                        <button class="btn-completo">Completo</button>
                        <button class="btn-eliminar">Eliminar</button>
                    </div>
                `;
                const listaTareas = document.querySelector('#listado-tareas');
                listaTareas.appendChild(nuevoTarea);*/
            }

        }catch(error){
            console.log(error);
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