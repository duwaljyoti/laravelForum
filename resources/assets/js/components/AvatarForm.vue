<template>
    <div>
        {{ data.name }}
        <p></p>
        <small>Since {{ data.created_at }}</small>
        <p></p>
        <img :src="avatar" height="50" width="50">
        <form enctype="multipart/form-data" v-if="canUpdate">
            <input type="file" name="avatar" @change="changed" accept="image/*">
        </form>
    </div>
</template>

<script>
    export default {
      props: ['data'],
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
        changed(e) {
          // here is a bit of confusion..

          // if dere are no files at all just return
          if(!e.target.files.length) return;

          // get the file
          let avatar = e.target.files[0];

          // instantiate a new file reader object
          let reader = new FileReader();


          reader.readAsDataURL(avatar);

          reader.onload = e => {
            this.avatar = e.target.result;
          };

          this.persist(avatar);
        },

        persist(avatar) {
          const data = new FormData();
          data.append('avatar', avatar);

          axios.post(`/api/users/${this.data.id}/avatar`, data)
            .then((response) => {
                flash('Avatar Uploaded Succesfully.');
            })
            .catch((error) => {
              console.log(error);
            });
        }
      },
    }
</script>