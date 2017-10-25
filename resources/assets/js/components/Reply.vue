<template>
    <div :id="'reply-' + id" class="panel" :class="[ isBest ? 'panel-success' : 'panel-default' ]">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profile/' + reply.owner.name" v-text="reply.owner.name"></a>
                    said <span v-text='ago'></span>
                </h5>
                <div v-if='signedIn'>
                    <favourite :reply="reply"></favourite>
                </div>
            </div>

        </div>
        <div class="panel-body">
            <div v-if='editing'>
                <form @submit="update">
                    <div class="form-group">
					<textarea class='form-control' v-model='reply.body' required>
					</textarea>
                    </div>
                    <button class='btn btn-primary btn-xs'>Update</button>
                    <button class='btn btn-xs' @click='editing = false' type="button">Cancel</button>
                </form>
            </div>

            <div v-else v-html='reply.body'></div>
        </div>
        <div class="panel-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class='btn-xs mr-1' @click='editing = true'>Edit</button>
                <button class='btn btn-default btn-xs btn-danger' @click='destroy'>Delete</button>
            </div>
            <button class='btn btn-default btn-xs btn-danger ml-a'
                    @click="toggleBest" v-if="authorize('owns', reply.thread)"
            >Best Reply
            </button>
        </div>
    </div>
</template>


<script>
  import Favourite from './Favourite.vue'
  import moment from 'moment'

  // confusing => how does it work with the blade component?
  export default {
    props: ['reply'],
    components: {Favourite},
    data() {
      return {
        editing: false,
        id: this.reply.id,
        isBest: this.reply.isBest,
      }
    },
    computed: {
      ago: function () {
        return moment(this.reply.created_at).fromNow()
      }
    },
    methods: {
      update() {
        axios.patch('/replies/' + this.reply.id,
          {body: this.reply.body})
          .then((response) => {
            if (response.status === 200) {
              flash('You have successfully edited your reply');
              this.editing = false
            }
          })
          .catch((error) => {
            flash(error.response.data, 'danger');
          })
      },
      destroy() {
        axios.delete("/replies/" + this.reply.id)
          .then((response) => {
            if (response.status == 200) {
              this.$emit('deleted')
            }
          })
          .catch((error) => {
            alert(error)
          })
      },
      toggleBest() {
        window.events.$emit('best-reply-selected', this.reply.id);
        axios.post(`/replies/${this.reply.id}/best`)
          .then((response) => {

          }).catch((exception) => {
        });
      },
    },
    created() {
      window.events.$on('best-reply-selected', (id) => {
        this.isBest = this.reply.id === id;
      });
    }
  }
</script>