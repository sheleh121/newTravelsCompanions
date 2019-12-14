<template>
<div>
    <section class="about-banner relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">
                       Сообщения
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="insurence-one-area section-gap whole-wrap">
        <div class="container border-top-generic" >
        <div id="frame">

            <div id="sidepanel">

                <div id="contacts">
                    <ul>
                        <li :class="'contact text-break ' + (room.id === selected.id ? 'active' : '')"
                            v-for="room in rooms"
                            @click="selected = room;">
                            <div class="wrap">
                                <!--<span class="contact-status online"></span>-->
                                <!--<img src="http://emilcarlsson.se/assets/louislitt.png" alt="" />-->
                                <div class="meta">
                                    <p class="name">{{room.name + ' ' + (room.no_seen > 0 ? '(' + room.no_seen + ')' : '')}}</p>
                                </div>
                            </div>
                        </li>
                        <!--             <li class="contact active">
                                         <div class="wrap">
                                             <span class="contact-status busy"></span>
                                             <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
                                             <div class="meta">
                                                 <p class="name">Harvey Specter</p>
                                                 <p class="preview">Wrong. You take the gun, or you pull out a bigger one. Or, you call their bluff. Or, you do any one of a hundred and forty six other things.</p>
                                             </div>
                                         </div>
                                     </li>-->

                    </ul>
                </div>

            </div>
            <chat-messages-component :room_id = selected.id :room = selected />


        </div>
    </div>
    </section>
</div>


</template>

<script>
    export default {
        data: function () {
            return {
                user: auth.user,
                rooms: [],
                selected_mobile: false,
                selected: [],
                window: {
                    width: 0,
                    height: 0
                }
            }
        },
        mounted() {
            this.getRooms()
        },
        methods: {
            getRooms() {
                axios.get('/api/chat/rooms/').then((response) => {
                    this.rooms = response.data.data;
                    this.selected = this.rooms[0];
                }).catch(({response}) => {

                });

                window.Echo.private('notice-message.' + this.user.id)
                    .listen('Chat\\RoomSubscribeEvent', ({room_id}) => {
                        this.rooms.forEach(function (room) {
                            if (room.id == room_id)
                                room.no_seen += 1
                        })
                    });

                this.window.width = window.innerWidth;
                this.window.height = window.innerHeight;
            }
        }
    }
</script>

<style>
    .select_chat{
        width: 100%;
        border-style: none;
        border-radius: 0;
        display: block;
        padding: 5px 5px 5px 5px;
        color: #000;
        margin: 2px;
    }
</style>
