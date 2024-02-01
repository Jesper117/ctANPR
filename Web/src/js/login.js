let CallbackDisplayText = document.getElementById("login-callback-display");
let FeedbackDiv = document.getElementById("feedback-div");
let PasswordInput = document.getElementById("password-input");
let UsernameInput = document.getElementById("username-input");

$(".input").focusin(function () {
    $(this).find("span").animate({"opacity": "0"}, 200);
});

$(".input").focusout(function () {
    $(this).find("span").animate({"opacity": "1"}, 300);
});

function DisplayCallback(PositiveBool, Feedback) {
    PositiveBool = (PositiveBool === 'true');

    if (PositiveBool) {
        $(".submit").find("i").removeAttr('class').addClass("fa fa-check").css({"color": "#fff"});
        $(".submit").css({"background": "#2ecc71", "border-color": "#2ecc71"});
        $(".feedback").show().animate({"opacity": "1", "bottom": "-80px"}, 400);
        $("input").css({"border-color": "#2ecc71"});

        if (FeedbackDiv.classList.contains("negative")) {
            FeedbackDiv.classList.remove("negative");
        }
        if (!FeedbackDiv.classList.contains("positive")) {
            FeedbackDiv.classList.add("positive");
        }

        UsernameInput.readOnly = true;
        PasswordInput.readOnly = true;
        UsernameInput.disabled = true;
        PasswordInput.disabled = true;
    } else {
        $(".submit").find("i").removeAttr('class').addClass("fa fa-times").css({"color": "#fff"});

        $(".submit").css({"background": "#e74c3c", "border-color": "#e74c3c"});
        $(".feedback").show().animate({"opacity": "1", "bottom": "-80px"}, 400);
        $("input").css({"border-color": "#e74c3c"});

        if (FeedbackDiv.classList.contains("positive")) {
            FeedbackDiv.classList.remove("positive");
        }
        if (!FeedbackDiv.classList.contains("negative")) {
            FeedbackDiv.classList.add("negative");
        }

        UsernameInput.readOnly = false;
        PasswordInput.readOnly = false;
        UsernameInput.disabled = false;
        PasswordInput.disabled = false;
    }

    CallbackDisplayText.innerHTML = Feedback;
}