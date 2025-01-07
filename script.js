document.getElementById('menuButton').addEventListener('click', function () {
    document.body.classList.toggle('drawer-open');
});
document.addEventListener('click', function (event) {
    var drawer = document.getElementById('drawer');
    var menuButton = document.getElementById('menuButton');

    if (!drawer.contains(event.target) && !menuButton.contains(event.target)) {
        document.body.classList.remove('drawer-open');
    }
});
document.getElementById('icon_user').addEventListener('click', function () {
    // Debugging output funktioniert
    const dropdown = document.getElementById('userDropdown');
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        dropdown.style.display = 'block';
    } else {
        dropdown.style.display = 'none';
    }
});

// Close dropdown if click outside of it
document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('userDropdown');
    const iconUser = document.getElementById('icon_user');

    // Close dropdown if click is outside the dropdown or icon
    if (!iconUser.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});