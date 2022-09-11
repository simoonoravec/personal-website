const popup = (type, title, text, button) => {
    cuteAlert({
        type: type,
        img: `${type}.svg`,
        title: title,
        message: text,
        buttonText: button
    });
}

$('.clickable').click(function() {
    let url = $(this).data('url');
    if (url != null) {
        window.location.href = url;
    }
});

const loadHcaptchaJS = () => {
    if ($("#hcaptcha-js").length == 0) {
        let el = document.createElement('script');
        el.src = 'https://js.hcaptcha.com/1/api.js';
        el.id = 'hcaptcha-js';
    
        document.head.appendChild(el);
    }
}

let currentPage;
const updatePage = () => {
    let hash = window.location.hash;
    let page = ($(hash).length != 0) ? $(hash) : $('#home');

    if ($(hash).length == 0) window.location.hash = '';

    let navItemCurrent = $('.nav-active');
    if (navItemCurrent.length != 0) navItemCurrent.removeClass('nav-active');

    let pageName = hash.replace('#','');

    if (pageName == 'contact') {
        loadHcaptchaJS();
    }

    let navItem = $('#navlink-'+pageName);
    if (navItem.length != 0) navItem.addClass('nav-active');

    if (currentPage != null) {
        currentPage.fadeOut(100, () => page.fadeIn());
    } else {
        page.fadeIn();
    }
    currentPage = page;
}

const roomTempUpdate = () =>  {
    $.get('/api/room-temperature', function(data) {
        if (data.success) {
            $('#room-temp').html(`<b>Current temperature in my room: </b>${data.temp_c}&deg;C<br><small class='dim'>(Useful for you, I know LOL)</small>`);
        }
    });
}

$(() => {
    updatePage();

    roomTempUpdate();
    setInterval(roomTempUpdate, 60000);
});

window.onhashchange = updatePage;