
function sweetAlert(route) {
    Swal.fire({
        title: '¿Está seguro/a que desea volver?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: "Cancelar",
        confirmButtonText: 'Aceptar'   
    }).then((result) => {
        if (result.isConfirmed) {
            if(route == "create"){
                window.location.href = 'view'
            }else{
                window.location.href = '../view'
            }
            
        }
    })
}

function sweetAlertDelete(id, caja = null) {
    let title;
    if(caja != null){
        title = '¿Desea eliminar también las carpetas asociadas?';
    }else{
        title = '¿Está seguro/a que desea eliminar?';
    }
    Swal.fire({
        title: title,
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        showDenyButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        denyButtonColor: '#3085d6',
        cancelButtonText: "Cancelar",
        confirmButtonText: "Borrar Todo",  
        denyButtonText: "Sólo la caja"
    }).then((result) => {
        if (result.isConfirmed) {
            //Acá acepta borrar todo
            let fd = new FormData();
            fd.append('id' , id);
            let url = id+"/deleteAll";
            $.ajax
            ({
                method: 'POST',
                url: url,
                data: fd,
                processData: false,
                contentType: false,

                success: function (res)
                {
                    if(res.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Se eliminó todo correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        setTimeout(function(){location.reload()}, 1500);
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: '¡Algo salió mal!',
                        })
                    }
                }
            });
        }else if(result.isDenied){
            //Acá si va a solo eliminar la carpeta
            let fd = new FormData();
            fd.append('id' , id);
            let url = id;
            $.ajax
            ({
                method: 'POST',
                url: url,
                data: fd,
                processData: false,
                contentType: false,

                success: function (res)
                {
                    if(res.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Se eliminó correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        setTimeout(function(){location.reload()}, 1500);
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: '¡Algo salió mal!',
                        })
                    }
                }
            });
        }
    })
}

// function sweetAlertUndo(id) {

//     Swal.fire({
//         title: '¿Está seguro/a que desea deshacer el movimiento?',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         cancelButtonText: "Cancelar",
//         confirmButtonText: 'Aceptar'   
//     }).then((result) => {
//         if (result.isConfirmed) {
            
//             let fd = new FormData();
//             fd.append('id' , id);
//             let url = "undo/"+id;
//             $.ajax
//             ({
//                 method: 'POST',
//                 url: url,
//                 data: fd,
//                 processData: false,
//                 contentType: false,

//                 success: function (res)
//                 {
//                     if(res.success){
//                         Swal.fire({
//                             icon: 'success',
//                             title: 'Se deshizo correctamente',
//                             showConfirmButton: false,
//                             timer: 1500
//                         })
//                         setTimeout(function(){location.reload()}, 1500);
//                     }
//                     else{
//                         Swal.fire({
//                             icon: 'error',
//                             title: 'Oops...',
//                             text: '¡Algo salió mal!',
//                         })
//                     }
//                 }
//             });
//         }
//     })
// }

function closeModal(idModal){
    Swal.fire({
        title: '¿Está seguro/a que desea cancelar el registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: "Cancelar",
        confirmButtonText: 'Aceptar'   
    }).then((result) => {
        if (result.isConfirmed) {
            $(idModal).modal('hide');
        }
    })
}

