<template>
    <div class="component-users">
        <div v-if="this.template === 'list'" class="title">
            <h2>Список пользователей</h2>
            <div @click="show_add_user()" class="button-add">Добавить</div>
        </div>
        <div v-if="this.template === 'list'" class="users-list">
            <div class="nav nav-title">
                <div class="number">ID</div>
                <div class="name">Имя</div>
                <div class="email">Email</div>
                <div class="role">Права</div>
                <div class="buttons"></div>
            </div>
            <div v-for="user in this.users_list" :key="user.id" class="nav">
                <div class="number">{{ user.id }}</div>
                <div class="name">{{ user.name }}</div>
                <div class="email">{{ user.email }}</div>
                <div class="role">{{ user.role }}</div>
                <div class="buttons">
                    <div @click="show_user_info(user.id)" class="icon-eye"></div>
                    <div @click="delete_user(user.id)" class="icon-delete"></div>
                </div>
            </div>
        </div>

        <div v-if="this.template === 'user_add'" class="title">
            <h2>Добавить пользователя</h2>
            <div @click="show_users_list()" class="button-add">Отмена</div>
        </div>
        <div v-if="this.template === 'user_add'" class="user-new">
            <div class="user-option">
                <div class="text">
                    <p>Имя</p><span>*</span>
                </div>
                <div class="input">
                    <input v-model="this.new_user.name" type="text">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Email</p><span>*</span>
                </div>
                <div class="input">
                    <input v-model="this.new_user.email" type="text">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Пароль</p><span>*</span>
                </div>
                <div class="input">
                    <input v-model="this.new_user.password" type="password">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Роль</p>
                </div>
                <div class="input">
                    <select v-model="this.new_user.role" name="user-role" id="user-role">
                        <template v-for="role in this.roles_list" :key="role.id">
                            <option v-bind:value="role.id">{{ role.name }}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="user-button">
                <div @click="create_user()">Создать</div>
            </div>
        </div>

        <div v-if="this.template === 'user_info'" class="title">
            <h2>Информация о пользователе: {{ this.users[this.view_user_id]['name'] }}</h2>
            <div @click="show_users_list()" class="button-add">Закрыть</div>
        </div>
        <div v-if="this.template === 'user_info'" class="user-update">
            <div class="user-option">
                <div class="text">
                    <p>Имя</p>
                </div>
                <div class="input">
                    <input v-model="this.edit_user.name" type="text">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Email</p>
                </div>
                <div class="input">
                    <input v-model="this.edit_user.email" type="text" readonly>
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Роль</p>
                </div>
                <div class="input">
                    <select v-model="this.edit_user.role" name="user-role" id="user-role">
                        <template v-for="role in this.roles_list" :key="role.id">
                            <option v-bind:value="role.id">{{ role.name }}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="user-button">
                <div @click="update_user()">Обновить</div>
            </div>
        </div>
        <div v-if="this.template === 'user_info'" class="title">
            <h2>Платежи пользователя</h2>
            <div v-if="this.payment_template === 'list'" @click="show_users_payment_new()" class="button-add">Добавить
            </div>
            <div v-if="this.payment_template === 'create'" @click="show_users_payment_list()" class="button-add">
                Отмена
            </div>
            <div v-if="this.payment_template === 'payment_info'" @click="show_users_payment_list()" class="button-add">
                Закрыть
            </div>
        </div>
        <div v-if="this.template === 'user_info' && this.payment_template === 'create'" class="user-payment-new">
            <div class="user-option">
                <div class="text">
                    <p>Номер платежа</p>
                </div>
                <div class="input">
                    <input v-model="this.new_user_payment.payment_number" type="text"
                           placeholder="0c5b2444-70a0-4932-980c-b4dc0d3f02b5">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Сумма платежа</p>
                </div>
                <div class="input">
                    <input v-model="this.new_user_payment.payment_total" type="text" placeholder="0.00">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Статус платежа</p>
                </div>
                <div class="input">
                    <select v-model="this.new_user_payment.payment_status" name="user-payments-status"
                            id="user-payments-status">
                        <template v-for="status in this.payments_status" :key="status.id">
                            <option v-bind:value="status.id">{{ status.name }}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="user-button">
                <div @click="create_payment()">Создать</div>
            </div>
        </div>
        <div v-if="this.template === 'user_info' && this.payment_template === 'list'" class="user-payments-list">
            <template v-if="Object.keys(this.users_list[this.view_user_id]['payments']).length > 0">
                <div class="nav nav-title">
                    <div class="number">ID</div>
                    <div class="payment_number">Номер</div>
                    <div class="total">Сумма</div>
                    <div class="status">Статус</div>
                    <div class="created-at">
                        <div>Создание</div>
                    </div>
                    <div class="updated-at">
                        <div>Обновление</div>
                    </div>
                    <div class="buttons"></div>
                </div>
                <div v-for="user_payment in this.users_list[this.view_user_id]['payments']" :key="user_payment.id"
                     class="nav">
                    <div class="number">{{ user_payment.id }}</div>
                    <div class="payment_number text-small">{{ user_payment.payment_number }}</div>
                    <div class="total">{{ user_payment.payment_total }}</div>
                    <div class="status">{{ this.payments_status[user_payment.payment_status].name }}</div>
                    <div class="created-at text-small">
                        <div class="date">{{ user_payment.created_at.slice(0, 10) }}</div>
                        <div class="time">{{ user_payment.created_at.slice(11, 19) }}</div>
                    </div>
                    <div class="updated-at text-small">
                        <div class="date">{{ user_payment.updated_at.slice(0, 10) }}</div>
                        <div class="time">{{ user_payment.updated_at.slice(11, 19) }}</div>
                    </div>
                    <div class="buttons">
                        <div @click="show_users_payment_info(user_payment.id)" class="icon-eye"></div>
                        <div @click="delete_user_payment(user_payment.id)" class="icon-delete"></div>
                    </div>
                </div>
            </template>
        </div>
        <div v-if="this.template === 'user_info' && this.payment_template === 'payment_info'"
             class="user-payment-update">
            <div class="user-option">
                <div class="text">
                    <p>Номер платежа</p>
                </div>
                <div class="input">
                    <input v-model="this.user_payment_edit.payment_number" type="text"
                           placeholder="0c5b2444-70a0-4932-980c-b4dc0d3f02b5">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Сумма платежа</p>
                </div>
                <div class="input">
                    <input v-model="this.user_payment_edit.payment_total" type="text" placeholder="0.00">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Статус платежа</p>
                </div>
                <div class="input">
                    <select v-model="this.user_payment_edit.payment_status" name="user-payments-status"
                            id="user-payments-status">
                        <template v-for="status in this.payments_status" :key="status.id">
                            <option v-bind:value="status.id">{{ status.name }}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="user-button">
                <div @click="user_payment_update()">Обновить</div>
            </div>
        </div>
    </div>

