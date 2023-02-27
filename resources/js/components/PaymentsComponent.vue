<template>
    <div class="component-payments">
        <div v-if="this.template === 'list'" class="title">
            <h2>Список платежей</h2>
            <div v-if="this.template === 'info'" @click="show_payments_list()" class="button-add">Закрыть</div>
        </div>
        <div v-if="this.template === 'list'" class="user-payments-list">
            <template v-if="Object.keys(this.payments_list).length > 0">
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
                <div v-for="user_payment in this.payments_list" :key="user_payment.id"
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
                        <div @click="user_payment_info(user_payment.id)" class="icon-eye"></div>
                        <div @click="user_payment_delete(user_payment.id)" class="icon-delete"></div>
                    </div>
                </div>
            </template>
        </div>

        <div v-if="this.template === 'info'" class="title">
            <h2>Просмотр платежа</h2>
            <div @click="show_payments_list()" class="button-add">Закрыть</div>
        </div>
        <div v-if="this.template === 'info'"
             class="user-payment-update">
            <div class="user-option">
                <div class="text">
                    <p>Пользователь</p>
                </div>
                <div class="input">
                    <input v-bind:value="this.users_list[this.payment_info.user_id].name" type="text" readonly>
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Номер платежа</p>
                </div>
                <div class="input">
                    <input v-model="this.payment_info.payment_number" type="text"
                           placeholder="0c5b2444-70a0-4932-980c-b4dc0d3f02b5">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Сумма платежа</p>
                </div>
                <div class="input">
                    <input v-model="this.payment_info.payment_total" type="text" placeholder="0.00">
                </div>
            </div>
            <div class="user-option">
                <div class="text">
                    <p>Статус платежа</p>
                </div>
                <div class="input">
                    <select v-model="this.payment_info.payment_status" name="user-payments-status"
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
    name: "PaymentsComponent",
    props: [
        'app_env',
        'user',
        'user_options',
        'payments',
        'payments_status',
        'users',
    ],
    data: function () {
        return {
            show_console: false,
            template: 'list',
            payments_list: {},
            payments_statuses: {},
            users_list: {},
            payment_info: {},
        };
    },
    setup() {
        const toast = useToast();
        return {toast}
    },
    mounted() {
        this.show_console = this.app_env === 'local';

        this.payments_list = this.payments;
        this.payment_statuses = this.payments_status;
        this.users_list = this.users;

        if (this.show_console) {
            console.log('PaymentsComponent');
            console.log('payments_list', this.payments_list);
            console.log('payment_statuses', this.payment_statuses);
            console.log('users_list', this.users_list);
        }
    },
    methods: {
        show_payments_list: function () {
            this.template = 'list';
        },
        user_payment_info: function (payment_id) {
            this.toast.info("Показываем платеж с id " + payment_id);
            this.payment_info = this.payments_list[payment_id];
            this.template = 'info';
        },
        user_payment_delete: function (payment_id) {
            this.toast.info("Удаляем платеж с id " + payment_id);
            let data = {
                payment_id: payment_id,
            };
            axios.post('/api/user/payment/delete', data)
                .then(response => {
                    if ('is_deleted' in response.data) {
                        if (response.data['is_deleted']) {
                            this.toast.success("Успешно");
                            delete this.payments_list[payment_id];
                        }
                    }
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        is_valid_payment_total: function (payment_total) {
            let re = /(^[0-9]{1,6}$)|(^[0-9]{1,6}\.[0-9]{1,2}$)/;
            return re.test(payment_total);
        },
        is_valid_payment_number: function (payment_number) {
            let re = /[a-zA-Z\d-]+/;
            return re.test(payment_number);
        },
        user_payment_update: function () {
            this.toast.info("Обновляем данные платежа с id: " + this.payment_info.id);
            if (!this.is_valid_payment_total(this.payment_info.payment_total)) {
                this.toast.error("Не правильная сумма платежа (используйте только цифры и знак '.'). Максимальная сумма: 999999.99");
            }
            if (!this.is_valid_payment_number(this.payment_info.payment_number)) {
                this.toast.error("Не правильный номер платежа (используйте цифры, латинский буквы и знак '-')");
            }
            if (this.is_valid_payment_total(this.payment_info.payment_total) && this.is_valid_payment_number(this.payment_info.payment_number)) {
                axios.post('/api/user/payment/update', this.payment_info)
                    .then(response => {
                        if ('is_updated' in response.data) {
                            if (response.data['is_updated']) {
                                this.toast.success("Успешно");
                                let userPayment = response.data['payment'];
                                this.show_payments_list();
                                this.payments_list[this.payment_info.id] = userPayment;
                                this.payment_info = {};
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
