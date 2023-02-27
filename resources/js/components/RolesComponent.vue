<template>
    <div class="component-user-roles">
        <div class="title">
            <h2>Список ролей пользователя</h2>
            <div v-if="this.show_template === 'list'" @click="add_role()" class="button-add">Добавить</div>
            <div v-if="this.show_template === 'new_role'" @click="view_list()" class="button-add">Отмена</div>
            <div v-if="this.show_template === 'view_role'" @click="view_list()" class="button-add">Закрыть</div>
        </div>

        <div v-if="this.show_template === 'list'" class="roles-list">
            <div class="nav nav-title">
                <div class="number">ID</div>
                <div class="name">Имя</div>
                <div class="default">По умолчанию</div>
                <div class="rules">
                    <div class="rule-title">Роли</div>
                    <div class="rule-title">Пользователи</div>
                    <div class="rule-title">Платежи</div>
                </div>
                <div class="buttons"></div>
            </div>
            <div v-for="role in this.user_roles" :key="role.id" class="nav">
                <div class="number">{{ role.id }}</div>
                <div class="name">{{ role.name }}</div>
                <div class="default">
                    <div @click="set_default_role(role.id)" class="default-checkbox"
                         v-bind:class="{ 'checked': role.is_default }"></div>
                </div>
                <div class="rules">
                    <div @click="change_roles_list(role.id)" class="rule-checkbox"
                         v-bind:class="{ 'checked': role.roles_list }"></div>
                    <div @click="change_users_list(role.id)" class="rule-checkbox"
                         v-bind:class="{ 'checked': role.users_list }"></div>
                    <div @click="change_payments_list(role.id)" class="rule-checkbox"
                         v-bind:class="{ 'checked': role.payments_list }"></div>
                </div>
                <div class="buttons">
                    <div @click="view_role(role.id)" class="icon-eye"></div>
                    <div @click="delete_role(role.id)" class="icon-delete"></div>
                </div>
            </div>
        </div>

        <div v-if="this.show_template === 'new_role'" class="roles-new">
            <div class="role-option">
                <div class="text">
                    <p>Наименование</p><span>*</span>
                </div>
                <div class="input">
                    <input v-model="this.new_role.name" type="text">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Назначить роль по умолчанию для новых пользователей</p>
                </div>
                <div class="input">
                    <input v-model="this.new_role.is_default" v-bind:class="{ 'checked': this.new_role.is_default }"
                           type="checkbox">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Просмотр прав</p>
                </div>
                <div class="input">
                    <input v-model="this.new_role.roles_list" v-bind:class="{ 'checked': this.new_role.roles_list }"
                           type="checkbox">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Просмотр пользователей</p>
                </div>
                <div class="input">
                    <input v-model="this.new_role.users_list" v-bind:class="{ 'checked': this.new_role.users_list }"
                           type="checkbox">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Просмотр платежей</p>
                </div>
                <div class="input">
                    <input v-model="this.new_role.payments_list"
                           v-bind:class="{ 'checked': this.new_role.payments_list }" type="checkbox">
                </div>
            </div>
            <div class="role-button">
                <div @click="create_new_role()">Добавить</div>
            </div>
        </div>

        <div v-if="this.show_template === 'view_role'" class="roles-update">
            <div class="role-option">
                <div class="text">
                    <p>Наименование</p><span>*</span>
                </div>
                <div class="input">
                    <input v-model="this.edit_role.name" type="text">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Назначить роль по умолчанию для новых пользователей</p>
                </div>
                <div class="input">
                    <input v-model="this.edit_role.is_default"
                           v-bind:class="{ 'checked': this.edit_role.is_default }"
                           type="checkbox">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Просмотр прав</p>
                </div>
                <div class="input">
                    <input v-model="this.edit_role.roles_list"
                           v-bind:class="{ 'checked': this.edit_role.roles_list }"
                           type="checkbox">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Просмотр пользователей</p>
                </div>
                <div class="input">
                    <input v-model="this.edit_role.users_list"
                           v-bind:class="{ 'checked': this.edit_role.users_list }"
                           type="checkbox">
                </div>
            </div>
            <div class="role-option">
                <div class="text">
                    <p>Просмотр платежей</p>
                </div>
                <div class="input">
                    <input v-model="this.edit_role.payments_list"
                           v-bind:class="{ 'checked': this.edit_role.payments_list }"
                           type="checkbox">
                </div>
            </div>
            <div class="role-button">
                <div @click="update_role()">Обновить</div>
            </div>
        </div>

    </div>
</template>

<script>
import {useToast} from "vue-toastification";

