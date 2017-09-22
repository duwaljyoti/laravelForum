<template>
    <div>
        <p></p>
        <small>Since {{ data.created_at }}</small>
        <div class="flex">
            <img :src="avatar" height="50" width="50" class="mr-1">
            {{ data.name }}
        </div>
        <p></p>
        <form enctype="multipart/form-data" v-if="canUpdate">
            <image-upload name="avatar" @loaded="onLoad" class="mr-1"></image-upload>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue'

    export default {
      props: ['data'],
      components: { ImageUpload },
      data() {
        return {
          avatar: this.data.avatar_path,
        }
      },
      computed: {
        canUpdate() {
          return this.authorize(user => this.data.id === user.id)
        }
      },
      methods: {
        onLoad(avatar) {
          this.avatar = avatar.src;
          this.persist(avatar.file);
        },

        persist(avatar) {
          const data = new FormData();
          data.append('avatar', avatar);

          axios.post(`/api/users/${this.data.id}/avatar`, data)
            .then(() => {
                flash('Avatar Uploaded Succesfully.');
            })
            .catch((error) => {
              console.log(error);
            });
        }
      },
    }
</script>