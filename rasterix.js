const url = 'http://localhost:23100/front.php';
const formData = new FormData();

let scaleUp = document.getElementById('scale-up');
scaleUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("scale", "1000") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});

let scaleDown = document.getElementById('scale-down');
scaleDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("scale", "-1000") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});

let xUp = document.getElementById('x-up');
xUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("x-translation", "10") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});

let xDown = document.getElementById('x-down');
xDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("x-translation", "-10") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});

let yUp = document.getElementById('y-up');
yUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("y-translation", "10") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});

let yDown = document.getElementById('y-down');
yDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("y-translation", "-10") 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        document.getElementById('render').contentWindow.location.reload();
    });
});

