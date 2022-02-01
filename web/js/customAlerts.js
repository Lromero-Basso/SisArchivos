// $('#button-a').click(function(){
//     Swal.fire({
//         title: '¿Está seguro/a que desea volver?',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         cancelButtonText: "Rechazar",
//         confirmButtonText: 'Aceptar'   
//     }).then((result) => {
//         if (result.isConfirmed) {
//             window.location.href = "{{path('viewAreas')}}"
//         }
//     })
// });


function sweetAlert() {
    Swal.fire({
        title: '¿Está seguro/a que desea volver?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: "Rechazar",
        confirmButtonText: 'Aceptar'   
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../view'
        }
    })
}