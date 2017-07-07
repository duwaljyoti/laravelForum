<script>
	import Replies from '../components/Replies.vue'

	export default {
		components: { Replies },

		props: ['repliesCounter'],

		data: function() {
			return {
				replyCount: this.repliesCounter
			}
		},

		mounted() {
			// console.log(this.repliesCounter)
		}
	}
</script>