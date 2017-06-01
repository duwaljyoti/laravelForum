<template>
	<div class='alert alert-success alert-flash' role='alert' v-if='show'>
		{{ messageBody }}
	</div>
</template>


<script>
	export default {
		props: ['message'],
		data() {
			return {
				messageBody: '',
				show: false
			}
		},
		created() {
			if(this.message) {
				this.flash(this.message);
			}

            window.events.$on(
                'flash', message => this.flash(message)
            );			
		},
		methods: {
			flash(message) {
				this.messageBody = message
				this.show = true

				this.hide()
			},

			hide() {
				setTimeout(() => {
					this.show = false;
				}, 3000)
			}
		}
	}
</script>


<style>
	.alert-flash {
		position: fixed;
		right: 25px;
		bottom: 25px;
	}
</style>