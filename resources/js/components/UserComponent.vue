<template>
    <div class="component-user">
        <div class="title">
            <h2>Личный кабинет пользователя: {{ this.user.name }}</h2>
        </div>

        <div class="user-update">
            <div class="user-option">
                <div class="text">
                    <p>Имя</p>
                </div>
                <div class="input">
                    <input v-model="this.user.name" type="text" readonly>
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Email</p>
                </div>
                <div class="input">
                    <input v-model="this.user.email" type="text" readonly>
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Роль</p>
                </div>
                <div class="input">
                    <input v-model="this.user_role.name" type="text" readonly>
                </div>
            </div>
<!--            <div class="user-button">-->
<!--                <div>Обновить</div>-->
<!--            </div>-->
        </div>
    </div>
</template>

<script>
import {useToast} from "vue-toastification";

export default {
    name: "UserComponent",
    props: [
        'app_env',
        'user',
        'user_options',
        'user_role',
    ],
    data: function () {
        return {
            show_console: false,
        };
    },
    setup() {
        const toast = useToast();
        return {toast}
    },
    mounted() {
        this.show_console = this.app_env === 'local';

        if (this.show_console) {
            console.log('UserComponent');
            console.log('show_console', this.show_console);
            console.log('this.user', this.user);
            console.log('user_options', this.user_options);
            console.log('user_role', this.user_role);
        }
    },
    methods: {
        showToast: function () {
            this.toast.info("I'm an info toast!");
            this.toast.success("My toast success", {timeout: 2000});
            this.toast.error("My toast error");
        },
    },
}
</script>

<style scoped>

</style>
