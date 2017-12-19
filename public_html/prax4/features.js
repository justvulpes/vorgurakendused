function party1() {
    setTimeout(party1, Math.random() * 10);
    let el = document.createElement("div");
    el.innerHTML = "💙";
    el.style.position = `absolute`;
    el.style.zIndex = 999999;
    el.style.fontSize = (((Math.random() * 48) | 0) + 16) + `px`;
    el.style.left = ((Math.random() * innerWidth) | 0) + `px`;
    el.style.top = ((Math.random() * (innerHeight + pageYOffset)) | 0) + `px`;
    document.body.appendChild(el);
}

function party2() {
    setTimeout(party2, 1 + Math.random() * 20);
    let el = document.createElement("div");
    el.innerHTML = "💩";
    el.style.position = `absolute`;
    el.style.zIndex = 999999;
    el.style.fontSize = (((Math.random() * 70) | 0) + 16) + `px`;
    el.style.left = ((Math.random() * innerWidth) | 0) + `px`;
    el.style.top = ((Math.random() * (innerHeight + pageYOffset)) | 0) + `px`;
    document.body.appendChild(el);
}

function party3() {
    setTimeout(party3, 1 + Math.random() * 10);
    let el = document.createElement("div");
    el.innerHTML = "💯";
    el.style.position = `absolute`;
    el.style.zIndex = 999999;
    el.style.fontSize = (((Math.random() * 120) + 30 | 0) + 16) + `px`;
    el.style.left = ((Math.random() * innerWidth) | 0) + `px`;
    el.style.top = ((Math.random() * (innerHeight + pageYOffset)) | 0) + `px`;
    document.body.appendChild(el);
}