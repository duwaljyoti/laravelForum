<script>
	import Replies from '../components/Replies.vue'
	import NewReply from '../components/NewReply.vue'
	import Subscription from '../components/Subscription.vue'

	export default {
		components: { Replies, NewReply, Subscription },

		props: ['repliesCounter'],

		data: function() {
			return {
				replyCount: this.repliesCounter
			}
		},

		methods: {
			aNewFunction() {
				console.log('testing one more time');
			}
		}
	}
</script>