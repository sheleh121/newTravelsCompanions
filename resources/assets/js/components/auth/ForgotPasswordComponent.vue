<template>
    <div class="form-wrap">
        <div class="input-group"  style="margin-bottom: 10px">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">+7</span>
            </div>
            <input type="tel"
                   v-model="phone"
                   name="phone"
                   id="phone"
                   placeholder="(XXX) XXX-XX-XX"
                   autocomplete="tel"
                   maxlength="15"
                   class="form-control input-group"
                   style="margin-bottom: 0"
                   v-phone
                   pattern="[(][0-9]{3}[)] [0-9]{3}-[0-9]{4}" required />

        </div>
         <button class="primary-btn text-uppercase" @click="login()">ВОССТАНОВИТЬ</button>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                email: '',
                phone: '',
                error: null
            };
        },

        methods: {
            login() {
                let data = {
                    email: this.email,
                    phone: this.phone
                };

                axios.post('/api/forgot', data)
                    .then(() => {
                        this.$router.push('/login');
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.error = error.response.data;
                        }
                    });
            }
        }
    }
</script>

<style scoped>

</style>
