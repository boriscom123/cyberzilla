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

            <!--            <div class="user-info-name">-->
            <!--                <h3>Имя:</h3>-->
            <!--                <div class="user-info-name-content">-->
            <!--                    <div class="input-name">-->
            <!--                        <input v-model="this.users[this.view_user_id]['name']" type="text">-->
            <!--                    </div>-->
            <!--                    <div class="action-buttons">-->
            <!--                        <button @click="change_user_name(this.view_user_id)">Изменить</button>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="user-info-phone">-->
            <!--                <h3>Телефоны:</h3>-->
            <!--                <div class="user-info-phone-content">-->
            <!--                    <template v-if="Object.keys(this.users[this.view_user_id]['phones']).length > 0">-->
            <!--                        <div class="item" v-for="(item, index) in this.users[this.view_user_id]['phones']">-->
            <!--                            <div class="input-phone">-->
            <!--                                <input v-model="this.users[this.view_user_id]['phones'][index]['phone_number']"-->
            <!--                                       type="text" placeholder="Телефон">-->
            <!--                            </div>-->
            <!--                            <select v-model="this.users[this.view_user_id]['phones'][index]['status_id']"-->
            <!--                                    class="phone-status">-->
            <!--                                <option value="1" selected>Рабочий</option>-->
            <!--                                <option value="2">Домашний</option>-->
            <!--                                <option value="3">Дополнительный</option>-->
            <!--                                <option value="4">Личный</option>-->
            <!--                                <option value="5">Корпоративный</option>-->
            <!--                            </select>-->
            <!--                            <select v-model="this.users[this.view_user_id]['phones'][index]['is_confirmed']"-->
            <!--                                    class="phone-confirmation">-->
            <!--                                <option value="1" selected>Подтвержден</option>-->
            <!--                                <option value="0">Не подтвержден</option>-->
            <!--                            </select>-->
            <!--                            <div class="action-buttons">-->
            <!--                                <button @click="change_user_phone_number(this.view_user_id, index)">Изменить</button>-->
            <!--                                <button @click="remove_user_phone_number(this.view_user_id, index)">Удалить</button>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </template>-->
            <!--                    <div class="item">-->
            <!--                        <div class="input-phone">-->
            <!--                            <input v-model="this.new_phone_number" type="text" placeholder="Телефон">-->
            <!--                        </div>-->
            <!--                        <select v-model="this.new_phone_status" class="phone-status">-->
            <!--                            <option value="1" selected>Рабочий</option>-->
            <!--                            <option value="2">Домашний</option>-->
            <!--                            <option value="3">Дополнительный</option>-->
            <!--                            <option value="3">Личный</option>-->
            <!--                            <option value="5">Корпоративный</option>-->
            <!--                        </select>-->
            <!--                        <select v-model="this.new_phone_confirmation" class="phone-confirmation">-->
            <!--                            <option value="1">Подтвержден</option>-->
            <!--                            <option value="0">Не подтвержден</option>-->
            <!--                        </select>-->
            <!--                        <div class="action-buttons">-->
            <!--                            <button @click="add_user_phone_number(this.view_user_id)">Добавить</button>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="user-info-payments">-->
            <!--                <h3>Платежи:</h3>-->
            <!--                <div class="user-info-payments-content">-->
            <!--                    <template v-if="Object.keys(this.users[this.view_user_id]['payments']).length > 0">-->
            <!--                        <div class="item" v-for="(item, index) in this.users[this.view_user_id]['payments']">-->
            <!--                            <div class="input-payment">-->
            <!--                                <input v-model="this.users[this.view_user_id]['payments'][index]['payment_number']"-->
            <!--                                       type="text" placeholder="№ платежа">-->
            <!--                            </div>-->
            <!--                            <select v-model="this.users[this.view_user_id]['payments'][index]['payment_status']"-->
            <!--                                    class="payment-status">-->
            <!--                                <option value="1">В обработке</option>-->
            <!--                                <option value="2" selected>Завершен</option>-->
            <!--                            </select>-->
            <!--                            <div class="action-buttons">-->
            <!--                                <button @click="change_user_payment(index)">Изменить</button>-->
            <!--                                <button @click="remove_user_payment(index)">Удалить</button>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </template>-->
            <!--                    <div class="item">-->
            <!--                        <div class="input-payment">-->
            <!--                            <input v-model="this.new_payment_number" type="text" placeholder="№ платежа">-->
            <!--                        </div>-->
            <!--                        <select v-model="this.new_payment_status" class="payment-status">-->
            <!--                            <option value="1" selected>В обработке</option>-->
            <!--                            <option value="2">Завершен</option>-->
            <!--                        </select>-->
            <!--                        <div class="action-buttons">-->
            <!--                            <button @click="add_user_payment(this.view_user_id)">Добавить</button>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
        <div v-if="this.template === 'user_info'" class="title">
            <h2>Платежи пользователя</h2>
            <div v-if="this.new_user_payment.is_hidden" @click="show_users_payment_new()" class="button-add">Добавить</div>
            <div v-if="!this.new_user_payment.is_hidden" @click="hide_users_payment_new()" class="button-add">Отмена</div>
        </div>
        <div v-if="!this.new_user_payment.is_hidden" class="user-payment-new">
            <div class="user-option">
                <div class="text">
                    <p>Номер платежа</p>
                </div>
                <div class="input">
                    <input v-model="this.new_user_payment.payment_number" type="text" placeholder="0c5b2444-70a0-4932-980c-b4dc0d3f02b5">
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
                    <select v-model="this.new_user_payment.payment_status" name="user-payments-status" id="user-payments-status">
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
        <div v-if="this.template === 'user_info'" class="user-payments-list">
            <div class="nav nav-title">
                <div class="number">ID</div>
                <div class="name">Номер</div>
                <div class="total">Сумма</div>
                <div class="status">Статус</div>
                <div class="created-at">Дата создания</div>
                <div class="updated-at">Дата обновления</div>
                <div class="buttons"></div>
            </div>
            <div v-for="payment in this.users_list[this.view_user_id]['payments']" :key="payment.id" class="nav">
                <div class="number">{{ payment.id }}</div>
                <div class="name">{{ payment.payment_number }}</div>
                <div class="total">{{ payment.payment_total }}</div>
                <div class="status">{{ payment.payment_status }}</div>
                <div class="created-at">{{ payment.created_at }}</div>
                <div class="updated-at">{{ payment.updated_at }}</div>
                <div class="buttons">
                    <div class="icon-eye"></div>
                    <div class="icon-delete"></div>
                </div>
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
        'users',
        'roles',
        'payments_status',
    ],
    data: function () {
        return {
            show_console: false,
            template: 'list',
            users_list: {},
            roles_list: {},
            payments_statuses: {},
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
                is_hidden: true,
                user_id: '',
                payment_number: '',
                payment_status: 1,
                payment_total: '',
            },
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
        console.log('UsersComponent');
        if (this.app_env === 'local') {
            this.show_console = true;
        }
        this.users_list = this.users;
        this.roles_list = this.roles;
        this.payment_statuses = this.payments_status;
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
            this.new_user_payment.is_hidden = true;
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
            let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        is_valid_password: function (password) {
            let re = /[a-zA-Z\d]{6,}/;
            return re.test(password);
        },
        is_valid_payment_total: function () {
            let re = /[\d.]+/;
            return re.test(this.new_user_payment.payment_total);
        },
        is_valid_payment_number: function () {
            let re = /[a-zA-Z\d-]+/;
            return re.test(this.new_user_payment.payment_number);
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
        add_user_phone_number: function (user_id) {
            let data = {
                user_id: user_id,
                phone: this.new_phone_number,
                status: this.new_phone_status,
                confirmation: this.new_phone_confirmation,
            };
            axios.post('/api/user/phone/add', data)
                .then(response => {
                    if ('is_added' in response.data) {
                        if (response.data['is_added']) {
                            this.new_phone_number = '';
                            this.new_phone_status = '';
                            this.new_phone_confirmation = '';
                            this.get_users();
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        change_user_phone_number: function (user_id, number_id) {
            let data = {
                user_id: user_id,
                number_id: number_id,
                data: this.users[this.view_user_id]['phones'][number_id],
            };
            axios.post('/api/user/phone/change', data)
                .then(response => {
                    if ('is_changed' in response.data) {
                        if (response.data['is_changed']) {
                            this.get_users();
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        remove_user_phone_number: function (user_id, number_id) {
            let data = {
                number_id: number_id,
            };
            axios.post('/api/user/phone/remove', data)
                .then(response => {
                    if ('is_removed' in response.data) {
                        if (response.data['is_removed']) {
                            this.get_users();
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        create_payment: function () {
            if(!this.is_valid_payment_total()){
                this.toast.error("Не правильная сумма платежа (используйте только цифры и знак ',')");
            }
            if(!this.is_valid_payment_number()){
                this.toast.error("Не правильный номер платежа (используйте цифры, латинский буквы и знак '-')");
            }
            let data = {
                'user_id': this.view_user_id,
                'payment_number': this.new_user_payment.payment_number,
                'payment_total': this.new_user_payment.payment_total,
                'payment_status': this.new_user_payment.payment_status,
            };
            if(this.is_valid_payment_total() && this.is_valid_payment_number()) {
                this.toast.info("Добавляем новый платеж для пользователя");
                axios.post('/api/user/payment/create', data)
                    .then(response => {
                        if ('is_created' in response.data) {
                            if (response.data['is_created']) {
                                this.toast.success("Успешно");
                                let newUserPayment = response.data['payment'];
                                console.log('newUserPayment', newUserPayment);
                                this.users_list[this.view_user_id]['payments'][newUserPayment.id] = newUserPayment;
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        change_user_payment: function (payment_id) {
            let data = {
                user_id: this.view_user_id,
                payment_id: payment_id,
                data: this.users[this.view_user_id]['payments'][payment_id],
            };
            axios.post('/api/user/payment/change', data)
                .then(response => {
                    if ('is_changed' in response.data) {
                        if (response.data['is_changed']) {
                            this.get_users();
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        remove_user_payment: function (payment_id) {
            let data = {
                payment_id: payment_id,
            };
            axios.post('/api/user/payment/remove', data)
                .then(response => {
                    if ('is_removed' in response.data) {
                        if (response.data['is_removed']) {
                            this.get_users();
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
            this.new_user_payment.is_hidden = false;
        },
        hide_users_payment_new: function () {
            this.new_user_payment.is_hidden = true;
        },
    },
}
</script>

<style scoped>

</style>
