const url = 'http://localhost:8083/index2.php';
const formData = new FormData();

let scaleUp = document.getElementById('scale-up');
scaleUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("scale", "2") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});
let scaleDown = document.getElementById('scale-down');
scaleDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("scale", "-2") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});

