<script>
	export default {
		props: ['attribute'],
		data() {
			return {
				editing: false,
				reply: this.attribute.body
			}
		},
		methods: {
			update(newReply) {
				axios.patch('/replies/' + this.attribute.id,
					 { body: this.reply })
					.then((response) => {
						if(response.status == 200) {
							flash('You have succesfully edited your reply')
							this.editing = false
						}
					})
					.catch((error) => {
						console.log(error)
					})
			},
			destroy() {
				axios.delete("/replies/" + this.attribute.id)
					.then((response) => {
						if(response.status == 200) {
							$(this.$el).fadeOut(300, () => {
								flash('Your reply has been deleted');
							});
						}
					})
					.catch((error) => {
						alert(error)
					})
			}
		},
		mounted() {
		}
	}
</script>