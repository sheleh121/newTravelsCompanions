<template>
    <section  class="banner-area relative" >
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row align-items-center justify-content-between" :style="'height: ' + $root.windowHeight + 'px'">
                <div class="col-lg-3 col-md-3 banner-left" >
                    <h6 class="text-white">Необходимо подтвердить номер телефона</h6>
                </div>

                <div class="col-lg-6 col-md-6 banner-right">

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" >
                            <div class="form-wrap">
                                <div v-if="errors.length !== 0" class="alert alert-danger alert-dismissible" role="alert">
                                    {{ errors }}
                                    <button type="button" class="close" @click="errors = null" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <input v-model="key_sms" id="password" type="text" :class="'form-control' + ('key_sms' in errors ? ' is-invalid' : '')" name="password" required>

                                <button class="genric-btn primary-border text-uppercase  " @click="getSms()">Выслать код повторно</button>
                                <button class="genric-btn success text-uppercase float-right" @click="login()">Подтвердить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
    export default {
        data() {
            return {
                key_sms: '',
                errors: [],
                status: null
            };
        },

        methods: {
            login() {
                let data = {
                    key_sms: this.key_sms,
                };
                axios.put('/api/register/confirm', data)
                    .then(() => {
                        auth.getUser();
                        this.$router.push('/');
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
            },
            getSms() {
                axios.get('/api/register/confirm/sms')
                    .then((response) => {
                        this.status = response.data;
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
            }
        }
    }
</script>

<style scoped>

</style>
