<template>
	<div>
		<button type='submit' :class='classes' @click='toggle'>
			<span class='glyphicon glyphicon-heart' >{{ count }}</span>
		</button>					
	</div>
</template>

<script>
	export default {
		props: ['reply'],
		data() {
			return {
				isFavourited: this.reply.isFavourited,
				count: this.reply.favourites_count
			}
		},
		mounted() {
		},
		computed: {
			classes() {
				return ['btn', this.isFavourited ? 'btn-primary' : 'btn-default'];
			},
			endpoint() {
				return '/replies/' + this.reply.id + '/favourite';
			}
		},
		methods: {
			toggle() {
				return this.isFavourited ? this.destroy() : this.create()
			},
			destroy() {
				axios.delete(this.endpoint)
				this.count--
				this.isFavourited = false
			},
			create() {
				axios.post(this.endpoint)
				.then((response) => {
					this.count++
					this.isFavourited = true
				})			
			}
		},
	}
</script>