<template>

    <h1 v-if="this.show_form === 'login'" class="title">Вход</h1>
    <form v-if="this.show_form === 'login'" class="login-form" method="POST" action="/user/login">
        <div class="form-block">
            <label for="login-form-email" class="text">Введите email</label>
            <div class="input"
                 v-bind:class="{ 'success': this.login.is_email_valid, 'error': this.login.is_email_valid === false }">
                <input v-model="this.login.email" type="email" placeholder="email@email.ru" id="login-form-email"
                       name="email">
            </div>
        </div>
        <div class="form-block">
            <label for="login-form-password" class="text">Введите пароль</label>
            <div class="input"
                 v-bind:class="{ 'success': this.login.is_password_valid, 'error': this.login.is_password_valid === false }">
                <input v-model="this.login.password" type="password" placeholder="Пароль" id="login-form-password"
                       name="password">
            </div>
        </div>
        <div class="form-block-checkbox">
            <div class="input">
                <input v-model="this.login.remember" type="checkbox" id="login-form-remember" name="remember">
            </div>
            <label for="login-form-remember" class="text">Запомнить меня</label>
        </div>
        <div class="form-block">
            <div class="input">
                <input @click.prevent="user_login_check()" type="submit" value="Войти">
            </div>
        </div>
    </form>
    <label v-if="this.show_form === 'login'" @click="this.show_registration()" class="login-form-label"
           for="user-login-switch">Зарегистрироваться</label>

    <h1 v-if="this.show_form === 'registration'" class="title">Регистрация</h1>
    <form v-if="this.show_form === 'registration'" class="registration-form" method="POST" action="/user/registration">
        <div class="form-block">
            <label for="registration-form-name" class="text">Введите Имя</label>
            <div class="input"
                 v-bind:class="{ 'success': this.registration.is_name_valid, 'error': this.registration.is_name_valid === false }">
                <input v-model="this.registration.name" type="text" placeholder="Имя" id="registration-form-name"
                       name="name">
            </div>
        </div>
        <div class="form-block">
            <label for="registration-form-email" class="text">Введите email</label>
            <div class="input"
                 v-bind:class="{ 'success': this.registration.is_email_valid, 'error': this.registration.is_email_valid === false }">
                <input v-model="this.registration.email" type="email" placeholder="email@mail.ru"
                       id="registration-form-email" name="email">
            </div>
        </div>
        <div class="form-block">
            <label for="registration-form-password" class="text">Введите пароль</label>
            <div class="input"
                 v-bind:class="{ 'success': this.registration.is_email_valid, 'error': this.registration.is_email_valid === false }">
                <input v-model="this.registration.password" type="password" placeholder="password"
                       id="registration-form-password" name="password">
            </div>
        </div>
        <div class="form-block">
            <div class="input">
                <input @click.prevent="user_registration_check()" type="submit" value="Зарегистрироваться">
            </div>
        </div>
    </form>
    <label v-if="this.show_form === 'registration'" @click="this.show_login()" class="registration-form-label"
           for="user-login-switch">Уже зарегистрированы?</label>

</template>

<script>
import {useToast} from "vue-toastification";

export default {
    name: "LoginComponent",
    props: [
        'app_env',
    ],
    data: function () {
        return {
            show_console: false,
            show_form: 'login',
            login: {
                email: '',
                password: '',
                remember: true,
                is_email_valid: null,
                is_password_valid: null,
            },
            registration: {
                name: '',
                email: '',
                password: '',
                remember: true,
                is_name_valid: null,
                is_email_valid: null,
                is_password_valid: null,
            },
        };
    },
    setup() {
        const toast = useToast();
        return {toast}
    },
    mounted() {
        if (this.app_env === 'local') {
            this.show_console = true;
        }
    },
    methods: {
        showToast: function () {
            this.toast.info("I'm an info toast!");
            this.toast.success("My toast success", {timeout: 2000});
            this.toast.error("My toast error");
        },
        show_registration: function () {
            this.show_form = 'registration';
        },
        show_login: function () {
            this.show_form = 'login';
        },
        user_login_check: function () {
            if (this.show_console) {
                console.log('this.login', this.login);
            }
            if (!this.is_valid_email(this.login.email)) {
                this.toast.error("Введите правильный адрес email");
                this.login.is_email_valid = false;
            } else {
                this.login.is_email_valid = true;
            }
            if (!this.is_valid_password(this.login.password)) {
                this.toast.error("Введите правильный пароль");
                this.toast.info("Для пароля используйте только латинские буквы и цифры не менее 6 символов");
                this.login.is_password_valid = false;
            } else {
                this.login.is_password_valid = true;
            }
            if (this.login.is_email_valid && this.login.is_password_valid) {
                this.toast.info("Проверяем введенные данные", {timeout: 10000});
                axios.post('/user/login', this.login)
                    .then(response => {
                        if (response.data['is_user_login']) {
                            window.location.href = '/';
                        } else {
                            this.toast.error("Введены неправильные данные");
                        }
                    })
                    .catch(error => {
                        this.toast.error("error", error);
                    });
            }
        },
        is_valid_name: function (name) {
            let re = /[a-zA-Zа-я\d]{2,}/;
            return re.test(name);
        },
        is_valid_email: function (email) {
            let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        is_valid_password: function (password) {
            let re = /[a-zA-Z\d]{6,}/;
            return re.test(password);
        },
        user_registration_check: function () {
            if (this.show_console) {
                console.log('this.registration', this.registration);
            }
            if (!this.is_valid_name(this.registration.name)) {
                this.toast.error("Введите правильное имя для пользователя");
                this.toast.info("Для имени используйте буквы и цифры не менее 2-х символов");
                this.registration.is_name_valid = false;
            } else {
                this.registration.is_name_valid = true;
            }
            if (!this.is_valid_email(this.registration.email)) {
                this.toast.error("Введите правильный адрес email");
                this.registration.is_email_valid = false;
            } else {
                this.registration.is_email_valid = true;
            }
            if (!this.is_valid_password(this.registration.password)) {
                this.toast.error("Введите правильный пароль");
                this.toast.info("Для пароля используйте только латинские буквы и цифры не менее 6 символов");
                this.registration.is_password_valid = false;
            } else {
                this.registration.is_password_valid = true;
            }
            if (this.registration.is_name_valid && this.registration.is_email_valid && this.registration.is_password_valid) {
                this.toast.info("Проверяем введенные данные", {timeout: 10000});
                axios.post('/user/registration', this.registration)
                    .then(response => {
                        if (response.data['is_user_registered']) {
                            window.location.href = '/';
                        }
                        if ('message' in response.data) {
                            this.toast.error(response.data['message']);
                        }
                    })
                    .catch(error => {
                        this.toast.error("error", error);
                    });
            }
        },

    },
}
</script>

<style scoped>

</style>
