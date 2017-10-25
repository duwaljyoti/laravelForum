<script>
  import Replies from '../components/Replies.vue'
  import NewReply from '../components/NewReply.vue'
  import Subscription from '../components/Subscription.vue'

  export default {
    components: {Replies, NewReply, Subscription},

    props: ['thread'],

    data: function () {
      return {
        replyCount: this.thread.replies_count,
        locked: this.thread.locked,
      }
    },
    methods: {
      toggleLock() {
        this.locked = !this.locked;
        const method = (this.locked) ? 'delete' : 'post';
        const action = (this.locked) ? 'unlock' : 'lock';
        axios[
          method
          ](`/threads/${this.thread.slug}/${action}`);
      },
    },
    computed: {
      lockDisplay() {
        return this.locked ? 'Unlock' : 'Lock';
      }
    },
  }
</script>