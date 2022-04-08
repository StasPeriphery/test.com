let saveButton = document.querySelector('.save-button');

saveButton.addEventListener('click', ()=>{
    let dataSave = document.querySelector('.input-save-sort');
    let formData = new FormData();
    formData.append('action', 'save_post_data');
    formData.append('data', dataSave.checked);

    fetch('http://test.com/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData,
        'Content-Type': 'application/x-www-form-urlencoded'
    }).then(response => console.log(response))
        .then(answer => {
        });

})