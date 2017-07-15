<template>
	<div :id = "'reply-' + id" class="panel panel-default">
	     <div class="panel-heading">
	     	<div class="level">
		     	<h5 class="flex">
			     	<a :href = "'/profile/' + data.owner.name" v-text="data.owner.name"></a>
			     	said <span v-text='ago'></span>
			    </h5>
			    <div v-if='signedIn'>
			    	<favourite :reply="data"></favourite>
			    </div> 	
	     	</div>

	     </div>
	      <div class="panel-body">
	      	<div v-if='editing'>
		      	<div class="form-group">
					<textarea class = 'form-control' v-model='reply'>
					</textarea>
				</div>	
				<button class='btn btn-primary btn-xs' @click='update'>Update</button>
				<button class='btn btn-xs' @click='editing = false'>Cancel</button>
	      	</div>

	      	<div v-else v-text='reply'></div>    
	     </div>
	     <div class="panel-footer level" v-if='canUpdate'>
	     	<button class='btn-xs mr-1' @click='editing = true'>Edit</button>
	     	<button class='btn btn-default btn-xs btn-danger' @click='destroy'>Delete</button>
		</div>
	</div>
</template>


<script>
	import Favourite from './Favourite.vue'
	import moment from 'moment'

	export default {
		props: ['data'],
		components: { Favourite },
		data() {
			return {
				editing: false,
				reply: this.data.body,
				id: this.data.id
			}
		},
		computed: {
			signedIn: function() {
				return window.App.signedIn
			},
			canUpdate: function() {
				return this.authorize(user =>  this.data.user_id == user.id)
			},
			ago: function() {
				return moment(this.data.created_at).fromNow()
			}
		},
		methods: {
			update(newReply) {
				axios.patch('/replies/' + this.data.id,
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
				axios.delete("/replies/" + this.data.id)
					.then((response) => {
						if(response.status == 200) {
							this.$emit('deleted')
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