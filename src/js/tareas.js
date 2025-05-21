(function(){
    //boton para mostrar el modal de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

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
            /*if(e.target.classList.contains('submit-nueva-tarea')){
                const tarea = document.querySelector('#tarea').value;
                if(tarea === ''){
                    alert('Agrega una tarea');
                    return;
                }
                const nuevaTarea = document.createElement('LI');
                nuevaTarea.innerHTML = `
                    <p>${tarea}</p>
                    <div class="acciones">
                        <button class="completar-tarea">Completar</button>
                        <button class="eliminar-tarea">Eliminar</button>
                    </div>
                `;
                document.querySelector('.tareas').appendChild(nuevaTarea);
                modal.remove();
            }
            if(e.target.classList.contains('eliminar-tarea')){
                e.target.parentElement.parentElement.remove();
            }*/

        });
            
        document.querySelector('body').appendChild(modal);
       
    }
})();