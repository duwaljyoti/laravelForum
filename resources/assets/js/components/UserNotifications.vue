<template>
        <li class="dropdown" >
            <a href="#"  class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-bell"></span>
            </a>

            <ul class="dropdown-menu" v-if="notifications.length">
                <li v-for="notification in notifications">
                    <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)">
                    </a>
                </li>
            </ul>

            <ul class="dropdown-menu" v-else> &nbsp All caught Up</ul>
        </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: false
            }
        },

        mounted(){
            this.fetchNotifications();
        },

        methods: {
            markAsRead(notification) {
                axios.delete(`/profile/${window.App.loggedUser.name}/notifications/${notification.id}`)
                    .then(response => {
                        this.fetchNotifications();
                    })
            },
            fetchNotifications() {
                axios.get("/profile/" + window.App.loggedUser.name + "/notifications")
                    .then(response => {
                        this.notifications = response.data;
                    });
            }
        }
    }
</script>