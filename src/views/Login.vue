<template>
  <form @submit.prevent="exec">
    <div>
      ログイン画面（席番号入力）
      <input type="text" v-model="SEAT_CD">
      <input type="submit" value="Login">
    </div>
  </form>
</template>

<style lang="scss">
  
</style>

<script>
export default {
  data: function(){
    return {
      SEAT_CD: process.env.VUE_APP_DEFAULT_SEAT_CD
    }
  },
  methods: {
    exec: function () {
      let params = new FormData();
      params.append("SEAT_CD",this.SEAT_CD);
      this.postJson(process.env.VUE_APP_API_URL_BASE+'/login',params,this.collback_Login);
    },
    collback_Login: function(response) {
      this.login = response.data;
      if( this.login != null ) {
        // ログイン情報をクッキーに保存
        this.$cookies.set('USER_ID',this.login.USER_ID);
        this.$cookies.set('SEAT_CD',this.login.SEAT_CD);
        this.$cookies.set('GROUP_CD',this.login.GROUP_CD);
        this.$cookies.set('MEMBER_NAME',this.login.MEMBER_NAME);
        this.$cookies.set('COMPANY_NAME',this.login.COMPANY_NAME);
        this.$cookies.set('LECTURE_DT',this.login.LECTURE_DT);
        this.$router.push({ name: 'agreement' }); // ログインできたら誓約書画面に遷移
        //console.log("登録されている受講者です");
      } else {
        console.log("登録されていない受講者です");
      }
    },
  }
}
</script>
