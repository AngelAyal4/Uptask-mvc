(function(){

    obtenerTareas();

    //boton para mostrar el modal de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    async function obtenerTareas(){
        try{
            const id = obtenerProyecto();
            const url = `api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            console.log(resultado);

        }catch(error){
            console.log(error);
        }
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

})();