<template>
    <div class="content" >
        <div class="contact-profile" style="padding: 0px 10px ">
            <p>{{ room.name}}</p>
        </div>
        <div id="messages" class="messages">
        <ul>
            <li v-for="messageIn in messages_history.slice().reverse()" :class="messageIn.user_id == user.id ? 'sent' : 'replies'" >
                <!--<img src="http://emilcarlsson.se/assets/mikeross.png" :alt="'/users/' + messageIn.user_id" />-->
                <p>{{messageIn.text}}</p>
            </li>

            <li v-for="messageIn in messages_in" :class="messageIn.user_id == user.id ? 'sent' : 'replies'">
                <!--<img src="http://emilcarlsson.se/assets/mikeross.png" :alt="'/users/' + messageIn.user_id" />-->
                <p>{{messageIn.text}}</p>
            </li>
        </ul>
    </div>
    <div class="message-input">
        <div class="wrap">
            <input type="text" placeholder="Введите ваше сообщение..." v-model="message_out" @keyup.enter="send" @click="seen()"/>
            <button class="genric-btn success" @click="send">отправить</button>
        </div>
    </div>
</div>




</template>

<script>
    export default {
        props: [

            ,'room_id'
            ,'room'
        ],
        data: function (){
            return{
                authenticated: auth.check(),
                user: auth.user,
                messages_in: [],
                messages_history: [],
                message_out: '',
                previous_room_id: 0,
                next_page_url: '',
                test: [],
            }
        },
        watch: {
            room_id: {
                handler: function() {
                    this.init()
                }
            }
        },
        mounted() {
            this.init()
        },
        methods: {
            send: function () {
                axios.post('/api/chat/messages/',{text: this.message_out, chat_room_id: this.room_id

                })
                this.message_out = ""
            },
            init: function () {
                this.messages_in = [];
                this.messages_history = [];
                this.message_out = '';
                window.Echo.leave('room.' + this.previous_room_id)
                this.previous_room_id = this.room_id
                window.Echo.private('room.' + this.room_id)
                    .listen('Chat\\ChatMessageEvent', ({message}) => {
                        this.messages_in.push(message);
                        setTimeout(() => {
                            this.scrollToEnd();
                        }, 90);


                    });
                axios.get('/api/chat/messages/room' + this.room_id)
                    .then((response) =>{
                        this.messages_history = response.data.data
                        this.next_page_url = response.data.next_page_url
                    })
                    .finally(() => (this.scrollToEnd()));
            },
            scrollToEnd: function() {

                var container = this.$el.querySelector("#messages");
                container.scrollTop = container.scrollHeight;
            },
            seen: function(){
                var seen_messages_id = [];
                this.messages_in.forEach(function (message) {
                    if (message.seen == false){
                        message.seen = true;
                        seen_messages_id.push(message.id)
                    }
                });
                this.messages_history.forEach(function (message) {
                    if (message.seen == false){
                        message.seen = true;
                        seen_messages_id.push(message.message_id)
                    }
                });
                axios.post('/api/chat/messages/seen', {
                    'messages_id': seen_messages_id
                }).then((response) =>{
                    this.$root.no_seen_messages  -= seen_messages_id.length;
                    this.room.no_seen = 0;
                })

            },
            Pagination: function () {
                if (this.next_page_url) {
                    axios.get(this.next_page_url)
                        .then((response) =>{
                                response.data.data.forEach(function (data) {
                                    this.messages_history.push(data)
                                }, this),
                                    this.next_page_url = response.data.next_page_url,
                                    this.test = response.data
                            }
                        )
                }
            }
        }
    }
</script>
