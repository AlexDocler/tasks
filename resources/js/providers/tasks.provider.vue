<script>
    export default {
        name: 'tasks.provider',

        getUrl (src) {
            return '/tasks' + (src ? '/' + src : '');
        },

        get (id) {
            return Vue.axios.get(this.getUrl(id));
        },

        post (options) {
            return Vue.axios.post(this.getUrl(''), options);
        },

        update (id, options) {
            return Vue.axios.put(this.getUrl(id), options);
        },

        delete (id) {
            return Vue.axios.delete(this.getUrl(id));
        },
  }
</script>
