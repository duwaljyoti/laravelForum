<template>
	<div
			class='alert alert-flash'
			:class="'alert-' + type"
			role='alert'
			v-if='show'
			v-text="messageBody">
	</div>
</template>


<script>
	export default {
		props: ['message'],
		data() {
			return {
				messageBody: '',
				type: 'success',
				show: false
			}
		},
		created() {
			if(this.message) {
				this.flash(this.message);
			}

            window.events.$on(
                'flash', data => this.flash(data)
            );			
		},
		methods: {
			flash(data) {
				this.messageBody = data.message;
				this.show = true;
				this.type = data.type;

				this.hide();
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