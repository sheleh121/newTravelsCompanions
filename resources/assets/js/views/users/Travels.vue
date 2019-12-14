<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h1 v-if="no_data" class="text-white">
                            Здесь будут отображаться мероприятия, в которых вы принимаете участие.
                        </h1>
                        <h1 v-else class="text-white">
                            Мои мероприятия
                        </h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="destinations-area section-gap">
            <div class="container">
                <div class="row">
                    <travel-component v-for="travelUser in sortTravelsUser" :travel="travelUser.travel" :claim ="travelUser.claim.id"/>
                </div>
            </div>
        </section>

    </div>

</template>

<script>
    export default {
        data: function (){
            return{
                auth_user: auth.user,
                travelsUser: [],
                next_page_url: '',
                no_data: false,
            }
        },
        mounted() {
            this.GetData();
            this.Pagination();
            this.notice();

        },
        computed: {
            sortTravelsUser() {
                return this.travelsUser.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
            }
        },
        methods: {
            GetData: function () {
                axios.get('/api/users/' + this.auth_user.id + '/travels/all').then((response) =>{
                    response.data.data.forEach(function (data) {
                        this.travelsUser.push(data);
                    }, this);
                    this.next_page_url = response.data.next_page_url;
                    this.no_data = this.travelsUser.length <= 0;
                })

            },
            claimUpdate: function (travelUser, new_claim) {
                axios
                    .put('/api/travels/' + travelUser.travel.id + '/claim', {
                        claim_id: travelUser.id,
                        new_claim: new_claim
                    }).then(() => {
                    travelUser.claim.id = new_claim
                });
            },
            notice() {
                if (auth.check()) {
                    window.Echo.private('user_travels.' + this.auth_user.id)
                        .listen('Travels\\TravelInviteEvent', ({travel_user}) => {
                            this.travelsUser.push(travel_user);
                        });
                }
            },
            Pagination: function () {
                window.onscroll = () => {
                    let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight;
                    if (bottomOfWindow) {
                        if (this.next_page_url != null) {
                            axios.get(this.next_page_url)
                                .then((response) =>{
                                    response.data.data.forEach(function (data) {
                                        this.travelsUser.push(data)
                                    }, this),
                                        this.next_page_url = response.data.next_page_url
                                })
                        }
                    }
                };
            }
        }
    }
</script>
