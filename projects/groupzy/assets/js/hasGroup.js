var idGroup = JSON.parse(sessionStorage.getItem('idGroup'));
if (idGroup == null) {
    window.location = '/groups';
}