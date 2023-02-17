<template>
    <div class="user-component">
        <div v-if="this.template === 'list'" class="title">
            <h2>Список пользователей</h2>
            <div class="action-buttons">
                <button @click="get_users()" class="color-lightgreen">Обновить</button>
                <button @click="show_add_user()" class="color-lightgreen">Добавить</button>
            </div>
        </div>
        <div v-if="this.template === 'list'" class="users-list">
            <div class="user-nav">
                <div class="user-id">№</div>
                <div class="user-name">Имя</div>
                <div class="user-email">Email</div>
                <div class="user-action-buttons"></div>
            </div>
            <div v-for="user in this.users" class="user">
                <div class="user-id">{{ user['id'] }}</div>
                <div class="user-name">{{ user['name'] }}</div>
                <div class="user-email">{{ user['email'] }}</div>
                <div class="user-action-buttons">
                    <!--                    <a v-bind:href="'/admin/user/' + user['id']" class="color-lightgreen">Просмотр</a>-->
                    <button @click="show_user_info(user['id'])" class="color-lightgreen">Просмотр</button>
                    <button @click="remove_user(user['id'])" class="color-lightcoral">Удалить</button>
                </div>
            </div>
        </div>

        <div v-if="this.template === 'user_add'" class="title">
            <h2>Добавить пользователя</h2>
            <div class="action-buttons">
                <button @click="show_users_list()" class="color-lightgreen">Закрыть</button>
            </div>
        </div>
        <div v-if="this.template === 'user_add'" class="new-user-fields">
            <input v-model="this.new_user.name"
                   v-bind:class="{ 'correct': this.new_user.is_name_valid, 'error': this.new_user.is_name_valid === false }"
                   type="text" placeholder="Имя пользователя">
            <input v-model="this.new_user.email"
                   v-bind:class="{ 'correct': this.new_user.is_email_valid, 'error': this.new_user.is_email_valid  === false }"
                   type="email" placeholder="Email пользователя">
            <input v-model="this.new_user.password"
                   v-bind:class="{ 'correct': this.new_user.is_password_valid, 'error': this.new_user.is_password_valid === false }"
                   type="password" placeholder="Пароль пользователя">
            <input @click="add_user()" type="submit" value="Создать">
        </div>

        <div v-if="this.template === 'user_info'" class="title">
            <h2>Информация о пользователе {{ this.users[this.view_user_id]['name'] }}</h2>
            <div class="action-buttons">
                <button @click="show_users_list()" class="color-lightgreen">Закрыть</button>
            </div>
        </div>
        <div v-if="this.template === 'user_info'" class="user-info-block">
            <div class="user-info-name">
                <h3>Имя:</h3>
                <div class="user-info-name-content">
                    <div class="input-name">
                        <input v-model="this.users[this.view_user_id]['name']" type="text">
                    </div>
                    <div class="action-buttons">
                        <button @click="change_user_name(this.view_user_id)">Изменить</button>
                    </div>
                </div>
            </div>
            <div class="user-info-phone">
                <h3>Телефоны:</h3>
                <div class="user-info-phone-content">
                    <template v-if="Object.keys(this.users[this.view_user_id]['phones']).length > 0">
                        <div class="item" v-for="(item, index) in this.users[this.view_user_id]['phones']">
                            <div class="input-phone">
                                <input v-model="this.users[this.view_user_id]['phones'][index]['phone_number']"
                                       type="text" placeholder="Телефон">
                            </div>
                            <select v-model="this.users[this.view_user_id]['phones'][index]['status_id']"
                                    class="phone-status">
                                <option value="1" selected>Рабочий</option>
                                <option value="2">Домашний</option>
                                <option value="3">Дополнительный</option>
                                <option value="4">Личный</option>
                                <option value="5">Корпоративный</option>
                            </select>
                            <select v-model="this.users[this.view_user_id]['phones'][index]['is_confirmed']"
                                    class="phone-confirmation">
                                <option value="1" selected>Подтвержден</option>
                                <option value="0">Не подтвержден</option>
                            </select>
                            <div class="action-buttons">
                                <button @click="change_user_phone_number(this.view_user_id, index)">Изменить</button>
                                <button @click="remove_user_phone_number(this.view_user_id, index)">Удалить</button>
                            </div>
                        </div>
                    </template>
                    <div class="item">
                        <div class="input-phone">
                            <input v-model="this.new_phone_number" type="text" placeholder="Телефон">
                        </div>
                        <select v-model="this.new_phone_status" class="phone-status">
                            <option value="1" selected>Рабочий</option>
                            <option value="2">Домашний</option>
                            <option value="3">Дополнительный</option>
                            <option value="3">Личный</option>
                            <option value="5">Корпоративный</option>
                        </select>
                        <select v-model="this.new_phone_confirmation" class="phone-confirmation">
                            <option value="1">Подтвержден</option>
                            <option value="0">Не подтвержден</option>
                        </select>
                        <div class="action-buttons">
                            <button @click="add_user_phone_number(this.view_user_id)">Добавить</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-info-payments">
                <h3>Платежи:</h3>
                <div class="user-info-payments-content">
                    <template v-if="Object.keys(this.users[this.view_user_id]['payments']).length > 0">
                        <div class="item" v-for="(item, index) in this.users[this.view_user_id]['payments']">
                            <div class="input-payment">
                                <input v-model="this.users[this.view_user_id]['payments'][index]['payment_number']"
                                       type="text" placeholder="№ платежа">
                            </div>
                            <select v-model="this.users[this.view_user_id]['payments'][index]['payment_status']" class="payment-status">
                                <option value="1">В обработке</option>
                                <option value="2" selected>Завершен</option>
                            </select>
                            <div class="action-buttons">
                                <button @click="change_user_payment(index)">Изменить</button>
                                <button @click="remove_user_payment(index)">Удалить</button>
                            </div>
                        </div>
                    </template>
                    <div class="item">
                        <div class="input-payment">
                            <input v-model="this.new_payment_number" type="text" placeholder="№ платежа">
                        </div>
                        <select v-model="this.new_payment_status" class="payment-status">
                            <option value="1" selected>В обработке</option>
                            <option value="2">Завершен</option>
                        </select>
                        <div class="action-buttons">
                            <button @click="add_user_payment(this.view_user_id)">Добавить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "UsersComponent",
    data: function () {
        return {
            users: [],
            template: 'list',
            new_user: {
                'name': '',
                'email': '',
                'password': '',
                'is_name_valid': null,
                'is_email_valid': null,
                'is_password_valid': null,
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
    mounted() {
        this.get_users();
    },
    methods: {
        get_users: function () {
            axios.get('/api/users/get')
                .then(response => {
                    if ('users' in response.data) {
                        this.users = response.data['users'];
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
        },
        add_user: function () {
            this.new_user.is_name_valid = this.new_user.name.length > 0;
            this.new_user.is_email_valid = this.validEmail(this.new_user.email);
            this.new_user.is_password_valid = this.new_user.password.length > 5;
            if (this.new_user.is_name_valid && this.new_user.is_email_valid && this.new_user.is_password_valid) {
                axios.post('/api/user/add', this.new_user)
                    .then(response => {
                        if ('is_added' in response.data) {
                            if (response.data['is_added']) {
                                this.get_users();
                                this.show_users_list();
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
        },
        remove_user: function (user_id) {
            if (user_id === 1) {
                console.log('Нельзя удалить пользователя №1');
            } else {
                let data = {
                    user_id: user_id,
                };
                axios.post('/api/user/remove', data)
                    .then(response => {
                        if ('is_removed' in response.data) {
                            if (response.data['is_removed']) {
                                delete this.users[user_id];
                                this.get_users();
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            }
        },
        validEmail: function (email) {
            let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        change_user_name: function (user_id) {
            let data = {
                user_id: user_id,
                name: this.users[user_id]['name'],
            };
            axios.post('/api/user/name/change', data)
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
        add_user_payment: function (user_id) {
            let data = {
                user_id: user_id,
                payment_number: this.new_payment_number,
                payment_status: this.new_payment_status,
                payment_total: this.new_payment_total,
            };
            axios.post('/api/user/payment/add', data)
                .then(response => {
                    if ('is_added' in response.data) {
                        if (response.data['is_added']) {
                            this.new_payment_number = '';
                            this.new_payment_status = 1;
                            this.get_users();
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
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
    },
}
</script>

<style scoped>

</style>
