<script>
	import Replies from '../components/Replies.vue'
	import NewReply from '../components/NewReply.vue'

	export default {
		components: { Replies, NewReply },

		props: ['repliesCounter'],

		data: function() {
			return {
				replyCount: this.repliesCounter
			}
		},

		mounted() {
			// console.log(this.repliesCounter)
		},
		methods: {
			aNewFunction() {
				console.log('testing one more time');
			}
		}
	}
</script>