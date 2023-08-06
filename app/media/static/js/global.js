function buildUsername() {
    const uType = $("#type").val();
    const fn = $("#fn").val().replace(" ", "").toLowerCase();
    const ln = $("#ln").val().replace(" ", "").toLowerCase();
    console.log(uType);
    let username = uType == "CLIENT" ? "ltpmdq_" : "";
    username += fn.substring(0, 3) + ln;
    $(".username").val(username.toLowerCase());
}

$(function () {
    $("#nav-btn").click(function () {
        $("#nav-links").toggle();
    });

    [...document.querySelectorAll("a")].forEach((e) => {
        const url = new URL(e.href);
        for (let [k, v] of new URLSearchParams(
            window.location.search
        ).entries()) {
            if (url.searchParams.get(k) == null) {
                url.searchParams.set(k, v);
            }
        }
        e.href = url.toString();
    });

    $("#fn").keydown(buildUsername);
    $("#ln").keydown(buildUsername);
    $("#fn").keyup(buildUsername);
    $("#ln").keyup(buildUsername);
    $("#type").change(buildUsername);
    buildUsername();
});
