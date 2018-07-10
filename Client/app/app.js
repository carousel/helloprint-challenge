let loginForm = document.getElementById('login-form');
let recoveryForm = document.getElementById('recovery-form');
let registerForm = document.getElementById('register-form');

//this function is sending ajax request to broker
function send(data) {
    unfetch("http://broker:8880/", {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: "POST",
            body: JSON.stringify(data)
        })
        .then(response => {
            if (response.status == 200) {
                response.json()
                    .then(data => {
                        let message = data.success;
                        let successMessage = document.getElementById('success-message');
                        let successMessageText = document.createTextNode(message)
                        successMessage.appendChild(successMessageText)
                    })
            } else {
                response.json()
                    .then(data => {
                        let errors = data.errors;
                        if (errors.username) {
                            let message = errors.username;
                            let errorMessage = document.getElementById('username-error');
                            let errorMessageText = document.createTextNode(message)
                            errorMessage.appendChild(errorMessageText)
                        }
                        if (errors.email) {
                            let message = errors.email;
                            let errorMessage = document.getElementById('email-error');
                            let errorMessageText = document.createTextNode(message)
                            errorMessage.appendChild(errorMessageText)
                        }
                        if (errors.password) {
                            let message = errors.password;
                            let errorMessage = document.getElementById('password-error');
                            let errorMessageText = document.createTextNode(message)
                            errorMessage.appendChild(errorMessageText)
                        }
                    })

            }
        })
}

//these are event handlers for form submit
function onLogin(e) {
    if (e) {
        event.preventDefault();
    }
    let username = document.forms['login-form']['username'].value;
    let password = document.forms['login-form']['password'].value;
    document.getElementById('success-message').innerHTML = "";
    document.getElementById('username-error').innerHTML = "";
    document.getElementById('password-error').innerHTML = "";
    let data = {};
    data['username'] = username;
    data['password'] = password;
    data['type'] = 'login';
    document.forms['login-form']['username'].value = ""
    document.forms['login-form']['password'].value = ""
    send(data)
}
function onRegister(e) {
    if (e) {
        event.preventDefault();
    }
    let username = document.forms['register-form']['username'].value;
    let email = document.forms['register-form']['email'].value;
    let password = document.forms['register-form']['password'].value;
    document.getElementById('success-message').innerHTML = "";
    document.getElementById('username-error').innerHTML = "";
    document.getElementById('email-error').innerHTML = "";
    document.getElementById('password-error').innerHTML = "";
    let data = {};
    data['username'] = username;
    data['email'] = email;
    data['password'] = password;
    data['type'] = 'register';
    document.forms['register-form']['username'].value = ""
    document.forms['register-form']['email'].value = ""
    document.forms['register-form']['password'].value = ""
    send(data)
}
function onRecovery(e) {
    if (e) {
        event.preventDefault();
    }
    let username = document.forms['recovery-form']['username'].value;
    document.getElementById('success-message').innerHTML = "";
    document.getElementById('username-error').innerHTML = "";
    let data = {};
    data['username'] = username;
    data['type'] = 'recovery';
    document.forms['recovery-form']['username'].value = ""
    send(data)
}

if (loginForm) {
    loginForm.addEventListener('submit', onLogin, false);
    loginForm.submit = onLogin;
}
if (recoveryForm) {
    recoveryForm.addEventListener('submit', onRecovery, false);
    recoveryForm.submit = onRecovery;
}
if (registerForm) {
    registerForm.addEventListener('submit', onRegister, false);
    registerForm.submit = onRegister;
}
setTimeout(function(){
    $('#success-message').fadeOut();
},6000);
