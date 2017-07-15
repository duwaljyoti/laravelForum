<template>
	<div>
	    <div class="form-group" v-if='isSignedIn'>
	        <textarea 
	        	rows = '5'
	        	name = 'body'
	        	class = 'form-control'
	        	v-model = 'body'
	        	placeholder = 'This sounds more appropriate!!'
	        >
	        </textarea>
	    	<button type="submit" class = 'btn btn-default' @click='saveReply'>Submit</button>
	    </div>
		<div v-else>
		    <p class='text-center'>
		    <a href = '/login'>
		    Please sign</a> in to participate</p>
		</div> 	    
	</div>
</template>


<script>
	export default {

		data() {
			return {
				body: '',
			}
		},
		
		methods: {
			saveReply: function() {
				axios.post(location.pathname + '/replies', {
						body: this.body
					})
					.then((response) => {
						this.body = ''
						this.$emit('created', response.data)
						flash('Your Reply is posted')
					})
					.catch((error) => {

					})
			}
		},
		computed: {
			isSignedIn: function() {
				return window.App.signedIn;
			}
		}
	}

</script>