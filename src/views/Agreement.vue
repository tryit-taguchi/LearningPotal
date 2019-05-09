<template>
  <form @submit.prevent="exec">
    <div>
      誓約書
      <input type="checkbox" id="AgreeCheck" v-model="checked">
      <input type="submit" value="始める">
    </div>
  </form>
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
		exec: function () {
			this.$router.push("/"); // ホームに遷移
		},
	}
}
</script>
