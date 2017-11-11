<script type="text/ecmascript-6">
  import Replies from '../components/Replies.vue'
  import NewReply from '../components/NewReply.vue'
  import Subscription from '../components/Subscription.vue'

  export default {
    components: {Replies, NewReply, Subscription},

    props: ['thread', 'path'],

    data: function () {
      return {
        replyCount: this.thread.replies_count,
        locked: this.thread.locked,
        editing: false,
        title: this.thread.title,
        body: this.thread.body,
        form : {}
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
      update() {
        axios.put(this.path, {
          title: this.form.title,
          body: this.form.body
        })
          .then(() => {
            this.editing = false;
            this.title = this.form.title;
            this.body = this.form.body;
            flash('Your thread has been successfully updated.');
          })
          .catch((e) => {
            console.log(e);
          });
      },
      resetForm() {
        this.form = {
          title: this.thread.title,
          body: this.thread.body
        };
        this.editing = false;
      }
    },
    computed: {
      lockDisplay() {
        return this.locked ? 'Unlock' : 'Lock';
      },
      canUpdate() {
        return this.authorize('owns', this.thread);
      }
    },
    mounted() {
      this.resetForm();
    }
  }
</script>