export default {
    name: "RolesComponent",
    props: [
        'app_env',
        'user',
        'user_options',
        'roles',
    ],
    data: function () {
        return {
            show_console: false,
            show_template: 'list',
            user_roles: {},
            new_role: {
                name: '',
                is_default: false,
                roles_list: false,
                users_list: false,
                payments_list: false,
            },
            edit_role: {
                id: '',
                name: '',
                is_default: false,
                roles_list: false,
                users_list: false,
                payments_list: false,
            },
        };
    },
    setup() {
        const toast = useToast();
        return {toast}
    },
    mounted() {
        console.log('RolesComponent');
        if (this.app_env === 'local') {
            this.show_console = true;
        }
        this.user_roles = this.roles;
    },
    methods: {
        showToast: function () {
            this.toast.info("I'm an info toast!");
            this.toast.success("My toast success", {timeout: 2000});
            this.toast.error("My toast error");
        },
        set_default_role: function (id) {
            if (this.user_options['user_role_id'] >= id) {
                this.toast.error('Нет доступа для изменения прав данной роли');
                this.toast.info("Для возможности изменения данной роли необходимы права выше уровнем чем изменяемая роль");
            } else {
                this.toast.info("Назначаем роль по умолчанию для новых пользователей с id: " + id);
                let data = {
                    role_id: id,
                };
                axios.post('/api/roles/set_default', data)
                    .then(response => {
                        if ('is_changed' in response.data) {
                            if (response.data['is_changed']) {
                                this.toast.success("Успешно");
                            }
                        }
                        if ('roles' in response.data) {
                            this.user_roles = response.data['roles'];
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        change_roles_list: function (id) {
            if (this.user_options['user_role_id'] >= id) {
                this.toast.error('Нет доступа для изменения прав данной роли');
                this.toast.info("Для возможности изменения данной роли необходимы права выше уровнем чем изменяемая роль");
            } else {
                this.toast.info("Изменяем права доступа к просмотру ролей у роли с id " + id);
                let data = {
                    role_id: id,
                };
                axios.post('/api/roles/roles_list', data)
                    .then(response => {
                        if ('is_changed' in response.data) {
                            if (response.data['is_changed']) {
                                this.toast.success("Успешно");
                                this.user_roles[id] = response.data['role'];
                            }
                            if (response.data['message']) {
                                this.toast.error(response.data['message']);
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        change_users_list: function (id) {
            if (this.user_options['user_role_id'] >= id) {
                this.toast.error('Нет доступа для изменения прав данной роли');
                this.toast.info("Для возможности изменения данной роли необходимы права выше уровнем чем изменяемая роль");
            } else {
                this.toast.info("Изменяем права доступа к просмотру пользователей у роли с id " + id);
                let data = {
                    role_id: id,
                };
                axios.post('/api/roles/users_list', data)
                    .then(response => {
                        if ('is_changed' in response.data) {
                            if (response.data['is_changed']) {
                                this.toast.success("Успешно");
                                this.user_roles[id] = response.data['role'];
                            }
                            if (response.data['message']) {
                                this.toast.error(response.data['message']);
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        change_payments_list: function (id) {
            if (this.user_options['user_role_id'] >= id) {
                this.toast.error('Нет доступа для изменения прав данной роли');
                this.toast.info("Для возможности изменения данной роли необходимы права выше уровнем чем изменяемая роль");
            } else {
                this.toast.info("Изменяем права доступа к просмотру платежей у роли с id " + id);
                let data = {
                    role_id: id,
                };
                axios.post('/api/roles/payments_list', data)
                    .then(response => {
                        if ('is_changed' in response.data) {
                            if (response.data['is_changed']) {
                                this.toast.success("Успешно");
                                this.user_roles[id] = response.data['role'];
                            }
                            if (response.data['message']) {
                                this.toast.error(response.data['message']);
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        delete_role: function (id) {
            this.toast.info("Удаляем роль с id " + id);
            if (this.user_roles[id]['is_default']) {
                this.toast.error("Переопределите роль по умолчанию");
            } else {
                let data = {
                    role_id: id,
                };
                axios.post('/api/roles/delete', data)
                    .then(response => {
                        if ('is_deleted' in response.data) {
                            if (response.data['is_deleted']) {
                                this.toast.success("Успешно");
                                delete this.user_roles[id];
                            }
                        }
                        if ('message' in response.data) {
                            if (response.data['message']) {
                                this.toast.error(response.data['message']);
                            }
                        }

                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        add_role: function () {
            this.show_template = 'new_role';
        },
        view_role: function (id) {
            this.edit_role = this.roles[id];
            this.edit_role = {
                id: id,
                name: this.user_roles[id].name,
                is_default: Boolean(this.user_roles[id].is_default),
                roles_list: Boolean(this.user_roles[id].roles_list),
                users_list: Boolean(this.user_roles[id].users_list),
                payments_list: Boolean(this.user_roles[id].payments_list),
            };
            this.show_template = 'view_role';
        },
        view_list: function () {
            this.show_template = 'list';
        },
        create_new_role: function () {
            this.toast.info("Добавляем новую роль");
            let data = {
                role: this.new_role,
            };
            axios.post('/api/roles/create', data)
                .then(response => {
                    if ('is_created' in response.data) {
                        if (response.data['is_created']) {
                            this.toast.success("Успешно");
                            let newRole = response.data['role'];
                            this.roles[newRole.id] = newRole;
                            this.view_list();
                            this.new_role = {
                                name: '',
                                is_default: false,
                                roles_list: false,
                                users_list: false,
                                payments_list: false,
                            };
                        }
                        if (response.data['message']) {
                            this.toast.error(response.data['message']);
                        }
                    }
                    if ('roles' in response.data) {
                        this.user_roles = response.data['roles'];
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        update_role: function () {
            this.toast.info("Обновляем данные роли");
            if (this.roles[this.edit_role.id].is_default === true && this.edit_role.is_default === false) {
                this.toast.error('Нельзя изменить роль установленную по умолчанию.' + this.edit_role.id);
            } else if (this.edit_role.id === 1) {
                this.toast.error('Нельзя изменять права для роли с id: ' + this.edit_role.id);
            } else {
                let data = {
                    role: this.edit_role,
                };
                axios.post('/api/roles/update', data)
                    .then(response => {
                        if ('is_updated' in response.data) {
                            if (response.data['is_updated']) {
                                this.toast.success("Успешно");
                                let updatedRole = response.data['role'];
                                this.roles[updatedRole.id] = updatedRole;
                                this.view_list();
                                this.edit_role = {};
                            }
                            if (response.data['message']) {
                                this.toast.error(response.data['message']);
                            }
                            if ('roles' in response.data) {
                                this.user_roles = response.data['roles'];
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
    },
}
</script>

<style scoped>

</style>
