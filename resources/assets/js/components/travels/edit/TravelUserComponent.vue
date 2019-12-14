<template>
    <div>
        <div class="section-top-border">
            <h3 class="mb-30">Участники</h3>
            <div class="progress-table-wrap">
                <div class="progress-table">
                    <div class="table-head">
                        <div class="fio">ФИО</div>
                        <div class="status">Роль</div>
                        <div class="button_block">Действия</div>
                    </div>
                    <div class="table-row" v-for="(user, index) in users">
                        <div class="fio">
                            <router-link :to="{ name: 'user', params: { user_id: user.user_id  }} " >
                                {{user.user_info.surname + ' ' + user.user_info.name + ' ' + user.user_info.patronymic}}
                            </router-link>
                        </div>
                        <div class="status"> {{user.claim.status_name}}</div>
                        <div class="button_block" style="float: right" v-if="author_status">
                            <div class="input-group">
                                <button
                                    class="btn btn-danger"
                                    style="border-radius: 0px;"
                                    v-if="user.claim.id > 2 && user.user_id !== author.id "
                                    @click="claimUpdate(index, 1)"
                                >Отклонить</button>
                                <button
                                    class="btn btn-success"
                                    style="border-radius: 0px;"
                                    v-if="user.claim.id === 3"
                                    @click="claimUpdate(index, 5)"
                                >Принять</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            travel_id: 0,
            author_status: false,
            author: {}
        },
        data: function (){
            return{
                users: [],
            }
        },
        mounted() {
            this.init()
        },
        methods: {
            init: function () {
                axios
                    .get('/api/travels/' + this.travel_id + '/users/', {
                        params: {
                            travel_id: this.travel_id
                        }}
                    )
                    .then((response) =>{
                        this.users = response.data
                    })
            },
            claimUpdate: function (claim_index, new_claim) {
                axios
                    .put('/api/travels/' + this.travel_id + '/claim', {
                        claim_id: this.users[claim_index]['claim']['id'],
                        new_claim: new_claim
                    })
                this.users[claim_index]['claim']['id'] = new_claim;
                switch(new_claim) {
                    case 1:
                        this.users[claim_index]['claim']['status_name'] = 'заявка отклонена организатором';
                        break
                    case 2:
                        this.users[claim_index]['claim']['status_name'] = 'заявка отклонена участником ';
                        break
                    case 3:
                        this.users[claim_index]['claim']['status_name'] = 'заявка на участие';
                        break
                    case 4:
                        this.users[claim_index]['claim']['status_name'] = 'приглашение';
                        break
                    case 5:
                        this.users[claim_index]['claim']['status_name'] = 'участник';
                        break
                }
            }
        }
    }
</script>
<style>
    .progress-table .fio {
        width: 40%;
    }
    .progress-table .button_block {
        width: 30%;
    }
    .progress-table .status {
        width: 30%;
    }
</style>
