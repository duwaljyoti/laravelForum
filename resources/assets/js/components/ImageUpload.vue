<template>
    <input type="file"
           @change="changed"
           accept="image/*">
</template>

<script>
  export default {
    methods: {
      changed(event) {
        if (!event.target.files.length) return;

        let file = event.target.files[0];

        const reader = new FileReader();

        reader.readAsDataURL(file);

        reader.onload = event => {
          const src = event.target.result;

          this.$emit('loaded', {file, src});
        };
      }
    }
  }
</script>