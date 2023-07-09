const url = 'http://rasterix.test/center.php';
const formData = new FormData();

// TRANSLATION

const STEP = 10;

let xUp = document.getElementById('x-up');
xUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("x-translation", STEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("x-translation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let xDown = document.getElementById('x-down');
xDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("x-translation", - STEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("x-translation") 
        document.getElementById('render').contentWindow.location.reload();
    });

});

let yUp = document.getElementById('y-up');
yUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("y-translation", STEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("y-translation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let yDown = document.getElementById('y-down');
yDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("y-translation", - STEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("y-translation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let zUp = document.getElementById('z-up');
zUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("z-translation", STEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("z-translation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let zDown = document.getElementById('z-down');
zDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("z-translation", - STEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("z-translation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});


// Rotation
const RSTEP = 0.1;

let zRUp = document.getElementById('z-r-up');
zRUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("z-rotation", RSTEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("z-rotation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let zRDown = document.getElementById('z-r-down');
zRDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("z-rotation", - RSTEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("z-rotation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let yRUp = document.getElementById('y-r-up');
yRUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("y-rotation", RSTEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("y-rotation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let yRDown = document.getElementById('y-r-down');
yRDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("y-rotation", - RSTEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("y-rotation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let xRUp = document.getElementById('x-r-up');
xRUp.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("x-rotation", RSTEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("x-rotation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});

let xRDown = document.getElementById('x-r-down');
xRDown.addEventListener('click', function (e) {
    e.preventDefault();
    formData.append("x-rotation", - RSTEP) 
    fetch(url, { method: 'POST', body: formData })
    .then(() => {
        formData.delete("x-rotation") 
        document.getElementById('render').contentWindow.location.reload();
    });
});
