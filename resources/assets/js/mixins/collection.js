export default {
  data() {
    return {
      items: ''
    }
  },

  methods: {
    remove: function (index) {
      this.items.splice(index, 1)

      this.$emit('removed');

      flash('Reply Was deleted');
    },

    add: function (items) {
      this.items.push(items)
      this.$emit('reply-created')
    },
  }
}