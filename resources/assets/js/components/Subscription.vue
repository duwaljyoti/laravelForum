<template>
    <button
            :class="classes"
            @click="subscribe"
            v-text="toDisplay"
    >
    </button>
</template>

<script>
  export default {
    props: ['isSubscribed'],

    computed: {
      classes() {
        return this.subscribed ? 'btn btn-default' : 'btn btn-primary';
      },
      toDisplay() {
        return this.subscribed ? 'UnSubscribe' : 'Subscribe';
      }
    },

    methods: {
      subscribe() {
        let endPointType = this.subscribed ? 'delete' : 'post';
        axios[endPointType](location.pathname + '/subscribe')
          .then(response => {
            console.log(response);
          })
          .catch(error => {
            console.log(error);
          });

        this.subscribed = !this.subscribed;

        flash('You have subscribed succesfully');
      }
    },
    data() {
      return {
        subscribed: null,
      }
    },

    mounted() {
      this.subscribed = this.isSubscribed;
    }
  }
</script>