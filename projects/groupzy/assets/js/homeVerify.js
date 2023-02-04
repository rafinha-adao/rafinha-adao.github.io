if (sessionStorage.getItem("login")) {
    document.getElementById("btn-login").style.display = "none";
    document.getElementById("btn-signup").style.display = "none";

    let user = JSON.parse(sessionStorage.getItem('user'));
    if (user.hasGroup == "0") {
        document.getElementById("btn-mygroup").style.display = "none";
    } else if (user.hasGroup == "1") {
        document.getElementById("btn-create").style.display = "none";
    }
}

if (!sessionStorage.getItem("login")) {
    document.getElementById("btn-chat").style.display = "none";
    document.getElementById("btn-groups").style.display = "none";
    document.getElementById("btn-profile").style.display = "none";
    document.getElementById("btn-logout").style.display = "none";
    document.getElementById("btn-mygroup").style.display = "none";
    document.getElementById("btn-create").style.display = "none";
}