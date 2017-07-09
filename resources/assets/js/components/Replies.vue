<template>
	<div>
		<div v-for="(reply, index) in items">
			<reply :data='reply' @deleted=remove(index)></reply>
		</div>

		<new-reply :endpoint='endpoint' @created="addReply"></new-reply>
	</div>
</template>

<script>
	import Reply from './Reply.vue'
	import NewReply from './NewReply.vue'
	export default {
		props: ['data'],

		components: { Reply, NewReply },

		data() {
			return  {
				items: this.data
			}
		},

		methods: {
			remove: function(index) {
				this.items.splice(index, 1)

				this.$emit('removed');

				flash('Reply Was deleted');
			},
			addReply: function(newReply) {
				this.items.push(newReply)
				this.$emit('reply-created')
			}
		},

		computed: {
			endpoint: function() {
				return location.pathname + '/replies'
			}
		}
	}	
</script>
