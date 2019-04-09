<template>
  <div>
    ログイン
    <input type="submit" value="Login" v-on:click="login">
  </div>
</template>

<style lang="scss">
  
</style>

<script>
export default {
  data: function(){
    return {
    }
  },
  methods: {
    login: function() {
      Auth.login();
      router.push(this.$route.query.redirect);
    }
  }
}
</script>
