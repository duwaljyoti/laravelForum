<template>
	<div>
		<div v-for="(reply, index) in items" :key='reply.id'>
			<reply :data='reply' @deleted=remove(index)></reply>
		</div>

		<paginator :dataSet="dataSet" @pageUpdated="fetch"></paginator>

		<new-reply @created="add"></new-reply>
	</div>
</template>

<script>
	import Reply from './Reply.vue'
	import NewReply from './NewReply.vue'
	import collection from '../mixins/collection.js'
	import { getParameterByName } from '../mixins/utils/index.js'

	export default {
		components: { Reply, NewReply },

		mixins: [ collection ],

		data() {
			return  {
				dataSet: false,
			}
		},

		methods: {
			fetch(page) {
				axios.get(this.url(page))
					.then(this.refresh)
					.catch(exception => {
						console.log('Error', exception)
					})
			},

			url(page) {
			    if(!page) {
			        page = getParameterByName('page') ? getParameterByName('page') : 1;
				}
				return location.pathname + '/replies?page=' + page;
			},

			refresh({data}) {
				this.dataSet = data;
				this.items = data.data;
			},
		},

		created() {
			this.fetch()
		}
	}	
</script>
