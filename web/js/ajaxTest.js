function retireFolder(id){
    console.log(id);
    let fd = new FormData();
    fd.append('id' , id);

    $.ajax
    ({
        method: 'POST',
        url: 'returnFolder',
        data: fd,
        processData: false,
        contentType: false,

        success: function (res)
            {
                if(res.success){
                    alert(res.res);
                }
                else{
                    alert(res.res);
                }
            }
    });
        
}



