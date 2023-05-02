export default {
  data() {
    return {
      msg: 'Hello World',
    }
  },
  created: function () {
    this.displayMessage();
  },
  methods: {
    displayMessage() {
      this.callFromMixinfunction();
    },
  },
}