</template>

<script>
import {useToast} from "vue-toastification";

export default {
    name: "UsersComponent",
    props: [
        'app_env',
        'user',
        'user_options',
        'users',
        'roles',
        'payments_status',
    ],
    data: function () {
        return {
            show_console: false,
            template: 'list',
            payment_template: 'list',
            users_list: {},
            roles_list: {},
            payments_statuses: {},
            user_payments_list: {},
            user_payments_count: 0,
            new_user: {
                name: '',
                email: '',
                password: '',
                role: 1,
                is_name_valid: null,
                is_email_valid: null,
                is_password_valid: null,
            },
            edit_user: {
                name: '',
                role: 1,
                is_name_valid: null,
            },
            new_user_payment: {
                user_id: '',
                payment_number: '',
                payment_status: 1,
                payment_total: '',
            },
            user_payment_edit: {},
            view_user_id: null,
            new_phone_number: '',
            new_phone_status: 1,
            new_phone_confirmation: 0,
            new_payment_number: '',
            new_payment_status: 1,
            new_payment_total: 0,
        };
    },
    setup() {
        const toast = useToast();
        return {toast}
    },
    mounted() {
        this.show_console = this.app_env === 'local';

        this.users_list = this.users;
        this.roles_list = this.roles;
        this.payment_statuses = this.payments_status;

        if (this.show_console) {
            console.log('UsersComponent');
            console.log('users_list', this.users_list);
            console.log('roles_list', this.roles_list);
            console.log('payment_statuses', this.payment_statuses);
        }
    },
    methods: {
        get_users: function () {
            axios.get('/api/users/get')
                .then(response => {
                    if ('users' in response.data) {
                        // this.users = response.data['users'];
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        show_add_user: function () {
            this.template = 'user_add';
        },
        show_users_list: function () {
            this.template = 'list';
            this.show_users_payment_list();
        },
        create_user: function () {
            if (this.is_valid_name(this.new_user.name)) {
                this.new_user.is_name_valid = true;
            } else {
                this.new_user.is_name_valid = false;
                this.toast.error("Введите правильное имя для пользователя не менее 2-х символов");
            }
            if (this.is_valid_email(this.new_user.email)) {
                this.new_user.is_email_valid = true;
            } else {
                this.new_user.is_email_valid = false;
                this.toast.error("Введите правильный email для пользователя");
            }
            if (this.is_valid_password(this.new_user.password)) {
                this.new_user.is_password_valid = true;
            } else {
                this.new_user.is_password_valid = false;
                this.toast.error("Введите правильный пароль для пользователя не менее 6 символов");
            }
            if (this.new_user.is_name_valid && this.new_user.is_email_valid && this.new_user.is_password_valid) {
                this.toast.info("Добавляем нового пользователя");
                axios.post('/api/user/create', this.new_user)
                    .then(response => {
                        if ('is_created' in response.data) {
                            if (response.data['is_created']) {
                                this.toast.success("Успешно");
                                let newUser = response.data['user'];
                                this.users_list[newUser.id] = newUser;
                                this.show_users_list();
                                this.new_user = {
                                    name: '',
                                    email: '',
                                    password: '',
                                    role: 1,
                                    is_name_valid: null,
                                    is_email_valid: null,
                                    is_password_valid: null,
                                };
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        show_user_info: function (user_id) {
            this.template = 'user_info';
            this.view_user_id = user_id;
            this.edit_user = {
                name: this.users_list[user_id].name,
                email: this.users_list[user_id].email,
                password: this.users_list[user_id].password,
                role: this.get_role_id(this.users_list[user_id].role),
            };
        },
        delete_user: function (user_id) {
            this.toast.info("Удаляем пользователя с id " + user_id);
            if (user_id === 1) {
                this.toast.info("Нельзя удалить пользователя ID " + user_id);
            } else {
                let data = {
                    user_id: user_id,
                };
                axios.post('/api/user/delete', data)
                    .then(response => {
                        if ('is_deleted' in response.data) {
                            if (response.data['is_deleted']) {
                                this.toast.success("Успешно");
                                delete this.users_list[user_id];
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        is_valid_name: function (name) {
            let re = /[a-zA-Zа-я\d]{2,}/;
            return re.test(name);
        },
        is_valid_email: function (email) {
            const re = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;
            return re.test(email);
        },
        is_valid_password: function (password) {
            let re = /[a-zA-Z\d]{6,}/;
            return re.test(password);
        },
        is_valid_payment_total: function (payment_total) {
            let re = /(^[0-9]{1,6}$)|(^[0-9]{1,6}\.[0-9]{1,2}$)/;
            return re.test(payment_total);
        },
        is_valid_payment_number: function (payment_number) {
            let re = /[a-zA-Z\d-]+/;
            return re.test(payment_number);
        },
        update_user: function () {
            this.toast.info("Обновляем данные пользователя с id " + this.view_user_id);
            let data = {
                user_id: this.view_user_id,
                name: this.edit_user.name,
                role_id: this.edit_user.role,
            };
            axios.post('/api/user/update', data)
                .then(response => {
                    if ('is_updated' in response.data) {
                        if (response.data['is_updated']) {
                            this.toast.success("Успешно");
                            this.users_list[this.view_user_id].name = this.edit_user.name;
                            this.users_list[this.view_user_id].role = this.roles_list[this.edit_user.role].name;
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        user_phone_create: function (user_id) {
            let data = {
                user_id: user_id,
                phone: this.new_phone_number,
                status: this.new_phone_status,
                confirmation: this.new_phone_confirmation,
            };
            axios.post('/api/user/phone/create', data)
                .then(response => {
                    if ('is_created' in response.data) {
                        if (response.data['is_created']) {
                            this.new_phone_number = '';
                            this.new_phone_status = '';
                            this.new_phone_confirmation = '';
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        user_phone_number_update: function (user_id, number_id) {
            let data = {
                user_id: user_id,
                number_id: number_id,
                data: this.users[this.view_user_id]['phones'][number_id],
            };
            axios.post('/api/user/phone/update', data)
                .then(response => {
                    if ('is_updated' in response.data) {
                        if (response.data['is_updated']) {
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        user_phone_number_delete: function (user_id, number_id) {
            let data = {
                number_id: number_id,
            };
            axios.post('/api/user/phone/delete', data)
                .then(response => {
                    if ('is_deleted' in response.data) {
                        if (response.data['is_deleted']) {
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        create_payment: function () {
            if (!this.is_valid_payment_total(this.new_user_payment.payment_total)) {
                this.toast.error("Не правильная сумма платежа (используйте только цифры и знак '.'). Максимальная сумма: 999999.99");
            }
            if (!this.is_valid_payment_number(this.new_user_payment.payment_number)) {
                this.toast.error("Не правильный номер платежа (используйте цифры, латинский буквы и знак '-')");
            }
            let data = {
                'user_id': this.view_user_id,
                'payment_number': this.new_user_payment.payment_number,
                'payment_total': this.new_user_payment.payment_total,
                'payment_status': this.new_user_payment.payment_status,
            };
            if (this.is_valid_payment_total(this.new_user_payment.payment_total) && this.is_valid_payment_number(this.new_user_payment.payment_number)) {
                this.toast.info("Добавляем новый платеж для пользователя");
                axios.post('/api/user/payment/create', data)
                    .then(response => {
                        if ('is_created' in response.data) {
                            if (response.data['is_created']) {
                                this.toast.success("Успешно");
                                let newUserPayment = response.data['payment'];
                                let paymentId = newUserPayment.id;
                                if (Object.keys(this.users_list[this.view_user_id]['payments']).length === 0) {
                                    this.users_list[this.view_user_id]['payments'] = {[paymentId]: newUserPayment};
                                } else {
                                    this.users_list[this.view_user_id]['payments'][paymentId] = newUserPayment;
                                }
                                this.new_user_payment = {
                                    is_hidden: true,
                                    user_id: '',
                                    payment_number: '',
                                    payment_status: 1,
                                    payment_total: '',
                                };
                                this.show_users_payment_list();
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        user_payment_update: function () {
            this.toast.info("Обновляем данные платежа с id: " + this.user_payment_edit.payment_id);
            if (!this.is_valid_payment_total(this.user_payment_edit.payment_total)) {
                this.toast.error("Не правильная сумма платежа (используйте только цифры и знак '.'). Максимальная сумма: 999999.99");
            }
            if (!this.is_valid_payment_number(this.user_payment_edit.payment_number)) {
                this.toast.error("Не правильный номер платежа (используйте цифры, латинский буквы и знак '-')");
            }
            if (this.is_valid_payment_total(this.user_payment_edit.payment_total) && this.is_valid_payment_number(this.user_payment_edit.payment_number)) {
                axios.post('/api/user/payment/update', this.user_payment_edit)
                    .then(response => {
                        if ('is_updated' in response.data) {
                            if (response.data['is_updated']) {
                                this.toast.success("Успешно");
                                let userPayment = response.data['payment'];
                                let paymentId = userPayment.id;
                                if (Object.keys(this.users_list[this.view_user_id]['payments']).length === 0) {
                                    this.users_list[this.view_user_id]['payments'] = {[paymentId]: userPayment};
                                } else {
                                    this.users_list[this.view_user_id]['payments'][paymentId] = userPayment;
                                }
                                this.user_payment_edit = {};
                                this.show_users_payment_list();
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        delete_user_payment: function (payment_id) {
            this.toast.info("Удаляем платеж с id: " + payment_id);
            let data = {
                payment_id: payment_id,
            };
            axios.post('/api/user/payment/delete', data)
                .then(response => {
                    if ('is_deleted' in response.data) {
                        if (response.data['is_deleted']) {
                            this.toast.success("Успешно");
                            delete this.users_list[this.view_user_id]['payments'][payment_id];
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        get_role_id: function (role_name) {
            let roleId = 1
            for (let key in this.roles_list) {
                if (role_name === this.roles_list[key].name) {
                    roleId = key;
                }
            }
            return roleId;
        },
        show_users_payment_new: function () {
            this.payment_template = 'create';
        },
        show_users_payment_list: function () {
            this.payment_template = 'list';
        },
        show_users_payment_info: function (payment_id) {
            this.payment_template = 'payment_info';
            this.user_payment_edit = this.users_list[this.view_user_id]['payments'][payment_id];
            console.log('this.user_payment_edit', this.user_payment_edit);
        },
    },
}
</script>

<style scoped>

</style>
